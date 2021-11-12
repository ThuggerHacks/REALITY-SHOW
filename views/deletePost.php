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

  if(!isset($_REQUEST['id'])){
      exit;
  }
  $adm=new Adm();
  //echo $_COOKIE['email'];
  foreach($adm->managers() as $adms):
  
  if((strtolower($adms)==strtolower($_COOKIE['email']) || strtolower($adms)==strtolower($_COOKIE['numero'])) && (isset($_COOKIE['numero']) || isset($_COOKIE['email']))):
        $model=new Model();
        $model->setId($_REQUEST['id']);
        $model->deletePost();

    endif;
  endforeach;

?>