<?php
session_start();

require_once "conexao.php"; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = trim($_POST['usuario']);
    $senha = $_POST['senha'];

    $stmt = $conn->prepare("SELECT id, usuario, senha, tipo FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $user, $senha_hash, $tipo);
        $stmt->fetch();

        if (password_verify($senha, $senha_hash)) {
            $_SESSION['usuario'] = $user;
            $_SESSION['tipo'] = $tipo;

            if ($tipo === "admin") {
                header("Location: ../CRUD/adminCrud.php");
            } else {
                header("Location: userDashboard.php"); 
            }
            exit;
        } else {
            echo "⚠️ Senha incorreta!";
        }
    } else {
        echo "⚠️ Usuário não encontrado!";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Método inválido!";
}
?>
