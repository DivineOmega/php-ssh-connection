<?php


namespace DivineOmega\SSHConnection;


class SSHConnection
{
    private $hostname;
    private $port = 22;
    private $username;
    private $password;
    private $publicKeyPath;
    private $privateKeyPath;
    private $connected;

    public function to(string $hostname): self
    {
        $this->hostname = $hostname;
        return $this;
    }

    public function onPort(int $port): self
    {
        $this->port = $port;
        return $this;
    }

    public function as(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function withPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function withKeyPair(string $publicKeyPath, string $privateKeyPath): self
    {
        $this->publicKeyPath = $publicKeyPath;
        $this->privateKeyPath = $privateKeyPath;
        return $this;
    }

    public function connect()
    {
        // TODO: Sanity check required variables.
        // TODO: Make SSH connection.

        $this->connected = true;
    }

    public function run($commands)
    {
        if (is_string($commands)) {
            $commands = [$commands];
        }

        if (!is_array($commands)) {
            throw new \InvalidArgumentException('Command(s) passed should be a string or array.');
        }

        if (!$this->connected) {
            throw new \RuntimeException('Unable to run commands when not connected.');
        }

        // TODO: Execute commands
    }
}