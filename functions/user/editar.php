<?php
session_start();
require_once(__DIR__ . '/../../banco.php');
require_once(__DIR__ . '/../funcoes.php');

// Verifica se foi enviado o ID do usuário
if (empty($_POST['id_usuario'])) {
    $_SESSION['msg'] = 'ID de usuário não especificado.';
    header('Location: /logintemplate/views/user/list.php');
    exit;
}

$id = $_POST['id_usuario'];

try {
    $pdo->beginTransaction();

    // Atualizar dados pessoais
    $sqlUsuario = "UPDATE tb_usuario SET 
        nome = :nome, 
        email = :email, 
        data_nascimento = :data_nascimento, 
        telefone = :telefone,
        celular = :celular
        WHERE id_usuario = :id";
    $stmtUsuario = $pdo->prepare($sqlUsuario);
    $stmtUsuario->execute([
        ':nome' => $_POST['nome'],
        ':email' => $_POST['email'],
        ':data_nascimento' => $_POST['data_nascimento'],
        ':telefone' => $_POST['telefone'] ?? '',
        ':celular' => $_POST['celular'] ?? '',
        ':id' => $id
    ]);

    // Atualizar endereço
    $sqlEndereco = "UPDATE tb_endereco SET 
        cep = :cep, 
        logradouro = :logradouro, 
        numero = :numero, 
        complemento = :complemento, 
        cidade = :cidade, 
        bairro = :bairro,
        referencia = :referencia
        WHERE id_usuario = :id";
    $stmtEndereco = $pdo->prepare($sqlEndereco);
    $stmtEndereco->execute([
        ':cep' => $_POST['cep'],
        ':logradouro' => $_POST['logradouro'],
        ':numero' => $_POST['numero'],
        ':complemento' => $_POST['complemento'],
        ':cidade' => $_POST['cidade'],
        ':bairro' => $_POST['bairro'],
        ':referencia' => $_POST['referencia'],
        ':id' => $id
    ]);

    // Atualizar documentos
    $sqlDocumento = "UPDATE tb_documento SET 
        cpf = :cpf, 
        rg = :rg, 
        cnh = :cnh 
        WHERE id_usuario = :id";
    $stmtDocumento = $pdo->prepare($sqlDocumento);
    $stmtDocumento->execute([
        ':cpf' => $_POST['cpf'],
        ':rg' => $_POST['rg'],
        ':cnh' => $_POST['cnh'],
        ':id' => $id
    ]);

    // Atualizar foto (se enviada)
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $fotoTmp = $_FILES['foto']['tmp_name'];
        $fotoNome = uniqid('foto_') . '.' . pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $destino = '../../uploads/' . $fotoNome;

        if (move_uploaded_file($fotoTmp, $destino)) {
            $sqlFoto = "UPDATE tb_usuario SET foto = :foto WHERE id_usuario = :id";
            $stmtFoto = $pdo->prepare($sqlFoto);
            $stmtFoto->execute([
                ':foto' => $destino,
                ':id' => $id
            ]);
        }
    }

    $pdo->commit();
    registraMovimentacao($_SESSION['id_usuario'], $id, 'Usuario editado por admin: ' . $_SESSION['id_usuario'], 'Usuario editado', $pdo);

    header("Location: /logintemplate/views/user/edit.php?id=$id&msgSucesso=Editado com sucesso!");
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    header("Location: /logintemplate/views/user/edit.php?id=$id&msgErro=$e.");
    exit;
}
