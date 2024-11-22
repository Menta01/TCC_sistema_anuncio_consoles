<?php
function renderHeader($currentPage = '') {
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
                <!-- Marca/Logo -->
                <a class="navbar-brand" href="index.php">
                    <img src="assets/logo.png" alt="Logo" style="height: 40px; margin-right: 10px;"> Projeto TCC
                </a>
                
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
                            <a class="nav-link <?= $currentPage === 'produtos' ? 'active' : '' ?>" href="produtos.php">Produtos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage === 'contato' ? 'active' : '' ?>" href="contato.php">Contato</a>
                        </li>
                    </ul>
                    
                    <!-- Botões à direita -->
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="btn btn-warning" href="login.php">Login</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    <?php
}
?>
