# PHP SSH Connection

[![Build Status](https://travis-ci.com/DivineOmega/php-ssh-connection.svg?branch=master)](https://travis-ci.com/DivineOmega/php-ssh-connection)
[![Coverage Status](https://coveralls.io/repos/github/DivineOmega/php-ssh-connection/badge.svg?branch=master)](https://coveralls.io/github/DivineOmega/php-ssh-connection?branch=master)

## Installation

```bash
composer require divineomega/php-ssh-connection
```

## Usage

````php
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
```