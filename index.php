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
    <meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1.0,maximum-scale=1.0">
    <title>Reality Show</title>
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
    <link href='css/mdal.css' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/bootstrap-337.min.css">
    <link rel="stylesheet" href="font-awsome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<!-------------profile pic change---------------->
  <div class='modal fade' role='dialog' id='profile'>
    <div class='modal-dialog'>
      <div class='modal-content'>
        <div class='modal-header bg-warning'>
          <h6 class='modal-title'>Mudar foto do perfil</h6>
          <button class='close' data-dismiss='modal'>&times;</button>
       </div>
       <div class='modal-body' style='display:flex;justify-content:center'>
         <?php
         //show profile pic
            $pic=new Model();
            $pic->setNumero($_COOKIE['numero']);
            $pic->setEmail($_COOKIE['email']);
            $result=$pic->logged();

            if($result->rowCount()>0){
              foreach($result as $linha){
                if($linha['avatar']!=''){
                    echo "<img src='ficheiros/".$linha['avatar']."' class='img-fluid'>";
                }else{
                  echo "<span class='fa fa-user' style='font-size:70px'></span>";
                }
              }
            }
            
         ?>
       </div>
        <div class='modal-footer bg-warning' id='p_footer'>
        <form method='post' enctype='multipart/form-data'>
            <input type='file' name='profile_pic' id='perfil' accept="image/*"/>
            <span class='fa fa-camera text-warning profile_gimmick' style='font-size:50px;cursor:pointer'></span>
            <button class='btn btn-warning' style='margin-top:-25px' name='profile'>
              <span class='fa fa-check'></span>
            </button>
        </form>
        </div>
      </div>
    </div>
  </div>
<!----------------------------------------------->
  <!--Botao para subir ao topo-->

  <div class="scroll-up-btn">
    <i class="fa fa-angle-up"></i>
  </div>

  <nav class="navbar"><!--inicio navbar -->
    <div class="max-width"><!--inicio max-width -->

      <div class="logo"> <a href='index.php'><img src='imagens/reality.png' width=120 height=58></a></div>

      <ul class="menu"><!--inicio ul menu -->

        <li> <a href="#home" class="menu-btn">Inicio</a> </li>
        <li> <a href="votar.php" class="menu-btn">Votar</a> </li>
        <li> <a href="#about" class="menu-btn">Sobre</a> </li>
        <li> <a href="#services" class="menu-btn">Patrocinadores</a> </li>
        <li> <a href="#skills" class="menu-btn">Categorias</a> </li>
        <?php

                    //ocultar participantes
                    $cadastro=new Model();
                    $cadastro->setTipo("participantes");

                    foreach($cadastro->oculto() as $oculto):
                    if($oculto['ocultar']!=true):

                  ?>
        <li> <a href="#teams" class="menu-btn">Participantes</a> </li>
                      <?php
                        endif;
                      endforeach;
                      ?>
                      
        <?php if($_COOKIE['email']!='none' || $_COOKIE['numero']!='none'):?>
          <li class='feed'> <a href="#contact" class="menu-btn">Feed</a> </li>
         <?php else:?> 
                  <?php

                    //ocultar cadastro
                    $cadastro=new Model();
                    $cadastro->setTipo("cadastro");

                    if($cadastro->oculto()->rowCount()>0):
                      foreach($cadastro->oculto() as $oculto):
                        if($oculto['ocultar']!=true):

                  ?>
                      <li> <a href="#contact" class="menu-btn">Cadastrar</a> </li>
                 <?php
                    endif;
                  endforeach;
                else:
                 ?>
                    <li> <a href="#contact" class="menu-btn">Cadastrar</a> </li>
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
  <section class="home" id="home"><!--inicio home -->
    <div class="max-width"><!--inicio max-width -->
        <div class="home-content"><!--inicio home-content -->

            <div class="text-1">Seja Bem vindo</div>
            <div class="text-2">Estrelas do Amanh&atilde;</div>
            <div class="text-3">Junte-se <span>a n&oacute;s</span></div>
            <a href="https://api.whatsapp.com/send?phone=258848499142&text=Ol%C3%A1%2C%20seja%20bem-vindo(a)%2C%20Obrigado%20por%20nos%20contactar!" target="_blank">Contacto</a>

        </div><!--fim home-content -->
    </div><!--fim max-width -->
  </section><!--fim home -->

  <!-- Inicio Seccao Sobre-->

 <section class="about" id="about"><!--inicio about -->
   <div class="max-width"><!--inicio max-width -->
    <h2 class="title">Sobre </h2>
     <div class="about-content"><!--inicio about-content -->
       <div class="column left"><!--inicio colum left -->

         <img src="imagens/user.jpg"  class='img-fluid' alt="">
       
       </div><!--fim colum left -->

       <div class="column right">
          <div class="text"> <span id='ini-text'>Estrelas do Amanh&atilde;</span></div>
          <p> Um programa criado com o objectivo de entreter o nosso povo, al&eacute;m disso, tamb&eacute;m para mostrar o talento dos nossos cantores em diferentes estilos musicais com diferentes ritmos e cultura. Es um talento perdido? n&atilde;o perca mais tempo, inscreva-te, mostre-nos o seu talento e habilite-se a ganhar varios premios</p>
          <!-- <a href="#">Trabalhos</a> -->
       </div>

     </div><!--fim about-content -->
   </div><!--fim max-width -->
 </section><!--fim about -->


  <!-- Inicio Seccao services-->
 <section class="services" id="services"><!--inicio about -->
   <div class="max-width"><!--inicio max-width -->

   <!-------producao 1------------------------->
   <h2 class="title my-0" style='margin-top:-10px;'>Produ&ccedil;&atilde;o</h2>
   <div class="serv-content" style='justify-content:center'><!--inicio about-content -->
      <!-------producao 2------------------------->
        <div class="card m "><!--inicio card -->
          <div class="box"><!--inicio box -->
          <a href='#' target='_blank'>
            <img src='imagens/PRODUCAO.png' style='width-width:100%;height:120px'>
            <div class="text">Manheche Records</div>
            <p>Este espa&ccedil;o foi reservado para apresentar informa&ccedil;&otilde;es relacionadas a produ&ccedil;&atilde;o do projecto.</p>
          </a>  
          </div><!--fim box -->
        </div><!--fim card -->

