<!-- Sidebar -->
<?php 

require __DIR__ . '/../../functions/funcoes.php';



               
function post_data($field){
  $_POST[$field] ??= '';
  
  return htmlspecialchars(stripslashes($_POST[$field]));
}

define('REQUIRED_FIELD_ERROR', 'É necessario preencher esse campo!');
$errors = [];

$nome = '';
$sobrenome = '';
$email = '';
$senha = '';
$senha2 = '';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome = post_data('nome');
  $sobrenome = post_data('sobrenome');
  $email = post_data('email');
  $senha = post_data('senha');
  $senha2 = post_data('senha2');

  // Validações
  if (!$nome) {
    $errors['nome'] = REQUIRED_FIELD_ERROR;
  } elseif (strlen($nome) < 3 || strlen($nome) > 16) {
    $errors['nome'] = 'O nome precisa ser entre 3 e 16 caracteres!';
  }

  if (!$sobrenome) {
    $errors['sobrenome'] = REQUIRED_FIELD_ERROR;
  } elseif (strlen($sobrenome) < 3 || strlen($sobrenome) > 16) {
    $errors['sobrenome'] = 'O sobrenome precisa ser entre 3 e 16 caracteres!';
  }

  if (!$email) {
    $errors['email'] = REQUIRED_FIELD_ERROR;
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Esse campo precisa ser um email válido!';
  }

  if (!$senha) {
    $errors['senha'] = REQUIRED_FIELD_ERROR;
  } elseif (strlen($senha) < 4 || strlen($senha) > 10) {
    $errors['senha'] = 'A senha precisa ser entre 4 e 10 caracteres!';
  }

  if (!$senha2) {
    $errors['senha2'] = REQUIRED_FIELD_ERROR;
  }

  if ($senha && $senha2 && strcmp($senha, $senha2) !== 0) {
    $errors['senha2'] = 'As senhas precisam ser iguais!';
  }

  if(!empty($nome) && !empty($sobrenome)){
  // Pega o nome completo do POST
  $nomeCompleto = trim($nome . ' ' . $sobrenome);

  // Separa em partes pelo espaço
  $partes = preg_split('/\s+/', $nomeCompleto);

  // Gera o nome de usuário
  if (count($partes) > 1) {
    $usuario = removerAcentos($partes[0]) . '-' . removerAcentos($partes[count($partes) - 1]);
  } else {
    $usuario = removerAcentos($nomeCompleto);
  }
  }



  // Se não houver erros, redireciona para o registrar.php
  if (empty($errors)) {

      //Armazena o arquivo 
    if (isset($_FILES['arquivos']['name'][0])) {
      $file_name = $_FILES['arquivos']['name'][0];
      $tmp_name = $_FILES['arquivos']['tmp_name'][0];
      
      $url = $usuario . '_'  . $file_name;

      $destino = 'C:/xampp/htdocs/logintemplate/images/user/' . $url ;

      if (move_uploaded_file($tmp_name, $destino)) {
          $_SESSION['foto_nome'] = $url;
      } else {
          $_SESSION['foto_nome'] = null;
      }
    }


    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    $_SESSION['nome_completo'] = $nomeCompleto;
    $_SESSION['usuario'] = $usuario;
    $_SESSION['email'] = $email;
    $_SESSION['senha'] = $senha;
    $_SESSION['foto_nome'] = $file_name ;

    header('Location: /logintemplate/functions/user/registrar.php');
    exit;
  }

  
}

include '../../components/sidebar.php';

$sql = "SELECT * FROM tb_usuario where id_usuario = '".$_REQUEST['id']."'";

$stmt = $pdo->prepare("SELECT * FROM tb_usuario where id_usuario = '".$_REQUEST['id']."'");
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
?>




<!-- Página escura de fundo -->
<div class="container-fluid  min-vh-100 d-flex justify-content-center align-items-center">
  <div class="card shadow-lg bg-dark p-4" style="width: 100%; max-width: 400px;">

    <ul class="nav nav-tabs" id="myTab" role="tablist" style="color:white;">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Dados Pessoais</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Documentos</button>
      </li>
    </ul>

<br>

<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nome" class="form-label text-white">Nome</label>
            <input type="text" class="form-control <?php echo isset($errors['nome']) ? 'is-invalid' : '' ?>" id="nome" name="nome"  value="<?php echo $row['nome'] ?>" >
            <div class="invalid-feedback"> 
              <?php echo $errors['nome'] ?>
            </div>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label text-white">Email</label>
            <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" value="<?php echo $row['email'] ?>"  >
            <div class="invalid-feedback"> 
              <?php echo $errors['email'] ?>
            </div>
        </div>


        <div class="mb-3 centralizar-filepond">
            <label for="file" class="form-label text-white">Escolha uma foto de perfil</label>
            <input type="file" id="file" name="arquivos[]" class="filepond " accept="image/*" /><br>
            <?php if (isset($errors['arquivo'])): ?>
                <div class="invalid-feedback d-block text-danger">
                  <?php echo $errors['arquivo']; ?>
                </div>
            <?php endif; ?>
        </div>

          <script>
            FilePond.registerPlugin(
              FilePondPluginFileEncode,
              FilePondPluginFileValidateType,
              FilePondPluginImageExifOrientation,
              FilePondPluginImagePreview,
              FilePondPluginImageCrop,
              FilePondPluginImageResize,
              FilePondPluginImageTransform
            );

            // Cria a instância FilePond no input
            const pond = FilePond.create(document.querySelector('input[type="file"].filepond'), {
              labelIdle: 'Arraste e solte a imagem ou <span class="filepond--label-action">Navegue</span>',
              acceptedFileTypes: ['image/*'],
              allowImagePreview: true,
              imagePreviewHeight: 100,
              imageCropAspectRatio: '1:1',
              imageResizeTargetWidth: 100,
              imageResizeTargetHeight: 100,
              stylePanelLayout: 'compact circle',
              styleLoadIndicatorPosition: 'center bottom',
              styleButtonRemoveItemPosition: 'center bottom',
              storeAsFile: true // importante se você está usando FormData manual
            });
          </script>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-warning">Cadastrar</button>
        </div>
    </form>
</div>

<div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">...

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="cpf" class="form-label text-white">CPF</label>
            <input type="text" class="form-control <?php echo isset($errors['nome']) ? 'is-invalid' : '' ?>" id="cpf" name="cpf"  value="<?php echo $cpf ?>" >
            <div class="invalid-feedback"> 
              <?php echo $errors['cpf'] ?>
            </div>
        </div>

        <div class="mb-3">
            <label for="RG" class="form-label text-white">RG</label>
            <input type="text" class="form-control <?php echo isset($errors['RG']) ? 'is-invalid' : '' ?>" id="RG" name="RG"  value="<?php echo $RG ?>" >
            <div class="invalid-feedback"> 
              <?php echo $errors['RG'] ?>
            </div>
        </div>

        <div class="mb-3">
            <label for="CNH" class="form-label text-white">CNH</label>
            <input type="text" class="form-control <?php echo isset($errors['CNH']) ? 'is-invalid' : '' ?>" id="CNH" name="CNH"  value="<?php echo $CNH ?>" >
            <div class="invalid-feedback"> 
              <?php echo $errors['CNH'] ?>
            </div>
        </div>

        <br>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-warning">Cadastrar</button>
        </div>
    </form>




</div>










</div>

  </div>
</div>
