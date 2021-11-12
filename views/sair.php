<?php

if(isset($_COOKIE['email']) || isset($_COOKIE['numero'])){
    setcookie("email","none",time()+60*60*24,"/");
    setcookie("numero","none",time()+60*60*24,"/");
    header("location:../index.php");
  }