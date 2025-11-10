<?php
session_start();
date_default_timezone_set('America/Sao_Paulo'); 

require 'conexao.php'; 


const MAX_ATTEMPTS = 3;           
const LOCKOUT_TIME = 20;         

$erro_reset = "";
$user = null;
$etapa_atual = 'identificacao'; 


if (isset($_SESSION['reset_user'])) {
    $user = $_SESSION['reset_user'];
    $etapa_atual = 'verificacao'; 
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    $email_post = $_POST['email'] ?? '';
    $telefone_post = $_POST['telefone'] ?? ''; 


    if ($acao === 'identificar') {
     
        $stmt = $conn->prepare("SELECT id, email, tipo, palavra_chave, senha, codigo_admin_2fa, telefone FROM usuarios WHERE email = ? AND telefone = ?");
        
     
        $stmt->bind_param("ss", $email_post, $telefone_post);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $found_user = $resultado->fetch_assoc();
        $stmt->close();
        
        if ($found_user) {
            $_SESSION['reset_user'] = $found_user;
            $_SESSION['reset_attempts'] = 0;
            unset($_SESSION['locked_until']);
            $user = $found_user;
            $etapa_atual = 'verificacao';
        } else {
          
            $erro_reset = "E-mail e/ou Telefone não correspondem a um usuário cadastrado.";
            $etapa_atual = 'identificacao';
        }
    } 
    

    elseif ($acao === 'verificar_codigo' && $user) {
        
        if ($user['tipo'] !== 'admin' && isset($_SESSION['locked_until']) && $_SESSION['locked_until'] > time()) {
            $etapa_atual = 'bloqueado';
        } else {
            $codigo_enviado = trim($_POST['codigo']);
            $is_valid = false;
            
            if ($user['tipo'] === 'admin') {
                if (!empty($user['codigo_admin_2fa']) && $codigo_enviado === $user['codigo_admin_2fa']) {
                    $is_valid = true;
                } else {
                    $erro_reset = "Código de Admin incorreto. Verifique com a TI da empresa.";
                }
            } else {
                if (password_verify($codigo_enviado, $user['senha'])) {
                    $is_valid = true;
                } else {
                    $_SESSION['reset_attempts']++;
                    $tentativas_restantes = MAX_ATTEMPTS - $_SESSION['reset_attempts'];
                    
                    if ($_SESSION['reset_attempts'] >= MAX_ATTEMPTS) {
                        $_SESSION['locked_until'] = time() + LOCKOUT_TIME; 
                        $etapa_atual = 'bloqueado';
                    } else {
                        $erro_reset = "Senha atual incorreta. Você tem mais {$tentativas_restantes} tentativas.";
                    }
                }
            }
            
            if ($is_valid) {
                $etapa_atual = 'resetar_senha';
                unset($_SESSION['reset_attempts']); 
            }
        }
    }

    elseif ($acao === 'atualizar_senha' && $user) {
        
        $nova_senha = $_POST['nova_senha'] ?? '';
        $confirma_senha = $_POST['confirma_senha'] ?? '';

        if (empty($nova_senha) || $nova_senha !== $confirma_senha) {
            $erro_reset = "As senhas não coincidem ou estão vazias.";
            $etapa_atual = 'resetar_senha';
        } else {
            $nova_senha_hashed = password_hash($nova_senha, PASSWORD_DEFAULT);
            
            $stmt_update = $conn->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
            $stmt_update->bind_param("si", $nova_senha_hashed, $user['id']);
            
            if ($stmt_update->execute()) {
                unset($_SESSION['reset_user']);
                $_SESSION['cadastro_sucesso'] = "Sua senha foi redefinida com sucesso! Faça login.";
                $conn->close();
                header("Location: telaLogin.php");
                exit;
            } else {
                $erro_reset = "Erro interno ao salvar a nova senha: " . $conn->error;
                $etapa_atual = 'resetar_senha';
            }
            $stmt_update->close();
        }
    }
} 

