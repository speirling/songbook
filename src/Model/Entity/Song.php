<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Song Entity.
 *
 * @property int $id
 * @property string $title
 * @property string $written_by
 * @property string $performed_by
 * @property string $base_key
 * @property string $content
 * @property string $original_filename
 * @property string $meta_tags
 * @property \App\Model\Entity\SongInstance[] $song_instances
 * @property \App\Model\Entity\SongTag[] $song_tags
 */
class Song extends Entity
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
