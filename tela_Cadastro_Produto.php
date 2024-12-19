<?php
include 'php/valida_sessao.php'; 
include 'visual/header.php';
renderHeader('postar_anuncio'); 
require 'php/conexaoBD.php';

// Caminho do diretório para armazenar as imagens (diretório absoluto)
$diretorio_destino = 'C:/xampp/htdocs/ProjetoTCC/uploads/imagens_produtos/';

// Verifica se o diretório existe, caso contrário, cria-o
if (!is_dir($diretorio_destino)) {
    mkdir($diretorio_destino, 0777, true); 
}

$produto_cadastrado = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados do formulário
    $nome = mysqli_real_escape_string($link, $_POST['nome']);
    $categoria = mysqli_real_escape_string($link, $_POST['categoria']);
    $descricao = mysqli_real_escape_string($link, $_POST['descricao']);
    $status = mysqli_real_escape_string($link, $_POST['status']); // Recebe o status

    // Verifica o número de imagens enviadas
    $num_imagens = count($_FILES['imagens']['name']);
    
    if ($num_imagens < 3 || $num_imagens > 5) {
        echo "<p>Você deve enviar entre 3 e 5 imagens.</p>";
        exit;
    }
    
    // Primeiro, inserimos o produto na tabela 'produtos'
    $sql_produto = "INSERT INTO produtos (nome, categoria, descricao, status, id_usuario) 
                    VALUES ('$nome', '$categoria', '$descricao', '$status', '{$_SESSION['id_usuario']}')";

    if (mysqli_query($link, $sql_produto)) {
        $id_produto = mysqli_insert_id($link);

        // Upload das imagens
        if (isset($_FILES['imagens']) && $_FILES['imagens']['error'][0] != UPLOAD_ERR_NO_FILE) {
            foreach ($_FILES['imagens']['name'] as $index => $nome_imagem) {
                $imagem_nome = uniqid('img_') . '.' . pathinfo($nome_imagem, PATHINFO_EXTENSION);
                $caminho_destino = $diretorio_destino . $imagem_nome; 

                if ($_FILES['imagens']['error'][$index] === UPLOAD_ERR_OK) {
                    if (move_uploaded_file($_FILES['imagens']['tmp_name'][$index], $caminho_destino)) {
                        $caminho_relativo = 'uploads/imagens_produtos/' . $imagem_nome;
                        $sql_imagem = "INSERT INTO imagens_produto (id_produto, url_imagem) 
                                       VALUES ('$id_produto', '$caminho_relativo')";

                        if (!mysqli_query($link, $sql_imagem)) {
                            echo "Erro ao inserir imagem no banco de dados: " . mysqli_error($link);
                        }
                    } else {
                        echo "Erro ao mover a imagem para o diretório de destino.";
                    }
                } else {
                    echo "Erro no upload da imagem: " . $_FILES['imagens']['error'][$index];
                }
            }
        }

        $produto_cadastrado = true;
    } else {
        echo "Erro ao cadastrar o produto: " . mysqli_error($link);
    }
}

// Consulta para buscar os nomes das categorias do banco de dados
$sql_categorias = "SELECT nome_categoria FROM categoria";
$result_categorias = mysqli_query($link, $sql_categorias);

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <title>Cadastrar Produto</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="cadastro-produto-styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card p-4" style="width: 100%; max-width: 500px;">
            <h2 class="text-center mb-4">Cadastro de Produto</h2>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome do Produto:</label>
                    <input type="text" class="form-control" id="nome" placeholder="Digite o nome do produto" name="nome" required>
                </div>
                <div class="mb-3">
                    <label for="categoria" class="form-label">Categoria:</label>
                    <select class="form-select" id="categoria" name="categoria" required>
                        <option value="" disabled selected>Selecione uma categoria</option>
                        <?php if ($result_categorias && mysqli_num_rows($result_categorias) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result_categorias)): ?>
                                <option value="<?= htmlspecialchars($row['nome_categoria']); ?>"><?= htmlspecialchars($row['nome_categoria']); ?></option>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <option value="" disabled>Nenhuma categoria encontrada</option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="descricao" class="form-label">Descrição:</label>
                    <textarea class="form-control" id="descricao" rows="3" placeholder="Digite uma breve descrição do produto" name="descricao"></textarea>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status:</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="Funcionando">Funcionando</option>
                        <option value="Não Funcionando">Não Funcionando</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="imagens" class="form-label">Imagens do Produto (3 a 5 imagens):</label>
                    <input class="form-control" type="file" id="imagens" name="imagens[]" accept="image/*" multiple required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
            </form>

            <?php if ($produto_cadastrado): ?>
                <div class="alert alert-success mt-4" role="alert">
                    Produto cadastrado com sucesso!
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'visual/footer.php'; ?>
</body>

</html>
