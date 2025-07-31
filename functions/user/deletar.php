<?php 

require_once(__DIR__ . '/../../banco.php');


if(!empty($_REQUEST['id'])){

$sql = "SELECT * FROM tb_usuario where id_usuario = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $_REQUEST['id'] , PDO::PARAM_INT);
$stmt->execute();

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);


$sql = "UPDATE tb_usuario SET status = 0 where id_usuario = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $_REQUEST['id']);
$stmt->execute();

header("Location: ../../views/user/admin.php?msgErro=Usuario ".$usuario['usuario']." inativado!");

}else {
    header("Location: ../../index.php?msgErro=Não foi possivel realizar esta ação!");

}



?>