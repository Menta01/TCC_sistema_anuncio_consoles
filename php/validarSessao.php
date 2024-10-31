<?php
    session_start(); //Inicia uma sessão
    if (!isset($_SESSION["email"])){
        header('location:formLogin.php?pagina=formLogin&erroLogin=naoLogado');
    }
?>