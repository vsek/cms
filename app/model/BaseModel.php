<?php

namespace App\Model;

/**
 * Description of BaseModel
 *
 * @author Vsek
 */
class BaseModel extends \Nette\Object{
    
    /**
     *
     * @var \Nette\Database\Context 
     */
    public $database;
    
    function __construct(\Nette\Database\Context $database) {
        $this->database = $database;
    }
}
