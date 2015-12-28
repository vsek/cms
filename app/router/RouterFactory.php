<?php

namespace App;

use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;


class RouterFactory
{

    /** @var \App\Model\Module\Language @inject */
    public $languages;
    
    /** @var \App\Model\Module\Page @inject */
    public $pages;
    
    public function __construct(Model\Module\Language $language, Model\Module\Page $page) {
        $this->languages = $language;
        $this->pages = $page;
    }
    
    /**
     * @return Nette\Application\IRouter
     */
    public function createRouter()
    {
        $langRoute = array('language');
        foreach($this->languages->getAll() as $language){
            $langRoute[] = $language['link'];
        }
        
        $uri = explode('/', $_SERVER['REQUEST_URI']);
        if(isset($uri[1])){
            $language = $this->languages->where('link', $uri[1])->fetch();
        }else{
            $language = $this->languages->get(1);
        }
        
        $router = new RouteList();

        $router[] = $adminRouter = new RouteList('Admin');
        $adminRouter[] = new Route('[<locale=cs ' . implode('|', $langRoute) . '>/]admin/<presenter>/<action>', 'Homepage:default');

        $router[] = $frontRouter = new RouteList('Front');

        $pages = $this->pages->where('NOT module', null)->fetchAll();
        foreach($pages as $page){
            $link = $page['link'];

            //jen staticka
            if(in_array($page['module'], array())){

            //vypis => detail s ID
            }elseif(in_array($page['module'], array())){
                //katalog
                $frontRouter[] = new Route('[<locale=cs cs|en>/]' . $link . '/<link>/<id [0-9]+>/', array(
                    'action' => 'default',
                    'presenter' => array(
                        Route::FILTER_TABLE => array(
                            $link => $this->createPresenterName($page['module']),
                        ),
                        Route::FILTER_STRICT => true,
                    ),
                ));
                $frontRouter[] = new Route('[<locale=cs cs|en>/]' . $link . '/<url>/', array(
                    'action' => 'default',
                    'presenter' => array(
                        Route::FILTER_TABLE => array(
                            $link => $this->createPresenterName($page['module']),
                        ),
                        Route::FILTER_STRICT => true,
                    ),
                ));
                
            //jen specialni stranka modulu
            }else{ 
                $frontRouter[] = new Route('[<locale=cs cs|en>/]<presenter>/', array(
                    'action' => 'default',
                    'presenter' => array(
                        Route::FILTER_TABLE => array(
                            $link => $this->createPresenterName($page['module']),
                        ),
                        Route::FILTER_STRICT => true,
                    ),
                ));
                $frontRouter[] = new Route('[<locale=cs cs|en>/]<presenter>/[<id>/]', array(
                    'action' => 'detail',
                    'presenter' => array(
                        Route::FILTER_TABLE => array(
                            $link => $this->createPresenterName($page['module']),
                        ),
                        Route::FILTER_STRICT => true,
                    ),
                ));
            }
        }
        
        //obrazky
        $frontRouter[] = new Route('[<locale=cs ' . implode('|', $langRoute) . '>/]<presenter image>/<action preview>/', array(
            'presenter' => 'Homepage',
            'action' => 'default',
        ));

        //stranky
        $frontRouter[] = new Route('[<locale=cs ' . implode('|', $langRoute) . '>/]<url .*>/', array(
            'presenter' => 'Page',
            'action' => 'default',
        ));

        //vychozi router
        $frontRouter[] = new Route('[<locale=cs ' . implode('|', $langRoute) . '>/]<presenter>/<action>/[<id>/]', array(
            'presenter' => 'Homepage',
            'action' => 'default',
        ));

        return $router;
    }

    private function createPresenterName($string){
        $presenter = '';
        $enlarge = false;
        for($i = 0; $i < \Nette\Utils\Strings::length($string); $i++){
            $char = \Nette\Utils\Strings::substring($string, $i, 1);
            
            if($char == '-'){
                $enlarge = true;
            }
            
            if((ord($char) >= 65 && ord($char) <= 90) || (ord($char) >= 97 && ord($char) <= 122) ){
                if($i == 0 || $enlarge){
                    $presenter .= \Nette\Utils\Strings::upper($char);
                    if($enlarge){
                        $enlarge = false;
                    }
                }else{
                    $presenter .= $char;
                }
            }
        }
        return $presenter;
    }
}
