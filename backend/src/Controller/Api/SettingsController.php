<?php
namespace App\Controller\Api;

use App\Controller\ApiAppController;
use App\Model\Table\SettingsTable;

/**
 * Settings Controller
 * @property SettingsTable $Settings
 */
class SettingsController extends ApiAppController
{
    public function initialize()
    {
        parent::initialize();
    }

    public function autoload() {
        $settings = $this->Settings->find()->where(['Settings.autoload' => true]);
        $dataReturn = [];
		foreach ($settings as $k => $setting) {
			$checkJson = json_decode($setting->setting_value, true);

			if ($checkJson) {
				$dataReturn[] = [
					"id" => $setting->id,
					"setting_key" => $setting->setting_key,
					"setting_value" => $checkJson
				];
			} else {
				$dataReturn[] = [
					"id" => $setting->id,
					"setting_key" => $setting->setting_key,
					"setting_value" => $setting->setting_value
				];
			}
		}
		return $this->responseData(["status" => true, "data" => $dataReturn]);
    }

	public function update(){
        if (!$this->request->is('POST')) {
            return $this->responseData(['error_code' => 100]);
        }
        $dataPost = json_decode($this->request->input(), true);
        if (!isset($dataPost['id'])) {
            return $this->responseData(['error_code' => 101]);
        }
        $settings = $this->Settings->find()->where(['Settings.id' => $dataPost['id']])->first();
        if (!$settings) {
            return $this->responseData(['error_code' => 404]);
        }
        
        $settings = $this->Settings->patchEntity($settings, $dataPost);

        if ($settings->getErrors()) {
            return $this->responseData(['error_code' => 103, 'data' => $settings->getErrors()]);
        }
        if ($this->Settings->save($settings)) {
            return $this->responseData(['status' => true]);
        } else {
            if ($settings->getErrors()) {
                return $this->responseData(['error_code' => 103, 'data' => $settings->getErrors()]);
            }
            return $this->responseData(['error_code' => 102]);
        }
    }

}
