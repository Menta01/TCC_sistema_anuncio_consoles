<?php
function renderHeader($currentPage = '') {
    if (session_status() === PHP_SESSION_NONE) {
        session_start(); // Inicia a sessão apenas se ainda não tiver sido iniciada
    }

    // Verifica se o usuário está logado com base no email
    $emailUsuario = isset($_SESSION['email']) ? $_SESSION['email'] : null;
    // Verifica se o usuário é admin
    $isAdmin = isset($_SESSION['tipoUsuario']) && $_SESSION['tipoUsuario'] === 'admin';

    // Incluir a conexão com o banco de dados
    include('php/conexaoBD.php'); // Inclua o arquivo de conexão

    // Definir foto padrão caso o usuário não tenha foto
    $fotoPerfil = 'uploads/usuarios/default-profile.jpg'; 

    if ($emailUsuario) {
        // Consulta no banco de dados para pegar o nome da foto do usuário
        $query = "SELECT foto FROM usuariosbd WHERE email = ?";
        $stmt = mysqli_prepare($link, $query); // Usando $link para a conexão
        mysqli_stmt_bind_param($stmt, "s", $emailUsuario);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            // Verifique se o campo 'foto' contém um valor válido
            if (!empty($user['foto'])) {
                $fotoPerfil = str_replace('../', '', $user['foto']); // Remove "../" do início do caminho
            }
        }

        mysqli_stmt_close($stmt);
    }

    ?>
    <!DOCTYPE html>
    <html lang="pt">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <title>Projeto TCC</title>
    </head>
    <body>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                
                <!-- Botão de navegação responsivo -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <!-- Itens do menu -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage === 'produtos' ? 'active' : '' ?>" href="home_Page.php">Produtos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage === 'contato' ? 'active' : '' ?>" href="tela_Cadastro_Produto.php">Postar um Anuncio</a>
                        </li>
                        <?php if ($emailUsuario): ?>
                            <!-- Adicionando a opção de "Meus Anúncios" para usuários logados -->
                            <li class="nav-item">
                                <a class="nav-link <?= $currentPage === 'meusAnuncios' ? 'active' : '' ?>" href="meusAnuncios.php">Meus Anúncios</a>
                            </li>
                        <?php endif; ?>
                        <?php if ($isAdmin): ?>
                            <!-- Exibe as opções de gestão apenas para admins -->
                            <li class="nav-item">
                                <a class="nav-link <?= $currentPage === 'gestao_produtos' ? 'active' : '' ?>" href="tela_Gestão_Produtos.php">Gestão de Produtos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $currentPage === 'gestao_usuarios' ? 'active' : '' ?>" href="tela_Gestão_Usuarios.php">Gestão de Usuários</a>
                            </li>
                        <?php endif; ?>
                        <!-- Adicionando a opção de Analytics para usuários logados -->
                        <?php if ($emailUsuario): ?>
                            <li class="nav-item">
                                <a class="nav-link <?= $currentPage === 'analytics' ? 'active' : '' ?>" href="static.php">Analytics</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    
                    <!-- Botões à direita -->
                    <ul class="navbar-nav">
                        <?php if ($emailUsuario): ?>
                            <!-- Exibe o nome do usuário e a foto do perfil -->
                            <li class="nav-item">
                                <span class="navbar-text me-3 text-white d-flex align-items-center">
                                    <!-- Exibe a foto do usuário, com tamanho ajustado -->
                                    <img src="<?= htmlspecialchars($fotoPerfil) ?>" alt="Foto de Perfil" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover; margin-right: 8px;">
                                    Bem-vindo, <?= htmlspecialchars($emailUsuario) ?>
                                </span>
                            </li>
                            <li class="nav-item">
                                <a class="btn btn-danger" href="php/logout.php">Logout</a>
                            </li>
                        <?php else: ?>
                            <!-- Caso contrário, mostra o botão de login -->
                            <li class="nav-item">
                                <a class="btn btn-warning" href="php/login.php">Login</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    <?php
}
?>
