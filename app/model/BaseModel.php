<?php

namespace App\Model;
use Nette\Database\Context;
use Nette\SmartObject;

/**
 * Description of BaseModel
 *
 * @author Vsek
 */
class BaseModel{
    use SmartObject;

    /**
     *
     * @var Context
     */
    public $database;
    
    function __construct(Context $database) {
        $this->database = $database;
    }
}
