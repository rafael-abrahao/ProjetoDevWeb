<?php
    require_once "connect.php";
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Form Factory - Entrar</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    </head>
    <body class="bg-dark-subtle">
        <?php include "navbar.php";?>
        <div class="container d-flex justify-content-center align-items-center form-container mt-5 bg-light p-4">
            <form style="min-width: 1000px;" action="" method="post">
                <?php
                    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
                        $form_id = $_POST["form_id"];
                        $form_nome = $_POST["form_nome"];
                        
                        $sql_questoes = "SELECT id, enunciado FROM questoes WHERE id_form = ".$form_id.";";

                        $result_questoes = $conn->query($sql_questoes);
                        if($result_questoes->num_rows > 0){
                            $counter = 1;
                            while ($linha_questoes = $result_questoes->fetch_assoc()){
                                $enunciado = $linha_questoes["enunciado"];
                                $questao_id = $linha_questoes["id"];
                                echo "<p class='fs-2'><span class='fw-bold'>".$counter."- </span>".$enunciado."</p>";
                                $sql_respostas = "SELECT r.valor, u.usuario 
                                FROM respostas r 
                                JOIN usuarios u ON r.id_autor = u.id
                                WHERE r.id_questao =".$questao_id.";";
                                $result_respostas = $conn->query($sql_respostas);
                                if($result_respostas->num_rows > 0){
                                    while ($linha_respostas = $result_respostas->fetch_assoc()){
                                        $autor = $linha_respostas["usuario"];
                                        $valor = $linha_respostas["valor"];
                                        echo "<p><span class='fw-semibold'>".$autor.": </span>".$valor."</p>";
                                    }
                                }
                                $counter++;
                            }
                        }
                        $conn->close();
                    }
                ?>
                <div class="d-flex justify-content-end">
                    <a class="btn btn-secondary" href="profile.php">Voltar</a>
                </div>
            </form>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    </body>
</html>