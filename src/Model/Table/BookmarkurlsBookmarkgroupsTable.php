<?php
namespace App\Model\Table;

use App\Model\Entity\BookmarkurlsBookmarkgroup;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * BookmarkurlsBookmarkgroups Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Bookmarkgroups
 * @property \Cake\ORM\Association\BelongsTo $Bookmarkurls
 */
class BookmarkurlsBookmarkgroupsTable extends Table
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

        $this->setTable('bookmarkurls_bookmarkgroups');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Bookmarkgroups', [
            'foreignKey' => 'bookmarkgroup_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Bookmarkurls', [
            'foreignKey' => 'bookmarkurl_id',
            'joinType' => 'INNER'
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
        $rules->add($rules->existsIn(['bookmarkgroup_id'], 'Bookmarkgroups'));
        $rules->add($rules->existsIn(['bookmarkurl_id'], 'Bookmarkurls'));
        return $rules;
    }
}
