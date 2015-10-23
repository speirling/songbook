<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PlaylistSet Entity.
 *
 * @property int $id
 * @property int $set_id
 * @property \App\Model\Entity\Set $set
 * @property int $playlist_id
 * @property \App\Model\Entity\Playlist $playlist
 * @property int $order
 */
class PlaylistSet extends Entity
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
