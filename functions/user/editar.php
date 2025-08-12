<?php
session_start();
require_once(__DIR__ . '/../../banco.php');
require_once(__DIR__ . '/../funcoes.php');

unset($_SESSION['msg_erro']);
unset($_SESSION['msg_sucesso']);
// Função para validar e sanitizar dados
function validarDadosUsuario($dados) {
    $errors = [];
    $dadosLimpos = [];

    // Nome
    $nome = trim($dados['nome'] ?? '');
    if (empty($nome)) {
        $errors['nome'] = 'Nome é obrigatório';
    } elseif (strlen($nome) < 2 || strlen($nome) > 100) {
        $errors['nome'] = 'Nome deve ter entre 2 e 100 caracteres';
    } else {
        $dadosLimpos['nome'] = htmlspecialchars($nome, ENT_QUOTES, 'UTF-8');
    }

    // Email
    $email = trim($dados['email'] ?? '');
    if (empty($email)) {
        $errors['email'] = 'Email é obrigatório';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email inválido';
    } else {
        $dadosLimpos['email'] = $email;
    }

    // Data de nascimento
    $dataNascimento = $dados['data_nascimento'] ?? '';
    if (empty($dataNascimento)) {
        $errors['data_nascimento'] = 'Data de nascimento é obrigatória';
    } else {
        $data = DateTime::createFromFormat('Y-m-d', $dataNascimento);
        $hoje = new DateTime();
        
        if (!$data || $data > $hoje) {
            $errors['data_nascimento'] = 'Data de nascimento inválida';
        } elseif ($data->diff($hoje)->y < 18) {
            $errors['data_nascimento'] = 'Usuário deve ter pelo menos 18 anos';
        } else {
            $dadosLimpos['data_nascimento'] = $dataNascimento;
        }
    }

    // Telefones (opcionais)
    $dadosLimpos['telefone'] = preg_replace('/[^0-9]/', '', $dados['telefone'] ?? '');
    $dadosLimpos['celular'] = preg_replace('/[^0-9]/', '', $dados['celular'] ?? '');

    // Status
    $status = $dados['status'] ?? '';
    if (!in_array($status, [ 1, 0 ])) {
        $errors['status'] = 'Status inválido';
    } else {
        $dadosLimpos['status'] = $status;
    }

    // Permissão
    $permissao = $dados['permissao'] ?? '';
    if (!in_array($permissao, ['Admin', 'Usuario', 'Gerente'])) {
        $errors['permissao'] = 'Permissão inválida';
    } else {
        $dadosLimpos['permissao'] = $permissao;
    }

    return ['dados' => $dadosLimpos, 'errors' => $errors];
}

function validarDadosEndereco($dados) {
    $dadosLimpos = [];
    
    // CEP
    $cep = preg_replace('/[^0-9]/', '', $dados['cep'] ?? '');
    if (strlen($cep) === 8) {
        $dadosLimpos['cep'] = $cep;
    } else {
        $dadosLimpos['cep'] = '';
    }
    
    $dadosLimpos['logradouro'] = htmlspecialchars(trim($dados['logradouro'] ?? ''), ENT_QUOTES, 'UTF-8');
    $dadosLimpos['numero'] = htmlspecialchars(trim($dados['numero'] ?? ''), ENT_QUOTES, 'UTF-8');
    $dadosLimpos['complemento'] = htmlspecialchars(trim($dados['complemento'] ?? ''), ENT_QUOTES, 'UTF-8');
    $dadosLimpos['cidade'] = htmlspecialchars(trim($dados['cidade'] ?? ''), ENT_QUOTES, 'UTF-8');
    $dadosLimpos['bairro'] = htmlspecialchars(trim($dados['bairro'] ?? ''), ENT_QUOTES, 'UTF-8');
    $dadosLimpos['referencia'] = htmlspecialchars(trim($dados['referencia'] ?? ''), ENT_QUOTES, 'UTF-8');
    
    return $dadosLimpos;
}

function validarDadosDocumento($dados) {
    $dadosLimpos = [];
    
    // CPF
    $cpf = preg_replace('/[^0-9]/', '', $dados['cpf'] ?? '');
    if (strlen($cpf) === 11 && validarCPF($cpf)) {
        $dadosLimpos['cpf'] = $cpf;
    } else {
        $dadosLimpos['cpf'] = '';
    }
    
    // RG
    $dadosLimpos['rg'] = preg_replace('/[^0-9X]/', '', strtoupper($dados['rg'] ?? ''));
    
    // CNH
    $dadosLimpos['cnh'] = preg_replace('/[^0-9]/', '', $dados['cnh'] ?? '');
    
    return $dadosLimpos;
}

function validarCPF($cpf) {
    // Implementação básica de validação de CPF
    if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }
    
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

