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

foreach($adm->managers() as $adms){
    if((strtolower($adms)==strtolower($_COOKIE['email']) || strtolower($adms)==strtolower($_COOKIE['numero'])) && (isset($_COOKIE['numero']) || isset($_COOKIE['email']))){
        $model=new Model();
        $model->setId($_REQUEST['id']);
        $model->repescar();
        //header("location:../index.php");
    }
}


$less=new Model();
$less->setId(isset($_REQUEST['id'])?$_REQUEST['id']:"");
$participantes1=$less->participantes();
?>

   <?php $id=""?>
   <?php foreach($participantes1 as $users):?>
      <div class="card " data-target='#modal<?php echo $users['id']?>' data-toggle='modal' data-id='<?php echo $users['id']?>' onclick='info(<?php echo $users["id"]?>)' ><!--inicio card-->
        <div class="box"><!--inicio box -->
          
           <img src="imagens/black.png" alt="">
           
           <div class="text"><?php echo $users['nome']." ".$users['apelido']?></div>
           <p>Categoria: <?php echo $users['categoria']?></p>
           <p>Codigo: <?php echo $users['codigo']?></p>
           
        </div><!--fim box -->
      </div><!--fim card-->
      <?php $id=$users['id']?>
   <?php endforeach?>
    <input type='hidden' value='<?php echo $id?>' id='hidden'/>
 