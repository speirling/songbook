<?php
namespace App\Model\Table;

use App\Model\Entity\Bookmarkurl;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Bookmarkurls Model
 *
 * @property \Cake\ORM\Association\BelongsToMany $Bookmarkgroups
 */
class BookmarkurlsTable extends Table
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

        $this->setTable('bookmarkurls');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->belongsToMany('Bookmarkgroups', [
            'foreignKey' => 'bookmarkurl_id',
            'targetForeignKey' => 'bookmarkgroup_id',
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
            ->requirePresence('query_string', 'create')
            ->notEmpty('query_string');

        return $validator;
    }
}
