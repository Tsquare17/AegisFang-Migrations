<?php

namespace AegisFang\Migrations\Tests;

use AegisFang\Migrations\Driver\AdapterInterface;
use AegisFang\Migrations\Driver\MysqlConnectionAdapter;
use AegisFang\Migrations\Table\MysqlBlueprint;
use AegisFang\Migrations\Table\MysqlBuilder;
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
        $blueprint = MysqlBlueprint::create('aegistest')
            ->id('test_id')
            ->string('testcol', 100)
            ->int('testint', true, true)
            ->text('text_col');

        $table = new MysqlBuilder($this->connection, $blueprint);
        $isCreated = $table->createTable();

        $this->assertTrue($isCreated);
    }

    /** @test */
    public function cannot_create_already_existing_table()
    {
        $blueprint = MysqlBlueprint::create('aegistest')
            ->id('test_id');

        $table = new MysqlBuilder($this->connection, $blueprint);
        $isCreated = $table->createTable();

        $this->assertFalse($isCreated);
    }

    /** @test */
    public function can_create_table_with_all_integer_types(): void
    {
        $blueprint = MysqlBlueprint::create('int_test')
            ->tinyint('tiny_col')
            ->int('int_col')
            ->bigint('big_col');

        $table = new MysqlBuilder($this->connection, $blueprint);
        $isCreated = $table->createTable();

        $this->assertTrue($isCreated);
    }

    /** @test */
    public function can_create_table_with_relationship(): void
    {
        $blueprint = MysqlBlueprint::create('foo')
            ->int('foo_id');
        $table = new MysqlBuilder($this->connection, $blueprint);
        $table->createTable();

        $blueprint = MysqlBlueprint::create('bar')
            ->int('foo_id')
            ->references('foo_id')
            ->on('foo')
            ->onUpdate('CASCADE')
            ->onDelete('CASCADE');
        $table = new MysqlBuilder($this->connection, $blueprint);
        $isCreated = $table->createTable();
        $table->createRelationships();

        $this->assertTrue($isCreated);

        $foreignKeyExists = $table->foreignKeyExists('foo_id', 'foo', 'foo_id');
        $this->assertTrue($foreignKeyExists);
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
