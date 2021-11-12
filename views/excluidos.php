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
  

  $model=new Model();
  
  foreach($model->excluidos() as $linha){
      ?>

        <span class='text-light' style='color:#000'>
            <strong><?php echo $linha['nome']." ".$linha['apelido']?></strong>
        </span>
        <?php
          $adm=new Adm();

          foreach($adm->managers() as $adms):
            if(strtolower($adms)==strtolower($_COOKIE['email']) || strtolower($adms)==strtolower($_COOKIE['numero'])):
        ?>
        <button class='btn btn-warning ' style='float:right;margin-right:10px' onclick='repescar(<?php echo $linha["id"]?>)' id='e<?php echo $linha['id']?>'>Repescar</button>
        <?php
          endif;
        endforeach;
        ?>
        <hr>
      <?php
  }
?>

<script>

//repescar participante
  function repescar(id){
    var xml;

    if(XMLHttpRequest){
      xml=new XMLHttpRequest();
    }else{
      xml=new ActiveXObject("MICROSOFT.HTTP");
    }
    xml.onreadystatechange=function(){
      if(this.status==200 && this.readyState==4){
        document.getElementById("e"+id).innerText="repescado";
        $('.teams-content').html(this.responseText)
      }
    }

    xml.open('POST',`views/repescar.php?id=${id}`,true);
    xml.send();
  }


</script>
