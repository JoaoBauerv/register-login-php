<?php

// endereco
// nome do BD
// usuario
// senha

$endereco = 'localhost';
$banco = 'logintemplate';
$usuario = 'root';
$senha = '';

try {
  // sgbd:host;port;dbname
  // usuario
  // senha
  // errmode
  $pdo = new PDO("mysql:host=$endereco;port=3306;dbname=$banco", $usuario, $senha, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

  //echo "Conectado no banco de dados!!!";

} catch (PDOException $e) {
  echo "Falha ao conectar ao banco de dados. <br/>";
  die($e->getMessage());
}


?>