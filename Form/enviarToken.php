<?php
session_start();
require_once "conexao.php";

$DOMINIO_BASE = "http://localhost/testebio/Form/";

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST['email'])) {

    $email = $conn->real_escape_string(trim($_POST['email']));
    $link_reset = '';

    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $stmt->close();

    if ($resultado->num_rows === 1) {
        $user = $resultado->fetch_assoc();
        $usuario_id = $user['id'];

        $token = bin2hex(random_bytes(32));
        $token_expira = date("Y-m-d H:i:s", time() + 3600);

        $link_reset = $DOMINIO_BASE . "resetSenha.php?token=" . $token;

        $stmt_update = $conn->prepare("UPDATE usuarios SET reset_token = ?, token_expira = ? WHERE id = ?");
        $stmt_update->bind_param("ssi", $token, $token_expira, $usuario_id);
        $stmt_update->execute();
        $stmt_update->close(); 

        $conn->close();
        ?>
        <!DOCTYPE html>
        <html lang="pt-br">
        <head>
            <meta charset="UTF-8">
            <title>Link de Redefinição</title>
        </head>
        <body>
            <h2>Link de redefinição de senha</h2>
            <p>Use este link para redefinir sua senha (válido por 1 hora):</p>
            <a href="<?php echo htmlspecialchars($link_reset); ?>"><?php echo htmlspecialchars($link_reset); ?></a>
        </body>
        </html>
        <?php
        exit;
    } else {
        $_SESSION['reset_sucesso'] = "Se o e-mail estiver cadastrado, um link de reset foi gerado (verifique a URL para teste).";
        $conn->close();
        header("Location: solicitarReset.php");
        exit;
    }

} else {
    $_SESSION['reset_erro'] = "Método inválido ou e-mail não fornecido.";
    header("Location: solicitarReset.php");
    exit;
}


?>
