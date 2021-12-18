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

    protected const CREATETABLE = self::CREATETABLE;
    protected const DROPTABLE = self::DROPTABLE;
    protected const PRIMARYKEY = self::PRIMARYKEY;


    /**
     * Builder constructor.
     *
     * @param AdapterInterface $adapter
     * @param string $table
     * @param Blueprint|null $blueprint
     */
    public function __construct(AdapterInterface $adapter, string $table, Blueprint $blueprint = null)
    {
        $this->connectionAdapter = $adapter;
        $this->table = $table;

        if ($blueprint) {
            $this->id = $blueprint->id ?: 'id';
            $this->columns = $blueprint->columns;
            $this->relationships = $blueprint->relationships;
        }
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
    public function tableExists(): bool
    {
        return $this->connectionAdapter->tableExists($this->table);
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
