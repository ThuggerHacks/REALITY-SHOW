<?php

//include '../controller/Controller.class.php';

class Model extends Controller{

    //cadastrar usuario
    public function cadastrar(){
        if(trim($this->getNome())=="" && trim($this->getApelido())==""){
            //nome e apelido nao podem estar em branco
            echo "Nome e apelido n&atilde;o podem estar em branco";
        }elseif(trim($this->getEmail())==""){
            //email nao pode estar em branco
            echo "Email n&atilde;o pode estar em branco";
        }elseif(filter_var($this->getEmail(),FILTER_VALIDATE_EMAIL)==false){
            //email invalido
            echo "Email invalido";
        }elseif(strlen($this->getSenha())<4 || strlen($this->getSenha())>15){
            //senha maior que 4 e menor que 16
            echo "Senha deve ter 4-15 digitos";
        }elseif($this->getSenha()!=$this->getCsenha()){
            //senhas diferentes
            echo "Senhas diferentes";
        }elseif(trim($this->getNumero())==""){
            //telefone nao pode estar vazio
            echo "Telefone n&atilde;o pode estar vazio";
        }elseif(trim($this->getIdade())==""){
            //idade nao pode estar vazio
            echo "Idade n&atilde;o pode estar vazio";
        }elseif(trim($this->getMorada())==""){
            //morada nao pode estar vazio
            echo "Morada n&atilde;o pode estar vazio";
        }elseif(strlen($this->getNumero())<9){
            //numero invalido
            echo "Numero invalido";
        }elseif(substr($this->getNumero(),0,2)!="84" && substr($this->getNumero(),0,2)!="85" && substr($this->getNumero(),0,2)!="86" && substr($this->getNumero(),0,2)!="87" && substr($this->getNumero(),0,2)!="82" && substr($this->getNumero(),0,2)!="83"){
            //numero invalido
            echo "Numero invalido";
        }else if($this->getIdade()<18 || $this->getIdade()>30){
            echo "Idade n&atilde;o permitida, idade permitida: 18-30 anos";
        }else{

            $con=$this->conect();
            $user=$con->prepare("SELECT*FROM usuarios WHERE (numero=? OR email=?)");
            $user->execute(array($this->getNumero(),$this->getEmail()));

            if($user->rowCount()>0){
                //usuario ja existe
                echo "Esta conta ja existe, tente mudar de email ou numero de telefone";
            }else{

                $codigo="";

                for($x=0;$x<4;$x++){
                    $codigo.=rand(0,9);
                }
                //inserir dados na db
            $sql="INSERT INTO usuarios (nome,idade,morada,email,categoria,senha,numero,apelido,estado,codigo,tipo) VALUES(?,?,?,?,?,?,?,?,?,?,?)";
            
            $motor=$con->prepare($sql);
            $motor->execute(array(
                filter_var($this->getNome(),FILTER_SANITIZE_SPECIAL_CHARS),
                filter_var($this->getIdade(),FILTER_SANITIZE_SPECIAL_CHARS),
                filter_var($this->getMorada(),FILTER_SANITIZE_SPECIAL_CHARS),
                filter_var($this->getEmail(),FILTER_SANITIZE_SPECIAL_CHARS),
                filter_var($this->getGenero(),FILTER_SANITIZE_SPECIAL_CHARS),
                filter_var(md5($this->getSenha()),FILTER_SANITIZE_SPECIAL_CHARS),
                filter_var($this->getNumero(),FILTER_SANITIZE_SPECIAL_CHARS),
                filter_var($this->getApelido(),FILTER_SANITIZE_SPECIAL_CHARS),
                filter_var($this->getTipo(),FILTER_SANITIZE_SPECIAL_CHARS),
                $codigo,
                filter_var($this->getCategoria(),FILTER_SANITIZE_SPECIAL_CHARS),
            ));

            setcookie("email",$this->getEmail(),time()+60*60*24*365,"/");
            setcookie("numero",$this->getNumero(),time()+60*60*365,"/");
            
            echo "<span style='color:#0f0 !important'>Registado com sucesso, fa&ccedil;a login para confirmar conta</span>";
        }
      }
    } // cadastrar usuario


    //entrar na conta

