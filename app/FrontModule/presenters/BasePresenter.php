<?php

namespace App\FrontModule\Presenters;

use Nette,
    \App\FrontModule\Form;
use \Mobile_Detect;
use Tracy\Debugger;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends \App\Presenters\BasePresenter
{
    
   /**
    * Jestli se jedna o homepage
    * @var type boolean
    */
   protected $isHp = false;
    
    private $detect = null;
    protected $useMobile = true;
    protected $isMobile = null;
    
    /** @persistent */
    public $fullSite = 0;
    
    /**
     *
     * @var \App\ScriptLoader\ScriptLoaderFactory
     */
    private $scriptLoaderFactory;
    
    /**
    * @var Nette\Caching\IStorage
    * @inject
    */
    public $storage;
    
    /**
     * 
     * @param \App\ScriptLoader\IScriptLoaderFactory $factory
     */
    public function injectScriptLoaderFactory(\App\ScriptLoader\IScriptLoaderFactory $factory)
    {
        $this->scriptLoaderFactory = $factory;
    }
    
    protected function createComponentScriptLoader()
    {
        return $this->scriptLoaderFactory->create();
    }
    
    /**
     * Detekuje jestli se jedna o mobilni nebo tablet verzi
     * @return type boolean
     */
    public function isMobile(){
        if(is_null($this->isMobile)){
            if($this->useMobile && $this->fullSite == 0){
                //return true;
                if(is_null($this->detect)){
                    $this->detect = new Mobile_Detect();
                }
                $this->isMobile = $this->detect->isMobile() || $this->detect->isTablet();
            }else{
                $this->isMobile = false;
            }
        }
        return $this->isMobile;
    }
   
   public function getNameSimple(){
        $name = str_replace('Front:', '', $this->getName());
        return \Nette\Utils\Strings::lower($name);
    }
    
    public function beforeRender() {
        parent::beforeRender();
        $this->template->setTranslator($this->translator);
        $this->template->isHp = $this->isHp;
        
        $this->template->isMobile = $this->isMobile();
        
        $this->template->getLatte()->addFilter('externalUrl', function ($s) {
            if(!Nette\Utils\Strings::startsWith($s, 'http://') && !Nette\Utils\Strings::startsWith($s, 'https://')){
                $s = 'http://' . $s;
            }
            return $s;
        });
    }
    
    protected function createComponentMenu($name) {
        return new \App\FrontModule\Components\Menu($this, $name, $this->pages);
    }
    
    public function startup() {
        parent::startup();

        if($this->isMobile()){
            $this->setLayout('layout.mobile');
        }
        $this->getUser()->getStorage()->setNamespace('front');
        
        \PavelMaca\Captcha\CaptchaControl::$session = $this->getSession('captcha');
        if (!$this->getSession()->isStarted()) {
            $this->getSession()->start();
        }
        
        $this->template->inUSA = false;
        /*
        //jen docasne pro parovani se starou DB
        if(isset($_GET['load'])){
            include dirname(__FILE__) . '/../../../vendor/dibi/dibi.php';
            \dibi::connect(array(
                'driver'   => 'mysql',
                'host'     => 'localhost',
                'username' => 'worldescortin001',
                'password' => 'aduhw6gRjas',
                'database' => 'worldescortin001',
                'charset'  => 'utf8',
            ));
            \dibi::connect(array(
                'driver'   => 'mysql',
                'host'     => '127.0.0.1',
                'username' => 'worldescortin001',
                'password' => 'Hou67eQKr4L',
                'database' => 'worldescortindexcom',
                'charset'  => 'utf8',
            ));

            foreach(\dibi::fetchAll('SELECT * FROM [banners] ORDER BY [sort]') as $banner){
                $imageName = time() . '_' . $banner['id'] . '.jpg';
                $prefixDir = substr($imageName, 0, 4);
                if(!is_dir($this->context->parameters['wwwDir'] . '/images/upload/' . $prefixDir)){
                    mkdir($this->context->parameters['wwwDir'] . '/images/upload/' . $prefixDir);
                    chmod($this->context->parameters['wwwDir'] . '/images/upload/' . $prefixDir, 0777);
                }
                file_put_contents($this->context->parameters['wwwDir'] . '/images/upload/' . $prefixDir . '/' . $imageName, file_get_contents('http://www.worldescortindex.com/theme/imgs/banners/' . $banner['id'] . '.jpg'));
                $dataBanner = array(
                    'link' => $banner['url'],
                    'image' => $imageName,
                    'expiration' => new Nette\Utils\DateTime($banner['expiration']),
                    'price' => (float)$banner['price'],
                    'email' => $banner['email'],
                );
                $this->banners->insert($dataBanner);
            }
            
            foreach(\dibi::fetchAll('SELECT * FROM [counters_main]') as $counterMain){
                $dataLocation = array(
                    'name' => $counterMain['name'],
                    'link' => Nette\Utils\Strings::webalize($counterMain['name']) . ($counterMain['id'] == 5 ? '1' : ''),
                    'is_usa' => $counterMain['id'] == 5,
                );
                $location = $this->locations->insert($dataLocation);
                foreach(\dibi::fetchAll('SELECT * FROM [counters] WHERE [main_parent] = %i AND [parent] IS NULL', $counterMain['id']) as $counter){
                    if(!is_null($counter['flag'])){
                        $imageName = time() . '_' . $counter['flag'];
                        $prefixDir = substr($imageName, 0, 4);
                        if(!is_dir($this->context->parameters['wwwDir'] . '/images/upload/' . $prefixDir)){
                            mkdir($this->context->parameters['wwwDir'] . '/images/upload/' . $prefixDir);
                            chmod($this->context->parameters['wwwDir'] . '/images/upload/' . $prefixDir, 0777);
                        }
                        file_put_contents($this->context->parameters['wwwDir'] . '/images/upload/' . $prefixDir . '/' . $imageName, file_get_contents('http://www.worldescortindex.com/theme/imgs/flags/' . $counter['flag']));
                    }else{
                        $imageName = null;
                    }
                    $dataState = array(
                        'name' => $counter['value'],
                        'link' => $this->getLocationLink($counter['address']),
                        'parent_id' => $location['id'],
                        'image' => $imageName,
                        'text' => $counter['text'] == '' ? NULL : $counter['text'],
                        'remote_id' => (int)$counter['id'],
                        'is_island' => (int)$counter['id'] == 854,
                    );
                    
                    $state = $this->locations->insert($dataState);
                    
                    foreach(\dibi::fetchAll('SELECT * FROM [counters] WHERE [parent] = %i', $counter['id']) as $counter1){
                        if(!is_null($counter1['flag'])){
                            $imageName = time() . '_' . $counter1['flag'];
                            $prefixDir = substr($imageName, 0, 4);
                            if(!is_dir($this->context->parameters['wwwDir'] . '/images/upload/' . $prefixDir)){
                                mkdir($this->context->parameters['wwwDir'] . '/images/upload/' . $prefixDir);
                                chmod($this->context->parameters['wwwDir'] . '/images/upload/' . $prefixDir, 0777);
                            }
                            file_put_contents($this->context->parameters['wwwDir'] . '/images/upload/' . $prefixDir . '/' . $imageName, file_get_contents('http://www.worldescortindex.com/theme/imgs/flags/' . $counter1['flag']));
                        }else{
                            $imageName = null;
                        }
                        $dataState1 = array(
                            'name' => $counter1['value'],
                            'link' => $this->getLocationLink($counter1['address']),
                            'parent_id' => $state['id'],
                            'image' => $imageName,
                            'text' => $counter1['text'] == '' ? NULL : $counter1['text'],
                            'remote_id' => (int)$counter1['id'],
                            'is_island' => (int)$counter1['id'] == 854,
                        );
                        $state1 = $this->locations->insert($dataState1);
                        
                        foreach(\dibi::fetchAll('SELECT * FROM [counters] WHERE [parent] = %i', $counter1['id']) as $counter2){
                            if(!is_null($counter2['flag'])){
                                $imageName = time() . '_' . $counter2['flag'];
                                $prefixDir = substr($imageName, 0, 4);
                                if(!is_dir($this->context->parameters['wwwDir'] . '/images/upload/' . $prefixDir)){
                                    mkdir($this->context->parameters['wwwDir'] . '/images/upload/' . $prefixDir);
                                    chmod($this->context->parameters['wwwDir'] . '/images/upload/' . $prefixDir, 0777);
                                }
                                file_put_contents($this->context->parameters['wwwDir'] . '/images/upload/' . $prefixDir . '/' . $imageName, file_get_contents('http://www.worldescortindex.com/theme/imgs/flags/' . $counter2['flag']));
                            }else{
                                $imageName = null;
                            }
                            $dataState2 = array(
                                'name' => $counter2['value'],
                                'link' => $this->getLocationLink($counter2['address']),
                                'parent_id' => $state1['id'],
                                'image' => $imageName,
                                'text' => $counter2['text'] == '' ? NULL : $counter2['text'],
                                'remote_id' => (int)$counter2['id'],
                                'is_island' => (int)$counter2['id'] == 854,
                            );
                            $this->locations->insert($dataState2);
                        }
                    }
                }
            }
             
            $categoryTranslate = array(
                1 => 'free',
                2 => 'top',
                3 => 'premium',
                4 => 'vip',
            );
            $directoryTranslate = array(
                1 => 'independent',
                2 => 'agency',
                3 => 'club',
                4 => 'directory',
            );
            $categoryToEticket = array(
                'top' => '174358',
                'premium' => '174359',
                'vip' => '174360',
            );
            $eticketRebiil = array(
                'top' => '16051',
                'premium' => '16052',
                'vip' => '16053',
            );
            $eticketCategoryPeriod = array(
                'top' => array(
                    '+1 month' => '16034',
                    '+3 month' => '16035',
                    '+6 month' => '16036',
                    '+1 year' => '16037',
                ),
                'premium' => array(
                    '+1 month' => '16038',
                    '+3 month' => '16039',
                    '+6 month' => '16040',
                    '+1 year' => '16041',
                ),
                'vip' => array(
                    '+1 month' => '16042',
                    '+3 month' => '16043',
                    '+6 month' => '16044',
                    '+1 year' => '16045',
                ),
            );
                
            $ordering = new \Nette\Utils\DateTime('2010-01-01 00:00:00');
            foreach(\dibi::fetchAll('SELECT * FROM [catalog]') as $catalog){
                
                if(in_array($catalog['category'], array('3', '4'))){
                    $imageName = time() . '_' . $catalog['id'] . '.jpg';
                    $prefixDir = substr($imageName, 0, 4);
                    if(!is_dir($this->context->parameters['wwwDir'] . '/images/upload/' . $prefixDir)){
                        mkdir($this->context->parameters['wwwDir'] . '/images/upload/' . $prefixDir);
                        chmod($this->context->parameters['wwwDir'] . '/images/upload/' . $prefixDir, 0777);
                    }
                    file_put_contents($this->context->parameters['wwwDir'] . '/images/upload/' . $prefixDir . '/' . $imageName, file_get_contents('http://www.worldescortindex.com/theme/imgs/images/' . $catalog['id'] . '.jpg'));
                }else{
                    $imageName = null;
                }
                
                $eticketId = null;
                if($catalog['category'] != 1 && !is_null($catalog['period'])){
                    $eticketId = $categoryToEticket[$categoryTranslate[$catalog['category']]] . ':';
                    if($catalog['rebill'] == 'yes'){
                        $eticketId .= $eticketRebiil[$categoryTranslate[$catalog['category']]];
                    }else{
                        if(!isset($eticketCategoryPeriod[$categoryTranslate[$catalog['category']]][$catalog['period']])){
                            \Tracy\Debugger::dump($catalog);exit;
                        }
                        $eticketId .= $eticketCategoryPeriod[$categoryTranslate[$catalog['category']]][$catalog['period']];
                    }
                }
                
                if(!is_null($catalog['recently_added'])){
                    $ordering = $catalog['recently_added'];
                }
                $dataAdvertise = array(
                    'name' => $catalog['name'],
                    'web' => $catalog['web'],
                    'description' => $catalog['description'],
                    'approval' => $catalog['valid'],
                    'category' => $categoryTranslate[$catalog['category']],
                    'paid' => $catalog['paid'],
                    'valid' => new Nette\Utils\DateTime($catalog['validUntil']),
                    'email' => $catalog['mail'],
                    'directory' => (int)$catalog['directory'] == 0 ? null : $directoryTranslate[$catalog['directory']],
                    'image' => $imageName,
                    'remote_id' => $catalog['id'],
                    'created' => is_null($catalog['created']) ? null : new Nette\Utils\DateTime($catalog['created']),
                    'ordering' => $ordering,
                    'in_export' => $catalog['newsletter'],
                    'note' => $catalog['note'],
                    'purchaseid' => is_null($catalog['purchaseid']) ? null :$catalog['purchaseid'],
                    'transactionid' => is_null($catalog['transactionid']) ? null : $catalog['transactionid'],
                    'etiketid' => $eticketId,
                    'host' => $this->advertises->getHost($catalog['web']),
                );
                if(!is_null($catalog['city'])){
                    $dataAdvertise['location_id'] = $this->locations->where('remote_id', $catalog['city'])->fetch()->id;
                }elseif(!is_null($catalog['state'])){
                    $dataAdvertise['location_id'] = $this->locations->where('remote_id', $catalog['state'])->fetch()->id;
                }else{
                    $dataAdvertise['location_id'] = $this->locations->where('remote_id', $catalog['country'])->fetch()->id;
                }
                $this->advertises->insert($dataAdvertise);
                
                $ordering->add(new \DateInterval('PT1S'));
            }
            foreach(\dibi::fetchAll('SELECT * FROM [emails]') as $email){
                $this->emailLog->insert(array(
                    'created' => $email['dateTime'] != '0000-00-00 00:00:00' ? new \Nette\Utils\DateTime($email['dateTime']) : new \Nette\Utils\DateTime($email['date']),
                    'adress' => $email['to'],
                    'text' => $email['content'],
                    'subject' => 'Neznamy', 
                ));
            }
        }*/
    }
    private function getLocationLink($link){
        while($this->locations->where('link', $link)->fetch()){
            $link = $link . '1';
        }
        return $link;
    }
}
