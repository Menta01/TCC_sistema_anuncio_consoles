<?php
// Incluindo o cabeçalho, validação de sessão e conexão com o banco de dados
include 'php/conexaoBD.php'; // Certifique-se de que o caminho esteja correto
include 'visual/header.php'; // Incluindo o cabeçalho
include 'php/valida_Sessao.php';

// Verifica se o ID do produto foi passado na URL
if (isset($_GET['id'])) {
    $idProduto = intval($_GET['id']); // Garante que o ID seja um número inteiro
} else {
    die('ID do produto não informado na URL.');
}

// Consulta para pegar as imagens associadas ao produto na tabela imagens_produto
$queryImagens = "SELECT i.url_imagem 
                 FROM imagens_produto i
                 WHERE i.id_produto = $idProduto";

// Executando a consulta de imagens
$resultImagens = mysqli_query($link, $queryImagens);

// Verifica se houve erro na execução da consulta
if (!$resultImagens) {
    die('Erro na consulta de imagens: ' . mysqli_error($link));
}

// Consulta para pegar as informações do produto (nome, descrição, categoria) e o nome do usuário
$queryProduto = "SELECT p.nome, p.descricao, p.categoria, u.nome AS nome_usuario
                 FROM produtos p
                 JOIN usuariosbd u ON p.id_usuario = u.ID
                 WHERE p.id = $idProduto";

// Executando a consulta do produto
$resultProduto = mysqli_query($link, $queryProduto);

// Verifica se houve erro na execução da consulta
if (!$resultProduto) {
    die('Erro na consulta de produto: ' . mysqli_error($link));
}

// Obtendo os dados do produto
$produto = mysqli_fetch_assoc($resultProduto);

// Armazenando as imagens em um array
$imagensProduto = [];
while ($row = mysqli_fetch_assoc($resultImagens)) {
    $imagensProduto[] = $row['url_imagem'];
}

// Fecha a conexão com o banco de dados
mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <title>Detalhes do Produto</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Estilo personalizado -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .navbar {
            margin-bottom: 20px;
        }

        .image-gallery {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }

        .main-image {
            width: 100%;
            max-height: 500px;
            object-fit: cover;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .thumbnail-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .thumbnail {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .thumbnail:hover {
            transform: scale(1.1);
            border: 2px solid #007bff;
        }

        .product-info {
            margin-top: 30px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .product-info h3 {
            color: #007bff;
        }

        .product-info p {
            font-size: 1.1rem;
        }

        .product-info strong {
            color: #333;
        }

        .container {
            max-width: 1200px;
        }
    </style>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

<!-- Navbar -->
<?php renderHeader() ?>

<div class="container mt-4">
    <h1 class="text-center mb-4">Detalhes do Produto</h1>

    <div class="row">
        <div class="col-md-8">
            <!-- Galeria de Imagens -->
            <div class="image-gallery">
                <?php if (!empty($imagensProduto)): ?>
                    <!-- Imagem Principal -->
                    <img id="mainImage" src="<?php echo $imagensProduto[0]; ?>" alt="Imagem Principal" class="main-image img-fluid">

                    <!-- Miniaturas -->
                    <div class="thumbnail-container">
                        <?php foreach ($imagensProduto as $imagem): ?>
                            <img src="<?php echo $imagem; ?>" alt="Miniatura" class="thumbnail" onclick="changeMainImage(this)">
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>Nenhuma imagem disponível para este produto.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Informações do Produto -->
            <div class="product-info">
                <h3>Informações do Produto</h3>
                <p><strong>Nome:</strong> <?php echo htmlspecialchars($produto['nome']); ?></p>
                <p><strong>Descrição:</strong> <?php echo nl2br(htmlspecialchars($produto['descricao'])); ?></p>
                <p><strong>Categoria:</strong> <?php echo htmlspecialchars($produto['categoria']); ?></p>
                <p><strong>Postado por:</strong> <?php echo htmlspecialchars($produto['nome_usuario']); ?></p>
            </div>
        </div>
    </div>

</div>

<?php include 'visual/footer.php'; // Inclui o footer da pasta visual ?>

<script>
    // Função para alterar a imagem principal ao clicar na miniatura
    function changeMainImage(thumbnail) {
        const mainImage = document.getElementById('mainImage');
        mainImage.src = thumbnail.src;
    }
</script>

</body>
<?php include 'teste.php'; ?>
</html>