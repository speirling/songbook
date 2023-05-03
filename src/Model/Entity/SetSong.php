<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SetSong Entity.
 *
 * @property int $id
 * @property int $set_id
 * @property \App\Model\Entity\Set $set
 * @property int $song_id
 * @property \App\Model\Entity\Song $song
 * @property int $order
 * @property int $performer_id
 * @property \App\Model\Entity\Performer $performer
 * @property string $key
 * @property int $capo
 */
class SetSong extends Entity
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
        '*' => true,
        'id' => false,
    ];
}
