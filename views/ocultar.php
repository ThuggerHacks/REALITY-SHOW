<?php

session_start();
include '../conection/Conexao.class.php';
include '../controller/Controller.class.php';
include '../model/Model.class.php';
include '../library/Adm.class.php';

if(!isset($_REQUEST['menu'])){
    exit;
}

$adm=new Adm();

foreach($adm->managers() as $adms){
    if((strtolower($adms)==strtolower($_COOKIE['email']) || strtolower($adms)==strtolower($_COOKIE['numero'])) && (isset($_COOKIE['numero']) || isset($_COOKIE['email']))){
        $model=new Model();
        $model->setTipo($_REQUEST['menu']);
        $model->ocultar_menu();
        header("location:../index.php");
    }
}