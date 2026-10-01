<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Marcas Model
 *
 * @method \App\Model\Entity\Marca newEmptyEntity()
 * @method \App\Model\Entity\Marca newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Marca> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Marca get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Marca findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Marca patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Marca> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Marca|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Marca saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Marca>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Marca>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Marca>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Marca> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Marca>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Marca>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Marca>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Marca> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MarcasTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('marcas');
        $this->setDisplayField('nome');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
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
            ->scalar('nome')
            ->maxLength('nome', 180)
            ->requirePresence('nome', 'create')
            ->notEmptyString('nome');

        $validator
            ->dateTime('deleted')
            ->allowEmptyDateTime('deleted');

        return $validator;
    }
}
