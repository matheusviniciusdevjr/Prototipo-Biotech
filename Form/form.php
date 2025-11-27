<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Cadastro - Biotech</title>

  <link rel="stylesheet" href="./styleform.css">
 <link rel="shortcut icon" href="../assets/letra-b.png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>





  <header>

    <div class="cabecalho">

     <h1 class="logotipo"><a href="./index.html">BIOTECH</a></h1>

      <div class="nav-container">

        <ul class="menu">

        <a href="./index.html"><li>Home</li></a>
        <a href="./firstPages/sobreNos.html"><li>Sobre Nós</li></a>
        <a href="./firstPages/tecnologias.html"><li>Tecnologias</li></a>
        <a href="./firstPages/educacao.html"><li>Educação</li></a>
        <a href="./Solucoes/solucoes.html"><li>Pesquisas</li></a>
        <a href="./firstPages/parceiros.html"><li class="menuDestaque">Seja Nosso Parceiro</li></a>

        </ul>

        <div class="icones">

          <a href="./form.html"><i class="fa-solid fa-user iconPerfil"></i></a>

          <i class="fa-solid fa-bars iconMenu"></i>

        </div>

      </div>

    </div>

  </header>





  <main class="form-fundo">

    <div class="form-container">

      <h2 class="form-titulo">Cadastro de Usuário</h2>



    <form action="processaCadastro.php" method="POST">



      <div class="form-grupo">

        <label for="usuario">Usuário</label>

        <input type="text" id="usuario" name="usuario" placeholder="Digite um nome de usuário" >
      

      </div>




<div class="form-grupo">

  <label>Você faz parte ou tem uma empresa?</label>

  <div class="radio-opcoes">

    <input type="radio" id="empresa_sim" name="tipo_usuario" value="empresa"  required class="required">

    <label for="empresa_sim">Sim</label>

   

    <input type="radio" id="empresa_nao" name="tipo_usuario" value="comum"  required class="required">

    <label for="empresa_nao">Não</label>

  </div>

</div>

<div class="form-grupo">

  <label for="endereco">Endereço</label>

  <input type="text" id="endereco" name="endereco" placeholder="Digite seu endereço" required class="required">
     <span class="span-required"></span> 

</div>



      <div class="form-grupo">

        <label for="nome_completo">Nome Completo</label>

        <input type="text" id="nome_completo" name="nome_completo" placeholder="Digite seu nome completo" required class="required">
            <span class="span-required"></span> 

      </div>

      <div class="form-grupo">

        <label for="cpf">CPF</label>

        <input type="text" id="cpf" name="cpf" placeholder="Digite seu CPF" required class="required">
        <span class="span-required"></span> 
      </div>



      <div class="form-grupo">

        <label for="email">E-mail</label>

        <input type="email" id="email" name="email" placeholder="Digite seu e-mail" required class="required">
         <span class="span-required"></span> 
      </div>



      <div class="form-grupo">

        <label for="telefone">Telefone</label>

        <input type="tel" id="telefone" name="telefone" placeholder="Digite seu telefone" required class="required">
         <span class="span-required"></span> 

      </div>


        <div class="form-grupo">
    <label for="email_confirmacao">Confirme seu E-mail de Cadastro</label>
    <input type="email" id="email_confirmacao" name="email_confirmacao" placeholder="E-mail para verificação " required>
        </div>
  

      <div class="form-grupo">

        <label for="senha">Senha</label>

        <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required class="required">
        <span class="span-required"></span> 

      </div>



      <div class="form-grupo">

        <label for="confirmar_senha">Confirmar Senha</label>

        <input type="password" id="confirmar_senha" name="confirmar_senha" placeholder="Confirme sua senha" required class="required">
        <span class="span-required"></span> 

      </div>



      <div class="form-grupo">

        <label for="palavra_chave">Palavra-chave (para lembrar sua senha)</label>

        <input type="text" id="palavra_chave" name="palavra_chave" placeholder="Ex: nome do pet, lugar favorito..." required class="required">

      </div>



      <div class="form-botoes">

        <button type="submit" class="btn-cadastrar">Cadastrar</button>

        <button type="reset" class="btn-cancelar">Cancelar</button>

      </div>

      </form>



      <div style="margin-bottom: 10px;">

    <a href="./telaLogin.php" class="btn-voltar">

      <i class="fa-solid fa-arrow-left"></i> Voltar

    </a>

  </div>

    </div>

  </main>



  <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>

<script src="../javascript/validacao.js"></script>

<script>

  $('#cpf').mask('000.000.000-00');

  $('#telefone').mask('(00) 00000-0000');

  </script>

</body>

</html>