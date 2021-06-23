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
		return $this->responseData(["status" => true, "data" => $dataReturn]);
    }
}