$is_locked = ($etapa_atual === 'verificacao' && $user && $user['tipo'] !== 'admin' && isset($_SESSION['locked_until']) && $_SESSION['locked_until'] > time());
if ($is_locked) {
    $etapa_atual = 'bloqueado';
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Redefinir Senha </title>
    <link rel="stylesheet" href="./styleform.css">
   
</head>
<body>
    <main class="form-fundo">
        <div class="form-container">
            <h2 class="form-titulo">Redefinição de Senha</h2>

            <?php if (!empty($erro_reset)): ?>
                <p style="color: red; text-align: center; margin-bottom: 20px;"><?php echo $erro_reset; ?></p>
            <?php endif; ?>

            <?php if ($etapa_atual === 'identificacao'): ?>
                <p style="text-align: center; color: #EFEEEA; margin-bottom: 15px;">
                    Para iniciar, informe seu e-mail e o número de telefone cadastrado:
                </p>
                <form action="resetSenha.php" method="POST">
                    <input type="hidden" name="acao" value="identificar">
                    <div class="form-grupo">
                        <label for="email">E-mail Cadastrado</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-grupo">
                        <label for="telefone">Telefone Cadastrado</label>
                        <input type="text" id="telefone" name="telefone" placeholder="(XX) XXXXX-XXXX" required>
                    </div>
                    <div class="form-botoes" style="justify-content: center;">
                        <button type="submit" class="btn-cadastrar">Buscar Conta</button>
                    </div>
                </form>

            <?php elseif ($etapa_atual === 'bloqueado'): ?>
                <div class="locked-screen">
                    <h2> Conta Bloqueada!</h2>
                    <p>O limite de tentativas foi excedido.</p>
                    <p>Você pode tentar novamente em 10 minutos.</p>
                    <p style="margin-top: 20px;">
                        <a href="telaLogin.php" class="btn-voltar" style="background-color: #EFEEEA; color: #253900; border: none;">
                            Retornar à Tela Principal
                        </a>
                    </p>
                </div>

            <?php elseif ($etapa_atual === 'verificacao' && $user): ?>
                <p style="text-align: center; color: #EFEEEA; margin-bottom: 15px;">
                    Para confirmar sua identidade, insira o dado de segurança abaixo:
                </p>
                
                <form action="resetSenha.php" method="POST">
                    <input type="hidden" name="acao" value="verificar_codigo">
                    
                    <div class="form-grupo">
                        <label for="codigo">
                            <?php 
                            if ($user['tipo'] === 'admin') {
                                echo "Código Estático da Empresa (6 dígitos):";
                            } else {
                                echo "Sua Senha Atual:";
                            }
                            ?>
                        </label>
                        <input type="text" id="codigo" name="codigo" required>
                    </div>

                    <?php if ($user['tipo'] !== 'admin'): ?>
                        <div style="font-size: 0.9em; color: #ffc107; text-align: center; margin-bottom: 15px;">
                            Dica de Senha: Sua Palavra-chave é **"<?php echo htmlspecialchars($user['palavra_chave']); ?>"**
                        </div>
                        <p style="font-size: 0.9em; color: #f39c12; text-align: center;">
                            Tentativas restantes: <?php echo MAX_ATTEMPTS - $_SESSION['reset_attempts']; ?>
                        </p>
                    <?php endif; ?>

                    <div class="form-botoes" style="justify-content: center;">
                        <button type="submit" class="btn-cadastrar">Verificar e Continuar</button>
                    </div>
                </form>

            <?php elseif ($etapa_atual === 'resetar_senha' && $user): ?>
                <p style="color: #2ecc71; text-align: center; margin-bottom: 15px;">Verificação concluída. Defina sua nova senha.</p>
                <form action="resetSenha.php" method="POST">
                    <input type="hidden" name="acao" value="atualizar_senha">
                    
                    <div class="form-grupo">
                        <label for="nova_senha">Nova Senha</label>
                        <input type="password" id="nova_senha" name="nova_senha" required>
                    </div>

                    <div class="form-grupo">
                        <label for="confirma_senha">Confirmar Senha</label>
                        <input type="password" id="confirma_senha" name="confirma_senha" required>
                    </div>

                    <div class="form-botoes" style="justify-content: center;">
                        <button type="submit" class="btn-cadastrar">Atualizar Senha</button>
                    </div>
                </form>

            <?php endif; ?>

            <div style="text-align: center; margin-top: 20px;">
                <a href="telaLogin.php" class="link-recuperar-senha">Voltar ao Login</a>
            </div>
        </div>
    </main>
</body>
</html>