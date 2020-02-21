<?php
namespace Log\Model\Entity;

use Cake\ORM\Entity;

/**
 * Log Entity
 *
 * @property int $id
 * @property string $uniqueId
 * @property string $sessionId
 * @property string $title
 * @property string $text
 * @property \Cake\I18n\FrozenTime $logEventDate
 * @property int $logType
 * @property string $applicationVersion
 * @property string $deviceId
 */
class Log extends Entity
{
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
        'id' => true,
        'uniqueId' => true,
        'sessionId' => true,
        'title' => true,
        'text' => true,
        'logEventDate' => true,
        'logType' => true,
        'applicationVersion' => true,
        'deviceId' => true,
    ];
}
