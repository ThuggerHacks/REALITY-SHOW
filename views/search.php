<?php

if($_SERVER['REQUEST_METHOD']!='POST'){
  header("location:../index.php");
  exit;
}
  session_start();
  include '../conection/Conexao.class.php';
  include '../controller/Controller.class.php';
  include '../model/Model.class.php';

  $search=new Model();
  $search->setEmail(isset($_REQUEST['nome'])?$_REQUEST['nome']:"")
?>

<?php foreach($search->search() as $users):?>
        <div class="card " data-target='#modal<?php echo $users['id']?>' data-toggle='modal' data-id='<?php echo $users['id']?>' onclick='info(<?php echo $users["id"]?>)' ><!--inicio card-->
          <div class="box"><!--inicio box -->
            
          <?php if($users['avatar']!=''): ?>
                    <img src='ficheiros/<?php echo $users['avatar']?>' class='img-fluid'>
          <?php else: ?>
                   <img src='imagens/black.png' alt=''>
          <?php endif;?>
             
             <div class="text"><?php echo $users['nome']." ".$users['apelido']?></div>
             <p>Categoria: <?php echo $users['categoria']?> (<?=$users['tipo']?>)</p>
             <p>Codigo: <?php echo $users['codigo']?></p>
             
          </div><!--fim box -->
        </div><!--fim card-->
        <?php $id=$users['id']?>
     <?php endforeach?>
