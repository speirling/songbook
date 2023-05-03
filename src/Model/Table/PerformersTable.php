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
 * @property \Cake\ORM\Association\HasMany $SetSongs
 * @property \Cake\ORM\Association\HasMany $Sets
 */
class PerformersTable extends Table
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

        $this->setTable('performers');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('SetSongs', [
            'foreignKey' => 'performer_id'
        ]);
        $this->hasMany('Sets', [
            'foreignKey' => 'performer_id'
        ]);
        $this->hasMany('Playlists', [
            'foreignKey' => 'performer_id'
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
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('nickname', 'create')
            ->notEmpty('nickname');

        return $validator;
    }
}
