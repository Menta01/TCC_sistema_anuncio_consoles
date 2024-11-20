<?php
session_start(); // Inicia a sessão

// Validar se o usuário está logado
if (!isset($_SESSION["email"])) {
    header('location:loginUser.php?pagina=formLogin&erroLogin=naoLogado');
    exit();
}

// Incluir a conexão com o banco de dados
include 'php/conexaoBD.php'; // Verifique se o caminho está correto

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obter os dados do formulário
    $comentario = mysqli_real_escape_string($link, $_POST['comentario']); // Passando $conn corretamente
    $usuario_id = $_POST['usuario_id']; // ID do usuário
    $post_id = $_POST['post_id']; // ID do post

    // Validar se o comentário não está vazio
    if (!empty($comentario)) {
        // Inserir o comentário no banco de dados
        $sql = "INSERT INTO comentarios (usuario_id, post_id, comentario) VALUES ('$usuario_id', '$post_id', '$comentario')";
        
        if (mysqli_query($link, $sql)) {
            echo "<p>Comentário postado com sucesso!</p>";
        } else {
            echo "<p>Erro ao postar o comentário: " . mysqli_error($link) . "</p>";
        }
    } else {
        echo "<p>O comentário não pode ser vazio.</p>";
    }
}

// Exibir os comentários do post com ID 9
$sql = "SELECT * FROM comentarios WHERE post_id = 9";
$result = mysqli_query($link, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<p><strong>Usuário ID " . $row['usuario_id'] . ":</strong> " . $row['comentario'] . " <em>Postado em " . $row['data_postagem'] . "</em></p>";
    }
} else {
    echo "<p>Ainda não há comentários para este post.</p>";
}
?>
