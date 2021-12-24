<?php

namespace AegisFang\Migrations\Driver;

use PDO;

class MysqlConnectionAdapter implements AdapterInterface
{
    /**
     * @var PDO
     */
    protected $connection;

    /**
     * @var string
     */
    protected $dbName;


    /**
     * @param PDO $connection
     * @param string $dbName
     */
    public function __construct(PDO $connection, string $dbName)
    {
        $this->connection = $connection;

        $this->dbName = $dbName;
    }

    /**
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * @return string
     */
    public function getDbName(): string
    {
        return $this->dbName;
    }

    /**
     * @param string $table
     *
     * @return bool
     */
    public function tableExists(string $table): bool
    {
        $query = $this->schemaQueryBuilder(
            'TABLES',
            [
                'TABLE_SCHEMA' => $this->dbName,
                'TABLE_NAME' => $table,
            ]
        );

        return (bool) $this->connection->query($query)->fetch();
    }

    /**
     * @param string $table
     * @param string $column
     *
     * @return bool
     */
    public function columnExists(string $table, string $column): bool
    {
        $query = $this->schemaQueryBuilder(
            'COLUMNS',
            [
                'TABLE_SCHEMA' => $this->dbName,
                'TABLE_NAME' => $table,
                'COLUMN_NAME' => $column,
            ]
        );

        return (bool) $this->connection->query($query)->fetch();
    }

    /**
     * @param string $table
     * @param string $column
     *
     * @return bool
     */
    public function keyExists(string $table, string $column): bool
    {
        $query = $this->schemaQueryBuilder(
            'STATISTICS',
            [
                'TABLE_SCHEMA' => $this->dbName,
                'TABLE_NAME' => $table,
                'COLUMN_NAME' => $column,
            ]
        );

        return (bool) $this->connection->query($query)->fetch();
    }

    /**
     * @param string $table
     * @param string $column
     * @param string $referencedTable
     * @param string $referencedColumn
     *
     * @return bool
     */
    public function foreignKeyExists(
        string $table,
        string $column,
        string $referencedTable,
        string $referencedColumn
    ): bool {
        $query = $this->schemaQueryBuilder(
            'KEY_COLUMN_USAGE',
            [
                'TABLE_SCHEMA' => $this->dbName,
                'TABLE_NAME' => $table,
                'COLUMN_NAME' => $column,
                'REFERENCED_TABLE_SCHEMA' => $this->dbName,
                'REFERENCED_TABLE_NAME' => $referencedTable,
                'REFERENCED_COLUMN_NAME' => $referencedColumn,
            ]
        );

        return (bool) $this->connection->query($query)->fetch();
    }

    /**
     * @param string $schema
     * @param array $params
     *
     * @return string
     */
    protected function schemaQueryBuilder(string $schema, array $params): string
    {
        $query = "SELECT * FROM INFORMATION_SCHEMA.{$schema} WHERE 1=1";

        foreach ($params as $key => $value) {
            $query .= " AND {$key} = '{$value}'";
        }

        return $query;
    }
}
