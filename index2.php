<?php 
date_default_timezone_set('America/Sao_Paulo');
//require_once (__DIR__ . '/components/middleware.php');
include __DIR__ . '/components/sidebar.php'; 

// var_dump($_SESSION);
?>

<div class="container-fluid min-vh-100 d-flex justify-content-center align-items-center bg-light">
    <div class="card shadow-lg border-0 p-4 w-100" style="max-width: 800px; border-radius: 1rem;">
        <div class="text-center mb-4">
            <h1 class="fw-bold text-primary">👋 Bem-vindo, <?= htmlspecialchars($dados_usuario['nome'] ?? 'Visitante') ?>!</h1>
            <p class="text-muted fs-5">Estamos felizes em ter você de volta ao sistema.</p>
        </div>

        <!-- Área de métricas / atalhos -->
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="p-3 bg-white rounded shadow-sm h-100">
                    <i class="bi bi-people-fill fs-2 text-primary"></i>
                    <h5 class="mt-2">Usuários</h5>
                    <p class="text-muted mb-0">Gerencie contas do sistema</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-white rounded shadow-sm h-100">
                    <i class="bi bi-gear-fill fs-2 text-success"></i>
                    <h5 class="mt-2">Configurações</h5>
                    <p class="text-muted mb-0">Personalize sua experiência</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-white rounded shadow-sm h-100">
                    <i class="bi bi-info-circle-fill fs-2 text-warning"></i>
                    <h5 class="mt-2">Ajuda</h5>
                    <p class="text-muted mb-0">Saiba como usar o sistema</p>
                </div>
            </div>
        </div>

        <!-- Botão de ação -->
        <div class="text-center mt-4">
            <a href="#" class="btn btn-primary btn-lg shadow-sm">
                <i class="bi bi-arrow-right-circle me-1"></i> Acessar funcionalidades
            </a>
        </div>
    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
