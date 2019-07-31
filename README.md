# PHP SSH Connection

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
            ->connect();

$command = $connection->run('echo "Hello world!"');

$command->getOutput();  // 'Hello World'
$command->getError();   // ''
```