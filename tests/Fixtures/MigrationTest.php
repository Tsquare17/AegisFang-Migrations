<?php

namespace AegisFang\Tests\Fixtures;

use AegisFang\Migrations\Migration;
use AegisFang\Migrations\Table\Blueprint;

class MigrationTest extends Migration
{
    public function table(Blueprint $blueprint): Blueprint
    {
        $blueprint->string('test');

        return $blueprint;
    }
}