<!-----------------producao 2---------------->
        <div class="card m patr"><!--inicio card -->
          <div class="box"><!--inicio box -->
          <a href='#' target='_blank'>
            <img src='imagens/PRODUCAO2.jpg' style='width:120px;height:120px;border-radius:120px'>
            <div class="text">TVA</div>
            <p>Este espa&ccedil;o foi reservado para apresentar informa&ccedil;&otilde;es relacionadas a produ&ccedil;&atilde;o do projecto.</p>
          </a>  
          </div><!--fim box -->
        </div><!--fim card -->
     
   </div>
   
   <hr>


   <!---------end parceiros----------------->
    <h2 class="title">Patrocinadores</h2>
     <div class="serv-content"><!--inicio about-content -->
      
        <div class="card"><!--inicio card -->
          <div class="box"><!--inicio box -->
            <a href='#' target='_blank'>
              <i class="fa fa-user" style='font-size:100px'></i>
                <div class="text">Patrocinador 1</div>
             </a>   
            <!-- <p>Este espa&ccedil;o foi reservado para apresentar informa&ccedil;&otilde;es relacionadas a um dos patrocinadores do projecto.</p> -->
          </div><!--fim box -->
        </div><!--fim card -->

         <div class="card"><!--inicio card -->
          <div class="box"><!--inicio box -->
             <a href='#' target='_blank'>
               <i class="fa fa-user" style='font-size:100px'></i>
                 <div class="text">Patrocinador 2</div>
             </a>    
            <!-- <p> Este espa&ccedil;o foi reservado para apresentar informa&ccedil;&otilde;es relacionadas a um dos patrocinadores do projecto.</p> -->
          </div><!--fim box -->
        </div><!--fim card -->

         <div class="card m"><!--inicio card -->
          <div class="box"><!--inicio box -->
            <a href='#' target='_blank'>
              <i class="fa fa-user" style='font-size:100px'></i>
                <div class="text">Patrocinador 3</div>
             </a>   
            <!-- <p>Este espa&ccedil;o foi reservado para apresentar informa&ccedil;&otilde;es relacionadas a um dos patrocinadores do projecto.</p> -->
          </div><!--fim box -->
        </div><!--fim card -->

     </div><!--fim about-content -->
   </div><!--fim max-width -->
 </section><!--fim about -->