    public function entrar(){

        if(trim($this->getNumero())=="" || trim($this->getSenha())==""){
            echo "Por favor preencha todos os dados";
            exit;
        }

        $con=$this->conect();
        $sql="SELECT*FROM usuarios WHERE (email=? OR numero=?) AND senha=?";
        $motor=$con->prepare($sql);
        $motor->execute([
            filter_var($this->getEmail(),FILTER_SANITIZE_SPECIAL_CHARS),
            filter_var($this->getNumero(),FILTER_SANITIZE_SPECIAL_CHARS),
            filter_var($this->getSenha(),FILTER_SANITIZE_SPECIAL_CHARS)
        ]);

        if($motor->rowCount()!=0){
           echo "<span style='color:#0f0;'>sucesso</span>";
            session_destroy();
            $_SESSION['email']=$this->getEmail();
            $_SESSION['numero']=$this->getNumero();

            setcookie("email",$_SESSION['email'],time()+60*60*24*365,"/");
            setcookie("numero",$_SESSION['numero'],time()+60*60*365,"/");
           // echo $_SESSION['email'];
        }else{
            echo "Dados incorrectos";
        }
    } //fim entrar


    //mostrar participantes

    public function participantes(){
        $con=$this->conect();
        $sql="SELECT*FROM usuarios WHERE categoria<>? AND estado<>? ORDER BY id DESC LIMIT 3";
        $motor=$con->prepare($sql);
        $motor->execute(['','removido']);

        return $motor;
    }

    public function todos_participantes(){
        $con=$this->conect();
        $sql="SELECT*FROM usuarios WHERE categoria<>? AND estado<>? ORDER BY id DESC";
        $motor=$con->prepare($sql);
        $motor->execute(['','removido']);

        return $motor;
    }


    //percentagem em cada categoria

    public function percentagem(){
        $con=$this->conect();

        //categoria selecionada
        $sql="SELECT*FROM usuarios WHERE categoria=? AND tipo=?";
        $motor=$con->prepare($sql);
        $motor->execute([$this->getCategoria(),$this->getGenero()]);

        //total de categorias da db
        $moto=$con->prepare("SELECT*FROM usuarios WHERE categoria<>?");
        $moto->execute(['']);

        if($moto->rowCount()!=0 && $motor->rowCount()>0){
             return number_format(($motor->rowCount()/$moto->rowCount())*100,1);
        }else{
             return 0;
        }
    }

    public function percentagem_group(){
        $con=$this->conect();

        //categoria selecionada
        $sql="SELECT*FROM usuarios WHERE  tipo=?";
        $motor=$con->prepare($sql);
        $motor->execute([$this->getGenero()]);

        //total de categorias da db
        $moto=$con->prepare("SELECT*FROM usuarios WHERE categoria<>?");
        $moto->execute(['']);

        if($moto->rowCount()!=0 && $motor->rowCount()>0){
             return number_format(($motor->rowCount()/$moto->rowCount())*100,1);
        }else{
             return 0;
        }
    }

    //PEGAR MAIS INFORMACOES DO PARTICIPANTE

    public function info(){
        $con=$this->conect();
        $sql="SELECT*FROM usuarios WHERE id=?";
        $motor=$con->prepare($sql);
        $motor->execute([
            $this->getId()
        ]);
        return $motor;
    }

    //more participantes from database

    public function more(){
        $con=$this->conect();
        $sql="SELECT*FROM usuarios WHERE id<? AND categoria<>? AND estado<>? ORDER BY id DESC LIMIT 3";
        $motor=$con->prepare($sql);
        $motor->execute([
            $this->getId(),
            '',
            'removido'
        ]);
        
        return $motor;
    }


    
    //less participantes from database

    public function less(){
        $con=$this->conect();
        $sql="SELECT*FROM usuarios WHERE id<? AND categoria<>? AND estado<>? ORDER BY id DESC LIMIT 3";
        $motor=$con->prepare($sql);
        $motor->execute([
            $this->getId(),
            '',
            'removido'
        ]);
        return $motor;
    }

    //search

    public function search(){
        $con=$this->conect();
        $sql="SELECT*FROM usuarios WHERE (nome LIKE ? OR email LIKE ? OR numero LIKE ? OR codigo LIKE ?) AND estado<>? AND categoria<>? ORDER BY id DESC LIMIT 3";
        $motor=$con->prepare($sql);
        $motor->execute([
            filter_var($this->getEmail()."%",FILTER_SANITIZE_SPECIAL_CHARS),
            filter_var($this->getEmail()."%",FILTER_SANITIZE_SPECIAL_CHARS),
            filter_var($this->getEmail()."%",FILTER_SANITIZE_SPECIAL_CHARS),
            filter_var($this->getEmail()."%",FILTER_SANITIZE_SPECIAL_CHARS),
            'removido',
            ''
        ]);

        return $motor;
    }


