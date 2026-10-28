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
class InstallationFormFieldsTable extends Table
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

        $this->setTable('installation_form_fields');
        $this->setPrimaryKey('id');
        
        $this->addBehavior('Timestamp');
        $this->hasMany('InstallationFormOptions', [
            'foreignKey' => 'installation_form_field_id'
        ]);
    }

    public function getInstallationFields(){
        $result = $this->find()->hydrate(false)->where(['status'=>_ID_FLAG_YES])->toArray();
        return $result;
    }

    public function getInstallationFieldById($id){
        $result = $this->find()->contain(['InstallationFormOptions'=> function($q) { 
            return $q->where(['status'=>_ID_STATUS_ACTIVE]);
        }])->hydrate(false)->where(['id'=>$id])->first();
        return $result;
    }

    public function getInstallationFieldByIdObject($id){
        $result = $this->find()->where(['id'=>$id])->first();
        return $result;
    }

    public function getInstallationFieldsByProductId($product_id,$form_id){
        $result = $this->find()->where(['product_id'=>$product_id,'installation_form_id'=>$form_id,'status'=>_ID_STATUS_ACTIVE])->order(['field_order'=>'asc'])->toArray();
        return $result;
    }
   
}