<!-- Inicio Seccao Skills-->
 <section class="skills" id="skills"><!--inicio about -->
   <div class="max-width"><!--inicio max-width -->
    <h2 class="title">Categorias</h2>
     <div class="skills-content"><!--inicio about-content -->
       <div class="column left"><!--inicio colum left -->

        <div class="text">Percentagem de cadastrados em cada categoria</div>
            <p>Os graficos a seguir mostram a apercentagem de candidatos em cada categoria musical, cada categoria possui um certo numero de candidatos que fara parte deste programa.</p>

            <p>Cada categoria pode ir aumentando ou diminuindo de percentagem assim que os candidatos v&atilde;o se inscrevendo, Quando um grafico estiver no 100%, n&atilde;o significa que esta categoria atingiu o limite de candidatos, mas sim que todos os candidatos inscritos est&atilde;o cadastrado nesta categoria</p>

            <p>Quando um grafico tiver mais percentagem que o outro, n&atilde;o significa que os candidatos que cadastraram-se para esta categoria t&ecirc;m mais chances de vencer.<br>
            A percentagem somente mostra qual das categorias t&ecirc;m mais candidatos inscritos e qual t&ecirc;m menos candidatos inscritos.
            </p>

            <p>
            Este programa, para al&eacute;m de mostrar o talento dos nossos jovens e dar visibilidade a eles, tambem possui premios para os vencedores do mesmo.
            </p>

            <p>
              Para participar, fa&ccedil;a cadastro nesta pagina, selecionando a categoria, e selecionando a op&ccedil;&atilde;o "Participante", e depois continuar a preencher os dados normalmente, e no final, depois de verificar que todos os dados est&atilde;o correctos, clicar em "cadastrar"
            </p>

            <p>
              O cadastro possui uma data limite, se n&atilde;o estiver cadastrado ate essa data, o sistems de cadastro deixara de estar disponivel no site.
            </p>

            <!-- <a href="#">Ler Mais</a> -->

       </div><!--fim colum left -->

       <div class="column right append"><!--inicio column right -->

       <!----------canto------------------->
       <div class="bars canto_content" ><!--inicio bars-->
       <span class='fa fa-chevron-down'></span>
         <?php $percentagem->setGenero("canto")?>
           <div class="info" ><!--inicio info -->
             <span>Canto</span>
             <span><?php echo $percentagem->percentagem_group()?>%</span> 
           </div><!--fim info -->
           <!-----percentagem da bara-------------->
           <style>
              .ln1::before{
                width:<?php echo $percentagem->percentagem_group()."%"?> !important;
              }
           </style>
           <div class="line  ln1" ></div>
            
         </div><!--fim bars-->

           


       <div class='canto'><!---------canto start----------->
         <div class="bars" ><!--inicio bars-->
         <?php $percentagem->setCategoria("HipHop")?>
         <?php $percentagem->setGenero("canto")?>
           <div class="info" ><!--inicio info -->
             <span>HipHop</span>
             <span><?php echo $percentagem->percentagem()?>%</span> 
           </div><!--fim info -->
           <!-----percentagem da bara-------------->
           <style>
              .html::before{
                width:<?php echo $percentagem->percentagem()."%"?> !important;
              }
           </style>
           <div class="line html" ></div>
         </div><!--fim bars-->

         <div class="bars"><!--inicio bars-->
         <?php $percentagem->setCategoria("Zouk")?>
           <div class="info"><!--inicio info -->
             <span>Zouk</span>
             <span><?php echo $percentagem->percentagem()?>%</span>
           </div><!--fim info -->
           <!-----percentagem da bara-------------->
           <style>
              .css::before{
                width:<?php echo $percentagem->percentagem()."%"?> !important;
              }
           </style>
           
           <div class="line css"></div>
         </div><!--fim bars-->

         <div class="bars"><!--inicio bars-->
         <?php $percentagem->setCategoria("Marabenta")?>
           <div class="info"><!--inicio info -->
             <span>Marabenta</span>
             <span><?php echo $percentagem->percentagem()?>%</span>
           </div><!--fim info -->
           <!-----percentagem da bara-------------->
           <style>
              .js::before{
                width:<?php echo $percentagem->percentagem()."%"?> !important;
              }
           </style>
           
           <div class="line js"></div>
         </div><!--fim bars-->

         <div class="bars"><!--inicio bars-->
         <?php $percentagem->setCategoria("Pandza")?>
           <div class="info"><!--inicio info -->
             <span>Pandza</span>
             <span><?php echo $percentagem->percentagem()?>%</span>
           </div><!--fim info -->
           <!-----percentagem da bara-------------->
           <style>
              .php::before{
                width:<?php echo $percentagem->percentagem()."%"?> !important;
              }
           </style>
           
           <div class="line php"></div>
         </div><!--fim bars-->

         <div class="bars"><!--inicio bars-->
         <?php $percentagem->setCategoria("Gospel")?>
           <div class="info"><!--inicio info -->
             <span>Gospel</span>
             <span><?php echo $percentagem->percentagem()?>%</span>
           </div><!--fim info -->
           <!-----percentagem da bara-------------->
           <style>
              .gospel::before{
                width:<?php echo $percentagem->percentagem()."%"?> !important;
              }
           </style>
           
           <div class="line gospel"></div>
         </div><!--fim bars-->

         <div class="bars"><!--inicio bars-->
         <?php $percentagem->setCategoria("Kizomba")?>
           <div class="info"><!--inicio info -->
             <span>Kizomba</span>
             <span><?php echo $percentagem->percentagem()?>%</span>
           </div><!--fim info -->
           <!-----percentagem da bara-------------->
           <style>
              .kiz::before{
                width:<?php echo $percentagem->percentagem()."%"?> !important;
              }
           </style>
           
           <div class="line kiz"></div>
         </div><!--fim bars-->

         <div class="bars"><!--inicio bars-->
         <?php $percentagem->setCategoria("Rock")?>
           <div class="info"><!--inicio info -->
             <span>Rock</span>
             <span><?php echo $percentagem->percentagem()?>%</span>
           </div><!--fim info -->
           <!-----percentagem da bara-------------->
           <style>
              .rock::before{
                width:<?php echo $percentagem->percentagem()."%"?> !important;
              }
           </style>
           
           <div class="line rock"></div>
         </div><!--fim bars-->

         <div class="bars"><!--inicio bars-->
         <?php $percentagem->setCategoria("Afro Naija")?>
           <div class="info"><!--inicio info -->
             <span>Afro Naija</span>
             <span><?php echo $percentagem->percentagem()?>%</span>
           </div><!--fim info -->
           <!-----percentagem da bara-------------->
           <style>
              .naija::before{
                width:<?php echo $percentagem->percentagem()."%"?> !important;
              }
           </style>
           
           <div class="line naija"></div>
         </div><!--fim bars-->

         <div class="bars"><!--inicio bars-->
         <?php $percentagem->setCategoria("Outros")?>
           <div class="info"><!--inicio info -->
             <span>Outros</span>
             <span><?php echo $percentagem->percentagem()?>%</span>
           </div><!--fim info -->
           <!-----percentagem da bara-------------->
           <style>
              .out::before{
                width:<?php echo $percentagem->percentagem()."%"?> !important;
              }
           </style>
           
           <div class="line out"></div>
         </div><!--fim bars-->
      </div><!---------fim canto-------->


