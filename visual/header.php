<?php
function renderHeader($currentPage = '') {
    if (session_status() === PHP_SESSION_NONE) {
        session_start(); // Inicia a sessão apenas se ainda não tiver sido iniciada
    }
    
    // Verifica se o usuário está logado com base no email
    $emailUsuario = isset($_SESSION['email']) ? $_SESSION['email'] : null;

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
                            <a class="nav-link <?= $currentPage === 'index' ? 'active' : '' ?>" href="index.php">Início</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage === 'produtos' ? 'active' : '' ?>" href="home_Page.php">Produtos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage === 'contato' ? 'active' : '' ?>" href="tela_Cadastro_Produto.php">Postar um Anuncio</a>
                        </li>
                    </ul>
                    
                    <!-- Botões à direita -->
                    <ul class="navbar-nav">
                        <?php if ($emailUsuario): ?>
                            <!-- Se o usuário estiver logado, mostra o botão de logout -->
                            <li class="nav-item">
                                <span class="navbar-text me-3 text-white">Bem-vindo, <?= htmlspecialchars($emailUsuario) ?></span>
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
