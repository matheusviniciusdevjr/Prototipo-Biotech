<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Biotech</title>
  <link rel="stylesheet" href="./styleform.css">
  <link rel="shortcut icon" href="../assets/letra-b.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<?php

if (isset($_SESSION['cadastro_sucesso'])) {
    echo '<div class="mensagem-sucesso">' . $_SESSION['cadastro_sucesso'] . '</div>';
    unset($_SESSION['cadastro_sucesso']); 
}
?>

  <!-- HEADER -->
  <header>
    <div class="cabecalho">
      <h1 class="logotipo">BIOTECH</h1>
      <div class="nav-container">
        <ul class="menu">
          <li>Home</li>
          <li>Sobre Nós</li>
          <li>Tecnologias</li>
          <li>Educação</li>
          <li>Soluções</li>
          <li>Seja Nosso Parceiro</li>
        </ul>
        <div class="icones">
          <a href="./home.html"><i class="fa-solid fa-user iconPerfil"></i></a>
          <i class="fa-solid fa-bars iconMenu"></i>
        </div>
      </div>
    </div>
  </header>

  <!-- LOGIN -->
  <main class="form-fundo">
    <div class="form-container">
      <h2 class="form-titulo">Login</h2>

      <form action="validaLogin.php" method="POST">
        <div class="form-grupo">
          <label for="usuario">Usuário</label>
          <input type="text" id="usuario" name="usuario" placeholder="Digite seu usuário" required>
        </div>

        <div class="form-grupo">
          <label for="senha">Senha</label>
          <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
        </div>

        <div class="form-botoes">
          <button type="submit" class="btn-cadastrar">Entrar</button>
          <button type="reset" class="btn-cancelar">Cancelar</button>
        </div>
      </form>

   
      <div style="text-align: center; margin-top: 20px;">
        <p style="color: #EFEEEA; margin-bottom: 5px;">Não tem uma conta?</p>
        <a href="./form.php">
          <button class="btn-cadastrar">Cadastre-se</button>
        </a>
      </div>

   
<div style="margin-bottom: 10px;">
    <a href="../index.html" class="btn-voltar">
      <i class="fa-solid fa-arrow-left"></i> Voltar
    </a>
  </div>
  

    </div>
  </main>

</body>
</html>
