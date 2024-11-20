<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado com base no email
if (!isset($_SESSION["email"])) {
    header('location:loginUser.php?pagina=formLogin&erroLogin=naoLogado'); // Redireciona para a página de login
    exit(); // Impede a execução do código abaixo
}

// Recupera o ID do usuário logado, caso a sessão esteja válida
$emailUsuario = $_SESSION["email"]; // Aqui estamos usando o email para validar a sessão
include('php/conexaoBD.php'); // Inclui a conexão com o banco de dados

// Consulta para pegar o ID do usuário com base no email
$sql = "SELECT id FROM usuariosbd WHERE email = ?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, 's', $emailUsuario);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $_SESSION['id_usuario'] = $row['id']; // Armazena o ID do usuário na sessão
} else {
    // Caso o usuário não seja encontrado, redireciona para a página de login
    header('location:loginUser.php?pagina=formLogin&erroLogin=naoLogado');
    exit();
}
?>
