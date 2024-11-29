<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'conexaoBD.php'; // Arquivo de conexão com o banco de dados

    // Dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $telefone = $_POST['phone'];
    $estado = $_POST['estado'];
    $cidade = $_POST['cidade'];

    // Configuração do upload de fotos
    $foto = $_FILES['foto'];
    $diretorioUploads = '../uploads/usuarios/';
    $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];

    if (!is_dir($diretorioUploads)) {
        mkdir($diretorioUploads, 0755, true);
    }

    if ($foto['error'] === 0) {
        $extensao = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));

        if (in_array($extensao, $extensoesPermitidas)) {
            $nomeArquivo = uniqid() . '.' . $extensao;
            $caminhoCompleto = $diretorioUploads . $nomeArquivo;

            if (move_uploaded_file($foto['tmp_name'], $caminhoCompleto)) {
                // Corrigido: Usar $link em vez de $conn
                $sql = "INSERT INTO usuariosbd (nome, email, senha, Telefone, Estado, Cidade, foto) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";

                $stmt = $link->prepare($sql);

                if ($stmt) {
                    $stmt->bind_param('sssssss', $nome, $email, $senha, $telefone, $estado, $cidade, $caminhoCompleto);

                    if ($stmt->execute()) {
                        // Redireciona para a página home_Page.php
                        header("Location: ../home_Page.php");
                        exit;
                    } else {
                        echo "Erro ao salvar no banco de dados: " . $stmt->error;
                    }
                } else {
                    echo "Erro ao preparar a consulta: " . $link->error;
                }
            } else {
                echo "Erro ao fazer o upload da imagem.";
            }
        } else {
            echo "Apenas arquivos JPG, JPEG, PNG e GIF são permitidos.";
        }
    } else {
        echo "Erro ao enviar a foto.";
    }
} else {
    echo "Método de requisição inválido.";
}
?>
