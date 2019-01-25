<?php

namespace App\Component\Deploy;

use Deployment\Deployer;
use Deployment\Logger;
use Deployment\Server;
use Minetro\Deployer\Config\Config;
use Minetro\Deployer\Config\ConfigFactory;
use Minetro\Deployer\Config\Section;

/**
 * Description of Manager
 *
 * @author Vsek
 */
class Manager {
    /** @var Runner */
    private $runner;

    /** @var Config */
    private $config;

    /**
     * @param Runner $runner
     * @param ConfigFactory $config
     */
    public function __construct(Runner $runner, ConfigFactory $config)
    {
        $this->runner = $runner;
        $this->config = $config->create();
    }

    /**
     * Run automatic deploy
     */
    public function deploy()
    {
        $this->runner->run($this->config);
    }

    /**
     * Run deploy by given config
     *
     * @param Config $config
     */
    public function manualDeploy(Config $config)
    {
        $this->runner->run($config);
    }
}