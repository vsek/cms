<?php

namespace App\Grid\Menu;

/**
 * Description of Update
 *
 * @author Vsek
 */
class Update extends Menu{
    /**
     * Vykresli
     */
    public function render($row){
        $template = $this->getTemplate();
        $template->setFile(__DIR__ . '/update.latte');
        $template->setTranslator($this->getPresenter()->translator);
        
        $template->action = $this->getAction();
        $template->uniquete = $this->getParent()->getUniquete();
        $template->name = $this->getName();
        $template->row = $row;
        
        $template->render();
    }
}
