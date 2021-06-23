<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property \Cake\I18n\FrozenDate $birthday
 * @property int $gender
 * @property string|null $phone
 * @property string|null $address
 * @property string $password
 * @property string|null $position
 * @property int $type
 * @property int $role
 * @property bool $can_create_project
 * @property int $salary
 * @property string|null $st_tz
 * @property string|null $st_date_format
 * @property int $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Attendance[] $attendances
 * @property \App\Model\Entity\ProjectMember[] $project_members
 */
class User extends Entity
{
    const STATUS_NORMAL  = 1;
    const STATUS_BLOCKED = 0;

    const TYPE_OFFICIAL  = 1;
    const TYPE_TRAINEE   = 9;

    const GENDER_MALE    = 1;
    const GENDER_FEMALE  = 2;

    const ROLE_ADMIN = 1;
    const ROLE_HR = 2;
    const ROLE_USER = 9;

    public static function generatePassword($plainPassword) {
        return md5(env('SECURITY_SALT', 'dev'). $plainPassword);
    }
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        
    ];

    protected $_virtual = ['full_name'];

    protected function _getFullName() {
        return ($this->type == self::TYPE_TRAINEE ? '[TT] ' : '') . $this->first_name . ' ' . $this->last_name;
    }
}
