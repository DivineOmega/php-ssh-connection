<?php

use DivineOmega\SSHConnection\SSHConnection;
use PHPUnit\Framework\TestCase;

final class SSHConnectionTest extends TestCase
{
    public function testSSHConnection()
    {
        $connection = (new SSHConnection())
            ->to('test.rebex.net')
            ->onPort(22)
            ->as('demo')
            ->withPassword('password')
            ->connect();

        $command = $connection->run('echo "Hello world!"');

        $this->assertEquals('Hello world!', $command->getOutput());
        $this->assertEquals('Hello world!'."\n", $command->getRawOutput());

        $this->assertEquals('', $command->getError());
        $this->assertEquals('', $command->getRawError());
    }
}
