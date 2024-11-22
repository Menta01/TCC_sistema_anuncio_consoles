<?php

require 'php/conexaoBD.php'; // Arquivo com a conexão do banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    die("<p>Você precisa estar logado para comentar. Faça login e tente novamente.</p>");
}

$id_usuario = $_SESSION['id_usuario']; // ID do usuário logado
$nome_usuario = $_SESSION['nome'];    // Nome do usuário logado

// Verifica se o ID do produto foi passado na URL
if (isset($_GET['id'])) {
    $id_produto = (int)$_GET['id']; // Lê o ID do produto da URL e garante que seja um número inteiro
} else {
    die("<p>ID do produto não encontrado.</p>");
}

// Insere o comentário no banco de dados
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comentario'])) {
    $comentario = mysqli_real_escape_string($link, $_POST['comentario']);

    // Insere o comentário na tabela comentarios com a relação ao produto
    $sql = "INSERT INTO comentarios (id_usuario, id_produto, comentario) 
            VALUES ('$id_usuario', '$id_produto', '$comentario')";
    if (mysqli_query($link, $sql)) {
        echo "<div class='alert alert-success'>Comentário enviado com sucesso!</div>";
    } else {
        echo "<div class='alert alert-danger'>Erro ao enviar comentário: " . mysqli_error($link) . "</div>";
    }
}

// Exibe o formulário de comentário
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comentário no Produto</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center">Comentário no Produto ID <?php echo $id_produto; ?></h1>

    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Olá, <?php echo htmlspecialchars($nome_usuario); ?>! Insira seu comentário abaixo:</h5>
            <form method="POST" action="">
                <div class="form-group">
                    <textarea name="comentario" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Enviar Comentário</button>
            </form>
        </div>
    </div>

    <?php
    // Exibe os comentários existentes para o Produto do ID passado na URL
    $sql_comentarios = "
        SELECT c.comentario, c.data_comentario, u.nome
        FROM comentarios c
        JOIN usuariosbd u ON c.id_usuario = u.ID
        WHERE c.id_produto = $id_produto
        ORDER BY c.data_comentario DESC
    ";

    // Executa a consulta e verifica se ocorreu algum erro
    $resultado = mysqli_query($link, $sql_comentarios);

    // Verifica se a consulta foi bem-sucedida
    if ($resultado === false) {
        echo "<p>Erro ao consultar os comentários: " . mysqli_error($link) . "</p>";
    } else {
        if (mysqli_num_rows($resultado) > 0) {
            echo "<h2 class='mt-5'>Comentários:</h2>";
            while ($row = mysqli_fetch_assoc($resultado)) {
                echo "
                <div class='card mb-3'>
                    <div class='card-body'>
                        <h5 class='card-title'>" . htmlspecialchars($row['nome']) . "</h5>
                        <h6 class='card-subtitle mb-2 text-muted'>" . $row['data_comentario'] . "</h6>
                        <p class='card-text'>" . htmlspecialchars($row['comentario']) . "</p>
                    </div>
                </div>";
            }
        } else {
            echo "<p class='alert alert-info'>Sem comentários ainda.</p>";
        }
    }

    mysqli_close($link);
    ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
