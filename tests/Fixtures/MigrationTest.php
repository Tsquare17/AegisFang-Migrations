<?php

namespace AegisFang\Tests\Fixtures;

use AegisFang\Migrations\Migration;
use AegisFang\Migrations\Table\Blueprint;

class MigrationTest extends Migration
{
    public function up(Blueprint $blueprint): Blueprint
    {
        $table = $blueprint::create('migration')
            ->string('test');

        return $table;
    }

    public function down(Blueprint $blueprint): Blueprint
    {
        if ($this->tableExists('migration')) {
            if ($this->columnExists('test', 'migration')) {
                return $blueprint::drop('migration');
            }
        }

        return $blueprint;
    }
}
