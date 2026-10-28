<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AdditionalCosts Model
 *
 * @method \App\Model\Entity\AdditionalCost get($primaryKey, $options = [])
 * @method \App\Model\Entity\AdditionalCost newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AdditionalCost[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AdditionalCost|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AdditionalCost patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AdditionalCost[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AdditionalCost findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class InstallationFormMultipleChoiceValuesTable extends Table
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

        $this->setTable('installation_form_multiple_choice_values');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->belongsTo('InstallationDynamicFormValues', [
            'foreignKey' => 'installation_dynamic_form_value_id',
            'joinType' => 'INNER'
        ]);
    }
   
}
