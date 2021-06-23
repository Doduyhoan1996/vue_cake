<?php

use App\Model\Entity\User;
use Cake\I18n\Time;
use Migrations\AbstractSeed;

/**
 * Users seed.
 */
class UsersSeed extends AbstractSeed
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
                'id' => 1,
                'first_name' => 'Admin',
                'last_name' => 'admin',
                'email' => 'admin@gmail.com',
                'birthday' => '2019-07-07',
                'gender' => User::GENDER_MALE,
                'phone' => '0000000000',
                'address' => '250, Kim giang, Thanh Xuân, Hà Nội',
                'password' => User::generatePassword('password'),
                'position' => '',
                'type' => User::TYPE_OFFICIAL,
                'role' => User::ROLE_ADMIN,
                'can_create_project' => true,
                'salary' => 0,
                'st_tz' => 'Asia/Ho_Chi_Minh',
                'st_date_format' => 'd/m/Y',
                'status' => User::STATUS_NORMAL,
                'created' => Time::now()->format('Y-m-d H:i:s'),
                'modified' => Time::now()->format('Y-m-d H:i:s')
            ]
        ];

        $table = $this->table('users');
        $table->insert($data)->save();
    }
}
