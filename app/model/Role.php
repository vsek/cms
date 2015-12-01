<?php

namespace App\Model;

/**
 * Description of Role
 *
 * @author Vsek
 */
class Role extends BaseModel{
    private $table = 'role';
    
    /**
     * Upravi data v databazi
     * @param array $data
     * @return \Nette\Database\Table\Selection
     */
    public function update($data){
        return $this->database->table($this->table)->update($data);
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
     * Zkratka pro where
     * @param array $data
     * @return \Nette\Database\Table\Selection
     */
    public function order($columns){
        return $this->database->table($this->table)->order($columns);
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
        return $this->database->table($this->table)->insert($data);
    }
    
    /**
     * Vrati vsechny sloupecky
     * @return \Nette\Database\Table\Selection
     */
    public function getAll(){
        return $this->database->table($this->table)->where('system_name <> ?', 'super_admin');
    }
    
    /**
     * Vraci objekt databaze
     * @return \Nette\Database\Table\Selection
     */
    public function getDatabase(){
        return $this->database->table($this->table);
    }
}
