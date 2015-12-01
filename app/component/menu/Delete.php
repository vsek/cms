<?php

namespace App\Grid\Menu;

/**
 * Description of Delete
 *
 * @author Vsek
 */
class Delete extends Menu{
    /**
     * Vykresli
     */
    public function render($row){
        $template = $this->getTemplate();
        $template->setFile(__DIR__ . '/delete.latte');
        $template->setTranslator($this->getPresenter()->translator);
        
        $template->action = $this->getAction();
        $template->uniquete = $this->getParent()->getUniquete();
        $template->name = $this->getName();
        $template->row = $row;
        
        $template->render();
    }
}
