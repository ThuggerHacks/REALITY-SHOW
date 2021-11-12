<?php
if($_SERVER['REQUEST_METHOD']!='POST'){
    header("location:../index.php");
    exit;
}
include '../conection/Conexao.class.php';
include '../controller/Controller.class.php';
include '../model/Model.class.php';

$m=new Model();
$id=isset($_REQUEST['id'])?$_REQUEST['id']:"";
$m->setId($id);
$query=$m->comentarios();
?>


<?php
foreach($query as $linha){
    echo "<div class='card' style='margin:5px;border:1px solid #ebebebeb;padding:5px;border-radius:7px;background:ghostwhite'>";
     $m->setId($linha['id_usuario']);
     foreach($m->user_via_id() as $coluna){
    ?>
     
            <strong><?=ucwords(strtolower($coluna['nome']))?></strong><hr>
            <div><?=$linha['mensagem']?></div>
            <hr>
            <div>
                <small class='text-primary'>
                    <?php
                        $tempo=localtime($linha['tempo'],true);
                        echo (1900+$tempo['tm_year'])."/".$tempo['tm_mon']."/".$tempo["tm_mday"];
                    ?>
                </small>
                <?php
                 if($coluna['email']==$_COOKIE['email'] || $coluna['numero']==$_COOKIE['numero']):
                ?>
                    <small class='text-warning ' style='float:right;cursor:pointer' onclick='apagar_comentario(<?=$linha["id"]?>)'>
                    apagar
                    </small>
                <?php endif;?>    
            </div>    

    <?php
       
     }
     echo "</div>";
} 
?>

