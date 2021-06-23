<?php
namespace App\Controller\Api;

use App\Controller\ApiAppController;
use App\Model\Entity\User;
use App\Model\Entity\Calendar;

/**
 * Calendars Controller
 *
 * @property \App\Model\Table\CalendarsTable $Calendars
 *
 * @method \App\Model\Entity\Calendar[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CalendarsController extends ApiAppController
{
    public function initialize()
    {
        parent::initialize();
        $this->authAllow(['getList']);
    }

    public function getList(){
        if (!$this->request->is('GET')) {
            return $this->responseData(['error_code' => 100]);
        }
        $dataGet = $this->request->getQuery();
        $conditions = [];
        if (isset($dataGet['activeStart']) && $dataGet['activeStart'] 
            && isset($dataGet['activeEnd']) && $dataGet['activeEnd'] 
            ) 
        {
            $conditions['DATE(Calendars.date_start) >='] =  $dataGet['activeStart'] ;
            $conditions['DATE(Calendars.date_end) <'] =  $dataGet['activeEnd'] ;
        }

        $calendar['list'] = $this->Calendars->find()
            ->select([
                'Calendars.id',
                'Calendars.user_id',
                'Calendars.type',
                'start'             =>  'Calendars.date_start',
                'end'               =>  'Calendars.date_end',
                'Calendars.title',
                'Calendars.status',
                'Calendars.confirm_by',
                'Users.first_name',
                'Users.last_name',
            ])
            ->contain(['Users'])
            ->where($conditions)->toArray();

        return $this->responseData(['status' => true, 'data' => $calendar]);
    }

    public function get() {
        if (!$this->request->is('GET')) {
            return $this->responseData(['error_code' => 100]);
        }
        $dataGet = $this->request->getQuery();
        if (!isset($dataGet['id'])) {
            return $this->responseData(['error_code' => 101]);
        }
        $calendar = $this->Calendars->find()
        ->select([
            'Calendars.id',
            'Calendars.user_id',
            'Calendars.type',
            'start'             =>  'Calendars.date_start',
            'end'               =>  'Calendars.date_end',
            'Calendars.title',
            'Calendars.status',
            'Calendars.confirm_by',
            'Users.first_name',
            'Users.last_name',
        ])
        ->contain(['Users'])
        ->where(['Calendars.id' => $dataGet['id']])->first();
        if (!$calendar) {
            return $this->responseData(['error_code' => 404]);
        }
        $dataReturn = $calendar->toArray();
        return $this->responseData(['status' => true, 'data' => $dataReturn]);
    }
    public function save() {
        if (!$this->request->is('POST')) {
            return $this->responseData(['error_code' => 100]);
        }
        $dataPost = json_decode($this->request->input(), true);
        if (!isset($dataPost['type'])
            || !isset($dataPost['dates'])
            || !isset($dataPost['status'])
            
        ) {
            return $this->responseData(['error_code' => 101]);
        }
        if($dataPost['type'] == Calendar::TYPE_DAY_OFF){
            $dateStart = date($dataPost['dates'][0] .' '. $dataPost['times'][0] );
            $dateEnd = date($dataPost['dates'][1] .' '. $dataPost['times'][1] );
        } else{
            $dateStart = date($dataPost['day_metting'] .' '. $dataPost['times'][0] );
            $dateEnd = date($dataPost['day_metting'] .' '. $dataPost['times'][1] );
        }
       

        $dataPost['date_start'] = $dateStart;
        $dataPost['date_end']   = $dateEnd ;
        $dataPost['user_id']    = $this->JWTDecoded->profile->id;

        $calendar = $this->Calendars->newEntity($dataPost);
        if ($calendar->getErrors()) {
            return $this->responseData(['error_code' => 103, 'data' => $calendar->getErrors()]);
        }
        if ($this->Calendars->save($calendar)) {
            return $this->responseData(['status' => true]);
        } else {
            if ($calendar->getErrors()) {
                return $this->responseData(['error_code' => 103, 'data' => $calendar->getErrors()]);
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
        $calendar = $this->Calendars->find()->where(['Calendars.id' => $dataPost['id']])->first();
        if (!$calendar) {
            return $this->responseData(['error_code' => 404]);
        }

        if($dataPost['type'] == Calendar::TYPE_DAY_OFF){
            $dateStart = date($dataPost['dates'][0] .' '. $dataPost['times'][0] );
            $dateEnd = date($dataPost['dates'][1] .' '. $dataPost['times'][1] );
        } else{
            $dateStart = date($dataPost['day_metting'] .' '. $dataPost['times'][0] );
            $dateEnd = date($dataPost['day_metting'] .' '. $dataPost['times'][1] );
        }

        $dataPost['date_start'] = $dateStart;
        $dataPost['date_end']   = $dateEnd ;
        
        $calendar = $this->Calendars->patchEntity($calendar, $dataPost);
        if ($calendar->getErrors()) {
            return $this->responseData(['error_code' => 103, 'data' => $calendar->getErrors()]);
        }
        if ($this->Calendars->save($calendar)) {
            return $this->responseData(['status' => true]);
        } else {
            if ($calendar->getErrors()) {
                return $this->responseData(['error_code' => 103, 'data' => $calendar->getErrors()]);
            }
            return $this->responseData(['error_code' => 102]);
        }
    }
}
