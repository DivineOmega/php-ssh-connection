<?php

namespace DivineOmega\SSHConnection;

use RuntimeException;

class SSHCommand
{
    const EXECUTION_TIMEOUT_SECONDS = 30;
    const STREAM_BYTES_PER_READ = 4096;

    private $resource;
    private $command;
    private $output;
    private $error;

    public function __construct($resource, string $command)
    {
        $this->resource = $resource;
        $this->command = $command;

        $this->execute();
    }

    private function execute()
    {
        $stdout = ssh2_exec($this->resource, $this->command);

        if (!$stdout) {
            throw new RuntimeException('Failed to execute command (no stdout stream): '.$this->command);
        }

        $stderr = ssh2_fetch_stream($stdout, SSH2_STREAM_STDERR);

        if (!$stderr) {
            throw new RuntimeException('Failed to execute command (no stdout stream): '.$this->command);
        }

        $this->readStreams($stdout, $stderr);
    }

    private function readStreams($stdout, $stderr)
    {
        $startTime = time();

        while (true) {
            $this->output = fread($stdout, self::STREAM_BYTES_PER_READ);
            $this->error = fread($stderr, self::STREAM_BYTES_PER_READ);

            if (feof($stdout) && feof($stderr)) {
                break;
            }

            if (time() - $startTime > self::EXECUTION_TIMEOUT_SECONDS) {
                throw new RuntimeException(
                    'Command execution took over '.self::EXECUTION_TIMEOUT_SECONDS.' seconds: '.$this->command
                );
            }

            // Prevent thrashing
            sleep(1);
        }
    }

    public function getRawOutput(): string
    {
        return $this->output;
    }

    public function getRawError(): string
    {
        return $this->error;
    }

    public function getOutput(): string
    {
        return trim($this->getRawOutput());
    }

    public function getError(): string
    {
        return trim($this->getRawError());
    }
}