<!----------danca------------------->

<!----------danca------------------->
<div class="bars danca_content"  ><!--inicio bars-->
       <span class='fa fa-chevron-down'></span>
         <?php $percentagem->setGenero("danca")?>
           <div class="info" ><!--inicio info -->
             <span>Dan&ccedil;a</span>
             <span><?php echo $percentagem->percentagem_group()?>%</span> 
           </div><!--fim info -->
           <!-----percentagem da bara-------------->
           <style>
              .ln::before{
                width:<?php echo $percentagem->percentagem_group()."%"?> !important;
              }
           </style>
           <div class="line  ln" ></div>
            
         </div><!--fim bars-->


<div class='danca' style='display:none'><!---------danca start----------->

 

         <div class="bars" ><!--inicio bars-->
         <?php $percentagem->setCategoria("HipHop")?>
         <?php $percentagem->setGenero("danca")?>
           <div class="info" ><!--inicio info -->
             <span>HipHop</span>
             <span><?php echo $percentagem->percentagem()?>%</span> 
           </div><!--fim info -->
           <!-----percentagem da bara-------------->
           <style>
              .html1::before{
                width:<?php echo $percentagem->percentagem()."%"?> !important;
              }
           </style>
           <div class="line html1" ></div>
         </div><!--fim bars-->

         <div class="bars"><!--inicio bars-->
         <?php $percentagem->setCategoria("Zouk")?>
           <div class="info"><!--inicio info -->
             <span>Zouk</span>
             <span><?php echo $percentagem->percentagem()?>%</span>
           </div><!--fim info -->
           <!-----percentagem da bara-------------->
           <style>
              .css1::before{
                width:<?php echo $percentagem->percentagem()."%"?> !important;
              }
           </style>
           
           <div class="line css1"></div>
         </div><!--fim bars-->

         <div class="bars"><!--inicio bars-->
         <?php $percentagem->setCategoria("Marabenta")?>
           <div class="info"><!--inicio info -->
             <span>Marabenta</span>
             <span><?php echo $percentagem->percentagem()?>%</span>
           </div><!--fim info -->
           <!-----percentagem da bara-------------->
           <style>
              .js1::before{
                width:<?php echo $percentagem->percentagem()."%"?> !important;
              }
           </style>
           
           <div class="line js1"></div>
         </div><!--fim bars-->

         <div class="bars"><!--inicio bars-->
         <?php $percentagem->setCategoria("Pandza")?>
           <div class="info"><!--inicio info -->
             <span>Pandza</span>
             <span><?php echo $percentagem->percentagem()?>%</span>
           </div><!--fim info -->
           <!-----percentagem da bara-------------->
           <style>
              .php1::before{
                width:<?php echo $percentagem->percentagem()."%"?> !important;
              }
           </style>
           
           <div class="line php1"></div>
         </div><!--fim bars-->

         <div class="bars"><!--inicio bars-->
         <?php $percentagem->setCategoria("Gospel")?>
           <div class="info"><!--inicio info -->
             <span>Gospel</span>
             <span><?php echo $percentagem->percentagem()?>%</span>
           </div><!--fim info -->
           <!-----percentagem da bara-------------->
           <style>
              .gospel1::before{
                width:<?php echo $percentagem->percentagem()."%"?> !important;
              }
           </style>
           
           <div class="line gospel1"></div>
         </div><!--fim bars-->

         <div class="bars"><!--inicio bars-->
         <?php $percentagem->setCategoria("Kizomba")?>
           <div class="info"><!--inicio info -->
             <span>Kizomba</span>
             <span><?php echo $percentagem->percentagem()?>%</span>
           </div><!--fim info -->
           <!-----percentagem da bara-------------->
           <style>
              .kiz1::before{
                width:<?php echo $percentagem->percentagem()."%"?> !important;
              }
           </style>
           
           <div class="line kiz1"></div>
         </div><!--fim bars-->

         <div class="bars"><!--inicio bars-->
         <?php $percentagem->setCategoria("Rock")?>
           <div class="info"><!--inicio info -->
             <span>Rock</span>
             <span><?php echo $percentagem->percentagem()?>%</span>
           </div><!--fim info -->
           <!-----percentagem da bara-------------->
           <style>
              .rock1::before{
                width:<?php echo $percentagem->percentagem()."%"?> !important;
              }
           </style>
           
           <div class="line rock1"></div>
         </div><!--fim bars-->

         <div class="bars"><!--inicio bars-->
         <?php $percentagem->setCategoria("Afro Naija")?>
           <div class="info"><!--inicio info -->
             <span>Afro Naija</span>
             <span><?php echo $percentagem->percentagem()?>%</span>
           </div><!--fim info -->
           <!-----percentagem da bara-------------->
           <style>
              .naija1::before{
                width:<?php echo $percentagem->percentagem()."%"?> !important;
              }
           </style>
           
           <div class="line naija1"></div>
         </div><!--fim bars-->

         <div class="bars"><!--inicio bars-->
         <?php $percentagem->setCategoria("Outros")?>
           <div class="info"><!--inicio info -->
             <span>Outros</span>
             <span><?php echo $percentagem->percentagem()?>%</span>
           </div><!--fim info -->
           <!-----percentagem da bara-------------->
           <style>
              .out1::before{
                width:<?php echo $percentagem->percentagem()."%"?> !important;
              }
           </style>
           
           <div class="line out1"></div>
         </div><!--fim bars-->
      </div><!---------fim danca-------->

       </div><!--fim colum right -->
     </div><!--fim about-content -->
   </div><!--fim max-width -->
 </section><!--fim about -->

 <!-- Inicio Seccao Minha Equipa-->
 
 <section class="teams" id="teams"><!--inicio about -->
 <?php

