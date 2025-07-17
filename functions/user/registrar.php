<?php
require_once(__DIR__ . '/../../banco.php');
session_start();
require_once(__DIR__ . '/../funcoes.php');
//var_dump($_SESSION);

$nomeCompleto = ucwords(strtolower($_SESSION['nome_completo'] ?? ''));
$usuario = $_SESSION['usuario'] ?? '';
$email = $_SESSION['email'] ?? '';
$senha = $_SESSION['senha'] ?? '';
$foto = $_SESSION['foto_nome'] ?? '';



$nome_final_arquivo = $usuario . '_' . $foto;
$url_arquivo = '/logintemplate/images/user/' . $nome_final_arquivo;

// var_dump($usuario);
// var_dump($_SESSION['foto_nome']);
// var_dump($nome_final_arquivo);

// Grava no banco
try {
    $sql = "INSERT INTO tb_usuario (nome, email, senha, foto, usuario) 
            VALUES (:nome, :email, :senha, :foto, :usuario)";
    $stmt = $pdo->prepare($sql);

    $dados = array(
        ':nome' => $nomeCompleto,
        ':email' => $email,
        ':senha' => password_hash($senha, PASSWORD_DEFAULT),
        ':foto' => $url_arquivo,
        ':usuario' => $usuario
    );

    if ($stmt->execute($dados)) {
        unset($_SESSION['nome'], $_SESSION['sobrenome'], $_SESSION['email'], $_SESSION['senha'], $_SESSION['foto']);
        session_destroy();
       header("Location: ../../views/user/login.php?msgSucesso=Cadastro realizado com sucesso! Realize o login agora!");
    } else {
        header("Location: ../../views/user/register.php?msgErro=Erro ao executar o cadastro.");
    }
} catch (PDOException $e) {
   header("Location: ../../views/user/register.php?msgErro=Erro de banco de dados.");
    

}
exit;
?>
