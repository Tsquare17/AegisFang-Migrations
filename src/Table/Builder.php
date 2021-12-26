<?php

namespace AegisFang\Migrations\Table;

use AegisFang\Migrations\Driver\AdapterInterface;

abstract class Builder
{
    /**
     * @var AdapterInterface
     */
    protected $connectionAdapter;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var array
     */
    protected $columns;

    /**
     * @var array
     */
    protected $relationships;

    /**
     * @var string
     */
    protected $statement;

    /**
     * @var string
     */
    protected $engine;

    /**
     * @var string
     */
    protected $charset;

    /**
     * @var Blueprint
     */
    protected $blueprint;

    protected const CREATETABLE = self::CREATETABLE;
    protected const DROPTABLE = self::DROPTABLE;
    protected const PRIMARYKEY = self::PRIMARYKEY;


    /**
     * @param AdapterInterface $adapter
     * @param Blueprint|null $blueprint
     */
    public function __construct(AdapterInterface $adapter, Blueprint $blueprint)
    {
        $this->connectionAdapter = $adapter;

        $this->initializeBlueprintProperties($blueprint);
    }

    public function initializeBlueprintProperties(Blueprint $blueprint): void
    {
        $this->table = $blueprint->action['table'];
        $this->id = $blueprint->id ?: 'id';
        $this->columns = $blueprint->columns;
        $this->relationships = $blueprint->relationships;
        $this->engine = $blueprint->engine;
        $this->charset = $blueprint->charset;
    }

    /**
     * @return bool
     */
    abstract public function createTable(): bool;

    /**
     * Create foreign key relationships.
     */
    abstract public function createRelationships(): void;

    /**
     * @return bool
     */
    abstract public function updateTable(): bool;

    /**
     * @return bool
     */
    public function tableExists(): bool
    {
        return $this->connectionAdapter->tableExists($this->table);
    }

    /**
     * @param string $column
     *
     * @return bool
     */
    public function columnExists(string $column): bool
    {
        return $this->connectionAdapter->columnExists($this->table, $column);
    }

    /**
     * @param string $column
     * @param string $referencedTable
     * @param string $referencedColumn
     *
     * @return bool
     */
    public function foreignKeyExists(string $column, string $referencedTable, string $referencedColumn): bool
    {
        return $this->connectionAdapter->foreignKeyExists($this->table, $column, $referencedTable, $referencedColumn);
    }

    /**
     * @return void
     */
    abstract public function setColumns(): void;

    /**
     * @return void
     */
    abstract public function closeTable(): void;

    /**
     * @param AdapterInterface $connectionAdapter
     * @param string $table
     *
     * @return bool
     */
    abstract public static function destroy(AdapterInterface $connectionAdapter, string $table): bool;

    /**
     * @param $statement
     *
     * @return void
     */
    protected function statement($statement): void
    {
        $this->statement = $statement;
    }

    /**
     * @return bool
     */
    public function execute(): bool
    {
        $statement = $this->connectionAdapter->getConnection()->prepare($this->statement);

        $statement->execute();

        return true;
    }
}
