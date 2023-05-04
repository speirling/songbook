<?php
namespace App\Model\Table;

use App\Model\Entity\SetSong;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SetSongs Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Sets
 * @property \Cake\ORM\Association\BelongsTo $Songs
 * @property \Cake\ORM\Association\BelongsTo $Performers
 */
class SetSongsTable extends Table
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

        $this->setTable('set_songs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Sets', [])
        ->setForeignKey('set_id')
        ->setJoinType('INNER')
        ;
        
        $this->belongsTo('Songs', [])
        ->setForeignKey('song_id')
        ->setJoinType('INNER')
        ;
        
        $this->belongsTo('Performers')
        ->setForeignKey('performer_id')
        ->setJoinType('INNER')
       ;
        
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
            ->add('order', 'valid', ['rule' => 'numeric'])
            ->allowEmptyString('order');

        $validator
            ->allowEmptyString('key');

        $validator
            ->add('capo', 'valid', ['rule' => 'numeric'])
            ->allowEmptyString('capo');

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
        $rules->add($rules->existsIn(['set_id'], 'Sets'));
        $rules->add($rules->existsIn(['song_id'], 'Songs'));
        $rules->add($rules->existsIn(['performer_id'], 'Performers'));
        return $rules;
    }
}
