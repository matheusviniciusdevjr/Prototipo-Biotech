<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] !== 'admin') {
    
    header("Location: ../Form/telaLogin.html");
    exit;
}
require_once "../Form/conexao.php"; 

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
   
        header("Location: adminCrud.php?status=excluido"); 
    } else {
       
        header("Location: adminCrud.php?status=erro_exclusao&msg=" . urlencode($conn->error));
    }
    $stmt->close();
} else {
    header("Location: /testebio/CRUD/adminCrud.php");
}
exit;
?>