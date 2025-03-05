<?php

class DatabaseException extends Exception {
    private $method;
    private $sql;

    public function __construct($mensaje, $method = "", $sql = "", $codigo = 0, Exception $anterior = null) {
        // Llamar al constructor de clase padre (Exception)
        parent::__construct($mensaje, $codigo, $anterior);
        $this->method = $method;
        $this->sql = $sql;
    }

    public function getMethod(){
        return $this->method;
    }
    
    public function getSql(){
        return $this->sql;
    }   
}

?>