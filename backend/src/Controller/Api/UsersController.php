<?php
namespace App\Controller\Api;

use App\Controller\ApiAppController;
use App\Model\Entity\User;
use Firebase\JWT\JWT;
use App\Model\Table\SettingsTable;
use Cake\I18n\Time;
use App\Model\Entity\Attendance;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends ApiAppController
{
    public function initialize()
    {
        parent::initialize();
        $this->authAllow(['auth']);
    }

    public function auth() {
        if (!$this->request->is('POST')) {
            return $this->responseData(['error_code' => 100]);
        }
        $dataPost = json_decode($this->request->input(), true);
        if (!isset($dataPost['email']) || !isset($dataPost['password'])) {
            return $this->responseData(['error_code' => 101]);
        }
        $user = $this->Users->find()->where(['Users.email' => $dataPost['email'], 'Users.password' => User::generatePassword($dataPost['password'])])->first();
        if (!$user) {
            return $this->responseData(['error_code' => 201]);
        }
        if ($user->status == User::STATUS_BLOCKED) {
            return $this->responseData(['error_code' => 203]);
        }
        $payload = [
            'sub' => $user->id,
            'profile' => [
                'id' => $user->id
            ]
        ];
        $authorization = JWT::encode($payload, env('SECURITY_JWT', 'dev'));
        return $this->responseData([
            'status' => true, 
            'data' => [
                'Authorization' => $authorization,
                'user' => $user->toArray(),
                'setting' => $this->_getSettings()
            ]
        ]);
    }

    public function checkSession() {
        $user = $this->Users->find()->where(['Users.id' => $this->JWTDecoded->profile->id])->first();
        if (!$user) {
            return $this->responseData(['error_code' => 201]);
        }
        if ($user->status == User::STATUS_BLOCKED) {
            return $this->responseData(['error_code' => 203]);
        }
        $payload = [
            'sub' => $user->id,
            'profile' => [
                'id' => $user->id
            ]
        ];
        $authorization = JWT::encode($payload, env('SECURITY_JWT', 'dev'));
        return $this->responseData([
            'status' => true, 
            'data' => [
                'Authorization' => $authorization,
                'user' => $user->toArray(),
                'setting' => $this->_getSettings()
            ]
        ]);
    }

    public function getList() {
        if (!$this->request->is('GET')) {
            return $this->responseData(['error_code' => 100]);
        }
        $dataGet = $this->request->getQuery();
        
        $limit      = isset( $dataGet["limit"] ) ? intval( $dataGet["limit"] ) : User::USER_LIMIT_DEFAULT;
        $page       = isset( $dataGet["page"] ) ? intval( $dataGet["page"] ) : 1;
        $conditions = [];

        if (isset($dataGet['type']) && $dataGet['type']) {
            if(in_array($dataGet['type'],['active','block'])){
                if($dataGet['type'] == 'active'){
                    $conditions['Users.status'] = 1;
                } else  $conditions['Users.status'] = 0;

            } else {
                $conditions['Users.status'] = 1;
                $conditions['Users.role'] = $dataGet['type'];
            }     
        }
        if (isset($dataGet['keyword']) && $dataGet['keyword'] ) {
            $conditions["OR"] = [
                "CONCAT(Users.first_name, ' ', Users.last_name) LIKE" => '%' . $dataGet['keyword'] . '%',
                "Users.email LIKE"                                    => '%' . $dataGet['keyword'] . '%'
            ];
        }
        $listUsers = $this->Users->find()
            ->select([
                'Users.id',
                'Users.first_name',
                'Users.last_name',
                'Users.role',
                'Users.type',
                'Users.email',
                'total_project' => '(Select count(*) from project_members where user_id = Users.id)',
                'Users.created'
            ])
            ->where($conditions)
            ->order(['Users.id' => 'DESC'])
            ->page( $page, $limit );

        $users['pagination']['totalRecord'] = $listUsers->count();
        $users['pagination']['pageSize'] = User::USER_LIMIT_DEFAULT;
        $users['pagination']['page'] = $page;

        $users['list'] = $listUsers->toArray();

        $count_hd = $this->Users->find()->where(['Users.status' => User::STATUS_NORMAL])->count();
        $count_ad = $this->Users->find()->where(['Users.role' => User::ROLE_ADMIN, 'Users.status' => User::STATUS_NORMAL])->count();
        $count_hc = $this->Users->find()->where(['Users.role' => User::ROLE_HR, 'Users.status' => User::STATUS_NORMAL])->count();
        $count_tv = $this->Users->find()->where(['Users.role' => User::ROLE_USER, 'Users.status' => User::STATUS_NORMAL])->count();
        $count_k = $this->Users->find()->where(['Users.status' => User::STATUS_BLOCKED])->count();
        $users['count'] = [
            'count_hd' => $count_hd,
            'count_ad' => $count_ad,
            'count_hc' => $count_hc,
            'count_tv' => $count_tv,
            'count_k' => $count_k,
        ];

        return $this->responseData(['status' => true, 'data' => $users]);
    }

    public function register() {
        if (!$this->request->is('POST')) {
            return $this->responseData(['error_code' => 100]);
        }
        $dataPost = json_decode($this->request->input(), true);
        if (!isset($dataPost['first_name'])
            || !isset($dataPost['last_name'])
            || !isset($dataPost['email'])
            || !isset($dataPost['birthday'])
            || !isset($dataPost['gender'])
            || !isset($dataPost['password'])
            || !isset($dataPost['type'])
            || !isset($dataPost['role'])
            || !isset($dataPost['can_create_project'])
            || !isset($dataPost['salary'])
            || !isset($dataPost['status'])
        ) {
            return $this->responseData(['error_code' => 101]);
        }
        $dataPost['password'] = User::generatePassword($dataPost['password']);
        $user = $this->Users->newEntity($dataPost);
        if ($user->getErrors()) {
            return $this->responseData(['error_code' => 103, 'data' => $user->getErrors()]);
        }
        if ($this->Users->save($user)) {
            return $this->responseData(['status' => true]);
        } else {
            if ($user->getErrors()) {
                return $this->responseData(['error_code' => 103, 'data' => $user->getErrors()]);
            }
            return $this->responseData(['error_code' => 102]);
        }
    }

    public function update() {
        if (!$this->request->is('POST')) {
            return $this->responseData(['error_code' => 100]);
        }
        $dataPost = json_decode($this->request->input(), true);
        if (!isset($dataPost['id'])) {
            return $this->responseData(['error_code' => 101]);
        }
        $user = $this->Users->find()->where(['Users.id' => $dataPost['id']])->first();
        if (!$user) {
            return $this->responseData(['error_code' => 404]);
        }
        if(isset($dataPost['password']) && $dataPost['password'] != $user->password) {
            $dataPost['password'] = User::generatePassword($dataPost['password']);
        }
        $user = $this->Users->patchEntity($user, $dataPost);
        if ($user->getErrors()) {
            return $this->responseData(['error_code' => 103, 'data' => $user->getErrors()]);
        }
        if ($this->Users->save($user)) {
            return $this->responseData(['status' => true]);
        } else {
            if ($user->getErrors()) {
                return $this->responseData(['error_code' => 103, 'data' => $user->getErrors()]);
            }
            return $this->responseData(['error_code' => 102]);
        }
    }

    public function get() {
        if (!$this->request->is('GET')) {
            return $this->responseData(['error_code' => 100]);
        }
        $dataGet = $this->request->getQuery();
        if (!isset($dataGet['id'])) {
            return $this->responseData(['error_code' => 101]);
        }
        $user = $this->Users->find()->where(['Users.id' => $dataGet['id']])->first();
        if (!$user) {
            return $this->responseData(['error_code' => 404]);
        }
        $dataReturn = $user->toArray();
        $dataReturn['can_create_project'] = $dataReturn['can_create_project'] ? 1 : 0;
        return $this->responseData(['status' => true, 'data' => $dataReturn]);
    }

    public function changePassword() {
        if (!$this->request->is('POST')) {
            return $this->responseData(['error_code' => 100]);
        }
        $dataPost = json_decode($this->request->input(), true);
        if (!isset($dataPost['old_password']) || !isset($dataPost['password'])) {
            return $this->responseData(['error_code' => 101]);
        }
        $user = $this->Users->find()->where(['Users.id' => $this->JWTDecoded->profile->id])->first();
        if (!$user) {
            return $this->responseData(['error_code' => 404]);
        }
        if(User::generatePassword($dataPost['old_password']) != $user->password) {
            return $this->responseData(['error_code' => 103]);
        }
        $dataPost['password'] = User::generatePassword($dataPost['password']);
        $user = $this->Users->patchEntity($user, $dataPost);
        if ($this->Users->save($user)) {
            return $this->responseData(['status' => true]);
        } else {
            return $this->responseData(['error_code' => 102]);
        }
    }

    private function _getSettings() {
        /** @var SettingsTable $Settings */
        $this->loadModel('Settings');
        $settings = $this->Settings->find()->where(['Settings.autoload' => true]);
        $dataReturn = [];
		foreach ($settings as $k => $setting) {
			$checkJson = json_decode($setting->setting_value, true);

			if ($checkJson) {
				$dataReturn[] = [
					"setting_key" => $setting->setting_key,
					"setting_value" => $checkJson
				];
			} else {
				$dataReturn[] = [
					"setting_key" => $setting->setting_key,
					"setting_value" => $setting->setting_value
				];
			}
		}
		return $dataReturn;
    }

    public function getOption() {
        if (!$this->request->is('GET')) {
            return $this->responseData(['error_code' => 100]);
        }
        $dataGet = $this->request->getQuery();
        $conditions = [];
        $users = $this->Users->find()
            ->select([
                'Users.id',
                'Users.first_name',
                'Users.last_name',
                'Users.role',
                'Users.email'
            ])
            ->where($conditions)->order(['Users.last_name' => 'ASC']);
        return $this->responseData(['status' => true, 'data' => $users->toArray()]);
    }
    
}
