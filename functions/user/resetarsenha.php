<?php 

require_once(__DIR__ . '/../../banco.php');


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

header("Location: ../../views/user/admin.php?msgSucesso=Senha do usuario ".$usuario['usuario']." resetada!");

}else {
    header("Location: ../../index.php?msgErro=Não foi possivel realizar esta ação!");

}



?>