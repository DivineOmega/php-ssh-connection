<?php

namespace DivineOmega\SSHConnection;

class SSHCommand
{
    public $output = null;
    public $error = null;

    public function __construct($resource, string $command)
    {
        $stdout = ssh2_exec($resource, $command);
        $stderr = ssh2_fetch_stream($stdout, SSH2_STREAM_STDERR);

        if (empty($stdout)) {
            throw new \RuntimeException('Failed to execute command: '.$command);
        }

        $startTime = time();

        // Try for 30s
        do {
            $this->error = fread($stderr, 4096);
            $this->output = fread($stdout, 4096);
            $done = 0;

            if (feof($stderr)) {
                $done++;
            }

            if (feof($stdout)) {
                $done++;
            }

            $span = time() - $startTime;

            if ($done < 2) {
                sleep(1);
            }

        } while (($span < 30) && ($done < 2));

    }

    /**
     * @return bool|string|null
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @return bool|string|null
     */
    public function getError()
    {
        return $this->error;
    }
}