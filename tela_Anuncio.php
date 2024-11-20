<?php
include('php/valida_Sessao.php'); // Verifica se o usuário está logado
include('php/conexaoBD.php'); // Inclui a conexão com o banco de dados
include 'visual/header.php'; // Inclui o cabeçalho

// Verifica se o parâmetro 'id' foi passado na URL
if (isset($_GET['id'])) {
    $idProduto = $_GET['id'];

    // Consulta para pegar os detalhes do produto
    $sql = "SELECT * FROM produtos WHERE id = ?";
    $stmt = mysqli_prepare($link, $sql);
    
    if ($stmt === false) {
        die('Erro na preparação da consulta: ' . mysqli_error($link));
    }

    mysqli_stmt_bind_param($stmt, 'i', $idProduto);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Verifica se o produto foi encontrado
    if ($row = mysqli_fetch_assoc($result)) {
        $nomeProduto = $row['nome'];
        $categoriaProduto = $row['categoria'];
        $descricaoProduto = $row['descricao'];
        $imagensProduto = explode(',', $row['imagens']); // Se houver múltiplas imagens
        $anuncianteNome = "João da Silva"; // Exemplo de dados do anunciante
        $anuncianteTelefone = "(11) 12345-6789"; // Exemplo de telefone
        $anuncianteCidade = "São Paulo"; // Exemplo de cidade
    } else {
        echo "Produto não encontrado.";
        exit;
    }
} else {
    echo "ID do produto não informado.";
    exit;
}

// Processa o comentário se o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comentario'])) {
    $usuario_id = $_SESSION['id_usuario']; // ID do usuário logado
    $produto_id = $_POST['produto_id']; // ID do produto
    $comentario = mysqli_real_escape_string($link, $_POST['comentario']); // Comentário do usuário

    // Verifica se o comentário não está vazio
    if (!empty($comentario)) {
        // Insere o comentário no banco de dados
        $sql = "INSERT INTO comentarios (usuario_id, produto_id, comentario) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($link, $sql);

        if ($stmt === false) {
            die('Erro na preparação da consulta para inserir comentário: ' . mysqli_error($link));
        }

        mysqli_stmt_bind_param($stmt, 'iis', $usuario_id, $produto_id, $comentario);
        
        // Executa a consulta e verifica se foi bem-sucedida
        if (mysqli_stmt_execute($stmt)) {
            echo "<div class='alert alert-success'>Comentário enviado com sucesso!</div>";
        } else {
            echo "<div class='alert alert-danger'>Erro ao enviar comentário: " . mysqli_error($link) . "</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>Comentário não pode ser vazio.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <title>Detalhes do Anúncio</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Arquivo de Estilos Externo -->
    <link href="css/telaAnuncio.css" rel="stylesheet">
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

<?php
renderHeader(); // Renderiza o cabeçalho
?>

    <!-- Detalhes do Anúncio -->
    <div class="container ad-details mt-4">
        <div class="row">
            <div class="col-md-8">
                <!-- Imagens do Anúncio -->
                <div class="ad-images mb-3">
                    <h3>Imagens do Anúncio</h3>
                    <div id="carouselExampleCaptions" class="carousel slide">
                        <div class="carousel-inner">
                            <?php foreach ($imagensProduto as $index => $imagem): ?>
                                <div class="carousel-item <?php echo $index == 0 ? 'active' : ''; ?>">
                                    <img src="uploads/<?php echo $imagem; ?>" class="d-block w-100" alt="Imagem do Anúncio">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>

                <!-- Informações do Anúncio -->
                <div class="ad-info">
                    <h3>Detalhes do Anúncio</h3>
                    <p><strong>Nome do Produto:</strong> <?php echo $nomeProduto; ?></p>
                    <p><strong>Categoria:</strong> <?php echo $categoriaProduto; ?></p>
                    <p><strong>Descrição:</strong> <?php echo $descricaoProduto; ?></p>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Informações do Anunciante -->
                <div class="advertiser-info mb-3">
                    <h3>Informações do Anunciante</h3>
                    <p><strong>Nome:</strong> <?php echo $anuncianteNome; ?></p>
                    <p><strong>Telefone:</strong> <?php echo $anuncianteTelefone; ?></p>
                    <p><strong>Cidade:</strong> <?php echo $anuncianteCidade; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Seção de Comentários -->
    <div class="container comment-form mt-4">
        <h3>Deixe sua pergunta ou comentário</h3>
        <form action="telaAnuncio.php?id=<?php echo $idProduto; ?>" method="post">
            <input type="hidden" name="produto_id" value="<?php echo $idProduto; ?>"> <!-- ID do produto -->
            <div class="mb-3">
                <label for="comentario" class="form-label">Comentário</label>
                <textarea class="form-control" id="comentario" name="comentario" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Enviar Comentário</button>
        </form>
    </div>

    <!-- Seção de Comentários Existentes -->
    <div class="container comments-section mt-4">
        <h3>Comentários</h3>
        <?php
        // Consulta para pegar todos os comentários do produto
        $sqlComentarios = "SELECT c.comentario, u.nome FROM comentarios c JOIN usuariosbd u ON c.usuario_id = u.id WHERE c.produto_id = ? ORDER BY c.id DESC";
        $stmtComentarios = mysqli_prepare($link, $sqlComentarios);
        mysqli_stmt_bind_param($stmtComentarios, 'i', $idProduto);
        mysqli_stmt_execute($stmtComentarios);
        $resultComentarios = mysqli_stmt_get_result($stmtComentarios);

        if (mysqli_num_rows($resultComentarios) > 0) {
            while ($comentario = mysqli_fetch_assoc($resultComentarios)) {
                echo "<div class='comment'>";
                echo "<p><strong>" . $comentario['nome'] . ":</strong></p>";
                echo "<p>" . $comentario['comentario'] . "</p>";
                echo "</div><hr>";
            }
        } else {
            echo "<p>Nenhum comentário ainda.</p>";
        }
        ?>
    </div>

</body>

</html>
