<?php

namespace Dock\Docker\Machine;

use Dock\IO\ProcessRunner;

class DockerMachineCli implements Machine
{
    const MACHINE_NAME = 'dinghy';

    /**
     * @var ProcessRunner
     */
    private $processRunner;

    /**
     * @var string
     */
    private $name;

    /**
     * @param ProcessRunner $processRunner
     * @param string        $name
     */
    public function __construct(ProcessRunner $processRunner, $name = self::MACHINE_NAME)
    {
        $this->processRunner = $processRunner;
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function isRunning()
    {
        $process = $this->processRunner->run('docker-machine status '.$this->name, false);
        if (!$process->isSuccessful()) {
            return false;
        }

        return $process->getOutput() == 'Running';
    }

    /**
     * {@inheritdoc}
     */
    public function start()
    {
        $this->processRunner->run('docker-machine start '.$this->name);
    }

    /**
     * {@inheritdoc}
     */
    public function stop()
    {
        $this->processRunner->run('docker-machine stop '.$this->name);
    }

    /**
     * {@inheritdoc}
     */
    public function getIp()
    {
        $ip = $this->processRunner->run('docker-machine ip '.$this->name)->getOutput();
        $ip = trim($ip);

        return $ip;
    }

    /**
     * {@inheritdoc}
     */
    public function isCreated()
    {
        return $this->processRunner->run('docker-machine status '.$this->name, false)->isSuccessful();
    }

    /**
     * {@inheritdoc}
     */
    public function create()
    {
        $this->processRunner->run('docker-machine create --driver virtualbox '.$this->name);
    }

    /**
     * @return string
     */
    public function getEnvironmentDeclaration()
    {
        return $this->processRunner->run('docker-machine env '.$this->name)->getOutput();
    }
}