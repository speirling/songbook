<?php
namespace App\Model\Table;

use App\Model\Entity\Song;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Songs Model
 *
 * @property \Cake\ORM\Association\HasMany $SongInstances
 * @property \Cake\ORM\Association\HasMany $SongTags
 */
class SongsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('songs'); //matches the default - no need to specify
        $this->setDisplayField('title'); //matches default??
        $this->setPrimaryKey('id'); //matches the default - no need to specify

        $this->hasMany('SetSongs', [
            'foreignKey' => 'song_id'
        ]);

        $this->hasMany('SongTags', [
            'foreignKey' => 'song_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmptyString('id', 'create');

        $validator
            ->requirePresence('title', 'create')
            ->allowEmptyString('title');

        $validator
            ->allowEmptyString('written_by');

        $validator
            ->allowEmptyString('performed_by');

        $validator
            ->allowEmptyString('base_key');

        $validator
            ->allowEmptyString('content');

        $validator
            ->allowEmptyString('original_filename');

        $validator
            ->allowEmptyString('meta_tags');

        return $validator;
    }
}
