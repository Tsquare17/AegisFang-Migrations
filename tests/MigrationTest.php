<?php

namespace AegisFang\Migrations\Tests;

use AegisFang\Migrations\Driver\AdapterInterface;
use AegisFang\Migrations\Driver\MysqlConnectionAdapter;
use AegisFang\Tests\Fixtures\TableUpdateMigration;
use AegisFang\Tests\Fixtures\TestMigration;
use PDO;
use PHPUnit\Framework\TestCase;

class MigrationTest extends TestCase
{
    /**
     * @var AdapterInterface
     */
    protected $connection;

    public function setUp(): void
    {
        $pdo = new PDO(
            getenv('DB_CONNECTION') . ':host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_NAME'),
            getenv('DB_USERNAME'),
            getenv('DB_PASSWORD')
        );

        $this->connection = new MysqlConnectionAdapter($pdo, 'aegisfang_migrations_test');
    }

    /** @test */
    public function can_run_migration(): void
    {
        $migration = new TestMigration(
            $this->connection,
            '\AegisFang\Migrations\Table\MysqlBuilder',
            '\AegisFang\Migrations\Table\MysqlBlueprint'
        );

        $isCreated = $migration->run();

        $this->assertTrue($isCreated);
    }

    /** @test */
    public function can_update_table(): void
    {
        $migration = new TableUpdateMigration(
            $this->connection,
            '\AegisFang\Migrations\Table\MysqlBuilder',
            '\AegisFang\Migrations\Table\MysqlBlueprint'
        );

        $isUpdated = $migration->run();

        $this->assertTrue($isUpdated);
    }

    /** @test */
    public function can_run_migration_down(): void
    {
        $migration = new TableUpdateMigration(
            $this->connection,
            '\AegisFang\Migrations\Table\MysqlBuilder',
            '\AegisFang\Migrations\Table\MysqlBlueprint'
        );

        $isDestroyed = $migration->reverse();

        $this->assertTrue($isDestroyed);
    }
}
