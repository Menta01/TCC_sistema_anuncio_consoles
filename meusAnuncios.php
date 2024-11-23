<?php
include('php/valida_Sessao.php');
include('php/conexaoBD.php');
include('visual/header.php');

// Lógica de exclusão de produto e seus comentários
if (isset($_GET['excluir_produto'])) {
    $id_produto = $_GET['excluir_produto'];

    $delete_comentarios_sql = "DELETE FROM comentarios WHERE produto_id = $id_produto";
    mysqli_query($link, $delete_comentarios_sql);

    $delete_imagens_sql = "DELETE FROM imagens_produto WHERE id_produto = $id_produto";
    mysqli_query($link, $delete_imagens_sql);

    $delete_produto_sql = "DELETE FROM produtos WHERE id = $id_produto";
    if (mysqli_query($link, $delete_produto_sql)) {
        $_SESSION['mensagem'] = 'Produto excluído com sucesso!';
        $_SESSION['tipo_mensagem'] = 'success';
        header("Location: tela_Gestão_Produtos.php");
        exit();
    } else {
        $_SESSION['mensagem'] = 'Erro ao excluir produto: ' . mysqli_error($link);
        $_SESSION['tipo_mensagem'] = 'danger';
    }
}

// Lógica para adicionar produto
if (isset($_POST['adicionar_produto'])) {
    $nome = $_POST['nome'];
    $categoria = $_POST['categoria'];
    $descricao = $_POST['descricao'];
    $imagens = $_POST['imagens'];

    // Assumindo que o usuário logado tem seu ID em $_SESSION['usuario_id']
    $usuario_id = $_SESSION['usuario_id'];

    $insert_sql = "INSERT INTO produtos (nome, categoria, descricao, usuario_id) VALUES ('$nome', '$categoria', '$descricao', $usuario_id)";
    if (mysqli_query($link, $insert_sql)) {
        $id_produto = mysqli_insert_id($link);
        foreach ($imagens as $url_imagem) {
            $insert_imagens_sql = "INSERT INTO imagens_produto (id_produto, url_imagem) VALUES ($id_produto, '$url_imagem')";
            mysqli_query($link, $insert_imagens_sql);
        }
        $_SESSION['mensagem'] = 'Produto adicionado com sucesso!';
        $_SESSION['tipo_mensagem'] = 'success';
        header("Location: tela_Gestão_Produtos.php");
        exit();
    } else {
        $_SESSION['mensagem'] = 'Erro ao adicionar produto: ' . mysqli_error($link);
        $_SESSION['tipo_mensagem'] = 'danger';
    }
}
?>

<?php
// Renderiza o cabeçalho
renderHeader('location: tela_Gestão_Produtos.php');
?>

<div class="container mt-5">
    <h2>Gestão de Produtos</h2>

    <!-- Exibir mensagem de sucesso ou erro -->
    <?php if (isset($_SESSION['mensagem'])): ?>
        <div class="alert alert-<?php echo $_SESSION['tipo_mensagem']; ?>" role="alert">
            <?php echo $_SESSION['mensagem']; ?>
        </div>
        <?php 
        unset($_SESSION['mensagem']);
        unset($_SESSION['tipo_mensagem']);
        ?>
    <?php endif; ?>

    <!-- Formulário para adicionar produto -->
    <button class="btn btn-success mb-4" data-bs-toggle="modal" data-bs-target="#adicionarProdutoModal">Adicionar Produto</button>

    <!-- Modal para Adicionar Produto -->
    <div class="modal fade" id="adicionarProdutoModal" tabindex="-1" aria-labelledby="adicionarProdutoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adicionarProdutoModalLabel">Adicionar Novo Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" class="mb-4">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="categoria" class="form-label">Categoria</label>
                            <input type="text" class="form-control" name="categoria" required>
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea class="form-control" name="descricao" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="imagens" class="form-label">Imagens (URLs)</label>
                            <input type="text" class="form-control mb-2" name="imagens[]">
                            <input type="text" class="form-control mb-2" name="imagens[]">
                        </div>
                        <button type="submit" name="adicionar_produto" class="btn btn-success">Adicionar Produto</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de produtos -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Categoria</th>
                <th>Descrição</th>
                <th>Imagens</th>
                <th>Data Cadastro</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Captura o ID do usuário logado
            $usuario_id = $_SESSION['id_usuario'];

            // Exibe apenas os produtos do usuário logado
            $sql = "SELECT * FROM produtos WHERE id_usuario = $usuario_id";
            $result = mysqli_query($link, $sql);
            while ($produto = mysqli_fetch_assoc($result)) {
                $sql_imagens = "SELECT * FROM imagens_produto WHERE id_produto = " . $produto['id'];
                $result_imagens = mysqli_query($link, $sql_imagens);
                $imagens = [];
                while ($imagem = mysqli_fetch_assoc($result_imagens)) {
                    $imagens[] = $imagem['url_imagem'];
                }
                echo "<tr>";
                echo "<td>{$produto['id']}</td>";
                echo "<td>{$produto['nome']}</td>";
                echo "<td>{$produto['categoria']}</td>";
                echo "<td>{$produto['descricao']}</td>";
                echo "<td>" . implode(", ", $imagens) . "</td>";
                echo "<td>{$produto['data_cadastro']}</td>";
                echo "<td>
                        <a href='tela_Anuncio.php?id={$produto['id']}' class='btn btn-info'>Ver Anúncio</a>
                        <a href='?excluir_produto={$produto['id']}' class='btn btn-danger' onclick='return confirm(\"Tem certeza que deseja excluir este produto?\")'>Excluir</a>
                    </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include('visual/footer.php'); ?>
