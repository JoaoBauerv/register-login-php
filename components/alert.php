
<?php

if (isset($_GET['msgSucesso'])) {
$mensagem = htmlspecialchars($_GET['msgSucesso']);
echo "<div class='alert alert-success mx-auto w-100 text-center'>$mensagem</div>";
}

if (isset($_GET['msgErro'])) {
$mensagem = htmlspecialchars($_GET['msgErro']);
echo "<div class='alert alert-danger mx-auto w-100 text-center'>$mensagem</div>";
} 
?>  
