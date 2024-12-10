<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Inicia a sessão apenas se ainda não tiver sido iniciada
}

// Verifica se o usuário está logado com base no email armazenado na sessão
if (!isset($_SESSION["email"])) {
    header('location:loginUser.php?pagina=formLogin&erroLogin=naoLogado'); // Redireciona se não estiver logado
    exit();
}

// Recupera o email do usuário logado
$emailUsuario = $_SESSION["email"];
include('php/conexaoBD.php'); // Inclui a conexão com o banco de dados

// Consulta SQL para buscar o ID do usuário com base no email
$sql = "SELECT id FROM usuariosbd WHERE email = ?";
$stmt = mysqli_prepare($link, $sql);

if ($stmt) {
    // Vincula o email ao parâmetro da consulta
    mysqli_stmt_bind_param($stmt, 's', $emailUsuario);
    mysqli_stmt_execute($stmt); // Executa a consulta
    $result = mysqli_stmt_get_result($stmt);

    // Verifica se o ID foi encontrado
    if ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['id_usuario'] = $row['id']; 
    } else {
        // Caso o usuário não seja encontrado no banco, redireciona para login
        header('location:loginUser.php?pagina=formLogin&erroLogin=naoLogado');
        exit();
    }

    mysqli_stmt_close($stmt); 
} else {
    // Erro ao preparar a consulta
    echo "Erro ao preparar a consulta SQL: " . mysqli_error($link);
    exit();
}
?>
