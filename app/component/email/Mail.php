<?php

namespace App\Email;

/**
 * Description of Mail
 *
 * @author Vsek
 */
class Mail extends \Nette\Mail\Message{
    private $address = array();
    private $text;
    private $subject;
    
    public function getSubject(){
        return $this->subject;
    }
    
    public function getText(){
        return $this->text;
    }
    
    /**
     * Vraci adresy na ktere se odesila
     * @return array
     */
    public function getAddress(){
        return $this->address;
    }
    
    public function __construct(\Nette\Application\UI\Presenter $presenter) {
        parent::__construct();

        $this->setFrom($presenter->getSetting('email'), $presenter->getSetting('name'));
    }
    
    public function addTo($email, $name = NULL) {
        $this->address[] = $email;
        return parent::addTo($email, $name);
    }
    
    public function setHtmlBody($html, $basePath = NULL) {
        $html = '<html><head>'
                . '</head><body>'
                . $html . '</body></html>';
        $this->text = $html;
        return parent::setHtmlBody($html, $basePath);
    }
    
    public function setSubject($subject) {
        $this->subject = $subject;
        return parent::setSubject($subject);
    }
}
