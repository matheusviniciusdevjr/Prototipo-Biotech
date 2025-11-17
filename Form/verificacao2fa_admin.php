<?php
session_start();
require_once "conexao.php";

// Garante que o usuário veio do login
if (!isset($_SESSION['2fa_user_id'])) {
    header("Location: telaLogin.php");
    exit;
}

$usuario_id = $_SESSION['2fa_user_id'];
$erro = "";

// Quando o admin envia o código de autenticação
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $codigo_admin = trim($_POST['codigo_admin2fa']);

    // Busca o código de 2FA cadastrado no banco
    $stmt = $conn->prepare("SELECT codigo_admin_2fa FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $stmt->bind_result($codigo_admin_bd);
    $stmt->fetch();
    $stmt->close();

    // Verifica se o código está correto
    if ($codigo_admin === $codigo_admin_bd) {
        // Login confirmado — define as variáveis de sessão principais
        $_SESSION['usuario'] = $_SESSION['2fa_username'];
        $_SESSION['tipo'] = $_SESSION['2fa_tipo'];

        // Remove dados temporários usados na verificação
        unset($_SESSION['2fa_user_id'], $_SESSION['2fa_username'], $_SESSION['2fa_tipo']);

        // ✅ Redireciona corretamente para o painel do administrador
        header("Location: ../CRUD/adminCrud.php");
        exit;
    } else {
        $erro = "Código incorreto!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Verificação 2FA - Admin</title>
<link rel="stylesheet" href="./styleform.css">
</head>
<body>
<main class="form-fundo">
<div class="form-container">
<h2 class="form-titulo">Verificação 2FA - Administrador</h2>

<?php if (!empty($erro)): ?>
<p style="color:red; text-align:center;"><?php echo htmlspecialchars($erro); ?></p>
<?php endif; ?>

<form method="POST">
  <div class="form-grupo">
    <label for="codigo_admin2fa">Código de Acesso do Administrador</label>
    <input type="password" id="codigo_admin2fa" name="codigo_admin2fa" required>
  </div>

  <div class="form-botoes" style="justify-content:center;">
    <button type="submit" class="btn-cadastrar">Verificar</button>
  </div>
</form>

<a href="logout.php" class="link-recuperar-senha">Cancelar</a>
</div>
</main>
</body>
</html>
