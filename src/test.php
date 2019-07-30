<?php

use DivineOmega\SSHConnection\SSHConnection;

require_once __DIR__.'/../vendor/autoload.php';

$connection = (new SSHConnection())
    ->to('test.rebex.net')
    ->onPort(22)
    ->as('demo')
    ->withPassword('password')
    ->connect();

$outputs = $connection->run([
    'ls -lah',
    'echo "Hello world!"',
]);

foreach($outputs as $output) {
    var_dump($output->getOutput());
    var_dump($output->getError());
}
