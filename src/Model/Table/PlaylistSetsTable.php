<?php
namespace App\Model\Table;

use App\Model\Entity\PlaylistSet;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PlaylistSets Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Sets
 * @property \Cake\ORM\Association\BelongsTo $Playlists
 */
class PlaylistSetsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('playlist_sets');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Sets', [
            'foreignKey' => 'set_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Playlists', [
            'foreignKey' => 'playlist_id',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->add('order', 'valid', ['rule' => 'numeric'])
            ->requirePresence('order', 'create')
            ->allowEmpty('order');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['set_id'], 'Sets'));
        $rules->add($rules->existsIn(['playlist_id'], 'Playlists'));
        return $rules;
    }
}
