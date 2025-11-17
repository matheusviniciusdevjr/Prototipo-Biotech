<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
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
        $stmt->close();

        if (password_verify($senha, $senha_hash)) {

            // Gera código 2FA válido por 5 minutos
            $codigo_2fa = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $expira_em = date('Y-m-d H:i:s', strtotime('+5 minutes'));

            $stmt2 = $conn->prepare("UPDATE usuarios SET codigo_2fa = ?, codigo_2fa_expira = ? WHERE id = ?");
            $stmt2->bind_param("ssi", $codigo_2fa, $expira_em, $id);
            $stmt2->execute();
            $stmt2->close();

            // Guarda informações temporárias na sessão
            $_SESSION['2fa_user_id'] = $id;
            $_SESSION['2fa_username'] = $user;
            $_SESSION['2fa_tipo'] = $tipo;

            // Direciona para a página de verificação correta
            if ($tipo === "admin") {
                header("Location: verificacao2fa_admin.php");
            } else {
                header("Location: verificacao2fa_user.php");
            }
            exit;
        } else {
            echo "<script>alert('Senha incorreta!'); window.location.href='telaLogin.php';</script>";
        }
    } else {
        echo "<script>alert('Usuário não encontrado!'); window.location.href='telaLogin.php';</script>";
    }

    $conn->close();
} else {
    echo "Método inválido!";
}
?>
