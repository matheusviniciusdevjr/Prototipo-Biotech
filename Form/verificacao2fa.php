<?php
session_start();
require_once "conexao.php"; 

$erro = "";


if (!isset($_SESSION['2fa_user_id'])) {
 
    header("Location: telaLogin.php");
    exit;
}

$usuario_id = $_SESSION['2fa_user_id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo_enviado = trim($_POST['codigo_2fa']);
    $agora = date('Y-m-d H:i:s');


    $stmt = $conn->prepare("SELECT codigo_2fa, codigo_2fa_expira FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $stmt->bind_result($codigo_bd, $expira_bd);
    $stmt->fetch();
    $stmt->close();

 
    if ($codigo_enviado === $codigo_bd && $agora < $expira_bd) {
        
      
        
        $stmt_clean = $conn->prepare("UPDATE usuarios SET codigo_2fa = NULL, codigo_2fa_expira = NULL WHERE id = ?");
        $stmt_clean->bind_param("i", $usuario_id);
        $stmt_clean->execute();
        $stmt_clean->close();
        
   
        $_SESSION['usuario'] = $_SESSION['2fa_username']; 
        $_SESSION['tipo'] = $_SESSION['2fa_tipo'];
        
       
        unset($_SESSION['2fa_user_id']);
        unset($_SESSION['2fa_username']);
        unset($_SESSION['2fa_tipo']);
        
    
        header("Location: userDashboard.php");
        exit;

    } else {
     
        $erro = "Código de verificação incorreto ou expirado. Tente novamente.";
    }
}



$stmt_dev = $conn->prepare("SELECT codigo_2fa, codigo_2fa_expira FROM usuarios WHERE id = ?");
$stmt_dev->bind_param("i", $usuario_id);
$stmt_dev->execute();
$stmt_dev->bind_result($codigo_dev, $expira_dev);
$stmt_dev->fetch();
$stmt_dev->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificação 2FA</title>
    <link rel="stylesheet" href="./styleform.css">
    

</head>
<body>
    <main class="form-fundo">
        <div class="form-container">
            <h2 class="form-titulo">Verificação em Duas Etapas</h2>

            <?php if (!empty($erro)): ?>
                <p style="color: red; text-align: center; margin-bottom: 20px;"><?php echo $erro; ?></p>
            <?php endif; ?>

            <p style="text-align: center; color: #EFEEEA; margin-bottom: 15px;">
                Um código de 6 dígitos foi gerado. Insira-o abaixo:
            </p>
            
            <div class="alerta-dev">
                <strong>CÓDIGO (DEV ONLY): <?php echo htmlspecialchars($codigo_dev); ?></strong>
                <p>
                    Expira em: <?php echo date('H:i:s', strtotime($expira_dev)); ?>
                </p>
            </div>
            <form action="verificacao2fa.php" method="POST">
                <div class="form-grupo">
                    <label for="codigo_2fa">Código de Verificação</label>
                    <input type="text" id="codigo_2fa" name="codigo_2fa" placeholder="Ex: 123456" maxlength="6" required>
                </div>

                <div class="form-botoes" style="justify-content: center;">
                    <button type="submit" class="btn-cadastrar">Verificar</button>
                </div>
            </form>

            <div style="text-align: center; margin-top: 20px;">
                <a href="logout.php" class="link-recuperar-senha">Cancelar e Voltar ao Login</a>
            </div>
        </div>
    </main>
</body>
</html>