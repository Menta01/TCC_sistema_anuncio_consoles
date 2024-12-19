<?php
// Incluindo o cabeçalho, validação de sessão e conexão com o banco de dados
include 'visual/header.php';
include 'php/conexaoBD.php';

session_start();

// Evitar problemas de memória temporariamente (não recomendado para produção)
ini_set('memory_limit', '1024M');

// Array para armazenar os produtos e evitar duplicação
$produtos = [];
$produtosExibidos = []; // Array para armazenar os IDs dos produtos exibidos

// Obter a categoria e o status selecionados pelo usuário
$categoriaSelecionada = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$statusSelecionado = isset($_GET['status']) ? $_GET['status'] : '';

// Montar a consulta SQL
$sql = "
    SELECT DISTINCT p.id, p.nome, p.categoria, p.descricao, p.status, i.url_imagem
    FROM produtos p
    LEFT JOIN imagens_produto i ON p.id = i.id_produto
";

// Adicionar filtros de categoria e status, se aplicáveis
$filters = [];
if ($categoriaSelecionada) {
    $filters[] = "p.categoria = '" . mysqli_real_escape_string($link, $categoriaSelecionada) . "'";
}
if ($statusSelecionado) {
    $filters[] = "p.status = '" . mysqli_real_escape_string($link, $statusSelecionado) . "'";
}

// Se houver filtros, adicioná-los à consulta SQL
if (count($filters) > 0) {
    $sql .= " WHERE " . implode(" AND ", $filters);
}

// Adicionar ordenação aleatória e limitar a 5 produtos
$sql .= " ORDER BY RAND() LIMIT 5";

// Executar a consulta
$result = mysqli_query($link, $sql);

// Verifica se a consulta falhou
if (!$result) {
    die("Erro ao carregar produtos: " . mysqli_error($link));
}

while ($row = mysqli_fetch_assoc($result)) {
    // Se o produto ainda não foi exibido, adiciona ao array
    if (!in_array($row['id'], $produtosExibidos)) {
        // Armazenar o ID do produto
        $produtosExibidos[] = $row['id'];

        // Adicionar o produto ao array de produtos para exibição
        $produtos[] = [
            'id' => $row['id'],
            'nome' => $row['nome'],
            'categoria' => $row['categoria'],
            'descricao' => $row['descricao'],
            'status' => $row['status'],
            'url_imagem' => $row['url_imagem'] ?: 'assets/imagens/placeholder.png', // Adiciona uma imagem padrão
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Produtos - Projeto TCC</title>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .product-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .product-image {
            height: 200px;
            object-fit: cover;
        }
        .truncate-text {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2; /* Limita a duas linhas */
            -webkit-box-orient: vertical;
        }
    </style>
</head>
<body>
    <!-- Renderizar o cabeçalho -->
    <?php renderHeader('produtos'); ?>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Produtos Disponíveis</h1>

        <!-- Filtro por Categoria e Status -->
        <form method="GET" class="mb-4 text-center">
            <label for="categoria" class="form-label">Filtrar por Categoria:</label>
            <select name="categoria" id="categoria" class="form-select w-50 mx-auto">
                <option value="">Todas</option>
                <option value="Placa de Vídeo" <?php if ($categoriaSelecionada === 'Placa de Vídeo') echo 'selected'; ?>>Placa de Vídeo</option>
                <option value="Processador" <?php if ($categoriaSelecionada === 'Processador') echo 'selected'; ?>>Processador</option>
                <option value="Memória RAM" <?php if ($categoriaSelecionada === 'Memória RAM') echo 'selected'; ?>>Memória RAM</option>
                <option value="Placa-mãe" <?php if ($categoriaSelecionada === 'Placa-mãe') echo 'selected'; ?>>Placa-mãe</option>
                <option value="Armazenamento (HD/SSD)" <?php if ($categoriaSelecionada === 'Armazenamento (HD/SSD)') echo 'selected'; ?>>Armazenamento (HD/SSD)</option>
                <option value="Fonte de Alimentação" <?php if ($categoriaSelecionada === 'Fonte de Alimentação') echo 'selected'; ?>>Fonte de Alimentação</option>
                <option value="Coolers e Sistemas de Resfriamento" <?php if ($categoriaSelecionada === 'Coolers e Sistemas de Resfriamento') echo 'selected'; ?>>Coolers e Sistemas de Resfriamento</option>
            </select>

            <label for="status" class="form-label mt-3">Filtrar por Status:</label>
            <select name="status" id="status" class="form-select w-50 mx-auto">
                <option value="">Todos</option>
                <option value="Funcionando" <?php if ($statusSelecionado === 'Funcionando') echo 'selected'; ?>>Funcionando</option>
                <option value="Não Funcionando" <?php if ($statusSelecionado === 'Não Funcionando') echo 'selected'; ?>>Não Funcionando</option>
            </select>

            <button type="submit" class="btn btn-primary mt-3">Filtrar</button>
        </form>

        <?php if (!empty($produtos)): ?>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php foreach ($produtos as $produto): ?>
                    <div class="col">
                        <div class="card product-card shadow-sm h-100">
                            <img src="<?php echo htmlspecialchars($produto['url_imagem']); ?>" class="card-img-top product-image" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                            <div class="card-body">
                                <h5 class="card-title text-primary"><?php echo htmlspecialchars($produto['nome']); ?></h5>
                                <p class="card-text text-muted truncate-text"><?php echo htmlspecialchars($produto['descricao']); ?></p>
                                <p class="card-text"><small class="text-secondary">Categoria: <?php echo htmlspecialchars($produto['categoria']); ?></small></p>
                                <p class="card-text"><small class="text-secondary">Status: <?php echo htmlspecialchars($produto['status']); ?></small></p>
                                <a href="tela_Anuncio.php?id=<?php echo $produto['id']; ?>" class="btn btn-primary w-100">Ver Detalhes</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-danger">Nenhum produto disponível para a categoria ou status selecionado.</p>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <?php include 'visual/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>