<?php

//include '../conection/Conexao.class.php';
class Controller extends Conexao{
    private  $nome;
    private   $senha;
    private   $categoria;
    private   $idade;
    private   $morada;
    private   $email;
    private   $estado;
    private   $numero;
    private   $apelido;
    private   $id;
    private   $csenha;
    private   $tipo;
    private   $post;
    private   $file;
    private   $mensagem;
    private   $assunto;
    private   $myMail;
    private   $avatar;
    private   $genero;


    public function setMyMail($email="imolast66@gmail.com"){
        $this->myMail=$email;
    }

    public function getMyMail(){
        $this->setMyMail();
        return $this->myMail;
    }

    public function setGenero($genero){
        $this->genero=$genero;
    }

    public function getGenero(){
        return $this->genero;
    }

    public function setAvatar($avatar){
        $this->avatar=$avatar;
    }

    public function getAvatar(){
        return $this->avatar;
    }

    public function setAssunto($assunto){
        $this->assunto=$assunto;
    }

    public function getAssunto(){
        return $this->assunto;
    }

    public function setMensagem($mensagem){
        $this->mensagem=$mensagem;
    }

    public function getMensagem(){
        return $this->mensagem;
    }

    public function setFile($file){
        $this->file=$file;
    }

    public function getFile(){
        return $this->file;
    }

    public function setPost($post){
        $this->post=$post;
    }

    public function getPost(){
        return $this->post;
    }
    
    public function setTipo($tipo){
        $this->tipo=$tipo;
    }

    public function getTipo(){
        return $this->tipo;
    }
    public function setCsenha(  $senha){
        $this->csenha=$senha;
    }

    public function getCsenha(){
        return $this->csenha;
    }

    public function setNome(  $nome){
        $this->nome=$nome;
    }

    public function getNome(){
        return $this->nome;
    }

    public function setSenha(  $senha){
        $this->senha=$senha;
    }

    public function getSenha(){
        return $this->senha;
    }

    public function setCategoria(  $cat){
        $this->categoria=$cat;
    }

    public function getCategoria(){
        return $this->categoria;
    }

    public function setIdade(  $idade){
        $this->idade=$idade;
    }

    public function getIdade(){
        return $this->idade;
    }

    public function setMorada(  $morada){
        $this->morada=$morada;
    }

    public function getMorada(){
        return $this->morada;
    }

    public function setEmail(  $email){
        $this->email=$email;
    }

    public function getEmail(){
        return $this->email;
    }

    public function setEstado(  $estado){
        $this->estado=$estado;
    }

    public function getEstado(){
        return $this->estado;
    }

    public function setNumero(  $numero){
        $this->numero=$numero;
    }

    public function getNumero(){
        return $this->numero;
    }

    public function setApelido(  $apelido){
        $this->apelido=$apelido;
    }

    public function getApelido(){
        return $this->apelido;
    }

    public function setId(  $id){
        $this->id=$id;
    }

    public function getId(){
        return $this->id;
    }
}