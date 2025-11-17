<?php
session_start();
require_once "conexao.php";

// Verifica se o usuário veio do login
if (!isset($_SESSION['2fa_user_id'])) {
    header("Location: telaLogin.php");
    exit;
}

$usuario_id = $_SESSION['2fa_user_id'];
$erro = "";

// Quando o usuário envia o formulário
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $palavra_chave = trim($_POST['palavra_chave']);

    // Busca a palavra-chave no banco de dados
    $stmt = $conn->prepare("SELECT palavra_chave FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $stmt->bind_result($palavra_bd);
    $stmt->fetch();
    $stmt->close();

    if (strcasecmp($palavra_chave, $palavra_bd) === 0) {
        // Palavra correta → login concluído
        $_SESSION['usuario'] = $_SESSION['2fa_username'];
        $_SESSION['tipo'] = $_SESSION['2fa_tipo'];

        // Limpa dados temporários do 2FA
        unset($_SESSION['2fa_user_id'], $_SESSION['2fa_username'], $_SESSION['2fa_tipo']);

        header("Location: userDashboard.php");
        exit;
    } else {
        $erro = "Palavra-chave incorreta. Tente novamente.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificação 2FA - Usuário</title>
    <link rel="stylesheet" href="./styleform.css">
</head>
<body>
    <main class="form-fundo">
        <div class="form-container">
            <h2 class="form-titulo">Verificação em Duas Etapas</h2>

            <?php if (!empty($erro)): ?>
                <p style="color:red; text-align:center; margin-bottom:20px;"><?php echo $erro; ?></p>
            <?php endif; ?>

            <p style="text-align:center; color:#EFEEEA; margin-bottom:15px;">
                Digite sua <strong>palavra-chave</strong> para confirmar sua identidade:
            </p>

            <form method="POST">
                <div class="form-grupo">
                    <label for="palavra_chave">Palavra-chave</label>
                    <input type="password" id="palavra_chave" name="palavra_chave" placeholder="Digite aqui" required>
                </div>

                <div class="form-botoes" style="justify-content:center;">
                    <button type="submit" class="btn-cadastrar">Verificar</button>
                </div>
            </form>

            <div style="text-align:center; margin-top:20px;">
                <a href="logout.php" class="link-recuperar-senha">Cancelar e voltar ao login</a>
            </div>
        </div>
    </main>
</body>
</html>

