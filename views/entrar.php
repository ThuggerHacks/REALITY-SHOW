<?php
if($_SERVER['REQUEST_METHOD']!='POST'){
    header("location:../index.php");
    exit;
}
session_start();
include '../conection/Conexao.class.php';
include '../controller/Controller.class.php';
include '../model/Model.class.php';

$email=isset($_POST['email'])?$_POST['email']:"";
$senha=isset($_POST['senha'])?$_POST['senha']:"";

$entrar=new Model();
$entrar->setNumero($email);
$entrar->setEmail($email);
$entrar->setSenha(md5($senha));
$entrar->entrar();
