<!-- Sidebar -->
<?php include '../../components/sidebar.php'; 

?>

<!-- Página escura de fundo -->
<div class="container-fluid  min-vh-100 d-flex justify-content-center align-items-center">
  <div class="card shadow-lg bg-dark p-4" style="width: 100%; max-width: 400px;">
    <div class="text-center mb-4">
      <img src="logintemplate/images/logo.png" alt="Logo" style="max-height: 80px;" onerror="this.style.display='none'">
      <h3 class="mt-2 text-white">Login</h3>
    </div>

    <form action="/logintemplate/functions/user/login.php" method="POST">
      <div class="mb-3">
        <label for="usuario" class="form-label text-white">Usuário</label>
        <input type="text" class="form-control" id="usuario" name="usuario" required>
      </div>

      <div class="mb-3">
        <label for="senha" class="form-label text-white">Senha</label>
        <input type="password" class="form-control" id="senha" name="senha" required>
      </div>

      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary">Acessar</button>
      </div>
    </form>
  </div>
</div>
