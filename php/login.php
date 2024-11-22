<?php
session_start(); // Iniciar uma sessão
include("conexaoBD.php");


// Captura os dados do formulário
$email = mysqli_real_escape_string($link, $_POST['email']);
$senha = mysqli_real_escape_string($link, $_POST['senha']);

// Verifica se os campos de e-mail e senha estão vazios
if (empty($email) || empty($senha)) {
    header('Location: formLogin.php?pagina=formLogin&erroLogin=camposVazios');
    exit();
}


$buscarLogin = "SELECT * FROM usuariosBD WHERE email = '{$email}' AND senha = '{$senha}'";
$efetuarLogin = mysqli_query($link, $buscarLogin);

// Conta o número de registros encontrados
$quantidadeLogin = mysqli_num_rows($efetuarLogin);

if ($quantidadeLogin > 0) {
    
    $registro = mysqli_fetch_assoc($efetuarLogin);
    $email = $registro['email']; 
    $nome  = $registro['nome'];

    
    $_SESSION['email'] = $email;
    $_SESSION['nome']  = $nome;
    $_SESSION['logado'] = true;
    $_SESSION['id_usuario'] = $row['id'];
    

    header('Location: http://localhost/ProjetoTCC/home_Page.php'); 
    exit();
} else {
    // Se o login falhar (e-mail ou senha incorretos)
    header('Location: ../tela_Login.php?erroLogin=dadosInvalidos');
    exit();
}
?>
