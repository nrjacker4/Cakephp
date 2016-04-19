<?php
namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use Search\Manager;
/**
 * Users Model
 *
 */
class UsersTable extends Table
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

        $this->table('users');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
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
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->notEmpty('username','Username Must be Filled Out.');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
			->add('email', 'validFormat', ['rule' => 'email','message' => 'E-mail must be valid'])
            ->notEmpty('email','Email Address Must be Filled Out.');

        $validator
            ->requirePresence('password', 'create')
            ->notEmpty('password','Password Can not be Empty');

        $validator
			->notEmpty('fname','First Name Can not be Empty');
			
        $validator
			->notEmpty('lname','Last Name Can not be Empty');
			
        $validator
            ->integer('contact')
			->add('contact', 'minlength',  ['rule'  =>  ['minLength', 10]])
       		->add('contact', 'maxlength',  ['rule'  =>  ['maxLength', 10]])
			->notEmpty('contact','The Contact Number must be filled Out');

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
        $rules->add($rules->isUnique(['username']));
        $rules->add($rules->isUnique(['email']));
        return $rules;
    }
}
