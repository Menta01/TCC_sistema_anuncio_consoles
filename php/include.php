<?php
include ('conexaoBD.php');

// Exemplo de uma consulta
$sql = "SELECT * FROM usuariosBD";
$result = $link->query($sql);

if ($result->num_rows > 0) {
    // Exibe os dados em uma tabela
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["ID"]. " - nome: " . $row["nome"]. " - email: " . $row["email"]. " - senha: " . $row["senha"]. "<br>";
    }
} else {
    echo "";
}

$link->close();
?>