    //ocultar menu

    public function ocultar_menu(){
        $con=$this->conect();
        $sql="SELECT*FROM definicoes WHERE menu=?";
        $motor=$con->prepare($sql);
        $motor->execute(array($this->getTipo()));

        //insert menu se nao existir na db
        if($motor->rowCount()==0){
            $insert=$con->prepare("INSERT INTO definicoes (menu,ocultar) VALUES(?,?)");
            $insert->execute([
                $this->getTipo(),
                true
            ]);

            //atualizar menu se existir
        }else{
            foreach($motor as $menu){
                //mostrar menu se estiver oculto
                if($menu['ocultar']==true){

                    $ocultar=$con->prepare("UPDATE definicoes SET ocultar=? WHERE menu=?");
                    $ocultar->execute([
                        false,
                        $this->getTipo()
                    ]);
                    //ocultar menu se estiver a mostrar
                }else{
                    $ocultar=$con->prepare("UPDATE definicoes SET ocultar=? WHERE menu=?");
                    $ocultar->execute([
                        true,
                        $this->getTipo()
                    ]);
                }
            }

        }
    }
    
    //mostrar se menus estao ocultos ou nao

    public function oculto(){
        $con=$this->conect();
        $motor=$con->prepare("SELECT*FROM definicoes WHERE menu=?");
        $motor->execute([$this->getTipo()]);

        return $motor;
    }

    //remover participante

    public function remover(){
        $con=$this->conect();
        $sql="UPDATE usuarios SET estado=? WHERE id=?";
        $motor=$con->prepare($sql);
        $motor->execute([
            'removido',
            $this->getId()
        ]);
    }

    //show excluidos da gala

    public function excluidos(){
        $con=$this->conect();
        $sql="SELECT*FROM usuarios WHERE estado=?";
        $motor=$con->prepare($sql);
        $motor->execute(array('removido'));

        return $motor;
    }

    
    //show excluidos da gala

    public function repescar(){
        $con=$this->conect();
        $sql="UPDATE usuarios SET estado=? WHERE id=?";
        $motor=$con->prepare($sql);
        $motor->execute([
            'participante',
            $this->getId()
        ]);
    }

    //post text

    public function post(){
        
        //insert text
        $con=$this->conect();
        $sql="INSERT INTO posts (text,time,photo) VALUES(?,?,?)";
        $motor=$con->prepare($sql);
        $motor->execute([
            filter_var($this->getPost(),FILTER_SANITIZE_SPECIAL_CHARS),
            time(),
            $this->getFile()['name']
        ]);

        move_uploaded_file($this->getFile()['tmp_name'],"../ficheiros/".$this->getFile()['name']);
        //insert image
        
    }


    //show posts

    public function showPosts(){
        
        $con=$this->conect();
        $sql="SELECT*FROM posts ORDER BY id DESC";
        $motor=$con->prepare($sql);
        $motor->execute();

        return $motor;
    }

    //delete post

    public function deletePost(){
        $con=$this->conect();

        $sql1="SELECT*FROM posts WHERE id=?";
        $motor1=$con->prepare($sql1);
        $motor1->execute([$this->getId()]);

        foreach($motor1 as $linha){
            if(file_exists("../ficheiros/".$linha['photo'])){
                unlink("../ficheiros/".$linha['photo']);
            }
        }
        $sql="DELETE FROM posts WHERE id=?";
        $motor=$con->prepare($sql);
        $motor->execute([$this->getId()]);
    }

    //enviar email

    public function send(){

        //selecionar todos os participantes
        $con=$this->conect();
        $sql="SELECT*FROM usuarios WHERE categoria<>? AND estado<>? ORDER BY id DESC ";
        $motor=$con->prepare($sql);
        $motor->execute(['','removido']);

        return $motor;

    }

    //inserir menu cadastro ao abrir app

    public function inserirCadastro(){
        $this->setTipo('cadastro');
        $con=$this->conect();
        $sql="SELECT*FROM definicoes WHERE menu=?";
        $motor=$con->prepare($sql);
        $motor->execute(array($this->getTipo()));

        //insert menu se nao existir na db
        if($motor->rowCount()==0){
            $insert=$con->prepare("INSERT INTO definicoes (menu,ocultar) VALUES(?,?)");
            $insert->execute([
                $this->getTipo(),
                false
            ]);
    }

}

