<?php
// Incluir o header
include 'visual/header.php';

// Incluir a conexão com o banco de dados
include 'php/conexaoBD.php';

// Definir o diretório base absoluto
$diretorioBase = "C:/xampp/htdocs/ProjetoTCC/";

// Consulta para obter os produtos e suas imagens
$sql = "
    SELECT p.id, p.nome, p.categoria, p.descricao, i.url_imagem
    FROM produtos p
    LEFT JOIN imagens_produto i ON p.id = i.id_produto
";

$result = mysqli_query($link, $sql);

?>

<!-- Conteúdo principal -->
<div class="container mt-5">
    <h1 class="text-center mb-4">Produtos</h1>

    <!-- Cards de Produtos -->
    <div class="row row-cols-1 row-cols-md-3 g-4 d-flex justify-content-center">
        <?php
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='col mb-4'>
                        <div class='card shadow-sm h-100'>
                            <img src='" . $row['url_imagem'] . "' class='card-img-top' alt='" . $row['nome'] . "'>
                            <div class='card-body'>
                                <h5 class='card-title'>" . $row['nome'] . "</h5>
                                <p class='card-text'><strong>Categoria:</strong> " . $row['categoria'] . "</p>
                                <p class='card-text'>" . substr($row['descricao'], 0, 100) . "...</p>
                                <a href='tela_Anuncio.php?id=" . $row['id'] . "' class='btn btn-primary w-100'>Ver Detalhes</a>
                            </div>
                        </div>
                    </div>";
            }
        } else {
            echo "<p class='text-center text-danger'>Erro ao carregar produtos.</p>";
        }
        ?>
    </div>
</div>

<?php
// Incluir o footer
include 'footer.php';
?>
