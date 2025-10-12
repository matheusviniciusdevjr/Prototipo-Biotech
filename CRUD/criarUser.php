<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../Form/telaLogin.html");
    exit;
}
require_once "../Form/conexao.php"; 

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = trim($_POST['usuario']);
    $nome_completo = trim($_POST['nome_completo']);
    $cpf = trim($_POST['cpf']); 
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']); 
    $endereco = trim($_POST['endereco']); 
    $senha = $_POST['senha'];
    $palavra_chave = trim($_POST['palavra_chave']); 
    $tipo = $_POST['tipo'];

    if (empty($usuario) || empty($email) || empty($senha) || empty($cpf) || empty($telefone) || empty($endereco) || empty($palavra_chave) || empty($nome_completo)) {
        $mensagem = " Preencha todos os campos obrigatórios!";
    } else {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

       
        $stmt = $conn->prepare("INSERT INTO usuarios (usuario, nome_completo, cpf, email, telefone, endereco, senha, palavra_chave, tipo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $usuario, $nome_completo, $cpf, $email, $telefone, $endereco, $senha_hash, $palavra_chave, $tipo);

        if ($stmt->execute()) {
            header("Location: adminCrud.php?status=criado");
            exit;
        } else {
            $mensagem = " Erro ao cadastrar (Usuário, CPF ou E-mail já existe): " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Criar Usuário</title>
    <link rel="stylesheet" href="./styleAdmin.css">
    <link rel="shortcut icon" href="../assets/letra-b.png">
</head>
<body>
    <header class="admin-header">
        <h1>Admin - Criar Novo Usuário</h1>
        <a href="../Form/logout.php" class="btn-sair">Sair</a>
    </header>

    <main class="admin-main" style="justify-content: flex-start;">
        <nav class="admin-menu">
            <a href="adminCrud.php" class="menu-item ativo">Gerenciar Usuários</a>
        </nav>
        <section class="admin-content">
            <h2>Adicionar Novo Usuário</h2>
            <?php if ($mensagem): ?><p style="color: red; font-weight: bold;"><?php echo $mensagem; ?></p><?php endif; ?>

            <form action="criarUser.php" method="POST" class="crud-form">
                <div class="form-group">
                    <label for="usuario">Usuário*:</label>
                    <input type="text" id="usuario" name="usuario" required>
                </div>
                <div class="form-group">
                    <label for="nome_completo">Nome Completo*:</label>
                    <input type="text" id="nome_completo" name="nome_completo" required>
                </div>
                <div class="form-group">
                    <label for="cpf">CPF*:</label>
                    <input type="text" id="cpf" name="cpf" required>
                </div>
                <div class="form-group">
                    <label for="email">E-mail*:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="telefone">Telefone*:</label>
                    <input type="text" id="telefone" name="telefone" required>
                </div>
                <div class="form-group">
                    <label for="endereco">Endereço*:</label>
                    <input type="text" id="endereco" name="endereco" required>
                </div>
                <div class="form-group">
                    <label for="senha">Senha*:</label>
                    <input type="password" id="senha" name="senha" required>
                </div>
                <div class="form-group">
                    <label for="palavra_chave">Palavra-chave*:</label>
                    <input type="text" id="palavra_chave" name="palavra_chave" required>
                </div>
                <div class="form-group">
                    <label for="tipo">Tipo de Usuário:</label>
                    <select id="tipo" name="tipo">
                        <option value="comum">Comum</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn-crud">Salvar Usuário</button>
                <a href="adminCrud.php" class="btn-crud voltar">Voltar</a>
            </form>
        </section>
    </main>
</body>
</html>