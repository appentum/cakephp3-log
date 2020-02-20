<?php
namespace Log\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Log Model
 *
 * @method \Log\Model\Entity\Log get($primaryKey, $options = [])
 * @method \Log\Model\Entity\Log newEntity($data = null, array $options = [])
 * @method \Log\Model\Entity\Log[] newEntities(array $data, array $options = [])
 * @method \Log\Model\Entity\Log|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Log\Model\Entity\Log saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Log\Model\Entity\Log patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Log\Model\Entity\Log[] patchEntities($entities, array $data, array $options = [])
 * @method \Log\Model\Entity\Log findOrCreate($search, callable $callback = null, $options = [])
 */
class LogTable extends Table
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

        $this->setTable('logger_log');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');
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
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('uniqueId')
            ->maxLength('uniqueId', 255)
            ->requirePresence('uniqueId', 'create')
            ->notEmptyString('uniqueId');

        $validator
            ->scalar('sessionId')
            ->maxLength('sessionId', 255)
            ->requirePresence('sessionId', 'create')
            ->notEmptyString('sessionId');

        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('text')
            ->maxLength('text', 16777215)
            ->requirePresence('text', 'create')
            ->notEmptyString('text');

        $validator
            ->dateTime('logEventDate')
            ->requirePresence('logEventDate', 'create')
            ->notEmptyDateTime('logEventDate');

        $validator
            ->integer('logType')
            ->requirePresence('logType', 'create')
            ->notEmptyString('logType');

        $validator
            ->scalar('applicationVersion')
            ->maxLength('applicationVersion', 255)
            ->requirePresence('applicationVersion', 'create')
            ->notEmptyString('applicationVersion');

        $validator
            ->scalar('deviceId')
            ->maxLength('deviceId', 255)
            ->requirePresence('deviceId', 'create')
            ->notEmptyString('deviceId');

        return $validator;
    }
}
