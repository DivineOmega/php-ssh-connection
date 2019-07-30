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

        $startTime = time();

        do {
            $this->error = fread($stderr, self::STREAM_BYTES_PER_READ);
            $this->output = fread($stdout, self::STREAM_BYTES_PER_READ);

            $streamsComplete = (feof($stderr) && feof($stdout));

            if (!$streamsComplete) {
                // Prevent thrashing.
                sleep(1);
            }

            $executionDuration = time() - $startTime;

        } while ($executionDuration <= self::EXECUTION_TIMEOUT_SECONDS && !$streamsComplete);
    }

    public function getOutput(): string
    {
        return $this->output;
    }

    public function getError(): string
    {
        return $this->error;
    }
}