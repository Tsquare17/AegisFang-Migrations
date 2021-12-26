<?php

namespace AegisFang\Tests\Fixtures;

use AegisFang\Migrations\Migration;
use AegisFang\Migrations\Table\Blueprint;

class TableUpdateMigration extends Migration
{
    public function up(Blueprint $blueprint): Blueprint
    {
        $table = $blueprint::update('migration')
            ->string('test')
            ->change($blueprint->int('test'))
            ->dropColumn('drop_column');

        return $table;
    }

    public function down(Blueprint $blueprint): Blueprint
    {
        return $blueprint::drop('migration');
    }
}
