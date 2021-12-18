<?php

namespace AegisFang\Migrations;

use AegisFang\Migrations\Driver\AdapterInterface;
use AegisFang\Migrations\Table\Blueprint;

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
     * @return Blueprint
     */
    abstract public function table(Blueprint $blueprint): Blueprint;

    /**
     * @return bool
     */
    public function make(): bool
    {
        $table = new $this->builder($this->connection, $this->tableName, $this->table(new $this->blueprint()));

        $isCreated = $table->createTable();

        $table->createRelationships();

        return $isCreated;
    }

    /**
     * @return bool
     */
    public function unmake(): bool
    {
        return call_user_func([$this->builder, 'destroy'], $this->connection, $this->tableName);
    }
}
