<?php

if($_SERVER['REQUEST_METHOD']!='POST'){
  header("location:../index.php");
  exit;
}
  include '../conection/Conexao.class.php';
  include '../controller/Controller.class.php';
  include '../model/Model.class.php';

    $nome=isset($_REQUEST['nome'])?$_REQUEST['nome']:"";
    $categoria=isset($_REQUEST['categoria'])?$_REQUEST['categoria']:"";
    $idade=isset($_REQUEST['idade'])?$_REQUEST['idade']:"";
    $morada=isset($_REQUEST['morada'])?$_REQUEST['morada']:"";
    $email=isset($_REQUEST['email'])?$_REQUEST['email']:"";
    $apelido=isset($_REQUEST['apelido'])?$_REQUEST['apelido']:"";
    $senha=isset($_REQUEST['senha'])?$_REQUEST['senha']:"";
    $csenha=isset($_REQUEST['csenha'])?$_REQUEST['csenha']:"";
    $numero=isset($_REQUEST['numero'])?$_REQUEST['numero']:"";
    $tipo=isset($_REQUEST['tipo'])?$_REQUEST['tipo']:"";
    $genero="";

    if($categoria=="canto"){
      $genero=isset($_REQUEST['genero1'])?$_REQUEST['genero1']:"";
    }else{
      $genero=isset($_REQUEST['genero2'])?$_REQUEST['genero2']:"";
    }
    

    $cadastrar=new Model();
    $cadastrar->setNome($nome);
    $cadastrar->setCategoria($categoria);
    $cadastrar->setIdade($idade);
    $cadastrar->setMorada($morada);
    $cadastrar->setEmail($email);
    $cadastrar->setApelido($apelido);
    $cadastrar->setSenha($senha);
    $cadastrar->setCsenha($csenha);
    $cadastrar->setNumero($numero);
    $cadastrar->setTipo($tipo);
    $cadastrar->setGenero($genero);
    $cadastrar->cadastrar();