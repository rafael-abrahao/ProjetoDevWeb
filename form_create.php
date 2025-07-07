<?php
    require_once "connect.php";
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if(!isset($_SESSION["usuario_id"])){
        header("Location: login.php");
        exit;
    }
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        $form_nome = $_POST["formNome"];
        $autor = $_SESSION["usuario_id"];

        $stmt = $conn->prepare("INSERT INTO formularios (nome, id_autor) VALUES (?, ?)");
        if ($stmt === false) {
            die("Erro na preparação: " . $conn->error);
        }
        $stmt->bind_param("ss", $form_nome, $autor);
        if($stmt->execute()){
            $_SESSION["form_id_create"] = $conn->insert_id;
            $_SESSION["form_nome_create"] = $form_nome;
            $stmt->close();
            $conn->close();
            header("Location: quest_create.php");
        }else{
            $stmt->close();
            $conn->close();
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Form Factory - Entrar</title>
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
        <?php include "navbar.php";?>
        <div class="container d-flex justify-content-center align-items-center center-container">
            <form class="border p-5 rounded shadow bg-light" style="min-width: 350px;" action="" method="post">
                <div class="mb-4">
                    <input type="text" class="form-control form-control-lg" style="width: 700px;" id="formNome" name="formNome" placeholder="Nome do formulário..." maxlength="255" required>
                </div>

                <div class="d-flex justify-content-end gap-3 mb-3">
                    <button type="submit" class="btn btn-primary">Criar</button>
                    <a class="btn btn-secondary" href="index.php">Cancelar</a>
                </div>
                <?php
                    if(isset($_SESSION["mensagem"])){
                        if($_SESSION["mensagem"] === "sucesso_create"){
                            echo "  <div class='alert alert-success' role='alert'>
                                        Formulário publicado com sucesso!
                                    </div>";
                            unset($_SESSION["mensagem"]);
                        }
                    }
                ?>
            </form>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    </body>
</html>