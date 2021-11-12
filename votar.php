<?php
  session_start();
  include 'conection/Conexao.class.php';
  include 'controller/Controller.class.php';
  include 'model/Model.class.php';
  include 'library/Adm.class.php';
  
  
  if(!isset($_COOKIE['email']) && !isset($_COOKIE['numero'])){
    setcookie("email","none",time()+60*60*24,"/");
    setcookie("numero","none",time()+60*60*24,"/");
  }

  //change profile pic=======

  if(isset($_REQUEST['profile'])){
    $file=isset($_FILES['profile_pic'])?$_FILES['profile_pic']:"";

    if($file['type']=="image/jpg" || $file['type']=='image/png' || $file['type']=='image/jpeg' || $file['type']=='image/gif' || $file['type']==""){
        $profile=new Model();
        $profile->setNumero($_COOKIE['numero']);
        $profile->setEmail($_COOKIE['email']);
        $profile->setAvatar($file['name']);
        $profile->changeProfile();

        move_uploaded_file($file['tmp_name'],"ficheiros/".$file['name']);
        header('location:index.php');
    }  
  }

  //end============================

  //participantes
  $participantes=new Model();
  $participantes->inserirCadastro();
  $participantes->inserirParticipante();
  $participantes1=$participantes->participantes();

  //percentagens

  $percentagem=new Model();

?>

<!DOCTYPE html>
<html lang="pt">
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reality Show</title>
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
    <link href='css/mdal.css' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/bootstrap-337.min.css">
    <link rel="stylesheet" href="font-awsome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>



  <!--Botao para subir ao topo-->

  <div class="scroll-up-btn">
    <i class="fa fa-angle-up"></i>
  </div>

  <nav class="navbar" style='background: orange !important;padding:15px'><!--inicio navbar -->
    <div class="max-width"><!--inicio max-width -->

      <div class="logo"> <a href='index.php'><img src='imagens/reality.png' width=120 height=58></a></div>

      <ul class="menu"><!--inicio ul menu -->

        <li> <a href="index.php" class="menu-btn">Inicio</a> </li>
        <li> <a href="votar.php" class="menu-btn">Votar</a> </li>
        <li> <a href="index.php#about" class="menu-btn">Sobre</a> </li>
        <li> <a href="index.php#services" class="menu-btn">Patrocinadores</a> </li>
        <li> <a href="index.php#skills" class="menu-btn">Categorias</a> </li>
        <?php

                    //ocultar participantes
                    $cadastro=new Model();
                    $cadastro->setTipo("participantes");

                    foreach($cadastro->oculto() as $oculto):
                    if($oculto['ocultar']!=true):

                  ?>
        <li> <a href="index.php#teams" class="menu-btn">Participantes</a> </li>
                      <?php
                        endif;
                      endforeach;
                      ?>
                      
        <?php if($_COOKIE['email']!='none' || $_COOKIE['numero']!='none'):?>
          <li class='feed'> <a href="index.php#contact" class="menu-btn">Feed</a> </li>
         <?php else:?> 
                  <?php

                    //ocultar cadastro
                    $cadastro=new Model();
                    $cadastro->setTipo("cadastro");

                    if($cadastro->oculto()->rowCount()>0):
                      foreach($cadastro->oculto() as $oculto):
                        if($oculto['ocultar']!=true):

                  ?>
                      <li> <a href="index.php#contact" class="menu-btn">Cadastrar</a> </li>
                 <?php
                    endif;
                  endforeach;
                else:
                 ?>
                    <li> <a href="index.php#contact" class="menu-btn">Cadastrar</a> </li>
         <?php 
        endif;
        endif;
        
        ?> 

      </ul><!--fim ul menu -->

      <div class="menu-btn"><!--inicio ul menu-btn -->
        <i class="fa fa-bars"></i>  
      </div><!--fim ul menu-btn -->

    </div><!--fim max-width -->
  </nav><!--fim navbar -->

  <!-- Inicio de Seccao home -->
  <br><section class='votar_secao'><!--inicio home -->

    <?php
        $users=new Model();
        foreach($users->todos_participantes() as $linha){
            ?>
               <div class='votar_data' style='display:flex;align-items:center;'> 
                   <strong class='col-md-5' style='width:50%'><?=ucwords(strtolower($linha['nome']." ".$linha['apelido']))?></strong>
                 <div style='display:flex;align-items:center;justify-content:flex-end;width:50%' class='col-md-7'>  
                   <button class='btn btn-primary ml-0 mr-0 votar'>Votar</button>
                </div>  
                </div>
            <?php
        }
        
    ?>
  </section>
 <!-- <footer>
      <span>Estrelas do amanh&atilde; | <span class="fa fa-copyright"></span> <?php echo date("Y")?> Todos os Direitos Reservados</span>
 </footer> -->


<script src="js/jquery-331.min.js"></script>
<script src="js/bootstrap-337.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>


<script>

  //votar
$('.votar').on('click',function(){
    var opcao=prompt("1-Mkesh\n2-Conta Movel");
    var numero=prompt("Numero de celular");
    var senha=prompt("Codigo");
    //var valor=prompt("Valor");
    var numVotos=prompt("Numero de votos");
    var conf=window.confirm("Pagar: "+numVotos*5+"Mt?");

    if(conf){
        alert("Votado");
        $('.close').trigger('click');
        cont=0;
    }

})

</script>

