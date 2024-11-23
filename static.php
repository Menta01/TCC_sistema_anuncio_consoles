<?php
// Incluindo o cabeçalho, validação de sessão e conexão com o banco de dados
include 'php/conexaoBD.php';  // Certifique-se de que o caminho está correto
include 'php/valida_Sessao.php'; // Inclui a verificação de sessão
include 'visual/header.php';  // Inclui o arquivo do cabeçalho

// Consultas SQL
$queryProdutos = "SELECT p.id, p.nome, p.categoria, p.visualizacoes, p.id_usuario FROM produtos p";
$queryCategorias = "SELECT categoria, COUNT(*) AS total_produtos FROM produtos GROUP BY categoria";
$queryUsuarioMaisPostou = "SELECT u.nome, COUNT(p.id) AS total_postados FROM usuariosbd u LEFT JOIN produtos p ON u.id = p.id_usuario GROUP BY u.id ORDER BY total_postados DESC LIMIT 1";

// Execução das consultas e verificação de erros
$resultProdutos = mysqli_query($link, $queryProdutos);
if (!$resultProdutos) {
    die('Erro na consulta de produtos: ' . mysqli_error($link));
}

$resultCategorias = mysqli_query($link, $queryCategorias);
if (!$resultCategorias) {
    die('Erro na consulta de categorias: ' . mysqli_error($link));
}

$resultUsuarioMaisPostou = mysqli_query($link, $queryUsuarioMaisPostou);
if (!$resultUsuarioMaisPostou) {
    die('Erro na consulta de usuário mais postou: ' . mysqli_error($link));
}

// Armazenando os produtos
$produtos = [];
while ($row = mysqli_fetch_assoc($resultProdutos)) {
    $produtos[] = $row;
}

// Armazenando as categorias e contando o total de produtos por categoria
$categorias = [];
while ($row = mysqli_fetch_assoc($resultCategorias)) {
    $categorias[] = $row;
}

// Armazenando o usuário que mais postou
$usuarioMaisPostou = mysqli_fetch_assoc($resultUsuarioMaisPostou);

// Fecha a conexão com o banco de dados
mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Estatísticas de Visualizações</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .container {
            margin-top: 30px;
        }
        .card {
            margin-top: 20px;
        }
        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
        }
    </style>
</head>
<body>

<!-- Chamando o cabeçalho -->
<?php renderHeader('estatisticas'); ?>

<div class="container">
    <h1 class="text-center mb-4">Estatísticas de Visualizações</h1>

    <div class="row">
        <div class="col-md-6">
            <!-- Gráfico de Visualizações por Produto -->
            <div class="card">
                <div class="card-header">
                    Visualizações por Produto
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="visualizacoesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <!-- Gráfico de Produtos por Categoria -->
            <div class="card">
                <div class="card-header">
                    Produtos por Categoria
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="categoriasChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <!-- Exibe a Categoria Mais Popular -->
            <div class="card">
                <div class="card-header">
                    Maior Categoria
                </div>
                <div class="card-body">
                    <?php
                    // Encontrando a categoria com mais produtos
                    usort($categorias, function ($a, $b) {
                        return $b['total_produtos'] - $a['total_produtos'];
                    });
                    echo "<p><strong>" . htmlspecialchars($categorias[0]['categoria']) . "</strong> é a maior categoria com " . $categorias[0]['total_produtos'] . " produtos.</p>";
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <!-- Exibe o Produto Mais Visualizado -->
            <div class="card">
                <div class="card-header">
                    Produto Mais Visualizado
                </div>
                <div class="card-body">
                    <?php
                    // Encontrando o produto mais visualizado
                    $produtoMaisVisualizado = null;
                    $maxVisualizacoes = 0;
                    foreach ($produtos as $produto) {
                        if ($produto['visualizacoes'] > $maxVisualizacoes) {
                            $maxVisualizacoes = $produto['visualizacoes'];
                            $produtoMaisVisualizado = $produto;
                        }
                    }
                    echo "<p><strong>" . htmlspecialchars($produtoMaisVisualizado['nome']) . "</strong> é o produto mais visualizado com " . $maxVisualizacoes . " visualizações.</p>";
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <!-- Exibe o Usuário que Mais Postou -->
            <div class="card">
                <div class="card-header">
                    Usuário que Mais Postou
                </div>
                <div class="card-body">
                    <?php
                    if ($usuarioMaisPostou) {
                        echo "<p><strong>" . htmlspecialchars($usuarioMaisPostou['nome']) . "</strong> é o usuário que mais postou, com " . $usuarioMaisPostou['total_postados'] . " produtos.</p>";
                    } else {
                        echo "<p>Nenhum usuário postou produtos ainda.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    // Gráfico de Visualizações por Produto
    const produtosData = <?php echo json_encode($produtos); ?>;
    const visualizacoesChart = new Chart(document.getElementById('visualizacoesChart'), {
        type: 'bar',
        data: {
            labels: produtosData.map(p => p.nome),
            datasets: [{
                label: 'Visualizações',
                data: produtosData.map(p => p.visualizacoes),
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Gráfico de Produtos por Categoria
    const categoriasData = <?php echo json_encode($categorias); ?>;
    const categoriasChart = new Chart(document.getElementById('categoriasChart'), {
        type: 'pie',
        data: {
            labels: categoriasData.map(c => c.categoria),
            datasets: [{
                label: 'Produtos por Categoria',
                data: categoriasData.map(c => c.total_produtos),
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'],
                borderColor: '#fff',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true
        }
    });
</script>

<!-- Scripts Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
