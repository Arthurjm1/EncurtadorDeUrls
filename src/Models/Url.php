<?php

namespace App\Models;

class Url{

    private $id = null;   
    private $url_original = null;
    private $url_curta = null;
    private $validade = null;

    public function __get($attr){
        return $this->$attr;
    }

    public function __set($attr, $value){
        $this->$attr = $value;  
        return $this;      
    }

    public static function getUrls($db){

        $query = "select id, url_original, url_curta from urls";
        $stmt = $db->prepare($query);        
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function insertUrl($db){

        $query = 'insert into urls (url_original, url_curta, validade) values (:url_original, :url_curta, CURRENT_TIMESTAMP() + INTERVAL 24 HOUR)';
        $stmt = $db->prepare($query);
        $stmt->bindValue(':url_original', $this->__get('url_original'));
        $stmt->bindValue(':url_curta', $this->__get('url_curta'));        
        $stmt->execute();

        return true;
    }

}