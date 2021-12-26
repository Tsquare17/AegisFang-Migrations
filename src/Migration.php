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
    protected $builder;

    /**
     * @var string
     */
    protected $blueprintClass;

    /**
     * @var Blueprint
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
     * @param string $builder
     * @param string $blueprintClass
     */
    public function __construct(AdapterInterface $connection, string $builder, string $blueprintClass)
    {
        $this->connection = $connection;

        $this->builder = $builder;

        $this->blueprintClass = $blueprintClass;
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
        $this->blueprint = $this->up(new $this->blueprintClass());

        $this->table = new $this->builder($this->connection, $this->blueprint);

        return $this->{$this->blueprint->action['action']}();
    }

    /**
     * @return Blueprint
     */
    abstract public function down(Blueprint $blueprint);

    final public function reverse(): bool
    {
        $this->blueprint = $this->down(new $this->blueprintClass());

        $this->table = new $this->builder($this->connection, $this->blueprint);

        return $this->{$this->blueprint->action['action']}();
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

    final public function update(): bool
    {
        return $this->table->updateTable();
    }

    /**
     * @return bool
     */
    final public function drop(): bool
    {
        return call_user_func([$this->builder, 'destroy'], $this->connection, $this->blueprint->action['table']);
    }

    /**
     * @param string $table
     *
     * @return bool
     */
    final public function tableExists(string $table): bool
    {
        $blueprint = new $this->blueprintClass();
        $blueprint->action['table'] = $table;

        $this->table = new $this->builder($this->connection, $blueprint);

        return $this->table->tableExists();
    }

    /**
     * @param string $column
     * @param string $table
     *
     * @return bool
     */
    public function columnExists(string $column, string $table): bool
    {
        $blueprint = new $this->blueprintClass();
        $blueprint->action['table'] = $table;

        $this->table = new $this->builder($this->connection, $blueprint);

        return $this->table->columnExists($column);
    }
}
