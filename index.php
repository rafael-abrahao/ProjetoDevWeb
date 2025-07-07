<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Form Factory - Home</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
        <style>
            html, body {
                height: 100%;
            }
            .center-container {
                height: 70vh;
            }
        </style>
    </head>
    <body class="bg-dark-subtle">
        <?php include 'navbar.php';?>
        <div class="container d-flex align-items-center justify-content-center flex-column">
            <?php
                if(isset($_SESSION["mensagem"]) && $_SESSION["mensagem"] === "sucesso_resposta"){
                    echo "  <div class='alert alert-success mt-3' role='alert'>
                                Respostas enviadas!
                            </div>";
                    unset($_SESSION["mensagem"]);
                }
                echo "<div class='d-flex justify-content-center align-items-center center-container'>";
                if(isset($_SESSION["usuario_id"])){
                    echo "  <form class='d-flex gap-3'>
                                <button 
                                    type='submit' 
                                    class='btn btn-primary btn-lg' 
                                    formaction='form_create.php' 
                                    formmethod='get'>
                                Criar Formulário
                                </button>

                                <button 
                                    type='submit' 
                                    class='btn btn-success btn-lg' 
                                    formaction='form_search.php' 
                                    formmethod='get'>
                                Responder Formulário
                                </button>

                            </form>";
                }else{
                    echo "
                            <form class='text-center'>
                                <button 
                                    type='submit' 
                                    class='btn btn-primary btn-lg w-100 mb-3' 
                                    formaction='login.php' 
                                    formmethod='get'>
                                Entrar
                                </button>
                            </form>";
                }
            ?>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    </body>
</html>