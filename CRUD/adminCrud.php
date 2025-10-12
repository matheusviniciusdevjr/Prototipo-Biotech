<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../Form/telaLogin.html");
    exit;
}


require_once "../Form/conexao.php"; 


$sql = "SELECT id, usuario, nome_completo, cpf, email, telefone, tipo FROM usuarios ORDER BY id DESC";
$resultado = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gerenciar Usuários</title>
    <link rel="stylesheet" href="./styleAdmin.css">
    <link rel="shortcut icon" href="../assets/letra-b.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>

    <header class="admin-header">
        <h1>Admin - Dashboard</h1>
        <p>Bem-vindo, <?php echo $_SESSION['usuario']; ?></p>
        <a href="../index.html" class="btn-sair">Sair</a>
    </header>

    <main class="admin-main">
        <nav class="admin-menu">
            <a href="adminCrud.php" class="menu-item ativo">Gerenciar Usuários</a>
            <a href="#" class="menu-item">Gerenciar Empresas</a>
        </nav>
        
        <section class="admin-content">
            <h2>Gerenciamento de Usuários</h2>
            
            <div class="crud-actions">
                <a href="criarUser.php" class="btn-crud"><i class="fa-solid fa-user-plus"></i> Adicionar Novo</a>
            </div>

            <div class="user-list">
                <?php if ($resultado->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuário</th>
                            <th>Nome Completo</th>
                            <th>CPF</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Tipo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['usuario']; ?></td>
                            <td><?php echo $row['nome_completo']; ?></td>
                            <td><?php echo $row['cpf']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['telefone']; ?></td>
                            <td><?php echo ucfirst($row['tipo']); ?></td>
                            <td>
                                <a href="editarUser.php?id=<?php echo $row['id']; ?>" class="action-btn edit" title="Editar"><i class="fa-solid fa-pen-to-square"></i></a>                                   <a href="/testebio/CRUD/excluirUser.php?id=<?php echo $row['id']; ?>" 
                                class="action-btn delete" 
                                title="Excluir"  onclick="confirmarExclusao(event, <?php echo $row['id']; ?>)">
                              <i class="fa-solid fa-trash-can"></i>
                                                    </a>

                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <p>Nenhum usuário cadastrado.</p>
                <?php endif; ?>
            </div>
            
        </section>
    </main>
<script>
function confirmarExclusao(event, id) {
  event.preventDefault(); 

  Swal.fire({
    title: 'Tem certeza?',
    text: "Esta ação não poderá ser desfeita!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Sim, excluir!',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
    
      window.location.href = `/testebio/CRUD/excluirUser.php?id=${id}`;
    }
  });
}
</script>


</body>
</html>