<?php

require 'php/conexaoBD.php'; // Arquivo com a conexão do banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    die("<p>Você precisa estar logado para comentar. Faça login e tente novamente.</p>");
}

$id_usuario = $_SESSION['id_usuario']; // ID do usuário logado
$nome_usuario = $_SESSION['nome'];    // Nome do usuário logado

// Consulta para obter a foto do usuário logado
$sql_foto_usuario = "SELECT foto FROM usuariosbd WHERE ID = '$id_usuario'";
$resultado_foto = mysqli_query($link, $sql_foto_usuario);
$foto_usuario = mysqli_fetch_assoc($resultado_foto)['foto'] ?? '../uploads/usuarios/default.jpg';

// Ajusta o caminho da URL para exibição no navegador
$foto_usuario_url = str_replace('../', '', $foto_usuario); // Remove '../' do início
$foto_usuario_url = "http://localhost/ProjetoTCC/" . $foto_usuario_url; // Adiciona o base URL

// Verifica se o ID do produto foi passado na URL
if (isset($_GET['id'])) {
    $id_produto = (int)$_GET['id']; // Lê o ID do produto da URL e garante que seja um número inteiro
} else {
    die("<p>ID do produto não encontrado.</p>");
}

// Insere o comentário no banco de dados
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comentario'])) {
    $comentario = mysqli_real_escape_string($link, $_POST['comentario']);

    $sql = "INSERT INTO comentarios (id_usuario, id_produto, comentario) 
            VALUES ('$id_usuario', '$id_produto', '$comentario')";
    if (mysqli_query($link, $sql)) {
        echo "<div class='alert alert-success'>Comentário enviado com sucesso!</div>";
    } else {
        echo "<div class='alert alert-danger'>Erro ao enviar comentário: " . mysqli_error($link) . "</div>";
    }
}

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
        <div class="card-body d-flex align-items-center">
            <img src="<?php echo htmlspecialchars($foto_usuario_url); ?>" alt="Foto de Perfil" 
                class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover; margin-right: 10px;">
            <h5 class="card-title">Olá, <?php echo htmlspecialchars($nome_usuario); ?>! Insira seu comentário abaixo:</h5>
        </div>
        <form method="POST" action="">
            <div class="form-group mt-3">
                <textarea name="comentario" class="form-control" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Enviar Comentário</button>
        </form>
    </div>

    <?php
    $sql_comentarios = "
        SELECT c.comentario, c.data_comentario, u.nome, u.foto
        FROM comentarios c
        JOIN usuariosbd u ON c.id_usuario = u.ID
        WHERE c.id_produto = $id_produto
        ORDER BY c.data_comentario DESC
    ";

    $resultado = mysqli_query($link, $sql_comentarios);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        echo "<h2 class='mt-5'>Comentários:</h2>";
        while ($row = mysqli_fetch_assoc($resultado)) {
            $foto_comentario_url = str_replace('../', '', $row['foto'] ?? 'uploads/usuarios/default.jpg');
            $foto_comentario_url = "http://localhost/ProjetoTCC/" . $foto_comentario_url;
            echo "
            <div class='card mb-3'>
                <div class='card-body d-flex align-items-center'>
                    <img src='$foto_comentario_url' alt='Foto de Perfil' 
                        class='rounded-circle' style='width: 50px; height: 50px; object-fit: cover; margin-right: 10px;'>
                    <div>
                        <h5 class='card-title'>" . htmlspecialchars($row['nome']) . "</h5>
                        <h6 class='card-subtitle mb-2 text-muted'>" . $row['data_comentario'] . "</h6>
                        <p class='card-text'>" . htmlspecialchars($row['comentario']) . "</p>
                    </div>
                </div>
            </div>";
        }
    } else {
        echo "<p class='alert alert-info'>Sem comentários ainda.</p>";
    }

    mysqli_close($link);
    ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
