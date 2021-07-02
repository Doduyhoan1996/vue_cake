<?php
namespace App\Controller\Api;

use App\Controller\ApiAppController;

/**
 * Reports Controller
 *
 * @property \App\Model\Table\ReportsTable $Reports
 *
 * @method \App\Model\Entity\Report[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReportsController extends ApiAppController
{
    public function initialize()
    {
        parent::initialize();
        $this->authAllow(['getList']);
    }

    public function getList() {
        if (!$this->request->is('GET')) {
            return $this->responseData(['error_code' => 100]);
        }
        $dataGet = $this->request->getQuery();
        $conditions = [];
        $conditionsOr = [];
        // if (isset($dataGet['keyword']) && $dataGet['keyword']) {
        //     $keyword = preg_replace('/\s+/', '', $dataGet['keyword']);
        //     $conditionsOr["OR"] = [
        //         "CONCAT(Users.first_name, ' ', Users.last_name) LIKE" => '%' . $keyword . '%',
        //         "Users.email LIKE"                                    => '%' . $keyword . '%'
        //     ];
        // }
        if (isset($dataGet['keyword']) && $dataGet['keyword'] ) {
            $conditions["OR"] = [
                "CONCAT(Users.first_name, ' ', Users.last_name) LIKE" => '%' . $dataGet['keyword'] . '%',
                "Users.email LIKE"                                    => '%' . $dataGet['keyword'] . '%'
            ];
        }
        if (isset($dataGet['dates']) && $dataGet['dates']) {
            $start_date = date('Y-m-d', strtotime($dataGet['dates'][0]));
            $end_date   = date('Y-m-d', strtotime($dataGet['dates'][1]));
            $conditions["AND"] = [
                "Reports.date_report >=" =>  $start_date ,
                "Reports.date_report <=" =>  $end_date,
            ];
            
        }

        $reports = $this->Reports->find()
            ->select([
                'Reports.id',
                'Reports.user_id',
                'Reports.team',
                'Reports.date_report',
                'Reports.work_content',
                'Reports.next_date_report',
                'Reports.next_work_content',
                'Reports.difficult',
                'Reports.hight_light',
                'Users.first_name',
                'Users.last_name',  
                'Users.email', 
            ])
            ->contain(['Users'])
            ->where($conditions)
            ->where($conditionsOr)
            ->order(['Reports.date_report' => 'ASC'])
            ->toArray();

        $reportList = [];
        foreach($reports as $report){
            $reportList[$report->date_report->format('d/m/Y')][] = $report;
        }
        return $this->responseData(['status' => true, 'data' => $reportList]);
    }

    public function save() {
        if (!$this->request->is('POST')) {
            return $this->responseData(['error_code' => 100]);
        }
        $dataPost = json_decode($this->request->input(), true);
        if (!isset($dataPost['date_report'])
            || !isset($dataPost['work_content'])
            || !isset($dataPost['next_date_report'])
            || !isset($dataPost['next_work_content'])
         ) {
            return $this->responseData(['error_code' => 101]);
        }
        $dataPost['user_id']    = $this->JWTDecoded->profile->id;
        if( !empty($dataPost['difficult']) ){
            $dataPost['hight_light']    = true;
        }
        $reports = $this->Reports->newEntity($dataPost);
        if ($reports->getErrors()) {
            return $this->responseData(['error_code' => 103, 'data' => $reports->getErrors()]);
        }
        if ($this->Reports->save($reports)) {
            return $this->responseData(['status' => true]);
        } else {
            if ($reports->getErrors()) {
                return $this->responseData(['error_code' => 103, 'data' => $reports->getErrors()]);
            }
            return $this->responseData(['error_code' => 102]);
        }
    }

    public function update(){
        if (!$this->request->is('POST')) {
            return $this->responseData(['error_code' => 100]);
        }
        $dataPost = json_decode($this->request->input(), true);
        if (!isset($dataPost['id'])) {
            return $this->responseData(['error_code' => 101]);
        }
        $reports = $this->Reports->find()->where(['Reports.id' => $dataPost['id']])->first();
        if (!$reports) {
            return $this->responseData(['error_code' => 404]);
        }

        $dataPost['date_report']        = date('Y-m-d', strtotime($dataPost['date_report'])); 
        $dataPost['next_date_report']   = date('Y-m-d', strtotime($dataPost['next_date_report'])); 

        $reports = $this->Reports->patchEntity($reports, $dataPost);
        if ($reports->getErrors()) {
            return $this->responseData(['error_code' => 103, 'data' => $reports->getErrors()]);
        }
        if ($this->Reports->save($reports)) {
            return $this->responseData(['status' => true]);
        } else {
            if ($reports->getErrors()) {
                return $this->responseData(['error_code' => 103, 'data' => $reports->getErrors()]);
            }
            return $this->responseData(['error_code' => 102]);
        }
    }
}
