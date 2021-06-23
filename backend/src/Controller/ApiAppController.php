<?php
namespace App\Controller;

use Cake\Controller\Controller;
use \Cake\Event\Event;
use App\Model\Table\UsersTable;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;

/**
 * ApiApp Controller
 * @property UsersTable $Users
 */
class ApiAppController extends Controller
{
    private $cakeParams;
    protected $checkAuthAllow = false;
    protected $JWTDecoded;

    private $templateResponse = [
        'status' => false,
        'error_code' => 0,
        'error_message' => '',
        'data' => null
    ];

    private $errorCode = [
        100 => 'Bad request',
        101 => 'Invalid params',
        102 => 'System error',
        103 => 'Invalid validation form',
        201 => 'Authentication failed',
        202 => 'Authentication expired',
        203 => 'User was blocked',
        204 => 'You dont have permision',
        301 => 'Already check-in',
        302 => 'Check in too soon',
        303 => 'You must have to check-in before',
        304 => 'Already check-out',
        305 => 'Already send request',
        404 => 'Not Found'
    ];

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Users');
        $this->cakeParams = $this->request->getAttribute('params');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $headers = $this->request->getHeaders();
        if (!$this->checkAuthAllow) {
            if (isset($headers['Authorization'])) {
                try {
                    $this->JWTDecoded = JWT::decode($headers['Authorization'][0], env('SECURITY_JWT', 'dev'), ['HS256']);
                } catch (ExpiredException $exp) {
                    return $this->responseData(['error_code' => 202]);
                } catch (\Exception $ex) {
                    return $this->responseData(['error_code' => 201]);
                }
            } else {
                return $this->responseData(['error_code' => 201]);
            }
        }
    }

    protected function responseData($dataJson = []) {
        $dataReturn = $dataJson + $this->templateResponse;
        $dataReturn['error_message'] = isset($this->errorCode[$dataReturn['error_code']]) ? $this->errorCode[$dataReturn['error_code']] : '';
        $this->response = $this->response
            ->withType('application/json')
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Methods', '*')
            ->withHeader('Access-Control-Allow-Headers', 'Content-type, Authorization, X-Requested-With')
            ->withHeader('Access-Control-Max-Age', '172800')
            ->withStringBody(json_encode($dataReturn));
        return $this->response;
    }

    protected function authAllow($action) {
        if (is_array($action)) {
            $this->checkAuthAllow = in_array($this->cakeParams['action'], $action);
        } else {
            $this->checkAuthAllow = $this->cakeParams['action'] == $action;
        }
    }

    protected function getSettings($key = null, $autoload = false) {
        $this->loadModel('Settings');
        if ($key) {
            $settings = $this->Settings->find()->where(['Settings.setting_key' => $key])->first();
            return $settings ? $settings->setting_value : null;
        } else {
            $conditions = [];
            if ($autoload) {
                $conditions['Settings.autoload'] = true;
            }
            $settings = $this->Settings->find()->where($conditions);
            $data = [];
            foreach ($settings as $k => $v) {
                $data[$v->setting_key] = $v->setting_value;
            }
            return $data;
        }
    }
}
