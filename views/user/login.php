<!-- Sidebar -->
<?php 
require_once(__DIR__ . '/../../banco.php');
function post_data($field){
  $_POST[$field] ??= '';
  
  return htmlspecialchars(stripslashes($_POST[$field]));
}

define('REQUIRED_FIELD_ERROR', 'É necessario preencher esse campo!');
$errors = [];

$usuario = '';
$senha = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $usuario = post_data('usuario');
  $senha = post_data('senha');

  // Validações
  if (!$usuario) {
    $errors['usuario'] = REQUIRED_FIELD_ERROR;
  } elseif(strlen($usuario) > 1) {
    $stmt = $pdo->prepare("SELECT usuario FROM tb_usuario WHERE usuario = :usuario");
    $stmt->bindParam(':usuario', $usuario);
    $stmt->execute();

    if($stmt->rowCount() === 0){
      $errors['usuario'] = 'Esse usuario não existe!';
    }
  }

  if (!$senha) {
  $errors['senha'] = REQUIRED_FIELD_ERROR;
  }


  // Se não houver erros, redireciona para o login.php
  if (empty($errors)) {

    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    $_SESSION['usuario'] = $usuario;
    $_SESSION['senha'] = $senha;

    header('Location: /logintemplate/functions/user/login.php');
    exit;
  }

  
}

include '../../components/sidebar.php'; 

?>

<!-- Página escura de fundo -->
<div class="container-fluid  min-vh-100 d-flex justify-content-center align-items-center">
  <div class="card shadow-lg bg-dark p-4" style="width: 100%; max-width: 400px;">
    <div class="text-center mb-4">
      <img src="logintemplate/images/logo.png" alt="Logo" style="max-height: 80px;" onerror="this.style.display='none'">
      <h3 class="mt-2 text-white">Login</h3>

    </div>

    <form action="" method="POST">
      <div class="mb-3">
        <label for="usuario" class="form-label text-white">Usuário</label>
        <input type="text" class="form-control <?php echo isset($errors['usuario']) ? 'is-invalid' : '' ?>" id="usuario" name="usuario"  value="<?php echo $usuario ?>" >
        <div class="invalid-feedback"> 
        <?php echo $errors['usuario'] ?>
        </div>
      </div>

      <div class="mb-3">
        <label for="senha" class="form-label text-white">Senha</label>
        <input type="password"  class="form-control <?php echo isset($errors['senha']) ? 'is-invalid' : '' ?>" id="senha" name="senha" >
        <div class="invalid-feedback"> 
        <?php echo $errors['senha'] ?>
        </div>
      </div>

      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary">Acessar</button>
      </div>
    </form>
  </div>
</div>
