<?php

namespace App\Grid\Menu;

/**
 * Description of JavascriptWindow
 *
 * @author Vsek
 */
class JavascriptWindow extends Menu{
    public function render($row){
        $template = $this->getTemplate();
        $template->setFile(__DIR__ . '/javascriptWindow.latte');
        $template->setTranslator($this->getPresenter()->translator);
        
        $template->action = $this->getAction();
        $template->uniquete = $this->getParent()->getUniquete();
        $template->name = $this->getName();
        $template->row = $row;
        
        $template->render();
    }
}
