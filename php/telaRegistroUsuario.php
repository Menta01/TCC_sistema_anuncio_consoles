<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'conexaoBD.php'; // Arquivo de conexão com o banco de dados

    // Dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha']; // Sem hash
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
                $sql = "INSERT INTO usuariosbd (nome, email, senha, Telefone, Estado, Cidade, foto) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";

                $stmt = $link->prepare($sql);

                if ($stmt) {
                    $stmt->bind_param('sssssss', $nome, $email, $senha, $telefone, $estado, $cidade, $caminhoCompleto);

                    if ($stmt->execute()) {
                        // Obter o ID do usuário inserido
                        $id_usuario = $stmt->insert_id;

                        // Iniciar a sessão
                        session_start();

                        // Definir as variáveis de sessão com as informações do usuário recém-registrado
                        $_SESSION['usuario_id'] = $id_usuario;
                        $_SESSION['usuario_nome'] = $nome;
                        $_SESSION['usuario_email'] = $email;

                        // Redireciona para a página de login
                        header("Location: ../tela_Login.php");
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
