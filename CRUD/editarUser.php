<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../Form/telaLogin.html");
    exit;
}
require_once "../Form/conexao.php";

$mensagem = "";
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$usuario_data = null;


if ($id > 0) {
   
    $stmt = $conn->prepare("SELECT id, usuario, nome_completo, cpf, email, telefone, endereco, palavra_chave, tipo FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($resultado->num_rows === 1) {
        $usuario_data = $resultado->fetch_assoc();
    } else {
        $mensagem = "Usuário não encontrado.";
        $id = 0;
    }
    $stmt->close();
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && $id > 0) {
    $nome_completo = trim($_POST['nome_completo']);
    $cpf = trim($_POST['cpf']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $endereco = trim($_POST['endereco']);
    $palavra_chave = trim($_POST['palavra_chave']);
    $tipo = $_POST['tipo'];

   
    $stmt = $conn->prepare("UPDATE usuarios SET nome_completo = ?, cpf = ?, email = ?, telefone = ?, endereco = ?, palavra_chave = ?, tipo = ? WHERE id = ?");
    $stmt->bind_param("sssssssi", $nome_completo, $cpf, $email, $telefone, $endereco, $palavra_chave, $tipo, $id);

    if ($stmt->execute()) {
        header("Location: adminCrud.php?status=atualizado");
        exit;
    } else {
        $mensagem = " Erro ao atualizar: " . $stmt->error;
    }
    $stmt->close();
}

if ($id === 0 || $usuario_data === null) {
    header("Location: adminCrud.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Editar Usuário</title>
    <link rel="stylesheet" href="./styleAdmin.css">
    <link rel="shortcut icon" href="../assets/letra-b.png">
</head>
<body>
    <header class="admin-header">
        <h1>Admin - Editar Usuário</h1>
        <a href="../Form/logout.php" class="btn-sair">Sair</a>
    </header>

    <main class="admin-main" style="justify-content: flex-start;">
        <nav class="admin-menu">
            <a href="adminCrud.php" class="menu-item ativo">Gerenciar Usuários</a>
        </nav>
        <section class="admin-content">
            <h2>Editando: <?php echo $usuario_data['usuario']; ?></h2>
            <?php if ($mensagem): ?><p style="color: red; font-weight: bold;"><?php echo $mensagem; ?></p><?php endif; ?>

            <form action="editarUser.php?id=<?php echo $id; ?>" method="POST" class="crud-form">
                <div class="form-group">
                    <label>Usuário (Não Editável):</label>
                    <input type="text" value="<?php echo $usuario_data['usuario']; ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="nome_completo">Nome Completo:</label>
                    <input type="text" id="nome_completo" name="nome_completo" value="<?php echo $usuario_data['nome_completo']; ?>">
                </div>
                <div class="form-group">
                    <label for="cpf">CPF:</label>
                    <input type="text" id="cpf" name="cpf" value="<?php echo $usuario_data['cpf']; ?>">
                </div>
                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" value="<?php echo $usuario_data['email']; ?>">
                </div>
                <div class="form-group">
                    <label for="telefone">Telefone:</label>
                    <input type="text" id="telefone" name="telefone" value="<?php echo $usuario_data['telefone']; ?>">
                </div>
                <div class="form-group">
                    <label for="endereco">Endereço:</label>
                    <input type="text" id="endereco" name="endereco" value="<?php echo $usuario_data['endereco']; ?>">
                </div>
                <div class="form-group">
                    <label for="palavra_chave">Palavra-chave:</label>
                    <input type="text" id="palavra_chave" name="palavra_chave" value="<?php echo $usuario_data['palavra_chave']; ?>">
                </div>
                <div class="form-group">
                    <label for="tipo">Tipo de Usuário:</label>
                    <select id="tipo" name="tipo">
                        <option value="comum" <?php echo $usuario_data['tipo'] == 'comum' ? 'selected' : ''; ?>>Comum</option>
                        <option value="admin" <?php echo $usuario_data['tipo'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn-crud">Salvar Alterações</button>
                <a href="adminCrud.php" class="btn-crud voltar">Voltar</a>
            </form>
        </section>
    </main>
</body>
</html>