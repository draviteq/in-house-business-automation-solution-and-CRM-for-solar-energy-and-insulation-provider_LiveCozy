<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * InputTypes Model
 *
 * @method \App\Model\Entity\InputType get($primaryKey, $options = [])
 * @method \App\Model\Entity\InputType newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\InputType[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\InputType|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InputType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\InputType[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\InputType findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class InputTypesTable extends Table
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

        $this->setTable('input_types');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

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
            ->scalar('name')
            ->maxLength('name', 512)
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('status', 'create')
            ->notEmpty('status');

        return $validator;
    }
    
    /**
     * Get Input type
     * @return  Multidimensional array  get All Input Type where status is active.
     */
    public function getInputTypes(){
        $results = $this->find()->where(['status'=>_ID_STATUS_ACTIVE])->hydrate(false)->toArray();
        return $results;
    }
    
    /**
     * Get Input Type
     * @param Integer $id 
     * @return Array 
     */
    public function getInputType($id = 0){
        $result = $this->find()->where(['id'=>$id ,'status' => _ID_STATUS_ACTIVE])->hydrate(false)->first();
        return $result;
    }
}
