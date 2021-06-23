<?php
use Migrations\AbstractSeed;

/**
 * Settings seed.
 */
class SettingsSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'setting_key' => 'WORKING_START_TIME',
                'setting_value' => '08:30'
            ],
            [
                'setting_key' => 'WORKING_END_TIME',
                'setting_value' => '17:30'
            ],
            [
                'setting_key' => 'WORKING_BREAK_START',
                'setting_value' => '12:00'
            ],
            [
                'setting_key' => 'WORKING_BREAK_END',
                'setting_value' => '13:00'
            ],
            [
                'setting_key' => 'CHECK_IN_BEFORE',
                'setting_value' => '30'
            ],
            [
                'setting_key' => 'CHECK_IN_AFTER',
                'setting_value' => '10'
            ],
            [
                'setting_key' => 'ALLOW_IP',
                'setting_value' => '127.0.0.1'
            ]
        ];

        $table = $this->table('settings');
        $table->insert($data)->save();
    }
}
