<?php

namespace App\Model;

/**
 * Description of EmailLog
 *
 * @author Vsek
 */
class EmailLog extends BaseModel{
    private $table = 'email_log';
        
    /**
     * Upravi data v databazi
     * @param array $data
     * @return \Nette\Database\Table\Selection
     */
    public function update($data){
        return $this->database->table($this->table)->update($data);
    }
    
    /**
     * Zkratka pro where
     * @param array $data
     * @return \Nette\Database\Table\Selection
     */
    public function order($columns){
        return $this->database->table($this->table)->order($columns);
    }
    
    /**
     * Vyhleda podle primarniho klice
     * @param int $key
     * @return \Nette\Database\Table\ActiveRow
     */
    public function get($key){
        return $this->database->table($this->table)->get($key);
    }
    
    /**
     * 
     * @param type $params
     * @return \Nette\Database\Table\Selection
     */
    public function where($condition, $parameters = array()){
        return $this->database->table($this->table)->where($condition, $parameters);
    }
    
    /**
     * Vlozeni dat do DB
     * @param array $data
     * @return 
     */
    public function insert($data){
        if(!isset($data['created'])){
            $data['created'] = new \Nette\Utils\DateTime;
        }
        
        return $this->database->table($this->table)->insert($data);
    }
    
    /**
     * Vrati vsechny sloupecky
     * @return \Nette\Database\Table\Selection
     */
    public function getAll(){
        return $this->database->table($this->table);
    }
    
    /**
     * Vraci objekt databaze
     * @return \Nette\Database\Table\Selection
     */
    public function getDatabase(){
        return $this->database->table($this->table);
    }
}