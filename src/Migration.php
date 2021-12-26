<?php

namespace AegisFang\Migrations;

use AegisFang\Migrations\Driver\AdapterInterface;
use AegisFang\Migrations\Table\Blueprint;
use AegisFang\Migrations\Table\Builder;

abstract class Migration
{
    /**
     * @var AdapterInterface
     */
    protected $connection;

    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var string
     */
    protected $builder;

    /**
     * @var string
     */
    protected $blueprint;

    /**
     * @var Builder
     */
    protected $table;


    /**
     * Migration constructor.
     *
     * @param AdapterInterface $connection
     * @param string $tableName
     * @param string $builder
     * @param string $blueprint
     */
    public function __construct(AdapterInterface $connection, string $tableName, string $builder, string $blueprint)
    {
        $this->connection = $connection;

        $this->tableName = $tableName;

        $this->builder = $builder;

        $this->blueprint = $blueprint;
    }

    /**
     * @param Blueprint $blueprint
     *
     * @return mixed
     */
    abstract public function up(Blueprint $blueprint);

    /**
     * @return bool
     */
    final public function run(): bool
    {
        $blueprint = $this->up(new $this->blueprint());

        $this->table = new $this->builder($this->connection, $blueprint);

        return $this->{$blueprint->action['action']}();
    }

    /**
     * @return Blueprint
     */
    abstract public function down(Blueprint $blueprint);

    final public function reverse(): bool
    {
        $blueprint = $this->down(new $this->blueprint());

        $this->table = new $this->builder($this->connection, $blueprint);

        return $this->{$blueprint->action['action']}();
    }

    /**
     * @return bool
     */
    final public function create(): bool
    {
        $isCreated = $this->table->createTable();

        $this->table->createRelationships();

        return $isCreated;
    }

    /**
     * @return bool
     */
    final public function drop(): bool
    {
        return call_user_func([$this->builder, 'destroy'], $this->connection, $this->tableName);
    }
}
