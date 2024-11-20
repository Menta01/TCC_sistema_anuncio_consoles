<?php

    $servidorBD = "localhost:3306";
    $usuarioBD  = "root";
    $senhaBD    = "";
    $database   = "tcc";

    //Função do PHP para estabelecer conexao com o BD
    $link = mysqli_connect(hostname: $servidorBD, username: $usuarioBD, password: $senhaBD, database: $database);
    

    if(!$link){
        echo "<p>Erro ao tentar conectar à Base de Dados <b>$database</b></p>";
    }
?>