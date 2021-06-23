<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Calendar Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $type
 * @property \Cake\I18n\FrozenTime $date_start
 * @property \Cake\I18n\FrozenTime $date_end
 * @property string $note
 * @property int $status
 * @property int|null $confirm_by
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\User $user
 */
class Calendar extends Entity
{

    const STATUS_DENY           = -1;
    const STATUS_WAITING        = 0;
    const STATUS_ACCEPT         = 1;
    

    const TYPE_DAY_OFF          = 0;
    const TYPE_MEETING_ROOM     = 1;

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

    protected $_virtual = ['color', 'textColor'];
    

    protected function _getColor() {
        $color = $this->type == self::TYPE_DAY_OFF ? '#17a2b8' : '#007bff';
        return $color ;
    }
    protected function _getTextColor() {
        $textColor = '';
        if($this->type == self::TYPE_DAY_OFF){
            if($this->status == self::STATUS_WAITING) {
                $textColor = '#ffc107';
            } else if($this->status == self::STATUS_DENY) {
                $textColor = '#dc3545';
            } else $textColor = 'black';
        } else $textColor = 'black';

        return $textColor ;
    }
}
