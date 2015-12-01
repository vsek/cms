<?php

namespace App\FrontModule\Presenters;

/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter
{
    
    public function startup() {
        parent::startup();
        $this->isHp = true;
    }
    
    public function actionSetFullSite($fullSite, $callback){
        $this->fullSite = $fullSite;
        if(strpos($callback, '?') === false){
            $this->redirectUrl($callback . '?fullSite=' . $fullSite);
        }else{
            $this->redirectUrl($callback . '&fullSite=' . $fullSite);
        }
    }
}
