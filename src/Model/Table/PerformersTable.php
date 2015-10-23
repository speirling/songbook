<?php
namespace App\Model\Table;

use App\Model\Entity\Performer;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Performers Model
 *
 * @property \Cake\ORM\Association\HasMany $SongInstances
 */
class PerformersTable extends Table
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

        $this->table('performers');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->hasMany('SongInstances', [
            'foreignKey' => 'performer_id'
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
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('nickname', 'create')
            ->notEmpty('nickname');

        return $validator;
    }
}
