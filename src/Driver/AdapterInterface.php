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
     * @return mixed
     */
    public function tableExists(string $table);
}
