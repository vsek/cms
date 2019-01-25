<?php

namespace App\Component\Deploy;

use Minetro\Deployer\Config\Config;
use Nette\DI\CompilerExtension;
use Nette\DI\Statement;

/**
 * Deployer Extension
 *
 * @author Vsek
 */
final class DeployerExtension extends CompilerExtension
{

    /** @var array */
    private $defaults = [
        'config' => [
            'mode' => Config::MODE_TEST,
            'logFile' => '%appDir/../log/deploy.log',
            'tempDir' => '%appDir/../temp',
            'colors' => NULL,
        ],
        'sections' => [],
        'userdata' => [],
        'plugins' => [],
    ];

    /** @var array */
    private $sectionDefaults = [
        'testMode' => TRUE,
        'deployFile' => NULL,
        'remote' => NULL,
        'local' => '%appDir',
        'ignore' => [],
        'allowdelete' => TRUE,
        'before' => [],
        'after' => [],
        'purge' => [],
        'preprocess' => FALSE,
        'passiveMode' => FALSE,
    ];

    /**
     * Processes configuration data. Intended to be overridden by descendant.
     *
     * @return void
     */
    public function loadConfiguration()
    {
        // Validate config
        $config = $this->validateConfig($this->defaults);

        // Get builder
        $builder = $this->getContainerBuilder();

        // Process sections
        foreach ($config['sections'] as $name => $section) {

            // Validate and merge section
            $config['sections'][$name] = $this->validateConfig($this->sectionDefaults, $section);
        }

        // Add deploy manager
        $builder->addDefinition($this->prefix('manager'))
            ->setClass('App\Component\Deploy\Manager', [
                new Statement('App\Component\Deploy\Runner'),
                new Statement('Minetro\Deployer\Config\ConfigFactory', [$config]),
            ]);
    }

}