//ocultar participantes
$cadastro1=new Model();
$cadastro1->setTipo("participantes");

foreach($cadastro1->oculto() as $oculto):
if($oculto['ocultar']!=true):

?>
   <div class="max-width"><!--inicio max-width -->
    <h2 class="title">Participantes</h2>
         <!------------------search area------------------->
         <div class='search_area'>
           <input type='search' id='search' class='form-control' placeholder="Procurar"/>
         </div>
     <div class="teams-content"><!--inicio carousel -->

     <?php
      $id="";
      ?>
     <?php foreach($participantes1 as $users):?>
        <div class="card informacao" data-target='#modal<?php echo $users['id']?>' data-toggle='modal' data-id='<?php echo $users['id']?>' onclick='info(<?php echo $users["id"]?>)' ><!--inicio card-->
          <div class="box"><!--inicio box -->
            
          <?php if($users['avatar']!=''): ?>
                    <img src='ficheiros/<?php echo $users['avatar']?>' class='img-fluid'>
          <?php else: ?>
                   <img src='imagens/black.png' alt=''>
          <?php endif;?>
              
             <div class="text"><?php echo ucwords(strtolower($users['nome']." ".$users['apelido']))?></div>
             <p>Categoria: <?php echo $users['categoria']?> (<?=$users['tipo']?>)</p>
             <p>Codigo: <?php echo $users['codigo']?></p>
             
          </div><!--fim box -->
        </div><!--fim card-->
        <?php $id=$users['id']?>
     <?php endforeach?>
      <input type='hidden' value='<?php echo $id?>' id='hidden'/>
      <input type='hidden' value='<?php echo $id1?>' id='hidden1'/>

     </div><!--fim carousel -->
   </div><!--fim max-width -->

   <div id='botoes'><!---botao next e previous---->
   <!------------left---------------->
      <button class='btn btn-warning less'>
        <span class='text-light' style='font-weight:bolder;font-size:17px'><</span>
      </button>
      <!------right------------------>
      <button class='btn btn-warning more'>
        <span class='text-light' style='font-weight:bolder;font-size:17px'>></span>
      </button>
   </div><!---fim botoes--->
   <br><center>
      <button class='btn btn-warning excluidos' >Ver lista dos excluidos</button>
   </center>   
   <?php
endif;
endforeach;
?>
 </section><!--fim about -->


<!-- Inicio Seccao Contacto-->

<?php if($_COOKIE['email']=="none" && $_COOKIE['numero']=="none"):?>
  
  <?php

//ocultar participantes
$cadastro=new Model();
$cadastro->setTipo("cadastro");

