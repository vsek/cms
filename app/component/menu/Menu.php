<?php

namespace App\Grid\Menu;

/**
 * Description of Menu
 *
 * @author Vsek
 */
class Menu extends \Nette\Application\UI\Control{
    /**
     * Nazev odkazu
     * @var string
     */
    private $name;
    
    /**
     * Akce do presenteru
     * @var string
     */
    private $action;
    
    /**
     * Systemovy nazev komponenty, pouziva se pri duplikaci akci
     * @var type string
     */
    private $systemName = null;
    
    /**
     * Vraci systemovy nazev komponenty, pouziva se pri duplikaci akci
     * @return string
     */
    public function getSystemName(){
        if(is_null($this->systemName)){
            return $this->action;
        }else{
            return $this->systemName;
        }
    }
    
    /**
     * Nastavi systemovy nazev komponenty, pouziva se pri duplikaci akci
     * @param string $name
     */
    public function setSystemName($name){
        $this->systemName = $name;
    }
    
    public function __construct($action, $name) {
        $this->name = $name;
        $this->action = $action;
    }
    
    /**
     * Nastavi sloupecek, ktery se predava jako parametr
     * @param string $column
     */
    public function setColumn($column){
        $this->column = $column;
    }
    
    /**
     * Vrati odkaz
     * @return string
     */
    public function getName(){
        return $this->name;
    }
    
    /**
     * Vrati akci do presenteru
     * @return string
     */
    public function getAction(){
        return $this->action;
    }
    
    /**
     * Vykresli
     */
    public function render($row){
        $template = $this->template;
        $template->setFile(__DIR__ . '/menu.latte');
        
        $template->action = $this->getAction();
        $template->uniquete = $this->getParent()->getUniquete();
        $template->name = $this->getName();
        $template->row = $row;
        
        $template->render();
    }
}
