<?php
session_start();
require_once(__DIR__ . '/../../banco.php');

if (!empty($_POST)) {
    // Pegando os dados enviados pelo formulário
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Preparar a query (protege contra SQL Injection)
    $stmt = $pdo->prepare("SELECT * FROM tb_usuario WHERE usuario = :usuario");
    $stmt->bindParam(':usuario', $usuario);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se o usuário existe e a senha está correta
    if ($user && password_verify($senha, $user['senha'])) {
      $_SESSION['usuario'] = $_POST['usuario'];
      header("Location: ../../index.php?msgSucesso=Login realizado com sucesso!");
      exit;

    } else {
        echo "Usuário ou senha inválidos!";
    }
}

?>