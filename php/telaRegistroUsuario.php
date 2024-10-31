<?php
// Incluir o arquivo de conexão com o banco de dados
include 'conexaoBD.php';  // Certifique-se de que o caminho está correto

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura os dados do formulário
    $nome = mysqli_real_escape_string($link, $_POST['nome']);
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $senha = mysqli_real_escape_string($link, $_POST['senha']);
    $telefone = mysqli_real_escape_string($link, $_POST['phone']);
    $estado = mysqli_real_escape_string($link, $_POST['estado']);
    $cidade = mysqli_real_escape_string($link, $_POST['cidade']);

    // Verifica se o email já está cadastrado
    $sql = "SELECT * FROM usuariosBD WHERE email = '$email'";
    $result = $link->query($sql);

    if ($result->num_rows > 0) {
        // Caso o email já exista
        echo "Este email já está cadastrado.";
    } else {
        // Inserir o novo usuário no banco de dados sem criptografar a senha
        $sql = "INSERT INTO usuariosBD (Nome, Senha, Email, Telefone, Estado, Cidade, TipoUsuario)
                VALUES ('$nome', '$senha', '$email', '$telefone', '$estado', '$cidade', 'normal')";

        if ($link->query($sql) === TRUE) {
            echo "Usuário registrado com sucesso!";
        } else {
            echo "Erro ao registrar usuário: " . $link->error;
        }
    }

    // Fechar a conexão
    $link->close();
}
?>
