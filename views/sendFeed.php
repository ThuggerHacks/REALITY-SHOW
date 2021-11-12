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

  $info=new Model();

  if(isset($_REQUEST['text'])){
     $info->setPost(isset($_REQUEST['text'])?$_REQUEST['text']:"");
  }

  if(isset($_FILES['file'])){
      $info->setFile($_FILES['file']);
  }

  $adm=new Adm();
            //echo $_COOKIE['email'];
            foreach($adm->managers() as $adms){
              
              if((strtolower($adms)==strtolower($_COOKIE['email']) || strtolower($adms)==strtolower($_COOKIE['numero'])) && (isset($_COOKIE['numero']) || isset($_COOKIE['email']))){
                  $info->post();
              
            }
          }
?>
