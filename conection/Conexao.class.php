<?php

class Conexao{

    private   $db;
    private   $host;
    private   $pass;
    private   $name;


    public  function __construct(){
        $this->setDb("reality");
        $this->setHost("localhost");
        $this->setPass("1234");
        $this->setName("root");
    }
    public function setDb(  $db){
        $this->db=$db;
    }

    public function getDb(){
        return $this->db;
    }

    public function setHost(  $host){
        $this->host=$host;
    }

    public function getHost(){
        return $this->host;
    }

    public function setPass(  $pass){
        $this->pass=$pass;
    }

    public function getPass( ){
        return $this->pass;
    }

    public function setName(  $name){
        $this->name=$name;
    }

    public function getName(){
        return $this->name;
    }

    public function conect(){
        try{

            $con=new PDO("mysql:dbname=".$this->getDb().";host=".$this->getHost(),$this->getName(),$this->getPass());

            return $con;
        }catch(PDOEXCEPTION $ex){
            echo "Erro ao conectar-se a base de dados";
        }
    }
    
}