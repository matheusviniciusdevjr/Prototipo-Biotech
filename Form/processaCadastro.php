<?php

session_start();


require_once "conexao.php"; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $usuario = trim($_POST['usuario']);
    $nome_completo = trim($_POST['nome_completo']);
    $cpf = trim($_POST['cpf']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $endereco = trim($_POST['endereco']);
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    $palavra_chave = trim($_POST['palavra_chave']);
    
    $tipo = $_POST['tipo_usuario'] ?? 'comum'; 
    if (!in_array($tipo, ['comum', 'empresa'])) {
        $tipo = 'comum'; 
    }


   
    if ($senha !== $confirmar_senha) {
        
        $_SESSION['erro'] = " As senhas digitadas não conferem! Por favor, tente novamente.";
        header("Location: form.html"); 
        exit();
    }

  
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);


    $stmt = $conn->prepare("INSERT INTO usuarios (usuario, nome_completo, cpf, email, telefone, endereco, senha, palavra_chave, tipo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $usuario, $nome_completo, $cpf, $email, $telefone, $endereco, $senha_hash, $palavra_chave, $tipo);

    if ($stmt->execute()) {
        
        
        
        $_SESSION['cadastro_sucesso'] = " Cadastro de $usuario realizado com sucesso! Agora você pode fazer o login.";
        
      
        header("Location: telaLogin.php"); 
        
     
        exit(); 
        
    } else {
       
        $_SESSION['erro'] = " Erro ao cadastrar. Tente novamente. Detalhe do erro: " . $stmt->error;
        header("Location: form.html");
        exit();
    }

   
    $stmt->close();
    $conn->close();

} else {
   
    header("Location: form.html");
    exit();
}
?>