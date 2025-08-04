<!-- Sidebar -->
<?php include '../../components/sidebar.php'; ?>

<?php if (!empty($dados_usuario['admin'])): ?>
<div class="container py-5">
  <!-- Título e botão -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-semibold text-dark">👤 Painel de Administração</h2>
    <a href="/logintemplate/views/user/register.php" class="btn btn-success shadow-sm">
      <i class="bi bi-person-plus-fill me-1"></i> Novo Usuário
    </a>
  </div>

  <!-- Alerta -->
  <?php require_once '../../components/alert.php'; ?>

  <!-- Tabela -->
  <div class="card shadow-sm border-0">
    <div class="card-body p-0">
      <?php
        $stmt = $pdo->prepare("SELECT * FROM tb_usuario WHERE status = 1 ORDER BY id_usuario");
        $stmt->execute();
        $rowCount = $stmt->rowCount();

        if ($rowCount > 0):
      ?>
      <div class="table-responsive">
        <table class="table table-borderless align-middle mb-0">
          <thead class="bg-primary text-white sticky-top">
            <tr>
              <th scope="col">#</th>
              <th scope="col">Usuário</th>
              <th scope="col">Nome</th>
              <th scope="col" class="text-center">Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <tr class="border-bottom">
              <td class="fw-medium text-muted"><?= htmlspecialchars($row["id_usuario"]) ?></td>
              <td><?= htmlspecialchars($row["usuario"]) ?></td>
              <td><?= htmlspecialchars($row["nome"]) ?></td>
              <td class="text-center">
                <a href="/logintemplate/views/user/edit.php?id=<?= $row["id_usuario"] ?>" class="btn btn-sm btn-warning me-1">
                  <i class="bi bi-pencil"></i>
                </a>
                <a href="/logintemplate/functions/user/resetarsenha.php?id=<?= $row["id_usuario"] ?>" class="btn btn-sm btn-primary me-1">
                  <i class="bi bi-key"></i>
                </a>
                <form action="/logintemplate/functions/user/deletar.php?id=<?= $row["id_usuario"] ?>" method="post" class="d-inline">
                  <button type="submit" class="btn btn-sm btn-danger">
                    <i class="bi bi-trash3"></i>
                  </button>
                </form>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
      <?php else: ?>
        <div class="text-center p-4 text-muted">Nenhum usuário encontrado.</div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php else: ?>
<!-- Página bloqueada para não-admins -->
<div class="container-fluid min-vh-100 d-flex justify-content-center align-items-center bg-light">
  <div class="text-center bg-white p-5 shadow rounded" style="max-width: 500px;">
    <h4 class="text-danger mb-3"><i class="bi bi-shield-lock-fill"></i> Acesso Negado</h4>
    <p class="text-muted">Essa página não está disponível para o seu usuário.</p>
    <a href="/logintemplate/index.php" class="btn btn-primary mt-3">Voltar</a>
  </div>
</div>
<?php endif; ?>
