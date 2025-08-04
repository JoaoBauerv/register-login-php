<?php
session_start();
require_once(__DIR__ . '/../../banco.php');
require_once(__DIR__ . '/../funcoes.php');

var_dump($_REQUEST);
// echo '<br>';
// var_dump($_SESSION);



$nomeCompleto = ucwords(strtolower($_REQUEST['nome_completo'] ?? ''));
$usuario = $_REQUEST['usuario'] ?? '';
$email = $_REQUEST['email'] ?? '';
$senha = $_REQUEST['senha'] ?? '';
$foto = $_REQUEST['foto_nome'] ?? '';
$data = $_REQUEST['data'] ?? '';
$admin = $_REQUEST['admin'] ?? '';
$permissao = $_REQUEST['permissao'] ?? 'usuario';



$nome_final_arquivo = $usuario . '_' . $foto;
$url_arquivo = '/logintemplate/images/user/' . $nome_final_arquivo;

// var_dump($usuario);
// var_dump($_SESSION['foto_nome']);
// var_dump($nome_final_arquivo);

// Grava no banco
try {
    $sql = "INSERT INTO tb_usuario (nome, email, senha, foto, usuario, data_nascimento, permissao) 
            VALUES (:nome, :email, :senha, :foto, :usuario, :data, :permissao)";
    $stmt = $pdo->prepare($sql);

    $dados = array(
        ':nome' => $nomeCompleto,
        ':email' => $email,
        ':senha' => password_hash($senha, PASSWORD_DEFAULT),
        ':foto' => $url_arquivo,
        ':usuario' => $usuario,
        ':data' => $data,
        ':permissao' => $permissao
    );

    // Verifica se foi enviado via POST (admin logado cadastrando outro)

    if ($stmt->execute($dados)) {
        $sql = "SELECT * FROM tb_usuario ORDER BY id_usuario DESC LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $id_cadastrado = $stmt->fetch(PDO::FETCH_ASSOC);

        $sql = "INSERT INTO tb_documento (id_usuario) VALUES (:id_cadastrado)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id_cadastrado', $id_cadastrado['id_usuario'], PDO::PARAM_STR);
        $stmt->execute();

        
        $sql = "INSERT INTO tb_endereco (id_usuario) VALUES (:id_cadastrado)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id_cadastrado', $id_cadastrado['id_usuario'], PDO::PARAM_STR);
        $stmt->execute();

        
        if (empty($admin)) {
            // Cadastro por usuário comum
            header("Location: ../../views/user/login.php?msgSucesso=Cadastro realizado com sucesso! Realize o login agora!");
        } else {
            // Cadastro feito por admin logado
 

            registraMovimentacao($admin, $id_cadastrado['id_usuario'], 'Usuario criado por admin: ' . $admin, 'Cadastro Usuario', $pdo);

            header("Location: ../../views/user/admin.php?msgSucesso=Cadastro realizado com sucesso!");
        }
    } else {
        header("Location: ../../views/user/register.php?msgErro=Erro ao executar o cadastro.");
    }

} catch (PDOException $e) {
    header("Location: ../../views/user/register.php?msgErro=Erro de banco de dados.");
}

exit;

?>
