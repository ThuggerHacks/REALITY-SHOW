<?php
if($_SERVER['REQUEST_METHOD']!='POST'){
    header("location:../index.php");
    exit;
}
include '../conection/Conexao.class.php';
include '../controller/Controller.class.php';
include '../model/Model.class.php';

$m=new Model();
$id_post=isset($_REQUEST['id_post'])?$_REQUEST['id_post']:"";
$m->setId($id_post);
echo $m->like();