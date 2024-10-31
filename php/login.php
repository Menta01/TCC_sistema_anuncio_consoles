<?php
    session_start(); // Iniciar uma sessão
    include("conexaoBD.php");

    // Captura os dados do formulário
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $senha = mysqli_real_escape_string($link, $_POST['senha']);

    // Verifica se os campos de e-mail e senha estão vazios
    if (empty($email) || empty($senha)) {
        header('location:formLogin.php?pagina=formLogin&erroLogin=camposVazios');
        exit();
    }

    
    $buscarLogin = "SELECT * FROM usuariosBD WHERE email = '{$email}' AND Senha = ('{$senha}')";
    $efetuarLogin = mysqli_query($link, $buscarLogin);

    // Conta o número de registros encontrados
    $quantidadeLogin = mysqli_num_rows($efetuarLogin);

    if ($quantidadeLogin > 0) {
        // Se encontrou algum registro
        $registro = mysqli_fetch_assoc($efetuarLogin);
        $email = $registro['email']; 
        $nome  = $registro['nome'];

        // Armazena os dados na sessão
        $_SESSION['email'] = $email;
        $_SESSION['nome']  = $nome;

        // Redireciona ou exibe mensagem de sucesso
        echo "<h1>LOGOU!</h1>";
        // header('location:formDocente.php?pagina=formDocente'); // Ative esta linha para redirecionar
    } else {
        // Se o login falhar (e-mail ou senha incorretos)
        header('location:formLogin.php?pagina=formLogin&erroLogin=dadosInvalidos');
        exit();
    }
?>
