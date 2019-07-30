<?php

use DivineOmega\SSHConnection\SSHConnection;

require_once __DIR__.'/../vendor/autoload.php';

$connection = (new SSHConnection())
    ->to('test.rebex.net')
    ->onPort(22)
    ->as('demo')
    ->withPassword('password')
    ->connect();

$command = $connection->run('ls -lah');

var_dump($command->getOutput());
var_dump($command->getError());
