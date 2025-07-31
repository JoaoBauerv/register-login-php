<?php

function removerAcentos($string) {
  $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
  $string = preg_replace('`[^a-zA-Z0-9]`', '', $string); //Remove caracteres não alfanuméricos
  return $string;
}


function validaCPF($cpf) {
 
    // Extrai somente os números
    $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
     
    // Verifica se foi informado todos os digitos corretamente
    if (strlen($cpf) != 11) {
        return false;
    }

    // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    // Faz o calculo para validar o CPF
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    return true;

}

function registraMovimentacao($id_cadastrou, $id_cadastrado, $descricao, $tipo, $pdo){

$sql = "INSERT INTO tb_registro_movimento (id_usuario_cadastrou, id_cadastrado, descricao, tipo, data) VALUES (:id_cadastrou, :id_cadastrado, :descricao, :tipo, :data)";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id_cadastrou', $id_cadastrou, PDO::PARAM_STR);
$stmt->bindValue(':id_cadastrado', $id_cadastrado, PDO::PARAM_STR);
$stmt->bindValue(':descricao', $descricao, PDO::PARAM_STR);
$stmt->bindValue(':tipo', $tipo, PDO::PARAM_STR);
$stmt->bindValue(':data', date('Y-m-d H:i:s'), PDO::PARAM_STR);
$stmt->execute();


}

?>