foreach($cadastro->oculto() as $oculto):
?>
 <section class="contact" id="contact"><!--inicio about -->
 
   <div class="max-width"><!--inicio max-width -->
    <h2 class="title">Login/Cadastro</h2>
     <div class="contact-content "><!--inicio contact-content -->
       <div class="column left" ><!--inicio colum left -->

       <div class="column right cadastrar" style='width:100%;'><!--inicio column right-->
       <div class='error text-danger'></div>
          <div class="text">Entrar</div>
            <form action="#"><!--inicio formulario de cadastro-->
                
              
            <div class="field" > 
                  <select name='categoria' class='form-control' id='categoria1' disabled style='opacity:0'>
                    <option value='Marabenta'>Marabenta</option>
                    <option value='Pandza'>Pandza</option>
                    <option value='Gospel'>Gospel</option>
                    <option value='Kizomba'>Kizomba</option>
                    <option value='Zouk'>Zouk</option>
                    <option value='Rock'>Rock</option>
                    <option value='HipHop'>HipHop</option>
                    <option value='Afro Naija'>Afro Naija</option>
                    <option value='Outros'>Outros</option>
                  </select>
                </div>
                <div class='field'>
                <select class='form-control' id='tipo1' disabled style='opacity:0'>
                    <option value='participante'>Participante</option>
                    <option value='participante'>Visitante</option>
                </select>
              </div>  
              
                  <div class="fields"><!--inicio fields-->

                <div class="field name">
                  <input type="text" name="" placeholder="Nome" required disabled style='opacity:0'>
                </div>

                <div class="field email">
                  <input type="text" name="" placeholder="Apelido" required disabled style='opacity:0'>
                </div>
              </div>
                
              <!--------------not disabled-------------->
              <div class="field">
                 <input type="text" name="email1" id='email1' placeholder="Email/Telefone"  min=8 max=90 required>
              </div>
              <div class="field">
                  <input type="password" name="senha1" id='senha1' placeholder="Senha" required>
             </div>
              <!------------------------------------------------------->

              
              <div class="fields"><!--inicio fields-->

                <div class="field name">
                  <input type="text" name="" placeholder="Nome" required disabled style='opacity:0'>
                </div>

                <div class="field email">
                  <input type="text" name="" placeholder="Apelido" required disabled style='opacity:0'>
                </div>
              </div>
                
           
                <div class="fields"><!--inicio fields-->

                <div class="field name">
                  <input type="email" name="" placeholder="Email" required disabled style='opacity:0'>
                </div>

                <div class="field email">
                  <input type="number" name="" placeholder="Numero de telefone" required disabled style='opacity:0'>
                </div>

              </div>

              <div class='fields'>
                <div class="field name">
                    <input type="password" name="" placeholder="Senha" required disabled style='opacity:0'>
                  </div>

                  <div class="field email">
                    <input type="password" name="" placeholder="Confirmar senha" required disabled style='opacity:0'>
                  </div>
                
              </div>
              
              
                <div class="button ">
                  <button type="submit" class='ent'>Entrar</button>
                </div>

            </form><!--fim formulario -->


       </div><!--fim column right -->


       </div><!--fim colum left -->


<?php
  if($oculto['ocultar']!=true):
