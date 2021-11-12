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

     $adm=new Adm();
    //echo $_COOKIE['email'];
    foreach($adm->managers() as $adms):
    
    if((strtolower($adms)==strtolower($_COOKIE['email']) || strtolower($adms)==strtolower($_COOKIE['numero'])) && (isset($_COOKIE['numero']) || isset($_COOKIE['email']))):
  ?>

  <!---------formulario para post---------------->

  <div class='posts'><!----inicio formulario---->
  <?php 
  date_default_timezone_set("Africa/Maputo")?>
    <textarea class='form-control' id='post' placeholder='Postar'></textarea><br>
    <!------foto and send button----------------->
    <div class='post-down'>
        <!-------main button------------>
        <button class='btn btn-info email'>
            <span class='fa fa-envelope'></span>
        </button>
        <!-----camera------------->
        <input type='file' id='file' name='file' accept='image/*'/>
        <button class='btn btn-warning' onclick='$("#file").trigger("click")'>
            <span class='fa fa-camera file'></span>
        </button>
        <!-------post button------------>
        <button class='btn btn-primary enviar'>
            <span class='fa fa-send'></span>
        </button>

    </div>
  </div><hr><!---fim formulario---->

  <?php
  endif;
endforeach;
  ?>

<!-----------show messages---------------->

<div class='bg-light p-2 posts my-3'>

    <?php 
    $model=new Model();
    foreach($model->showPosts() as $linha):
    ?>
      <div class='card1'>
        <div class='card1-header bg-warning'>
            <img src='imagens/reality.png' width=100 height=43>
            <!-- <strong>Estrelas do Amanh&atilde;</strong> -->
        </div>
        <div class='card1-body p-3 post-text'>
            <?php
              if($linha['photo']!='' && $linha['text']==''){
                //image without text
                echo "<center><img src='ficheiros/".$linha['photo']."' class='img-fluid'></center>";
              }else if($linha['photo']!='' && $linha['text']!=''){
                //image with text
                echo "<center>".$linha['text']."</center><hr>";
                //image here
                echo "<center><img src='ficheiros/".$linha['photo']."' class='img-fluid'></center>";
              }else if($linha['photo']=='' && $linha['text']!=''){
                //just text
                echo $linha['text'];
              }
            
            ?>
        </div>
        <div class='card1-footer bg-warning'>
        
          <?php 
            $months=[
              "Janeiro","Fevereiro","Mar&ccedil;o","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"
            ];
            $tempo=localtime($linha['time'],true);
            echo $tempo['tm_mday']." de ".$months[$tempo['tm_mon']].", ".$tempo['tm_hour'].":".$tempo['tm_min'];
            ?>
          <div >
            <!-------------like button---------->
           <button class='btn btn-danger like' id='like' onclick='like(<?=$linha["id"]?>)' data-post="<?=$linha['id']?>">
                 <span class='fa fa-heart'></span>
                 <span class='like_count<?=$linha['id']?>'>
                   <?php $model->setId($linha['id'])?>
                   <?php echo $model->mostrar_likes()->rowCount()?>
                 </span>
            </button>
             <!--------------comment button------------->
              <button class='btn btn-warning comments' onclick='comment(<?=$linha["id"]?>)'>
                 <span class='fa fa-comments'></span>
                 <span>
                  <?php $model->setId($linha['id'])?>
                   <?php echo $model->comentarios()->rowCount()?>
                 </span>
             </button>
          <?php
            //show delete button to the adm=====
            foreach($adm->managers() as $adms):
    
              if((strtolower($adms)==strtolower($_COOKIE['email']) || strtolower($adms)==strtolower($_COOKIE['numero'])) && (isset($_COOKIE['numero']) || isset($_COOKIE['email']))):
          ?>
             <button class='btn btn-light p-1 apagar' data-id='<?php echo $linha['id']?>'>apagar</button>
            <?php
            endif;
          endforeach;
            ?>    
         </div>
        </div>
      </div><br>
     <?php endforeach?> 

</div><br><br><br><br><hr>

<script>


//change image of feed camera if the adm select a photo to post

$('#file').on('change',function(){

if($(this).val()!=''){
  $('.file').removeClass('fa-camera');
  $('.file').addClass('fa-check')
}else{
  $('.file').removeClass('fa-check');
  $('.file').addClass('fa-camera')
}
});


//enviar post a database

    $('.enviar').on('click',function(){
        if($('#post').val().trim()!='' || $('#file').val()!=''){
            
            var formData=new FormData();
            formData.append('file',$('#file')[0].files[0]);
            formData.append('text',$('#post').val())

            // alert($('#file')[0].files[0])

          //uploading content
            $.ajax({
                url:'views/sendFeed.php',
                type:'post',
                data:formData,
                contentType:false,
                processData:false,
                success:function(data){
                    $('#post').val("");
                    $('#file').val("");
                    $('.file').removeClass('fa-check').addClass('fa-camera');
                   // alert("enviado...");
                    $('.feed').trigger('click');
                }
            });


        }
    })

//delete post

$('.apagar').on('click',function(){
  var id=$(this).data("id");
  $.ajax({
    url:'views/deletePost.php',
    type:'post',
    data:{id:id},
    success:function(data){
      $('.feed').trigger('click');
    }
  })
})

//email singers

$('.email').on('click',function(){
  var assunto=prompt("Assunto");
  var mensagem=prompt("Mensagem");

  if(assunto.trim()!='' && mensagem.trim()!=''){
    $.ajax({
      url:'views/sendEmail.php',
      type:'post',
      data:{assunto:assunto,mensagem:mensagem},
      success:function(data){
        alert("Email enviado a todos os participantes");
        //alert(data)
      },
      error:function(err){
        alert(err)
      }
    })
  }
})
 
//like

function like(ev){
  var id_post=ev;
  $.ajax({
      url:'views/like.php',
      type:"post",
      data:{id_post:id_post},
      success:function(data){
        $('.like_count'+id_post).html(data)
      }
  })
}

$('.closar').click(function(){
  $('.comments-modal').slideUp(100)
  $('.navbar').show(100)
});

//comment

function comment(id){
  $('.comments-modal').slideDown(100);
  $('.comments-modal').css('display','flex');
  $('.navbar').hide(100)
  $('#id').val(id)

  $.ajax({
    url:'views/comentarios.php',
    type:'post',
    data:{id:id},
    success:function(data){
      $('.comments-body').html(data)
    }
  })
  
}

function comentar(){

  $.ajax({
    url:'views/comment.php',
    type:'post',
    data:{id:$('#id').val(),msg:$('#mensagem').val()},
    success:function(data){
      comment($('#id').val());
      $('#mensagem').val("")
    }
  })
}

//apagar comentario

function apagar_comentario(id){

  $.ajax({
    url:'views/delete_coment.php',
    type:'post',
    data:{id:id},
    success:function(data){
      comment($('#id').val());
    }
  })
}

</script>



<!-------------------------comments modal--------------------->

<div class='comments-modal'>
  <div class='comments-content'>
    <div class='comments-header'>
      <button class='closar'>&times;</button>
    </div>
    <div class='comments-body'>
     <!-----------------mensagens do comentario------------>

    </div>
    <div class='comments-footer'>
      <textarea class='message' id='mensagem' placeholder='Comentar...'></textarea>
      <button class='btn btn-primary comentar' onclick='comentar()'>
          <span class='fa fa-send'></span>
      </button>
      <input type='hidden' id='id'/>
    </div>
  </div>
</div>