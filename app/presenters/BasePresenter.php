<?php

namespace App\Presenters;

use App\Model\Setting;
use App\Translate\Translator;
use Nette\Application\UI\Presenter;
use Nette\Database\Table\ActiveRow;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Presenter
{
    /** @persistent */
    public $locale;

    /** @var Translator @inject */
    public $translator;
    
    /** @var Setting @inject */
    public $settings;
    
    /**
     *
     * @var ActiveRow
     */
    private $setting = null;
    
    public function getSetting($name){
        if(is_null($this->setting)){
            $this->setting = $this->settings->where('id', 1)->fetch();
        }
        return $this->setting[$name];
    }
}
