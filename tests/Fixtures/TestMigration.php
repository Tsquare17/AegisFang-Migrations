<?php

namespace AegisFang\Tests\Fixtures;

use AegisFang\Migrations\Migration;
use AegisFang\Migrations\Table\Blueprint;

class TestMigration extends Migration
{
    public function up(Blueprint $blueprint): Blueprint
    {
        $table = $blueprint::create('migration')
            ->string('test')
            ->string('drop_column');

        return $table;
    }

    public function down(Blueprint $blueprint): Blueprint
    {
        return $blueprint;
    }
}
