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

  if(!isset($_REQUEST['assunto']) || !isset($_REQUEST['mensagem']) || !isset($_REQUEST['id'])){
      exit;
  }

  
  $info=new Model();
  $info->setId($_REQUEST['id']);

  foreach($info->info() as $linha){
    if(trim($_REQUEST['assunto'])==""){
        echo "Assunto nao pode estar em branco";
    }else if(trim($_REQUEST['mensagem'])==""){
        echo "Mensagem nao pode estar em branco";
    }else{
        mail($linha['email'],$_REQUEST['assunto'],$_REQUEST['mensagem'],"from:".$info->getMyMail());
        echo "email enviado com sucesso";
    }
  }