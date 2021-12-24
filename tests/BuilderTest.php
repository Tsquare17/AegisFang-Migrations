<?php

namespace AegisFang\Migrations\Tests;

use AegisFang\Migrations\Driver\AdapterInterface;
use AegisFang\Migrations\Driver\MysqlConnectionAdapter;
use AegisFang\Migrations\Table\MysqlBlueprint;
use AegisFang\Migrations\Table\MysqlBuilder;
use AegisFang\Tests\Fixtures\MigrationTest;
use PDO;
use PHPUnit\Framework\TestCase;

class BuilderTest extends TestCase
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
    public function can_create_table(): void
    {
        $blueprint = new MysqlBlueprint();
        $blueprint->id('test_id');
        $blueprint->string('testcol', 100);
        $blueprint->int('testint', true, true);
        $blueprint->text('text_col');

        $table = new MysqlBuilder($this->connection, 'aegistest', $blueprint);
        $isCreated = $table->createTable();

        $this->assertTrue($isCreated);
    }

    /** @test */
    public function cannot_create_already_existing_table()
    {
        $blueprint = new MysqlBlueprint();
        $blueprint->id('test_id');

        $table = new MysqlBuilder($this->connection, 'aegistest', $blueprint);
        $isCreated = $table->createTable();

        $this->assertFalse($isCreated);
    }

    /** @test */
    public function can_create_table_with_all_integer_types(): void
    {
        $blueprint = new MysqlBlueprint();

        $blueprint->tinyint('tiny_col');
        $blueprint->int('int_col');
        $blueprint->bigint('big_col');

        $table = new MysqlBuilder($this->connection, 'int_test', $blueprint);
        $isCreated = $table->createTable();

        $this->assertTrue($isCreated);
    }

    /** @test */
    public function can_create_table_with_relationship(): void
    {
        $blueprint = new MysqlBlueprint();
        $blueprint->int('foo_id');
        $table = new MysqlBuilder($this->connection, 'foo', $blueprint);
        $table->createTable();

        $blueprint = new MysqlBlueprint();
        $blueprint->int('foo_id')
            ->references('foo_id')
            ->on('foo');
        $table = new MysqlBuilder($this->connection, 'bar', $blueprint);
        $isCreated = $table->createTable();
        $table->createRelationships();

        $this->assertTrue($isCreated);

        $foreignKeyExists = $table->foreignKeyExists('foo_id', 'foo', 'foo_id');
        $this->assertTrue($foreignKeyExists);
    }

    /** @test */
    public function can_run_migration(): void
    {
        $migration = new MigrationTest(
            $this->connection,
            'migration',
            '\AegisFang\Migrations\Table\MysqlBuilder',
            '\AegisFang\Migrations\Table\MysqlBlueprint'
        );

        $isCreated = $migration->make();

        $this->assertTrue($isCreated);

        $isDestroyed = $migration->unmake();

        $this->assertTrue($isDestroyed);
    }

    /** @test */
    public function can_delete_tables(): void
    {
        $isDestroyed = MysqlBuilder::destroy($this->connection, 'aegistest');

        $this->assertTrue($isDestroyed);

        MysqlBuilder::destroy($this->connection, 'int_test');
        MysqlBuilder::destroy($this->connection, 'bar');
        MysqlBuilder::destroy($this->connection, 'foo');
    }
}
