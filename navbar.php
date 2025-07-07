<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $curr_page = $_SERVER['REQUEST_URI'];
?>
<nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <img src="imgs/logo.png" alt="Logo" width="30" height="24" class="d-inline-block align-text-top"
                style="width: 250px;height: 75px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarItens"
            aria-controls="navbarItens" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarItens">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <?php
                        if($curr_page == "/" || $curr_page == "/index.php"){
                            echo "<a class='nav-link active' aria-current='page' href='javascript:void(0)'>Início</a>";
                        }else{
                            echo "<a class='nav-link' href='index.php'>Início</a>";
                        }
                    ?>
                </li>
                <?php
                    if(isset($_SESSION["usuario_id"])){
                        echo "  <li class='nav-item'>
                                    <a class='nav-link' href='profile.php'>".$_SESSION["usuario_nome"]."</a>
                                </li>
                                <li class='nav-item'>
                                    <a class='nav-link' href='logout.php'>Sair</a>
                                </li>";
                    }else{
                        echo "<li class='nav-item'>";
                        if($curr_page == "/signup.php"){
                            echo "<a class='nav-link active' aria-current='page' href='javascript:void(0)'>Cadastrar</a>";
                        }else{
                            echo "<a class='nav-link' href='signup.php'>Cadastrar</a>";
                        }
                        echo "</li>
                              <li class='nav-item'>";
                        if($curr_page == "/login.php"){
                            echo "<a class='nav-link active' aria-current='page' href='javascript:void(0)'>Entrar</a>";
                        }else{
                            echo "<a class='nav-link' href='login.php'>Entrar</a>";
                        }
                        echo "</li>";
                    }
                ?>
            </ul>
        </div>
    </div>
</nav>