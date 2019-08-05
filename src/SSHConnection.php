<?php

namespace DivineOmega\SSHConnection;

use InvalidArgumentException;
use RuntimeException;

class SSHConnection
{
    private $hostname;
    private $port = 22;
    private $username;
    private $password;
    private $publicKeyPath;
    private $privateKeyPath;
    private $connected;
    private $resource;

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

    private function sanityCheck()
    {
        if (!$this->hostname) {
            throw new InvalidArgumentException('Hostname not specified.');
        }

        if (!$this->username) {
            throw new InvalidArgumentException('Username not specified.');
        }

        if (!$this->password && (!$this->publicKeyPath || !$this->privateKeyPath)) {
            throw new InvalidArgumentException('No password or public-private key pair specified.');
        }
    }

    public function upload(string $localPath, string $remotePath): bool
    {
        if (!is_resource($this->resource)) {
            throw new InvalidArgumentException('The session resource is invalid.');
        }

        if (!file_exists($localPath)) {
            throw new InvalidArgumentException('The localPath is invalid.');
        }

        return ssh2_scp_send($this->resource, $localPath, $remotePath);
    }

    public function download(string $remotePath, string $localPath): bool
    {
        if (!is_resource($this->resource)) {
            throw new InvalidArgumentException('The session resource is invalid.');
        }

        return ssh2_scp_recv($this->resource, $remotePath, $localPath);
    }

    public function connect(): self
    {
        $this->sanityCheck();

        $this->resource = ssh2_connect($this->hostname, $this->port);

        if ($this->publicKeyPath || $this->privateKeyPath) {
            $authenticated = ssh2_auth_pubkey_file($this->resource, $this->username, $this->publicKeyPath, $this->privateKeyPath);
            if (!$authenticated) {
                throw new RuntimeException('Error authenticating with public-private key pair.');
            }
        }

        if ($this->password) {
            $authenticated = ssh2_auth_password($this->resource, $this->username, $this->password);
            if (!$authenticated) {
                throw new RuntimeException('Error authenticating with password.');
            }
        }

        $this->connected = true;

        return $this;
    }

    public function disconnect()
    {
        if (!$this->connected) {
            throw new RuntimeException('Unable to disconnect. Not yet connected.');
        }

        ssh2_disconnect($this->resource);
    }

    public function run(string $command)
    {
        if (!$this->connected) {
            throw new RuntimeException('Unable to run commands when not connected.');
        }

        return new SSHCommand($this->resource, $command);
    }
}
