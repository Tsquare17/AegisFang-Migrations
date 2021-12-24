<?php

namespace AegisFang\Migrations\Driver;

use PDO;

interface AdapterInterface
{
    /**
     * @return PDO
     */
    public function getConnection(): PDO;

    /**
     * @return string
     */
    public function getDbName(): string;

    /**
     * @param string $table
     *
     * @return bool
     */
    public function tableExists(string $table): bool;

    /**
     * @param string $table
     * @param string $column
     *
     * @return bool
     */
    public function columnExists(string $table, string $column): bool;

    /**
     * @param string $table
     * @param string $column
     *
     * @return bool
     */
    public function keyExists(string $table, string $column): bool;

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
    ): bool;
}
