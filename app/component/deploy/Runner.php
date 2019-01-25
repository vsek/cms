<?php

namespace App\Component\Deploy;

use Deployment\Deployer;
use Deployment\FtpServer;
use Deployment\Logger;
use Deployment\Preprocessor;
use Deployment\Server;
use Deployment\SshServer;
use Minetro\Deployer\Config\Config;
use Minetro\Deployer\Config\Section;
use Minetro\Deployer\Exceptions\DeployException;
use Minetro\Deployer\Logging\StdOutLogger;

/**
 * Description of Runner
 *
 * @author Vsek
 */
class Runner{
    
    /** @var Logger */
    private $logger;
    
    private $callback = array();

    /**
     * @param Config $config
     */
    public function run(Config $config)
    {
        // Create logger
        $logFile = $config->getLogFile();
        $this->logger = $logFile ? new Logger($logFile) : new StdOutLogger();
        $this->logger->useColors = $config->useColors();

        // Create temp dir
        if (!is_dir($tempDir = $config->getTempDir())) {
            $this->logger->log("Creating temporary directory $tempDir");
            @mkdir($tempDir, 0777, TRUE);
        }

        // Start time
        $time = time();
        $this->logger->log("Started at " . date('[Y/m/d H:i]'));

        // Get sections and get sections names
        $sections = $config->getSections();
        $sectionNames = array_map(function (Section $s) {
            return $s->getName();
        }, $sections);

        // Show info
        $this->logger->log(sprintf('Found sections: %d (%s)', count($sectionNames), implode(',', $sectionNames)));

        // Process all sections
        foreach ($sections as $section) {
            // Show info
            $this->logger->log("\nDeploying section [{$section->getName()}]");

            // Create deployer
            $deployment = $this->createDeployer($config, $section);
            $deployment->tempDir = $tempDir;

            // Detect mode -> generate
            if ($config->getMode() === 'generate') {
                $this->logger->log('Scanning files');
                $localFiles = $deployment->collectPaths();
                $this->logger->log("Saved " . $deployment->writeDeploymentFile($localFiles));
                continue;
            }

            // Show info
            if ($deployment->testMode) {
                $this->logger->log('Test mode');
            } else {
                $this->logger->log('Live mode');
            }

            if (!$deployment->allowDelete) {
                $this->logger->log('Deleting disabled');
            }

            // Deploy
            $deployment->deploy();
        }

        // Show elapsed time
        $time = time() - $time;
        $this->logger->log("\nFinished at " . date('[Y/m/d H:i]') . " (in $time seconds)", 'lime');
    }

    /**
     * @param Section $section
     * @return Deployer
     * @throws DeployException
     */
    public function createDeployer(Config $config, Section $section)
    {
        // Validate section remote
        if (!is_array($section->getRemote())) {
            throw new DeployException("Remote section mus be array.");
        }

        // Create *Server
        $server = $this->createServer($section);

        // Create deployer
        $deployment = new Deployer($server, $section->getLocal(), $this->logger);

        // Set-up preprocessing
        if ($section->isPreprocess()) {
            $masks = $section->getPreprocessMasks();
            $deployment->preprocessMasks = empty($masks) ? ['*.js', '*.css'] : $masks;
            $preprocessor = new Preprocessor($this->logger);
            $deployment->addFilter('js', [$preprocessor, 'expandApacheImports']);
            $deployment->addFilter('js', [$preprocessor, 'compress'], TRUE);
            $deployment->addFilter('css', [$preprocessor, 'expandApacheImports']);
            $deployment->addFilter('css', [$preprocessor, 'expandCssImports']);
            $deployment->addFilter('css', [$preprocessor, 'compress'], TRUE);
        }

        // Merge ignore masks
        $deployment->ignoreMasks = array_merge(
            ['*.bak', '.svn', '.git*', 'Thumbs.db', '.DS_Store', '.idea', 'images/upload/*', 'images/preview/*'],
            $section->getIgnoreMasks()
        );

        // Basic settings
        $deployFile = $section->getDeployFile();
        $deployment->deploymentFile = empty($deployFile) ? $deployment->deploymentFile : $deployFile;
        $deployment->allowDelete = $section->isAllowDelete();
        $deployment->toPurge = $section->getPurges();
        $deployment->testMode = $section->isTestMode();

        // Before callbacks
        $bc = [NULL, NULL];
        foreach ($section->getBeforeCallbacks() as $cb) {
            $bc[is_callable($cb)][] = $cb;
        }

        $deployment->runBefore = [];
        $deployment->runAfterUpload = $bc[0];
        $deployment->runAfterUpload[] = function ($server, $logger, $deployer) use ($bc, $config, $section) {
            foreach ((array) $bc[1] as $c) {
                if(!array_key_exists($c[0], $this->callback)){
                    $this->callback[$c[0]] = new $c[0];
                }
                call_user_func_array([$this->callback[$c[0]], $c[1]], [$config, $section, $server, $logger, $deployer]);
            }
        };

        // After callbacks
        $ac = [NULL, NULL];
        foreach ($section->getAfterCallbacks() as $cb) {
            $ac[is_callable($cb)][] = $cb;
        }
        $deployment->runAfter = $ac[0];
        $deployment->runAfter[] = function ($server, $logger, $deployer) use ($ac, $config, $section) {
            foreach ((array) $ac[1] as $c) {
                if(!array_key_exists($c[0], $this->callback)){
                    $this->callback[$c[0]] = new $c[0];
                }
                call_user_func_array([$this->callback[$c[0]], $c[1]], [$config, $section, $server, $logger, $deployer]);
            }
        };

        return $deployment;
    }

    /**
     * @param Section $section
     * @return Server
     * @throws \Exception
     */
    protected function createServer(Section $section)
    {
        $password = str_replace('+', '%2B', $section->getRemote()['password']);
        $params = [
            'scheme' => 'ftp',
            'user' => $section->getRemote()['user'],
            'pass' => $password,
            'host' => $section->getRemote()['server'],
        ];
        return new FtpServer($params, $section->isPassiveMode());
    }
}
