<?php
if($_SERVER['REQUEST_METHOD']!='POST'){
  header("location:../index.php");
  exit;
}
  session_start();
  include '../conection/Conexao.class.php';
  include '../controller/Controller.class.php';
  include '../model/Model.class.php';
  include '../library/Adm.class.php';

  $id=isset($_REQUEST['id'])?$_REQUEST['id']:"";
  $m=new Model();
  $m->setId($id);
  $m->delete_coments();