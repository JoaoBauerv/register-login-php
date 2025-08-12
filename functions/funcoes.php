<?php
date_default_timezone_set('America/Sao_Paulo');


function removerAcentos($string) {
  $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
  $string = preg_replace('`[^a-zA-Z0-9]`', '', $string); //Remove caracteres não alfanuméricos
  return $string;
}


function registraMovimentacao($id_cadastrou, $id_cadastrado, $descricao, $tipo, $pdo){

$sql = "INSERT INTO tb_registro_movimento (id_usuario_admin, id_usuario_modificado, descricao, tipo, data) VALUES (:id_cadastrou, :id_cadastrado, :descricao, :tipo, :data)";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id_cadastrou', $id_cadastrou, PDO::PARAM_STR);
$stmt->bindValue(':id_cadastrado', $id_cadastrado, PDO::PARAM_STR);
$stmt->bindValue(':descricao', $descricao, PDO::PARAM_STR);
$stmt->bindValue(':tipo', $tipo, PDO::PARAM_STR);
$stmt->bindValue(':data', date('Y-m-d H:i:s'), PDO::PARAM_STR);
$stmt->execute();

}

?>