?>
       <div class="column right cadastrar"><!--inicio column right-->
          <div class='erro text-danger'></div>
          <div class="text">Cadastrar</div>

            <form action="#" method='post'><!--inicio formulario de cadastro-->

            <!------------------participate ou nao-------------->
            <div class='field'>
                <select class='form-control' id='tipo' >
                    <option value='participante'>Participante</option>
                    <option value='visitante'>Visitante</option>
                </select>
              </div>  
  <!-------------------------------categoria---------------------------->
              <div class='field'>
                <select class='form-control' id='categoria' >
                    <!-- <option value='categoria' >Categoria</option> -->
                    <option value='canto'>Canto</option>
                    <option value='danca'>Dan&ccedil;a</option>
                </select>
              </div>  
            <!-------------categorias-------------------->
            

                <!----------------canto------------------------>
                <div class="field nome canto">
                <select name='categoria' class='form-control genero genero1' id='genero'>
                    <option value='Marabenta'>Marabenta</option>
                    <option value='Pandza'>Pandza</option>
                    <option value='Gospel'>Gospel</option>
                    <option value='Kizomba'>Kizomba</option>
                    <option value='Zouk'>Zouk</option>
                    <option value='Rock'>Rock</option>
                    <option value='HipHop'>HipHop</option>
                    <option value='Afro Naija'>Afro Naija</option>
                    <option value='Outros'>Outros</option>
                  </select>
                </div>

                <!--------------danca-------------------->
                <div class="field nome danca" style='display:none'>
                 <select class='form-control genero genero2' id='genero'  >
                 <option value='Marabenta'>Marabenta</option>
                    <option value='Pandza'>Pandza</option>
                    <option value='Gospel'>Gospel</option>
                    <option value='Kizomba'>Kizomba</option>
                    <option value='Zouk'>Zouk</option>
                    <option value='Rock'>Rock</option>
                    <option value='HipHop'>HipHop</option>
                    <option value='Afro Naija'>Afro Naija</option>
                    <option value='Outros'>Outros</option>
                 </select>
                </div>
 
            <!------------------------end----------------->
         
            
              <div class="fields"><!--inicio fields-->

                <div class="field name">
                  <input type="text" name="nome" placeholder="Nome" id='nome' required>
                </div>

                <div class="field email">
                  <input type="text" name="apelido" id='apelido' placeholder="Apelido" required>
                </div>
              </div>
                <div class="field">
                  <input type="number" name="idade" id='idade' placeholder="Idade"  min=15 max=25 required>
                </div>
                <div class="field">
                  <input type="text" name="morada" id='morada' placeholder="Morada" required>
                </div>
              

                <div class="fields"><!--inicio fields-->

                <div class="field name"> 
                  <input type="email" name="email" id='email' placeholder="Email" required>
                </div>

                <div class="field email">
                  <input type="number" name="numero" id='numero' placeholder="Numero de telefone" required>
                </div>

              </div>

              <div class='fields'>
                <div class="field name">
                    <input type="password" id='senha' name="senha" placeholder="Senha" required>
                  </div>

                  <div class="field email">
                    <input type="password" id='csenha' name="csenha" placeholder="Confirmar senha" required>
                  </div>
                
              </div>
              
              
                <div class="button ">
                  <button type="button" class='reg'>Cadastrar</button>
                </div>

            </form><!--fim formulario -->


       </div><!--fim column right -->
     <?php
      else:
        echo "<div style='width:100%;padding:5px'></div>";
      endif;
    endforeach;
  ?>
     </div><!--fim contact-content -->
   </div><!--fim max-width -->
 
 </section><!--fim about -->

 <?php else:?>
        <center><h3>
        <?php
            $logged=new Model();
            $logged->setEmail($_COOKIE['email']);
            $query=$logged->logged();

            foreach($query as $name){
              //show user icon if there's no profile pic
              if($name['avatar']==""){
                //alterar imagem
                if($name['email']==$_COOKIE['email'] || $name['numero']==$_COOKIE['numero']){
                  echo "<div class='avatar profile' data-target='#profile' data-toggle='modal'><i class='fa fa-user'></i></div>";
                }else{
                  echo "<div class='avatar'><i class='fa fa-user'></i></div>";
                }
                //show profile pic
              }else{
                if($name['email']==$_COOKIE['email'] || $name['numero']==$_COOKIE['numero']){
                    echo "<div class='avatar1 profile' data-target='#profile' data-toggle='modal'><img  src='ficheiros/".$name['avatar']."'></div>";
                }else{
                  echo "<div class='avatar1'><img  src='ficheiros/".$name['avatar']."'></div>";
                }  
              }
              echo $name['email'];
            }
           
           ?>
        </h3></center>
        <div class='menus'><!------menus ocultar e sair---->
        <?php
            $adm=new Adm();
            foreach($adm->managers() as $adms):
              if((strtolower($adms)==strtolower($_COOKIE['email']) || strtolower($adms)==strtolower($_COOKIE['numero'])) && (isset($_COOKIE['numero']) || isset($_COOKIE['email']))):
                //verificar se estaoculto ou nao
                $model=new Model();
                $model->setTipo("cadastro");
                foreach($model->oculto() as $oculto):
                  //se estiver oculto
                  if($oculto['ocultar']==true):
           ?>
                   <a href='views/ocultar.php?menu=cadastro'>Mostrar cadastro</a>
                   <!-----se nao estiver oculto----->
              <?php else:?>     
                   <a href='views/ocultar.php?menu=cadastro'>Ocultar cadastro</a>
               <?php 
                    endif;
                  endforeach;   

                  $model1=new Model();
                  $model1->setTipo("participantes");

                  //verificar se esta oculto ou nao
                  foreach($model1->oculto() as $oculto):
                    //se estiver oculto
                    if($oculto['ocultar']==true):
              ?>
                  <a href='views/ocultar.php?menu=participantes'>Mostrar participantes</a>
                  <!---se nao estiver oculto---->
              <?php else:?>
                   <a href='views/ocultar.php?menu=participantes'>Ocultar participantes</a>
                 <?php
                  endif;
                endforeach;
                 ?>  
        
        <?php
          endif;
        endforeach;
        
        ?>
        <a href='views/sair.php'>Sair</a>

        </div><!-----end menus ocultar e sair---->
      
<?php endif;?>
 <!-- Inicio Footer-->

 <footer>
      <span>Estrelas do amanh&atilde; | <span class="fa fa-copyright"></span> <?php echo date("Y")?> Todos os Direitos Reservados</span>
 </footer>


<script src="js/jquery-331.min.js"></script>
<script src="js/bootstrap-337.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>


<script>

//registar usuario====================

$('.reg').on('click',function(evt){
  evt.preventDefault();
  $.ajax({
  url:'views/cadastrar.php',
  type:'post',
  data:{
    numero:$('#numero').val(),
    nome:$('#nome').val(),
    categoria:$('#categoria').val(),
    senha:$('#senha').val(),
    csenha:$('#csenha').val(),
    idade:$('#idade').val(),
    morada:$('#morada').val(),
    apelido:$('#apelido').val(),
    email:$('#email').val(),
    tipo:$('#tipo').val(),
    genero1:$('.genero').val(),
    genero2:$('.genero2').val()
  },
  success:function(data){
   
    $('.erro').html(data);
    $('.erro').fadeIn(400);
      
    //limpar campos se ja tiver sucesso no registo
    if(data=="<span style='color:#0f0 !important'>Registado com sucesso, fa&ccedil;a login para confirmar conta</span>"){
      $('#nome').val("");
      $('#senha').val("");
      $('#csenha').val("");
      $('#idade').val("");
      $('#email').val("");
      $('#morada').val("");
      $('#apelido').val("");
      $('#numero').val("");
      //window.open(window.location.href,'_self');

    }
    
  }
})

})


