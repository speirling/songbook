<?php
namespace App\Model\Table;

use App\Model\Entity\SongInstance;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SongInstances Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Songs
 * @property \Cake\ORM\Association\BelongsTo $Performers
 */
class SongInstancesTable extends Table
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

        $this->table('song_instances');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Songs', [
            'foreignKey' => 'song_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Performers', [
            'foreignKey' => 'performer_id',
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
            ->requirePresence('key', 'create')
            ->notEmpty('key');

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
        $rules->add($rules->existsIn(['song_id'], 'Songs'));
        $rules->add($rules->existsIn(['performer_id'], 'Performers'));
        return $rules;
    }
}
