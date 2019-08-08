<?php

use DivineOmega\SSHConnection\SSHConnection;
use PHPUnit\Framework\TestCase;

final class SSHConnectionTest extends TestCase
{
    public function testUpload()
    {
        $connection = (new SSHConnection())
            ->to('localhost')
            ->onPort(22)
            ->as('travis')
            ->withPrivateKey('/home/travis/.ssh/id_rsa')
            ->connect();

        $remotePath =  __DIR__ . '/../fixtures/upload.txt';
        $localPath = __DIR__ . '/../fixtures/file.txt';

        $this->assertTrue($connection->upload($localPath, $remotePath));
        $this->assertFileExists($remotePath);
    }

    public function testDownload()
    {
        $connection = (new SSHConnection())
            ->to('localhost')
            ->onPort(22)
            ->as('travis')
            ->withPrivateKey('/home/travis/.ssh/id_rsa')
            ->connect();

        $remotePath =  __DIR__ . '/../fixtures/file.txt';
        $localPath = __DIR__ . '/../fixtures/download.txt';

        $this->assertTrue($connection->download($remotePath, $localPath));
        $this->assertFileExists($localPath);
    }

    public function testSSHConnectionWithKeyPair()
    {
        $connection = (new SSHConnection())
            ->to('localhost')
            ->onPort(22)
            ->as('travis')
            ->withPrivateKey('/home/travis/.ssh/id_rsa')
            ->connect();

        $command = $connection->run('echo "Hello world!"');

        $this->assertEquals('Hello world!', $command->getOutput());
        $this->assertEquals('Hello world!'."\n", $command->getRawOutput());

        $this->assertEquals('', $command->getError());
        $this->assertEquals('', $command->getRawError());
    }

    public function testSSHConnectionWithPassword()
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
