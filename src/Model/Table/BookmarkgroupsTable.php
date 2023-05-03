<?php
namespace App\Model\Table;

use App\Model\Entity\Bookmarkgroup;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Bookmarkgroups Model
 *
 * @property \Cake\ORM\Association\BelongsToMany $Bookmarkurls
 */
class BookmarkgroupsTable extends Table
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

        $this->setTable('bookmarkgroups');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->belongsToMany('Bookmarkurls', [
            'foreignKey' => 'bookmarkgroup_id',
            'targetForeignKey' => 'bookmarkurl_id',
            'joinTable' => 'bookmarkurls_bookmarkgroups'
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
            ->notEmpty('title')
            ->add('title', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->requirePresence('comment', 'create')
            ->notEmpty('comment');

        return $validator;
    }
}
