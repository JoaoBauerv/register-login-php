<?php
session_start();
session_destroy();
header("Location: ../../index.php?msgSucesso=Logout realizado com sucesso!");;