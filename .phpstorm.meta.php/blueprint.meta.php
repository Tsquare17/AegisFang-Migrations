<?php

namespace PHPSTORM_META {
    expectedArguments(
        \AegisFang\Migrations\Table\Blueprint::onUpdate(),
        0,
        'CASCADE',
        'SET NULL',
        'RESTRICT',
        'NO ACTION',
        'SET DEFAULT',
    );

    expectedArguments(
        \AegisFang\Migrations\Table\Blueprint::onDelete(),
        0,
        'CASCADE',
        'SET NULL',
        'RESTRICT',
        'NO ACTION',
        'SET DEFAULT',
    );
}
