# PHP SSH Connection

[![Build Status](https://travis-ci.com/DivineOmega/php-ssh-connection.svg?branch=master)](https://travis-ci.com/DivineOmega/php-ssh-connection)
[![Coverage Status](https://coveralls.io/repos/github/DivineOmega/php-ssh-connection/badge.svg?branch=master)](https://coveralls.io/github/DivineOmega/php-ssh-connection?branch=master)

The PHP SSH Connection package provides an elegant syntax to connect to SSH servers and execute commands. It supports both password and public-private keypair authentication, and can easily capture command output and errors.

## Installation

First, you may need to install the PHP SSH2 extension. In Ubuntu and other Debian-based systems, you can install this extension by running the following command.

```bash
sudo apt install php-ssh2
```

For other operating systems, see the [PHP SSH2 extension documentation](https://www.php.net/manual/en/book.ssh2.php).

You can then run the following Composer command to install the PHP SSH Connection package.

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
         // ->withKeyPair($publicKeyPath, $privateKeyPath)
            ->connect();

$command = $connection->run('echo "Hello world!"');

$command->getOutput();  // 'Hello World'
$command->getError();   // ''

$connection->upload($localPath, $remotePath);
$connection->download($remotPath, $localPath);
```
