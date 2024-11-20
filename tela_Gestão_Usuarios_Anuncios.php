<?php
    include('php/valida_Sessao.php');
?>



<!DOCTYPE html>
<html lang="pt">

<head>
    <title>Gestão de Usuários e Anúncios</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Arquivo de Estilos Externo -->
    <link href="css/telaGestãoUsuariosAnuncios.css" rel="stylesheet">
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <!-- Cabeçalho -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Console Anuncios</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Alternar navegação">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="homePage.html">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Sobre</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tela_Cadastro_Produto.html">Cadastrar Produto</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="telaLogin.html">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="php/logout.php">Logout</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="php/logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Gestão de Usuários -->
    <div class="container">
        <h2>Gestão de Usuários</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <!-- Exemplo de Usuário -->
                <tr>
                    <td>1</td>
                    <td>João da Silva</td>
                    <td>joao@example.com</td>
                    <td>(11) 12345-6789</td>
                    <td>
                        <a href="editar_usuario.php?id=1" class="btn btn-warning btn-sm">Editar</a>
                        <a href="excluir_usuario.php?id=1" class="btn btn-danger btn-sm">Excluir</a>
                    </td>
                </tr>
                <!-- Adicione outros usuários aqui -->
            </tbody>
        </table>
    </div>

    <!-- Gestão de Anúncios -->
    <div class="container mt-5">
        <h2>Gestão de Anúncios</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome do Produto</th>
                    <th>Categoria</th>
                    <th>Preço</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <!-- Exemplo de Anúncio -->
                <tr>
                    <td>1</td>
                    <td>Smartphone XYZ</td>
                    <td>Eletrônicos</td>
                    <td>R$ 1.500,00</td>
                    <td>
                        <a href="editar_anuncio.php?id=1" class="btn btn-warning btn-sm">Editar</a>
                        <a href="excluir_anuncio.php?id=1" class="btn btn-danger btn-sm">Excluir</a>
                    </td>
                </tr>
                <!-- Adicione outros anúncios aqui -->
            </tbody>
        </table>
    </div>

    <!-- Rodapé -->
    <div class="footer text-center">
        <div class="container">
            <p>&copy; 2023 Minha Empresa. Todos os direitos reservados.</p>
        </div>
    </div>

</body>

</html>