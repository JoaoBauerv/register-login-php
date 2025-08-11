<?php 
include '../../components/sidebar.php'; 

// Consulta os dados do usuário
$sqlUsuario = "SELECT * FROM tb_usuario WHERE id_usuario = ?";
$stmtUsuario = $pdo->prepare($sqlUsuario);
$stmtUsuario->execute([$_REQUEST['id']]);
$usuario = $stmtUsuario->fetch(PDO::FETCH_ASSOC);

// Consulta endereço
$sqlEndereco = "SELECT * FROM tb_endereco WHERE id_usuario = ?";
$stmtEndereco = $pdo->prepare($sqlEndereco);
$stmtEndereco->execute([$_REQUEST['id']]);
$endereco = $stmtEndereco->fetch(PDO::FETCH_ASSOC);

// Consulta documentos
$sqlDocumento = "SELECT * FROM tb_documento WHERE id_usuario = ?";
$stmtDocumento = $pdo->prepare($sqlDocumento);
$stmtDocumento->execute([$_REQUEST['id']]);
$documento = $stmtDocumento->fetch(PDO::FETCH_ASSOC);

// Cálculo da idade
$age = '';
if (!empty($usuario['data_nascimento'])) {
    $tz = new DateTimeZone('America/Sao_Paulo');
    $age = DateTime::createFromFormat('Y-m-d', $usuario['data_nascimento'], $tz)
        ->diff(new DateTime('now', $tz))
        ->y;
}

?>

<div class="container-fluid p-5">
    <h3 class="fw-bold text-uppercase mb-4"><?= htmlspecialchars($usuario['nome']) ?> <span class="fw-normal">:: Editar dados</span></h3>
    
            <?php require_once '../../components/alert.php'; 
            
            ?>

    
        <form method="POST" action="/logintemplate/functions/user/editar.php" enctype="multipart/form-data">
        <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">

        <div class="row g-4">
            <!-- Foto -->
            <div class="col-md-3 text-center">
                <div class="border p-3 bg-light rounded">
                    <img src="<?= $usuario['foto'] ?>" alt="Avatar" class="img-fluid rounded" onerror="this.style.display='none'">
                    <div class="mt-2">
                        <input type="file" name="foto" class="form-control mb-2">
                        <button class="btn btn-outline-danger btn-sm w-100" type="submit" name="excluir_foto">Excluir foto</button>
                    </div>
                </div>

                <!--Editar permissao do usuario -->
                <div class="border p-3 bg-light rounded">
                    <label class="form-label d-block mb-2">Permissão</label>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" name="permissao" id="permissaoAdmin" value="Admin" <?= ($usuario['permissao'] === 'Admin') ? 'checked' : '' ?>>
                        <label class="form-check-label" for="permissaoAdmin">Admininistrador</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" name="permissao" id="permissaoGerente" value="Gerente" <?= ($usuario['permissao'] === 'Gerente') ? 'checked' : '' ?>>
                        <label class="form-check-label" for="permissaoGerente">Gerente</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" name="permissao" id="permissaoUsuario" value="Usuario" <?= ($usuario['permissao'] === 'Usuario') ? 'checked' : '' ?>>
                        <label class="form-check-label" for="permissaoUsuario">Usuario</label>
                    </div>
                </div>
                
                <!-- Inativar ou ativar usuario -->
                <div class="border p-3 bg-light rounded">
                    <label class="form-label d-block mb-2">Status</label>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" name="status" id="statusAtivo" value="ativo" <?= ($usuario['status'] === 1) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="statusAtivo">Ativo</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" name="status" id="statusInativo" value="inativo" <?= ($usuario['status'] === 0) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="statusInativo">Inativo</label>
                    </div>
                </div>
            
            </div>


            <!-- Tabs -->
            <div class="col-md-9">
                <ul class="nav nav-tabs" id="meusDadosTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pessoais" type="button">Dados Pessoais</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#documentos" type="button">Documentos</button>
                    </li>
                </ul>

                <div class="tab-content border border-top-0 p-4 bg-white">
                    <!-- Dados pessoais -->
                    <div class="tab-pane fade show active" id="pessoais">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nome</label>
                                <input type="text" class="form-control" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($usuario['email']) ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Data nasc.</label>
                                <input type="date" class="form-control" name="data_nascimento" value="<?= $usuario['data_nascimento'] ?>">
                            </div>

                            <div class="col-md-3">
                            <label class="form-label">Data nasc.</label>
                            <input type="text" class="form-control" value="<?= $age ?>" readonly>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label">Telefone</label>
                                <input type="text" class="form-control" name="telefone" value="<?= htmlspecialchars($usuario['telefone'] ?? '') ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Celular</label>
                                <input type="text" class="form-control" name="celular" value="<?= htmlspecialchars($usuario['celular'] ?? '') ?>">
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5>Endereço</h5>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">CEP</label>
                                <input type="text" class="form-control" name="cep" value="<?= $endereco['cep'] ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Logradouro</label>
                                <input type="text" class="form-control" name="logradouro" value="<?= $endereco['logradouro'] ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Número</label>
                                <input type="text" class="form-control" name="numero" value="<?= $endereco['numero'] ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Complemento</label>
                                <input type="text" class="form-control" name="complemento" value="<?= $endereco['complemento'] ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Cidade</label>
                                <input type="text" class="form-control" name="cidade" value="<?= $endereco['cidade'] ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Bairro</label>
                                <input type="text" class="form-control" name="bairro" value="<?= $endereco['bairro'] ?>">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Referência</label>
                                <input type="text" class="form-control" name="referencia" value="<?= $endereco['referencia'] ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Documentos -->
                    <div class="tab-pane fade" id="documentos">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">CPF</label>
                                <input type="text" class="form-control" name="cpf" value="<?= $documento['cpf'] ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">RG</label>
                                <input type="text" class="form-control" name="rg" value="<?= $documento['rg'] ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">CNH</label>
                                <input type="text" class="form-control" name="cnh" value="<?= $documento['cnh'] ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botões -->
                <div class="mt-4 d-flex justify-content-end">
                    <a href="/logintemplate/views/user/admin.php" class="btn btn-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </div>
        </div>
    </form>
</div>
