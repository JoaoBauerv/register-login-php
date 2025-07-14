<!-- Sidebar -->
<?php 

include '../../components/sidebar.php'; 
// Supondo que os dados do usuário estejam em $_SESSION['usuario']
// Exemplo de array: $_SESSION['usuario'] = ['nome' => 'João', 'email' => 'joao@email.com', 'usuario' => 'joaosilva'];


//var_dump($dados_usuario);



?>

<div class="container-fluid min-vh-100 d-flex justify-content-center align-items-center">
    <div class="card shadow-lg bg-dark p-4" style="width: 100%; max-width: 400px;">
        <div class="text-center mb-4">
            <img src="/caminho/para/avatar.png" alt="Avatar" style="max-height: 80px;" onerror="this.style.display='none'">
            <img src="<?php echo $foto_usuario; ?>" alt="" width="64" height="64" class="rounded-circle me-2">
            <h3 class="mt-2 text-white">Perfil do Usuário</h3>
        </div>
        <?php if ($usuario): ?>
            <ul class="list-group list-group-flush">
                <li class="list-group-item bg-dark text-white"><strong>Nome:</strong> <?php echo htmlspecialchars($dados_usuario['nome']); ?></li>
                <li class="list-group-item bg-dark text-white"><strong>Usuário:</strong> <?php echo htmlspecialchars($dados_usuario['usuario']);?></li>
                <li class="list-group-item bg-dark text-white"><strong>Email:</strong> <?php echo htmlspecialchars($dados_usuario['email']); ?></li>
                
            </ul>
        <?php else: ?>
            <div class="alert alert-danger">Usuário não encontrado.</div>
        <?php endif; ?>
        <div class="mt-4 d-grid gap-2">
            <a href="/logintemplate/functions/user/logout.php" class="btn btn-danger">Sair</a>
        </div>
    </div>
</div>
