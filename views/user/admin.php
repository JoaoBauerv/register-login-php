<!-- Sidebar -->
<?php 

include '../../components/sidebar.php'; 

?>

<?php 
if(!empty($dados_usuario['admin'])){
?>
<!-- Conteúdo principal -->
<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold">PAINEL ADMIN</h1>
    <a href="#" class="btn btn-success">Cadastrar Usuario</a>
  </div>

  <div class="table-responsive">
    <?php
      $stmt = $pdo->prepare("SELECT * FROM tb_usuario ORDER BY id_usuario;");
      $stmt->execute();
      $rowCount = $stmt->rowCount();

      if ($rowCount > 0) {
          echo "<table class='table table-bordered table-striped'>";
          echo "<thead class='table-dark'>";
          echo "<tr><th>Id</th><th>Usuario</th><th>Nome Usuario</th><th>Opções</th></tr>";
          echo "</thead>";
          echo "<tbody>";

          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              echo "<tr>";
              echo "<td>" . htmlspecialchars($row["id_usuario"]) . "</td>";
              echo "<td>" . htmlspecialchars($row["usuario"]) . "</td>";
              echo "<td>" . htmlspecialchars($row["nome"]) . "</td>";
              echo "<td>";
              echo '<a href="edit.php?id=' . $row["id_usuario"] . '" class="btn btn-warning btn-sm me-2">Editar</a>';
              echo '<form action="/CarManager/functions/carro/deletar.php?id=' . $row["id_usuario"] . '" method="post" class="d-inline">';
              echo '<button type="submit" class="btn btn-danger btn-sm">Excluir</button>';
              echo '</form>';
              echo "</td>";
              echo "</tr>";
          }

          echo "</tbody>";
          echo "</table>";
      } else {
          echo "<p class='text-muted'>Nenhuma categoria cadastrada.</p>";
      }
    ?>
  </div>
</div>
<?php
}else{ ?>  

<div class="container-fluid min-vh-100 d-flex justify-content-center align-items-center">
  <div class="d-flex flex-column align-items-center gap-3" style="width: 100%; max-width: 500px;">
    <div class="w-100">
      
      <?php echo "<div class='alert alert-danger mx-auto w-100 text-center'>ESSA PÁGINA NÃO ESTA DISPONÍVEL PARA SEU USUÁRIO</div>";?>
    
    </div>
  </div>
</div>

<?php }?>
 