//fechar categoria se nao for participante

$('#tipo').on('change',function(){
  if($(this).val()=="visitante"){
    $('#categoria').attr('disabled','disabled');
    $('#danca').attr('disabled','disabled');
    $('#canto').attr('disabled','disabled');
    $('#categoria').val("null");
    $('.genero').attr('disabled','disabled');
    $('.genero').val("null");
    $('#danca').val("null");
    $('#canto').val("null");
    //alert($('#categoria').val())
  }else{
    $('#categoria').removeAttr('disabled');
    // $('.genero').removeAttr('disabled');
    $('#categoria').val('Marabenta');
    //alert($('#categoria').val())
  }
})

//entrar na conta

$('.ent').on('click',function(evt){
  evt.preventDefault();
  var email=$('#email1').val();
  var senha=$('#senha1').val();

  $.ajax({
    url:'views/entrar.php',
    type:'post',
    data:{email:email,senha:senha},
    success:function(data){
      $('.error').html(data);
      $('.error').fadeIn(400);

      if(data=="<span style='color:#0f0;'>sucesso</span>"){
        var url=window.location.href;
        var newUrl=url.split("#");
        window.open(newUrl[0],'_self');
      }
    }
  })
})

//mostrar mais informacoes do participante


function info(id){
  $.ajax({
    url:'views/info.php',
    type:'post',
    data:{id:id},
    success:function(data){
    $('body').append(data);
    $('.scroll-up-btn').css('display','none !important')
    }
})
}


//more participantes

$(document).on('click','.more',function(){
  $.ajax({
    url:'views/more.php',
    type:'post',
    data:{id:$('#hidden').val()},
    success:function(data){
      $('#hidden').remove();
      $('.teams-content').html(data);
     // alert(data)
    }
  })
})


//less participantes

$('.less').on('click',function(){
  //alert($('#hidden').val())
  $.ajax({
    url:'views/less.php',
    type:'post',
    data:{id:$('#hidden').val()},
    success:function(data){
      $('#hidden').remove();
      $('.teams-content').html(data);
     // alert(data)
    }
  })
})

//search area

$('#search').on('keydown',function(){
  if($(this).val().trim()!=''){
  $.ajax({
    url:'views/search.php',
    type:'post',
    data:{nome:$(this).val().trim()},
    success:function(data){
      //aalert(data)
      $('.teams-content').html(data);
    }
  })
  }else{
    $('.less').trigger('click')
  }
})

//mostrar excluidos

$('.excluidos').on('click',function(){
  $.ajax({
    url:'views/excluidos.php',
    type:'post',
    success:function(data){
      $('.big').fadeIn(500);
      $('.big-body').html(data);
      $('body').attr('style','overflow:hidden')
      //$('.scroll-up-btn li').addClass("fa-times");
     // $(window).scrollTop(100);
    }
  })
})

//fechar lista de excluidos

$(document).ready(function(){

$('.fechar').on('click',function(){
       $('.big').fadeOut(500);
      //$('.big-body').html(data);
      $('body').attr('style','overflow:auto')
      //$('.scroll-up-btn li').addClass("fa-times");
      $('.big-body').html("");
      //$(window).scrollTop(-100);
})
})

//feed

$('.feed').on('click',function(){
    $('.big').fadeIn(500);
    $('body').attr('style','overflow:hidden');
    $(window).scrollTop(340);

    $.ajax({
      url:'views/feed.php',
      type:'post',
      success:function(data){
        $('.big-body').html(data)
      }
    })
});


//open image gallery

$('.profile_gimmick').click(function(){
  $('#perfil').trigger('click');
});

//switch categoria

if($('#categoria').val()=="canto"){
  $('.canto').show();
}
$('#categoria').change(function(){
  var tipo=$(this).val();

  $('.genero').removeAttr('disabled');
  if(tipo=="danca"){
    $('.danca').slideDown(100);
    $('.canto').hide();
  }else{
    $('.canto').slideDown(100);
    $('.danca').hide();
  }

})


$('.canto_content').click(function(){
  $('.canto').slideToggle(100)
  $('.danca').slideUp(100)
})

$('.danca_content').click(function(){
  $('.danca').slideToggle(100)
  $('.canto').slideUp(100)
});

//realtime update percentagem


</script>


<!---------------------big modal----------------->


<div class='big'>
  <div class='big-header'>
      <button class='text-light fechar btn btn-warning'><span class='fa fa-times'></span></button>
  </div>

  <div class='big-body'>
  
  </div>

  <div class='big-footer'>
  
  </div>

</div>