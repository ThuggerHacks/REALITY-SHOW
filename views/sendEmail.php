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

  if(!isset($_REQUEST['assunto']) || !isset($_REQUEST['mensagem'])){
      exit;
  }

  $adm=new Adm();
            //echo $_COOKIE['email'];
  foreach($adm->managers() as $adms):
              
 if((strtolower($adms)==strtolower($_COOKIE['email']) || strtolower($adms)==strtolower($_COOKIE['numero'])) && (isset($_COOKIE['numero']) || isset($_COOKIE['email']))):

  $email=new Model();
  $email->setAssunto(isset($_REQUEST['assunto'])?$_REQUEST['assunto']:"");
  $email->setMensagem(isset($_REQUEST['mensagem'])?$_REQUEST['mensagem']:"");
  
  //send to everybody
  foreach($email->send() as $email){
      mail($email['email'],$this->getAssunto(),$this->getMensagem(),"from:".$this->getMyMail());
  }

 endif;
endforeach;
?>