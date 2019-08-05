<?php

use DivineOmega\SSHConnection\SSHConnection;
use PHPUnit\Framework\TestCase;

final class SSHConnectionTest extends TestCase
{
    public function testNoHostname()
    {
        $this->expectException(InvalidArgumentException::class);

        (new SSHConnection())
            ->onPort(22)
            ->as('travis')
            ->withKeyPair('/home/travis/.ssh/id_rsa.pub', '/home/travis/.ssh/id_rsa')
            ->connect();
    }

    public function testNoUsername()
    {
        $this->expectException(InvalidArgumentException::class);

        (new SSHConnection())
            ->to('localhost')
            ->onPort(22)
            ->withKeyPair('/home/travis/.ssh/id_rsa.pub', '/home/travis/.ssh/id_rsa')
            ->connect();
    }

    public function testNoAuthentication()
    {
        $this->expectException(InvalidArgumentException::class);

        (new SSHConnection())
            ->to('localhost')
            ->onPort(22)
            ->as('travis')
            ->connect();
    }
}
