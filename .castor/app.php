<?php

use Castor\Attribute\AsTask;
use function Castor\context;
use function Castor\run;

#[AsTask]
function start(): void
{
    run('docker compose build --no-cache');
    up();
}

#[AsTask]
function up(): void
{
    run('docker compose up -d --wait');
}

#[AsTask]
function stop(): void
{
    run('docker compose down');
}

#[AsTask]
function builder(): void
{
    $c = context()
        ->withTimeout(null)
        ->withTty()
        ->withEnvironment($_ENV + $_SERVER)
        ->withAllowFailure()
    ;
    run('docker compose exec -it php /bin/bash', context: $c);
}

#[AsTask]
function fixtures(): void
{
    $c = context()
        ->withTimeout(null)
        ->withTty()
        ->withEnvironment($_ENV + $_SERVER)
        ->withAllowFailure()
    ;
    run('docker compose exec -it php bin/console doctrine:fixtures:load --no-interaction', context: $c);
}
