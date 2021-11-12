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
  $info->setId(isset($_REQUEST['id'])?$_REQUEST['id']:"");
  
?>




<!------------------------dados dos participantes modal--------------->

<?php foreach($info->info() as $linha):?>
<div class='modal fade' id='modal<?php echo $linha['id']?>' role='dialog' >
  <div class='modal-dialog'>
    <div class='modal-content'>
      <div class='modal-header' style='background:orange'>
        <button class='close' data-dismiss='modal'>&times;</button>
        <h4 class='modal-title' style='font-weight:bold'><?php echo ucwords(strtolower($linha['nome']." ".$linha['apelido']))?></h4>
      </div>
      <div class='modal-body row'>
         <?php if($linha['avatar']!=''): ?>
                    <img src='ficheiros/<?php echo $linha['avatar']?>' class='img-fluid'>
          <?php else: ?>
                   <img src='imagens/black.png' alt=''>
          <?php endif;?>

        <div class='col-md-5'>
          <strong>Idade</strong>: <?php echo $linha['idade']?> anos<br>
          <strong>Email</strong>: <?php echo $linha['email']?><br>
          <strong>Morada</strong>: <?php echo $linha['morada']?><br>
          <strong>Categoria</strong>: <?php echo $linha['categoria']?><br>
          <strong>Telefone</strong>: <?php echo $linha['numero']?><br>
          <strong>Votos</strong>: 15 (40%)
        </div>
      </div>
      <div class='modal-footer' style='background:orange'>
      <?php
            $adm=new Adm();
            //echo $_COOKIE['email'];
            foreach($adm->managers() as $adms):
              
              if((strtolower($adms)==strtolower($_COOKIE['email']) || strtolower($adms)==strtolower($_COOKIE['numero'])) && (isset($_COOKIE['numero']) || isset($_COOKIE['email']))):
          ?>
          <button class='btn btn-info mr-0 ml-0 mail' data-id='<?php echo $info->getId()?>'><span class='fa fa-envelope'></span></button>
          <?php
          endif;
        endforeach;
          ?>
          <button class='btn btn-primary mr-0 ml-0 votar'>Votar</button>

          <?php
            $adm=new Adm();
            //echo $_COOKIE['email'];
            foreach($adm->managers() as $adms):
              
              if((strtolower($adms)==strtolower($_COOKIE['email']) || strtolower($adms)==strtolower($_COOKIE['numero'])) && (isset($_COOKIE['numero']) || isset($_COOKIE['email']))):
          ?>
              <button class='btn btn-danger mr-0 ml-0 exc'>Excluir</button>
          <?php
              endif;
            endforeach;
          ?>
      </div>
    </div>
  </div>
</div>
<?php endforeach?>


<script>
  //votar
  var cont=0;
$('.votar').on('click',function(){
  if(cont==0){
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
  }
    cont++;
})


//excluir usuario 

$('.exc').on('click',function(){
  //alert(<?php echo $_REQUEST['id']?>)
  $.ajax({
    url:'views/delete.php',
    type:'post',
    data:{id:<?php echo $_REQUEST['id']?>},
    success:function(data){
     // alert(data)
      $('.close').trigger('click');
      $('.teams-content').html(data);
    },
    error:function(error){
       console.log(error)
    }
  })
})


//mail a user

var cont1=0;
$('.mail').on('click',function(){
  if(cont1==0){
  var assunto=prompt("assunto");
  var msg=prompt("mensagem");
  var id=$(this).data('id')

  $.ajax({
    url:'views/sendSingleEmail.php',
    type:'post',
    data:{assunto:assunto,mensagem:msg,id:id},
    success:function(data){
      alert(data);
      $('.close').trigger('click');
      cont1=0;
    },
    error:function(err){
        alert(err);
    }
  })
  }

  cont1++;
})
</script>