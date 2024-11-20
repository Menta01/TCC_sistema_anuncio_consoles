<?php
include('valida_Sessao.php');
include 'conexaoBD.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $categoria = $_POST['categoria'];
    $descricao = $_POST['descricao'];
    $imagens = '';

    // Define o diretório para salvar as imagens
    $diretorioImagens = 'C:/xampp/htdocs/ProjetoTCC/uploads/';
    if (!is_dir($diretorioImagens)) {
        mkdir($diretorioImagens, 0777, true);
    }

    // Processamento das imagens
    $caminhosImagens = [];
    if (!empty($_FILES['imagens']['name'][0])) {
        $arquivos = $_FILES['imagens'];

        foreach ($arquivos['name'] as $index => $nomeArquivo) {
            $extensao = pathinfo($nomeArquivo, PATHINFO_EXTENSION);
            $idImagem = uniqid('img_'); // Gerando um ID único para cada imagem
            $nomeArquivoUnico = $idImagem . '.' . $extensao; // Renomeando a imagem com o ID
            $caminhoCompleto = $diretorioImagens . $nomeArquivoUnico;

            // Move o arquivo para o diretório com o nome único
            if (move_uploaded_file($arquivos['tmp_name'][$index], $caminhoCompleto)) {
                // Adiciona o caminho da imagem à lista
                $caminhosImagens[] = $nomeArquivoUnico;
            }
        }

        // Serializa os caminhos das imagens para armazenar no banco (se necessário para produtos)
        $imagens = implode(',', $caminhosImagens);
    }

    // Inserção dos dados no banco de dados para o produto
    $sql = "INSERT INTO produtos (nome, categoria, descricao, imagens) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($link, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ssss', $nome, $categoria, $descricao, $imagens);

        if (mysqli_stmt_execute($stmt)) {
            // Exibe pop-up e redireciona para a página home_Page.php após o sucesso
            echo "<script>
                    alert('Produto cadastrado com sucesso!');
                    window.location.href = 'home_Page.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Erro ao cadastrar produto.');
                  </script>";
        }

        // Fecha a declaração
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>
                alert('Erro ao preparar a consulta para cadastrar produto.');
              </script>";
    }
}
?>
