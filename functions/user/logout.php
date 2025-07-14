<?php
session_start();
session_destroy();
header("Location: ../../views/user/login.php?msgErro=Logout realizado com sucesso!");;