<?php

namespace App\Presenters;

use Nette;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    /** @persistent */
    public $locale;

    /** @var \Kdyby\Translation\Translator @inject */
    public $translator;
    
    /** @var \App\Model\Setting @inject */
    public $settings;
    
    /**
     *
     * @var \Nette\Database\Table\ActiveRow
     */
    private $setting = null;
    
    public function getSetting($name){
        if(is_null($this->setting)){
            $this->setting = $this->settings->where('id', 1)->fetch();
        }
        return $this->setting[$name];
    }
}
