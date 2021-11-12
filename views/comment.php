<?php
if($_SERVER['REQUEST_METHOD']!='POST'){
    header("location:../index.php");
    exit;
}
include '../conection/Conexao.class.php';
include '../controller/Controller.class.php';
include '../model/Model.class.php';

$m=new Model();
$msg=isset($_REQUEST['msg'])?$_REQUEST['msg']:"";
$id=isset($_REQUEST['id'])?$_REQUEST['id']:"";
$m->setMensagem($msg);
$m->setId($id);

if(trim($msg)!=""){
    $query=$m->comentar();
}
