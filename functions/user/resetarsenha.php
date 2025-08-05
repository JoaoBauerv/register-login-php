<?php 
session_start();
require_once(__DIR__ . '/../../banco.php');
require_once(__DIR__ . '/../funcoes.php');

// Buscar todas as informações do usuário no banco
$stmt = $pdo->prepare("SELECT * FROM tb_usuario WHERE usuario = :usuario");
$stmt->bindParam(':usuario', $usuario);
$stmt->execute();
$dados_usuario = $stmt->fetch(PDO::FETCH_ASSOC); // $dados_usuario será um array associativo com os dados do usuário


if(!empty($_REQUEST['id'])){
$sql = "SELECT usuario, data_nascimento FROM tb_usuario where id_usuario = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $_REQUEST['id'] , PDO::PARAM_INT);
$stmt->execute();

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);



$resetSenha =  $usuario['usuario'] . date("dm", strtotime($usuario['data_nascimento']));


$sql = "UPDATE tb_usuario SET senha = :senha where id_usuario = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $_REQUEST['id']);
$stmt->bindValue(':senha', password_hash($resetSenha, PASSWORD_DEFAULT));
$stmt->execute();

registraMovimentacao($_SESSION['id_usuario'], $_REQUEST['id'], 'Senha resetada por admin: ' . $_SESSION['id_usuario'], 'Senha resetada', $pdo);

header("Location: ../../views/user/admin.php?msgSucesso=Senha do usuario ".$usuario['usuario']." resetada!");

}else {
    header("Location: ../../index.php?msgErro=Não foi possivel realizar esta ação!");

}

?>