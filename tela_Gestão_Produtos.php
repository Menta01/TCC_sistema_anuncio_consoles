<?php
include('php/valida_Sessao.php');
include('php/conexaoBD.php');
include('visual/header.php');

// Chama a função renderHeader e passa 'produtos' como página atual para destacar no menu
renderHeader('produtos');

// Função para excluir produto e seus comentários
if (isset($_GET['excluir_produto'])) {
    $id_produto = $_GET['excluir_produto'];

    // Exclui os comentários relacionados ao produto
    $delete_comentarios_sql = "DELETE FROM comentarios WHERE produto_id = $id_produto";
    mysqli_query($link, $delete_comentarios_sql);

    // Exclui as imagens do produto
    $delete_imagens_sql = "DELETE FROM imagens_produto WHERE id_produto = $id_produto";
    mysqli_query($link, $delete_imagens_sql);

    // Exclui o produto
    $delete_produto_sql = "DELETE FROM produtos WHERE id = $id_produto";
    if (mysqli_query($link, $delete_produto_sql)) {
        echo "Produto excluído com sucesso!";
        header("Location: gestao_produtos.php");
        exit();
    } else {
        echo "Erro ao excluir produto: " . mysqli_error($link);
    }
}

// Adicionar Produto
if (isset($_POST['adicionar_produto'])) {
    $nome = $_POST['nome'];
    $categoria = $_POST['categoria'];
    $descricao = $_POST['descricao'];
    $imagens = $_POST['imagens']; // Considerando imagens como string de URLs ou identificadores

    $insert_sql = "INSERT INTO produtos (nome, categoria, descricao) VALUES ('$nome', '$categoria', '$descricao')";
    if (mysqli_query($link, $insert_sql)) {
        $id_produto = mysqli_insert_id($link); // Pega o ID do produto recém-inserido
        // Adiciona as imagens associadas ao produto
        foreach ($imagens as $url_imagem) {
            $insert_imagens_sql = "INSERT INTO imagens_produto (id_produto, url_imagem) VALUES ($id_produto, '$url_imagem')";
            mysqli_query($link, $insert_imagens_sql);
        }
        echo "Produto adicionado com sucesso!";
        header("Location: gestao_produtos.php");
        exit();
    } else {
        echo "Erro ao adicionar produto: " . mysqli_error($link);
    }
}

// Editar Produto
if (isset($_POST['editar_produto'])) {
    $id_produto = $_POST['id_produto'];
    $nome = $_POST['nome'];
    $categoria = $_POST['categoria'];
    $descricao = $_POST['descricao'];
    $imagens = $_POST['imagens'];

    $update_sql = "UPDATE produtos SET nome = '$nome', categoria = '$categoria', descricao = '$descricao' WHERE id = $id_produto";
    if (mysqli_query($link, $update_sql)) {
        // Atualiza as imagens se necessário
        $delete_imagens_sql = "DELETE FROM imagens_produto WHERE id_produto = $id_produto";
        mysqli_query($link, $delete_imagens_sql);
        foreach ($imagens as $url_imagem) {
            $insert_imagens_sql = "INSERT INTO imagens_produto (id_produto, url_imagem) VALUES ($id_produto, '$url_imagem')";
            mysqli_query($link, $insert_imagens_sql);
        }
        echo "Produto atualizado com sucesso!";
        header("Location: gestao_produtos.php");
        exit();
    } else {
        echo "Erro ao atualizar produto: " . mysqli_error($link);
    }
}
?>

<div class="container mt-5">
    <h2>Gestão de Produtos</h2>

    <!-- Formulário de Adicionar Produto -->
    <form method="POST" class="mb-4">
        <h4>Adicionar Novo Produto</h4>
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
            <input type="text" class="form-control" name="imagens[]" required>
            <input type="text" class="form-control" name="imagens[]">
            <input type="text" class="form-control" name="imagens[]">
        </div>
        <button type="submit" name="adicionar_produto" class="btn btn-success">Adicionar Produto</button>
    </form>

    <!-- Tabela de Produtos -->
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
            $sql = "SELECT * FROM produtos";
            $result = mysqli_query($link, $sql);
            while ($produto = mysqli_fetch_assoc($result)) {
                // Busca imagens associadas ao produto
                $sql_imagens = "SELECT * FROM imagens_produto WHERE id_produto = " . $produto['id'];
                $result_imagens = mysqli_query($link, $sql_imagens);
                $imagens = [];
                while ($imagem = mysqli_fetch_assoc($result_imagens)) {
                    $imagens[] = $imagem['url_imagem'];
                }

                echo "<tr>";
                echo "<td>" . $produto['id'] . "</td>";
                echo "<td>" . $produto['nome'] . "</td>";
                echo "<td>" . $produto['categoria'] . "</td>";
                echo "<td>" . $produto['descricao'] . "</td>";
                echo "<td>";
                foreach ($imagens as $imagem) {
                    echo "<img src='$imagem' alt='Imagem Produto' style='width: 100px; height: 100px; margin: 5px;'>";
                }
                echo "</td>";
                echo "<td>" . $produto['data_cadastro'] . "</td>";
                echo "<td>
                        <a href='#' data-bs-toggle='modal' data-bs-target='#editarProdutoModal" . $produto['id'] . "' class='btn btn-warning btn-sm'>Editar</a>
                        <a href='?excluir_produto=" . $produto['id'] . "' class='btn btn-danger btn-sm'>Excluir</a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Modal de Edição de Produto -->
<?php
$result = mysqli_query($link, $sql);
while ($produto = mysqli_fetch_assoc($result)) {
    // Busca as imagens do produto para edição
    $sql_imagens = "SELECT * FROM imagens_produto WHERE id_produto = " . $produto['id'];
    $result_imagens = mysqli_query($link, $sql_imagens);
    $imagens = [];
    while ($imagem = mysqli_fetch_assoc($result_imagens)) {
        $imagens[] = $imagem['url_imagem'];
    }
?>
<div class="modal fade" id="editarProdutoModal<?php echo $produto['id']; ?>" tabindex="-1" aria-labelledby="editarProdutoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarProdutoModalLabel">Editar Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <input type="hidden" name="id_produto" value="<?php echo $produto['id']; ?>">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" name="nome" value="<?php echo $produto['nome']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoria</label>
                        <input type="text" class="form-control" name="categoria" value="<?php echo $produto['categoria']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control" name="descricao" required><?php echo $produto['descricao']; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="imagens" class="form-label">Imagens (URLs)</label>
                        <?php foreach ($imagens as $imagem) { ?>
                            <input type="text" class="form-control" name="imagens[]" value="<?php echo $imagem; ?>" required>
                        <?php } ?>
                    </div>
                    <button type="submit" name="editar_produto" class="btn btn-primary">Salvar Alterações</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php } ?>

</body>
</html>
