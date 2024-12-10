<?php
include('php/valida_Sessao.php');
include('php/conexaoBD.php');
include('visual/header.php'); // Incluindo o arquivo de cabeçalho

// Função para desativar ou ativar usuário
if (isset($_GET['alterar_status'])) {
    $id_usuario = $_GET['alterar_status'];
    $current_status_query = "SELECT status FROM usuariosbd WHERE ID = $id_usuario";
    $current_status_result = mysqli_query($link, $current_status_query);
    $current_status = mysqli_fetch_assoc($current_status_result)['status'];

    $new_status = ($current_status === 'ativo') ? 'inativo' : 'ativo';

    $update_status_sql = "UPDATE usuariosbd SET status = '$new_status' WHERE ID = $id_usuario";
    if (mysqli_query($link, $update_status_sql)) {
        echo "Status do usuário atualizado com sucesso!";
        header("Location: tela_Gestão_Usuarios.php");
        exit();
    } else {
        echo "Erro ao atualizar status do usuário: " . mysqli_error($link);
    }
}

// Editar Usuário
if (isset($_POST['editar_usuario'])) {
    $id_usuario = $_POST['id_usuario'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $tipo_usuario = $_POST['tipo_usuario'];
    $estado = $_POST['estado'];
    $cidade = $_POST['cidade'];

    $update_sql = "UPDATE usuariosbd SET nome = '$nome', email = '$email', telefone = '$telefone', TipoUsuario = '$tipo_usuario', Estado = '$estado', Cidade = '$cidade' WHERE ID = $id_usuario";
    if (mysqli_query($link, $update_sql)) {
        echo "Usuário atualizado com sucesso!";
        header("Location: gestao_usuarios_anuncios.php");
        exit();
    } else {
        echo "Erro ao atualizar usuário: " . mysqli_error($link);
    }
}
?>

<!-- Cabeçalho -->
<?php renderHeader('gestao_usuarios_anuncios'); ?>

<div class="container mt-5">
    <h2>Gestão de Usuários</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Tipo de Usuário</th>
                <th>Estado</th>
                <th>Cidade</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM usuariosbd";
            $result = mysqli_query($link, $sql);
            while ($usuario = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $usuario['ID'] . "</td>";
                echo "<td>" . $usuario['nome'] . "</td>";
                echo "<td>" . $usuario['email'] . "</td>";
                echo "<td>" . $usuario['telefone'] . "</td>";
                echo "<td>" . $usuario['TipoUsuario'] . "</td>";
                echo "<td>" . $usuario['Estado'] . "</td>";
                echo "<td>" . $usuario['Cidade'] . "</td>";
                echo "<td>" . ucfirst($usuario['status']) . "</td>";
                echo "<td>
                        <a href='#' data-bs-toggle='modal' data-bs-target='#editarUsuarioModal" . $usuario['ID'] . "' class='btn btn-warning btn-sm'>Editar</a>
                        <a href='?alterar_status=" . $usuario['ID'] . "' class='btn " . ($usuario['status'] === 'ativo' ? 'btn-danger' : 'btn-success') . " btn-sm'>" . 
                        ($usuario['status'] === 'ativo' ? 'Desativar' : 'Ativar') . "</a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Modal de Edição de Usuário -->
<?php
$result = mysqli_query($link, $sql);
while ($usuario = mysqli_fetch_assoc($result)) {
    echo "
    <div class='modal fade' id='editarUsuarioModal" . $usuario['ID'] . "' tabindex='-1' aria-labelledby='editarUsuarioModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title' id='editarUsuarioModalLabel'>Editar Usuário</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body'>
                    <form method='POST'>
                        <input type='hidden' name='id_usuario' value='" . $usuario['ID'] . "'>
                        <div class='mb-3'>
                            <label for='nome' class='form-label'>Nome</label>
                            <input type='text' class='form-control' name='nome' value='" . $usuario['nome'] . "' required>
                        </div>
                        <div class='mb-3'>
                            <label for='email' class='form-label'>Email</label>
                            <input type='email' class='form-control' name='email' value='" . $usuario['email'] . "' required>
                        </div>
                        <div class='mb-3'>
                            <label for='telefone' class='form-label'>Telefone</label>
                            <input type='text' class='form-control' name='telefone' value='" . $usuario['telefone'] . "' required>
                        </div>
                        <div class='mb-3'>
                            <label for='tipo_usuario' class='form-label'>Tipo de Usuário</label>
                            <select class='form-select' name='tipo_usuario'>
                                <option value='normal' " . ($usuario['TipoUsuario'] == 'normal' ? 'selected' : '') . ">Normal</option>
                                <option value='admin' " . ($usuario['TipoUsuario'] == 'admin' ? 'selected' : '') . ">Admin</option>
                            </select>
                        </div>
                        <div class='mb-3'>
                            <label for='estado' class='form-label'>Estado</label>
                            <input type='text' class='form-control' name='estado' value='" . $usuario['Estado'] . "' required>
                        </div>
                        <div class='mb-3'>
                            <label for='cidade' class='form-label'>Cidade</label>
                            <input type='text' class='form-control' name='cidade' value='" . $usuario['Cidade'] . "' required>
                        </div>
                        <button type='submit' name='editar_usuario' class='btn btn-primary'>Salvar alterações</button>
                    </form>
                </div>
            </div>
        </div>
    </div>";
}
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
