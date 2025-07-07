<?php
    require_once "connect.php";
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if($_SERVER["REQUEST_METHOD"] == "POST"){
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Form Factory - <?php echo $_SESSION["usuario_nome"] ?? "Não Registrado" ?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    </head>
    <body class="bg-dark-subtle">
        <?php include "navbar.php";?>
        <div class="container d-flex justify-content-center align-items-center form-container mt-5 bg-light p-4">
            <div style="min-width: 1000px;">
                <h3 class='fw-semibold mb-5'>Seus formulários:</h3>
                <?php
                    $usuario_id = $_SESSION["usuario_id"];
                    $result = $conn->query("SELECT id, nome FROM formularios WHERE id_autor = ".$usuario_id." AND published = true;");
                    if($result->num_rows > 0){
                        while ($linha = $result->fetch_assoc()){
                            $form_id = $linha["id"];
                            $form_nome = $linha["nome"];
                            echo "
                            <form method='post' action='view_answers.php'>
                                <div class='container d-flex justify-content-start align-items-start gap-3'>
                                    <input type='text' class='form-control' value='".$form_id."' style='width:90px;' id='form_id' name='form_id' readonly>
                                    <input type='hidden' id='form_nome' name='form_nome' value='".$form_nome."'>
                                    <button type='submit' class='btn btn-link text-decoration-none'>".$form_nome."</btn>
                                </div>
                            </form>
                            ";
                        }
                    }
                    $conn->close();
                ?>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    </body>
</html>