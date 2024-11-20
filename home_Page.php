<?php
include 'php/valida_Sessao.php'; // Verifica a sessão
include 'visual/header.php'; // Inclui o cabeçalho

// Inclui a conexão com o banco de dados
include 'php/conexaoBD.php';

// Consulta SQL para buscar os produtos cadastrados
$sql = "SELECT id, nome, categoria, descricao, imagens FROM produtos ORDER BY RAND() LIMIT 6"; // Exibe 6 produtos aleatórios
$result = mysqli_query($link, $sql);

if (!$result) {
    die("Erro na consulta ao banco de dados: " . mysqli_error($link));
}

?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <title>Home Page</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Arquivo de Estilos Externo -->
    <link href="css/homePage.css" rel="stylesheet">
</head>

<body>

<?php
renderHeader(); // Renderiza o cabeçalho
?>

<!-- Barra de Pesquisa -->
<div class="container search-bar my-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <form class="d-flex" role="search" action="pesquisa.php" method="GET">
                <input class="form-control me-2" type="search" name="query" placeholder="Pesquisar anúncios..." aria-label="Pesquisar">
                <button class="btn btn-outline-primary" type="submit">Pesquisar</button>
            </form>
        </div>
    </div>
</div>

<!-- Seção de Anúncios -->
<div class="container ads-section my-4">
    <div class="row">
        <?php while ($produto = mysqli_fetch_assoc($result)): ?>
        <div class="col-sm-12 col-md-6 col-lg-4">
            <div class="card ad-card mb-4">
                <!-- Verifica se a imagem existe e exibe corretamente -->
                <img src="uploads/<?= htmlspecialchars($produto['imagens']) ?>" class="card-img-top" alt="Imagem do Anúncio <?= htmlspecialchars($produto['nome']) ?>">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($produto['nome']) ?></h5>
                    <p class="card-text"><?= htmlspecialchars($produto['descricao']) ?></p>
                    <!-- Altere o link para redirecionar para tela_Anuncio.php -->
                    <a href="tela_Anuncio.php?id=<?= $produto['id'] ?>" class="btn btn-primary">Ver mais</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php
include 'visual/footer.php';
?>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
