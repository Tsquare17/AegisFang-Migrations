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
        $query = sprintf(
            "SELECT * FROM information_schema.tables WHERE table_schema = '%s' AND table_name='%s'",
            $this->dbName,
            $table
        );

        return (bool) $this->connection->query($query)->fetch();
    }
}
