<?php

namespace App;

use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;


class RouterFactory
{

	/**
	 * @return Nette\Application\IRouter
	 */
	public function createRouter()
	{
            $router = new RouteList();
            
            $router[] = $adminRouter = new RouteList('Admin');
            $adminRouter[] = new Route('admin/<presenter>/<action>', 'Homepage:default');

            $router[] = $frontRouter = new RouteList('Front');
            
            //obrazky
            $frontRouter[] = new Route('[<locale=cs cs|en>/]<presenter image>/<action preview>/', array(
                'presenter' => 'Homepage',
                'action' => 'default',
            ));
            
            //stranky
            $frontRouter[] = new Route('[<locale=cs cs|en>/]<url .*>/', array(
                'presenter' => 'Page',
                'action' => 'default',
            ));
            
            //vychozi router
            $frontRouter[] = new Route('[<locale=cs cs|en>/]<presenter>/<action>/[<id>/]', array(
                'presenter' => 'Homepage',
                'action' => 'default',
            ));
            
            return $router;
	}

}
