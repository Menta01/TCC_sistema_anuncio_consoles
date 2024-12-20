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

    $insert_sql = "INSERT INTO produtos (nome, categoria, descricao) VALUES ('$nome', '$categoria', '$descricao')";
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

// Lógica para editar produto
if (isset($_POST['editar_produto'])) {
    $id_produto = $_POST['id_produto'];
    $nome = $_POST['nome'];
    $categoria = $_POST['categoria'];
    $descricao = $_POST['descricao'];
    $imagens = $_POST['imagens'];

    $update_sql = "UPDATE produtos SET nome = '$nome', categoria = '$categoria', descricao = '$descricao' WHERE id = $id_produto";
    if (mysqli_query($link, $update_sql)) {
        $delete_imagens_sql = "DELETE FROM imagens_produto WHERE id_produto = $id_produto";
        mysqli_query($link, $delete_imagens_sql);
        foreach ($imagens as $url_imagem) {
            $insert_imagens_sql = "INSERT INTO imagens_produto (id_produto, url_imagem) VALUES ($id_produto, '$url_imagem')";
            mysqli_query($link, $insert_imagens_sql);
        }
        $_SESSION['mensagem'] = 'Produto atualizado com sucesso!';
        $_SESSION['tipo_mensagem'] = 'success';
        header("Location: tela_Gestão_Produtos.php");
        exit();
    } else {
        $_SESSION['mensagem'] = 'Erro ao atualizar produto: ' . mysqli_error($link);
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
            $sql = "SELECT * FROM produtos";
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
                        <button class='btn btn-warning' data-bs-toggle='modal' data-bs-target='#editarProdutoModal' 
                        onclick='editarProduto({$produto['id']}, \"{$produto['nome']}\", \"{$produto['categoria']}\", \"{$produto['descricao']}\", " . json_encode($imagens) . ")'>Editar</button>
                        <a href='?excluir_produto={$produto['id']}' class='btn btn-danger' onclick='return confirm(\"Tem certeza que deseja excluir este produto?\")'>Excluir</a>
                    </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Modal para Editar Produto -->
<div class="modal fade" id="editarProdutoModal" tabindex="-1" aria-labelledby="editarProdutoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarProdutoModalLabel">Editar Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" class="mb-4">
                    <input type="hidden" name="id_produto" id="produtoId">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" name="nome" id="nomeProduto" required>
                    </div>
                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoria</label>
                        <input type="text" class="form-control" name="categoria" id="categoriaProduto" required>
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control" name="descricao" id="descricaoProduto" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="imagens" class="form-label">Imagens (URLs)</label>
                        <div id="imagensContainer"></div>
                        <input type="text" class="form-control mb-2" name="imagens[]">
                    </div>
                    <button type="submit" name="editar_produto" class="btn btn-primary">Salvar Alterações</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<script>
// Função para preencher os campos do modal de edição
function editarProduto(id, nome, categoria, descricao, imagens) {
    document.getElementById('produtoId').value = id;
    document.getElementById('nomeProduto').value = nome;
    document.getElementById('categoriaProduto').value = categoria;
    document.getElementById('descricaoProduto').value = descricao;

    // Preencher os campos de imagens
    const imagensContainer = document.getElementById('imagensContainer');
    imagensContainer.innerHTML = '';
    imagens.forEach(imagem => {
        const input = document.createElement('input');
        input.type = 'text';
        input.className = 'form-control mb-2';
        input.name = 'imagens[]';
        input.value = imagem;
        imagensContainer.appendChild(input);
    });
}
</script>

</body>
</html>
