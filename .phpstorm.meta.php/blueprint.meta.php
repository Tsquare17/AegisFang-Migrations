<?php

namespace PHPSTORM_META {
    expectedArguments(
        \AegisFang\Migrations\Table\MysqlBlueprint::onUpdate(),
        0,
        'CASCADE',
        'SET NULL',
        'RESTRICT',
        'NO ACTION',
        'SET DEFAULT',
    );

    expectedArguments(
        \AegisFang\Migrations\Table\MysqlBlueprint::onDelete(),
        0,
        'CASCADE',
        'SET NULL',
        'RESTRICT',
        'NO ACTION',
        'SET DEFAULT',
    );

    expectedArguments(
        \AegisFang\Migrations\Table\MysqlBlueprint::setEngine(),
        0,
        'InnoDB',
        'MyISAM',
        'MEMORY',
        'CSV',
        'ARCHIVE',
        'EXAMPLE',
        'FEDERATED',
        'HEAP',
        'MERGE',
        'NDB'
    );

    expectedArguments(
        \AegisFang\Migrations\Table\MysqlBlueprint::setCharset(),
        0,
        'utf8',
    );
}
