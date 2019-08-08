# PHP SSH Connection

[![Build Status](https://travis-ci.com/DivineOmega/php-ssh-connection.svg?branch=master)](https://travis-ci.com/DivineOmega/php-ssh-connection)
[![Coverage Status](https://coveralls.io/repos/github/DivineOmega/php-ssh-connection/badge.svg?branch=master)](https://coveralls.io/github/DivineOmega/php-ssh-connection?branch=master)

The PHP SSH Connection package provides an elegant syntax to connect to SSH servers and execute commands. It supports both password and public-private keypair authentication, and can easily capture command output and errors.

## Installation

You can install the PHP SSH Connection package by running the following Composer command.

```bash
composer require divineomega/php-ssh-connection
```

## Usage

```php
$connection = (new SSHConnection())
            ->to('test.rebex.net')
            ->onPort(22)
            ->as('demo')
            ->withPassword('password')
         // ->withPrivateKey($privateKeyPath)
            ->connect();

$command = $connection->run('echo "Hello world!"');

$command->getOutput();  // 'Hello World'
$command->getError();   // ''

$connection->upload($localPath, $remotePath);
$connection->download($remotePath, $localPath);
```
