<?php
namespace App\Model\Table;

use App\Model\Entity\Playlist;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Playlists Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Performers
 * @property \Cake\ORM\Association\HasMany $PlaylistSets
 */
class PlaylistsTable extends Table
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

        $this->setTable('playlists');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->belongsTo('Performers', [
            'foreignKey' => 'performer_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('PlaylistSets', [
            'foreignKey' => 'playlist_id'
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
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('title', 'create')
            ->notEmpty('title');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['performer_id'], 'Performers'));
        return $rules;
    }
}