   //inserir menu participantes ao abrir app

   public function inserirParticipante(){
    $this->setTipo('participantes');
    $con=$this->conect();
    $sql="SELECT*FROM definicoes WHERE menu=?";
    $motor=$con->prepare($sql);
    $motor->execute(array($this->getTipo()));

    //insert menu se nao existir na db
    if($motor->rowCount()==0){
        $insert=$con->prepare("INSERT INTO definicoes (menu,ocultar) VALUES(?,?)");
        $insert->execute([
            $this->getTipo(),
            false
        ]);
}

}

//user logged

    public function logged(){
        $con=$this->conect();
        $sql="SELECT*FROM usuarios WHERE numero=? OR email=?";
        $motor=$con->prepare($sql);
        $motor->execute([
            $this->getEmail(),
            $this->getEmail()
        ]);

        return $motor;
    }

    //change profile pic

    public function changeProfile(){
        $con=$this->conect();
        $sql="UPDATE usuarios SET avatar=? WHERE email=? OR numero=?";
        $motor=$con->prepare($sql);
        $motor->execute([
            $this->getAvatar(),
            $this->getEmail(),
            $this->getNumero()
        ]);
    }

    //like

    public function like(){
        //conexao
        $con=$this->conect();
        //pegar id do user
        $this->setEmail($_COOKIE['email']);
        $id=0;
        $bool=false;
        foreach($this->logged() as $linha){
            $id=$linha['id'];
            if($linha['estado']=="participante"){
                $bool=true;
            }
        }

        //so like se nao for participante
        // if($bool==false){
        //verificar se nao tem like
        $sql="SELECT*FROM likes WHERE id_usuario=? AND id_post=?";
        $motor=$con->prepare($sql);
        $motor->execute(array(
            $id,
            $this->getId()
        ));

        if($motor->rowCount()==0){
            //inserir like
            $sql1="INSERT INTO likes (id_usuario,id_post) VALUES(?,?)";
            $motor1=$con->prepare($sql1);
            $motor1->execute([
                $id,
                $this->getId()
            ]);
        }else{
            //remover like
            $sql2="DELETE FROM likes WHERE id_usuario=? AND id_post=?";
            $moto=$con->prepare($sql2);
            $moto->execute([
                $id,
                $this->getId()
            ]);
        }
    // }

    //mostrar likes

    return $this->mostrar_likes()->rowCount();
    }

    //show likes

    public function mostrar_likes(){
        $con=$this->conect();
        $sql="SELECT*FROM likes WHERE id_post=?";
        $motor=$con->prepare($sql);
        $motor->execute([$this->getId()]);

        return $motor;
    }

    //comentar

    public function comentar(){
        $con=$this->conect();
        $this->setEmail($_COOKIE['email']);
        $id=0;
        $bool=false;
        //pegar id do usuario
        foreach($this->logged() as $linha){
            $id=$linha['id'];
            if($linha['estado']=="participante"){
                $bool=true;
            }
        }

        //verificar se eh participante ou nao
        // if($bool==false){
            $sql="INSERT INTO comments (mensagem,id_post,tempo,id_usuario) VALUES(?,?,?,?)";
            $motor=$con->prepare($sql);
            $motor->execute([
                filter_var($this->getMensagem(),FILTER_SANITIZE_SPECIAL_CHARS),
                $this->getId(),
                time(),
                $id
            ]);
        // }

        return $this->comentarios();
        }

       

        //comments

        public function comentarios(){
            
        //retornar mensagens
        $con=$this->conect();
        $s="SELECT*FROM comments WHERE id_post=?";
        $moto=$con->prepare($s);
        $moto->execute([
            $this->getId()
        ]);

        return $moto;
        }

        //get user via id

        public function user_via_id(){
                $con=$this->conect();
                $sql="SELECT*FROM usuarios WHERE id=?";
                $motor=$con->prepare($sql);
                $motor->execute([
                    $this->getId()
                ]);
        
                return $motor;
            }

            //delete comments
        public function delete_coments(){
            $con=$this->conect();
            $sql="DELETE FROM comments WHERE id=?";
            $motor=$con->prepare($sql);
            $motor->execute([
                $this->getId()
            ]);
        }    
        
}