


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Tela de Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/login.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <div class="login-container">
    <?php
        if (isset($_GET["erroLogin"])){
            $erroLogin = $_GET["erroLogin"];
            if ($erroLogin == "dadosInvalidos"){
                echo "<div class='alert alert-danger text-center' style='text-align:center';><strong>USUÁRIO</strong> ou <strong>SENHA</strong> inválidos!</div>";
            }
        }
    ?>
        <h2>Tela de Login</h2>
        <form action="php/login.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" placeholder="Insira seu email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha:</label>
                <input type="password" class="form-control" id="senha" placeholder="Insira sua senha" name="senha" required>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-block">Entrar</button>
            </div>
            <div class="mt-3 text-center">
                <a href="telaRegistroUsuario.html">Registrar</a>
            </div>
        </form>
    </div>

</body>

</html>