function processarUploadFoto($arquivo, $usuario) {
    // Usar a função melhorada do exemplo anterior
    $uploadDir = realpath(__DIR__ . '/../../images/user/') . DIRECTORY_SEPARATOR;
    $allowedTypes = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png', 
        'image/gif' => 'gif',
        'image/webp' => 'webp'
    ];
    $maxSize = 5 * 1024 * 1024; // 5MB
    
    try {
        if (!isset($arquivo['error']) || $arquivo['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Erro no upload do arquivo');
        }
        
        if ($arquivo['size'] > $maxSize) {
            throw new Exception('Arquivo muito grande. Máximo 5MB permitido');
        }
        
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($arquivo['tmp_name']);
        
        if (!array_key_exists($mimeType, $allowedTypes)) {
            throw new Exception('Tipo de arquivo não permitido');
        }
        
        $imageInfo = getimagesize($arquivo['tmp_name']);
        if ($imageInfo === false) {
            throw new Exception('Arquivo não é uma imagem válida');
        }
        
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                throw new Exception('Erro ao criar diretório de upload');
            }
        }
        
        if (!is_writable($uploadDir)) {
            throw new Exception('Diretório sem permissão de escrita');
        }
        
        $extensao = $allowedTypes[$mimeType];
        $nomeArquivo =  $usuario . '_' . time() . '_' . uniqid() . '.' . $extensao;
        $caminhoCompleto = $uploadDir . $nomeArquivo;
        
        if (!move_uploaded_file($arquivo['tmp_name'], $caminhoCompleto)) {
            throw new Exception('Erro ao salvar arquivo no servidor');
        }
        
        return [
            'success' => true,
            'filename' => $nomeArquivo,
            'path' => $caminhoCompleto
        ];
        
    } catch (Exception $e) {
        error_log("Erro no upload: " . $e->getMessage());
        return [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
}

function verificarUsuarioExiste($pdo, $id) {
    $sql = "SELECT COUNT(*) FROM tb_usuario WHERE id_usuario = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    return $stmt->fetchColumn() > 0;
}

function verificarEmailUnico($pdo, $email, $idUsuario) {
    $sql = "SELECT COUNT(*) FROM tb_usuario WHERE email = :email AND id_usuario != :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':email' => $email, ':id' => $idUsuario]);
    return $stmt->fetchColumn() == 0;
}

// CÓDIGO PRINCIPAL
// ================

// Verificar se foi enviado o ID
if (empty($_POST['id_usuario'])) {
    $_SESSION['msg'] = 'ID de usuário não especificado.';
    header('Location: /logintemplate/views/user/admin.php');
    exit;
}

$id = (int)$_POST['id_usuario']; // Cast para inteiro

try {
    // Verificar se usuário existe
    if (!verificarUsuarioExiste($pdo, $id)) {
        throw new Exception('Usuário não encontrado.');
    }
    
    // Validar dados do usuário
    $validacaoUsuario = validarDadosUsuario($_POST);
    if (!empty($validacaoUsuario['errors'])) {
        $errosString = implode(', ', $validacaoUsuario['errors']);
        throw new Exception("Dados inválidos: " . $errosString);
    }
    
    // Verificar se email já existe para outro usuário
    if (!verificarEmailUnico($pdo, $validacaoUsuario['dados']['email'], $id)) {
        throw new Exception('Este email já está sendo usado por outro usuário.');
    }
    
    // Validar outros dados
    $dadosEndereco = validarDadosEndereco($_POST);
    $dadosDocumento = validarDadosDocumento($_POST);
    
    // Iniciar transação
    $pdo->beginTransaction();
    
    // Buscar foto atual para possível remoção
    $sqlFotoAtual = "SELECT foto FROM tb_usuario WHERE id_usuario = :id";
    $stmtFotoAtual = $pdo->prepare($sqlFotoAtual);
    $stmtFotoAtual->execute([':id' => $id]);
    $fotoAtual = $stmtFotoAtual->fetchColumn();
    
    // Atualizar dados pessoais
    $sqlUsuario = "UPDATE tb_usuario SET 
        nome = :nome, 
        email = :email, 
        data_nascimento = :data_nascimento, 
        telefone = :telefone,
        celular = :celular,
        status = :status,
        permissao = :permissao
        WHERE id_usuario = :id";
    $stmtUsuario = $pdo->prepare($sqlUsuario);
    $stmtUsuario->execute([
        ':nome' => $validacaoUsuario['dados']['nome'],
        ':email' => $validacaoUsuario['dados']['email'],
        ':data_nascimento' => $validacaoUsuario['dados']['data_nascimento'],
        ':telefone' => $validacaoUsuario['dados']['telefone'],
        ':celular' => $validacaoUsuario['dados']['celular'],
        ':status' => $validacaoUsuario['dados']['status'],
        ':permissao' => $validacaoUsuario['dados']['permissao'],
        ':id' => $id
    ]);
    
    // Verificar se endereço existe, senão criar
    $sqlVerificaEndereco = "SELECT COUNT(*) FROM tb_endereco WHERE id_usuario = :id";
    $stmtVerificaEndereco = $pdo->prepare($sqlVerificaEndereco);
    $stmtVerificaEndereco->execute([':id' => $id]);
    
    if ($stmtVerificaEndereco->fetchColumn() > 0) {
        // Atualizar endereço existente
        $sqlEndereco = "UPDATE tb_endereco SET 
            cep = :cep, 
            logradouro = :logradouro, 
            numero = :numero, 
            complemento = :complemento, 
            cidade = :cidade, 
            bairro = :bairro,
            referencia = :referencia
            WHERE id_usuario = :id";
    } else {
        // Inserir novo endereço
        $sqlEndereco = "INSERT INTO tb_endereco 
            (id_usuario, cep, logradouro, numero, complemento, cidade, bairro, referencia) 
            VALUES (:id, :cep, :logradouro, :numero, :complemento, :cidade, :bairro, :referencia)";
    }
    
    $stmtEndereco = $pdo->prepare($sqlEndereco);
    $stmtEndereco->execute([
        ':cep' => $dadosEndereco['cep'],
        ':logradouro' => $dadosEndereco['logradouro'],
        ':numero' => $dadosEndereco['numero'],
        ':complemento' => $dadosEndereco['complemento'],
        ':cidade' => $dadosEndereco['cidade'],
        ':bairro' => $dadosEndereco['bairro'],
        ':referencia' => $dadosEndereco['referencia'],
        ':id' => $id
    ]);
    
    // Mesma lógica para documentos
    $sqlVerificaDocumento = "SELECT COUNT(*) FROM tb_documento WHERE id_usuario = :id";
    $stmtVerificaDocumento = $pdo->prepare($sqlVerificaDocumento);
    $stmtVerificaDocumento->execute([':id' => $id]);
    
    if ($stmtVerificaDocumento->fetchColumn() > 0) {
        $sqlDocumento = "UPDATE tb_documento SET 
            cpf = :cpf, 
            rg = :rg, 
            cnh = :cnh 
            WHERE id_usuario = :id";
    } else {
        $sqlDocumento = "INSERT INTO tb_documento 
            (id_usuario, cpf, rg, cnh) 
            VALUES (:id, :cpf, :rg, :cnh)";
    }
    
    $stmtDocumento = $pdo->prepare($sqlDocumento);
    $stmtDocumento->execute([
        ':cpf' => $dadosDocumento['cpf'],
        ':rg' => $dadosDocumento['rg'],
        ':cnh' => $dadosDocumento['cnh'],
        ':id' => $id
    ]);
    
    // Processar upload de foto se enviada
    $novaFoto = null;

    $selecionaUsuario = "SELECT usuario FROM tb_usuario WHERE id_usuario = :id";
    $stmt = $pdo->prepare($selecionaUsuario);
    $stmt->execute([':id' => $id]);

    // Buscar resultado como array associativo
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $resultadoUpload = processarUploadFoto($_FILES['foto'], $usuario['usuario']);
        
        if ($resultadoUpload['success']) {
            $novaFoto = $resultadoUpload['filename'];
            
            // Atualizar foto no banco
            $sqlFoto = "UPDATE tb_usuario SET foto = :foto WHERE id_usuario = :id";
            $stmtFoto = $pdo->prepare($sqlFoto);
            $stmtFoto->execute([
                ':foto' => $novaFoto,
                ':id' => $id
            ]);
            
            // Remover foto antiga se existir
            if ($fotoAtual && $fotoAtual !== $novaFoto) {
                $caminhoFotoAntiga = __DIR__ . '/../../images/user/' . $fotoAtual;
                if (file_exists($caminhoFotoAntiga)) {
                    unlink($caminhoFotoAntiga);
                }
            }
        } else {
            throw new Exception('Erro no upload da foto: ' . $resultadoUpload['error']);
        }
    }
    
    // Commit da transação
    $pdo->commit();
    
    // Registrar movimentação
    if (function_exists('registraMovimentacao')) {
        registraMovimentacao(
            $_SESSION['id_usuario'], 
            $id, 
            'Usuário editado por admin: ' . $_SESSION['id_usuario'], 
            'Usuario editado', 
            $pdo
        );
    }
    
    $_SESSION['msg_sucesso'] = 'Usuário editado com sucesso!';
    header("Location: /logintemplate/views/user/edit.php?id=$id");
    exit;
    
} catch (Exception $e) {
    // Rollback da transação
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    // Log do erro real
    $_SESSION['msg_erro'] = 'Erro ao editar usuário '. $id . '. ' . $e->getMessage();
    
    // Mensagem genérica para o usuário
    
    header("Location: /logintemplate/views/user/edit.php?id=$id");
    exit;
}
?>