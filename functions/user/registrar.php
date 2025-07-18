<?php
require_once(__DIR__ . '/../../banco.php');
session_start();
require_once(__DIR__ . '/../funcoes.php');
// var_dump($_POST);
// echo '<br>';
// var_dump($_SESSION);
// exit;


if(empty($_POST['admin'])){
$nomeCompleto = ucwords(strtolower($_SESSION['nome_completo'] ?? ''));
$usuario = $_SESSION['usuario'] ?? '';
$email = $_SESSION['email'] ?? '';
$senha = $_SESSION['senha'] ?? '';
$foto = $_SESSION['foto_nome'] ?? '';
$data = $_SESSION['data'] ?? '';
}else {

$nomeCompleto = ucwords(strtolower($_POST['nome_completo'] ?? ''));
$usuario = $_POST['usuario'] ?? '';
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';
$foto = $_POST['foto_nome'] ?? '';
$data = $_POST['data'] ?? '';


}





$nome_final_arquivo = $usuario . '_' . $foto;
$url_arquivo = '/logintemplate/images/user/' . $nome_final_arquivo;

// var_dump($usuario);
// var_dump($_SESSION['foto_nome']);
// var_dump($nome_final_arquivo);

// Grava no banco
try {
    $sql = "INSERT INTO tb_usuario (nome, email, senha, foto, usuario, data_nascimento) 
            VALUES (:nome, :email, :senha, :foto, :usuario, :data)";
    $stmt = $pdo->prepare($sql);

    $dados = array(
        ':nome' => $nomeCompleto,
        ':email' => $email,
        ':senha' => password_hash($senha, PASSWORD_DEFAULT),
        ':foto' => $url_arquivo,
        ':usuario' => $usuario,
        ':data' => $data
    );

    // Verifica se foi enviado via POST (admin logado cadastrando outro)
    $admin = $_POST['admin'] ?? null;

    if ($stmt->execute($dados)) {
        if (empty($admin)) {
            // Cadastro por usuário comum
            unset($_SESSION['nome'], $_SESSION['sobrenome'], $_SESSION['email'], $_SESSION['senha'], $_SESSION['foto']);
            session_destroy();
            header("Location: ../../views/user/login.php?msgSucesso=Cadastro realizado com sucesso! Realize o login agora!");
        } else {
            // Cadastro feito por admin logado
            header("Location: ../../index.php?msgSucesso=Cadastro realizado com sucesso!");
        }
    } else {
        header("Location: ../../views/user/register.php?msgErro=Erro ao executar o cadastro.");
    }

} catch (PDOException $e) {
    header("Location: ../../views/user/register.php?msgErro=Erro de banco de dados.");
}

exit;

?>
