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
        return $blueprint::drop('migration');
    }
}
