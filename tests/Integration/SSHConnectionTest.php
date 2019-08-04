<?php

use DivineOmega\SSHConnection\SSHConnection;
use PHPUnit\Framework\TestCase;

final class SSHConnectionTest extends TestCase
{
    public function testSSHConnection()
    {
        $connection = (new SSHConnection())
            ->to('localhost')
            ->onPort(22)
            ->as('travis')
            ->withKeyPair('/home/travis/.ssh/id_rsa.pub', '/home/travis/.ssh/id_rsa')
            ->connect();

        $command = $connection->run('echo "Hello world!"');

        $this->assertEquals('Hello world!', $command->getOutput());
        $this->assertEquals('Hello world!'."\n", $command->getRawOutput());

        $this->assertEquals('', $command->getError());
        $this->assertEquals('', $command->getRawError());
    }
}
