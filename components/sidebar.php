<?php
require_once(__DIR__ . '/../banco.php');
session_start();

//var_dump($dados_usuario);
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LOGIN TEMPLATE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    
    <style> 
        .sidebar {
        height: 100vh; 
        background-color: #1c1c1c; 
        color: white;
        padding: 1rem;
        }

        html, body {
            height: 100%;
        }

        body {
            display: flex;
            flex-direction: column;
            
        }

        td {
        border: 4px solid #333;
        width: 200px;
        }

        thead,
        tfoot {
        background-color: #333;
        color: #fff;
        }

        main {
            flex: 1; 
        }

        .nav-link:hover {
            color:rgb(6, 87, 248) !important; 
        }

        select {
        color:#333 /* Cor da fonte dentro do select */
        
        }



        .scFormPage .select2-container .select2-dropdown {
            border-color:rgb(255, 255, 255) !important;
        }

        .select2-dropdown {
            border-radius: 0 0 10px 10px !important;
            overflow: hidden !important;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            border-color:rgb(255, 255, 255) ;
            border-radius: 10px !important;
        }



    </style>



</head>


<body>
    <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
    </svg>
    

    <main class="d-flex flex-nowrap">
        

                    <div class="d-flex">
                    <div class="sidebar d-flex flex-column p-3 text-white bg-dark" style="width: 250px; height: 100vh;">
                        <h5>LOGIN TEMPLATE</h5>
                            <ul class="nav nav-pills flex-column mb-auto p-3 bg-dark text-white rounded shadow">
                                <li class="nav-item mb-2">
                                    <a href="/logintemplate/index.php" class="nav-link active text-white bg-primary">
                                        Home
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <button class="btn btn-outline-light w-100 text-start" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#sidebarMenuLinks"
                                            aria-expanded="false" aria-controls="sidebarMenuLinks">
                                        #
                                    </button>

                                    <div class="collapse mt-2" id="sidebarMenuLinks">
                                        <ul class="nav flex-column">
                                            <li class="nav-item">
                                                <a href="#" class="nav-link text-white ps-4">#</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#" class="nav-link text-white ps-4">#</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#" class="nav-link text-white ps-4">#</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#" class="nav-link text-white ps-4">#</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>

                        <div class="mt-auto">

                        <?php if (!empty($_SESSION['usuario'])){ ?>
                            <div class="dropdown">

                                    <?php
                                        $usuario = $_SESSION['usuario'];

                                        // Buscar todas as informações do usuário no banco
                                        $stmt = $pdo->prepare("SELECT * FROM tb_usuario WHERE usuario = :usuario");
                                        $stmt->bindParam(':usuario', $usuario);
                                        $stmt->execute();
                                        $dados_usuario = $stmt->fetch(PDO::FETCH_ASSOC); // $dados_usuario será um array associativo com os dados do usuário

                                        // Buscar a foto do usuário no banco
                                        $stmt = $pdo->prepare("SELECT foto FROM tb_usuario WHERE usuario = :usuario");
                                        $stmt->bindParam(':usuario', $dados_usuario['usuario']);
                                        $stmt->execute();
                                        $foto = $stmt->fetchColumn(); // Retorna só o valor da coluna

                                        // Caminho padrão se não houver foto no banco
                                        $foto_usuario = !empty($foto) ? $foto : '/logintemplate/images/avatar.png';
                                    
                                    ?>

                                    <a href=""
                                    class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                                    <img src="<?php echo $foto_usuario; ?>" alt="" width="32" height="32" class="rounded-circle me-2">
                                    <strong><?php echo htmlspecialchars($dados_usuario['nome']); ?></strong>
                                    </a>
                                <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                                <?php if (!empty($dados_usuario['admin'])){ ?>    
                                    <li>
                                        <a class="dropdown-item" href="/logintemplate/views/user/admin.php">Admin</a>
                                    </li>
                                <?php } ?> 
                                    <li>
                                        <a class="dropdown-item" href="#">Settings</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="/logintemplate/views/user/perfil.php">Profile</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href='/logintemplate/functions/user/logout.php'>Sign out</a>
                                    </li>
                                </ul>
                            </div>
                        <?php }else{ ?>
                            <div class="text-center">
                                <a href="/logintemplate/views/user/login.php" class="btn btn-primary btn-sm me-2">Login</a>
                                <a href="/logintemplate/views/user/register.php" class="btn btn-warning btn-sm me-2">Registrar-se</a>
                            </div>
                        <?php }; ?>
                        
                        </div>
                    </div>
                    </div>

                    <div class="content d-flex justify-content-center" style="flex: 1;">
                        
                    
                

                
            
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>
</html>