<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] !== 'comum') {
    header("Location: telaLogin.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sustentabilidade Tech</title>
    <link rel="stylesheet" href="./styleUser.css">
    <link rel="shortcut icon" href="../assets/letra-b.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <header class="user-header">
        <h1>Bem-vindo à Comunidade Verde, <?php echo $_SESSION['usuario']; ?></h1>
        <a href="../index.html" class="btn-sair">Sair</a>
    </header>

    <main class="user-main">
        <section class="user-content">
            
            <h2>Seu Espaço de Tecnologia Verde e Sustentabilidade</h2>
            
            <div class="user-main-action">
                <a href="../index.html" class="btn-acao btn-principal">
                    <i class="fa-solid fa-tree"></i> Continuar Explorando o Site
                </a>
            </div>

            <hr class="separator">
            
            <div class="user-actions">
               <a href="../testebio/secondPages/artigos.html" class="btn-acao"><i class="fa-solid fa-book-open"></i> Acessar Artigos Premium</a>
                
                <a href="#" class="btn-acao"><i class="fa-solid fa-user"></i> Ver/Editar Perfil</a>
            </div>
            
        </section>
    </main>

</body>
</html>