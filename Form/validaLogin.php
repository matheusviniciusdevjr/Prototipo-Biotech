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
           
            
            $usuario_id = $id;
            $ip_usuario = $_SERVER['REMOTE_ADDR']; 
            $data_login = date('Y-m-d H:i:s'); 

            
            $stmt_reg = $conn->prepare("INSERT INTO registro_login (usuario_id, data_login, ip_usuario) VALUES (?, ?, ?)");
            if ($stmt_reg) {
                $stmt_reg->bind_param("iss", $usuario_id, $data_login, $ip_usuario);
                $stmt_reg->execute();
                $stmt_reg->close();
            } else {
                
            }

            if ($tipo === "admin") {
             
                $_SESSION['usuario'] = $user;
                $_SESSION['tipo'] = $tipo;
                header("Location: ../CRUD/adminCrud.php");
                exit;
            } else {
                
                $_SESSION['usuario'] = $user;
                $_SESSION['tipo'] = $tipo;
                
              
                header("Location: userDashboard.php"); 
                exit;
            }
        } else {
            echo " Senha incorreta!";
        }
    } else {
        echo " Usuário não encontrado!";
    }

    $conn->close();
} else {
    echo "Método inválido!";
}
?>