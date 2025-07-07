<?php
    require_once "connect.php";
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if(!isset($_SESSION["form_id_answer"])){
        header("Location: index.php");
    }
    $form_id = $_SESSION["form_id_answer"];
    $form_nome = $_SESSION["form_nome_answer"];

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        $autor_id = $_SESSION["usuario_id"];
        $questoes_qtd = $_POST["questoes_qtd"];
        echo $questoes_qtd;
        for($i = 1; $i <= $questoes_qtd; $i++){
            if(isset($_POST[strval($i)])){
                $resposta = $_POST[strval($i)];
                $questao_id = $_POST[strval($i)."_id"];
                $stmt = $conn->prepare("INSERT INTO respostas (id_form, id_questao, id_autor, valor) VALUES (?, ?, ?, ?)");
                if ($stmt === false) {
                    die("Erro na preparação: " . $conn->error);
                }
                $stmt->bind_param("iiis", $form_id, $questao_id, $autor_id, $resposta);
                $stmt->execute();
                $stmt->close();
            }
        }
        $conn->close();
        unset($_SESSION["form_id_answer"]);
        unset($_SESSION["form_nome_answer"]);
        $_SESSION["mensagem"] = "sucesso_resposta";
        header("Location: index.php");
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
                <p class="fs-2 mb-5"><?php echo $form_nome ?></p>
                <?php
                    $result = $conn->query("SELECT id, enunciado, tipo FROM questoes WHERE id_form = ".$form_id.";");
                    if($result->num_rows > 0){
                        echo "<input type='hidden' id='questoes_qtd' name='questoes_qtd' value='".$result->num_rows."'>";
                        $counter = 1;
                        $letras = ["a", "b", "c", "d", "e"];
                        while ($linha = $result->fetch_assoc()){
                            $quest_id = $linha["id"];
                            $quest_enum = $linha["enunciado"];
                            $quest_tipo = $linha["tipo"];
                            echo "<p class='fs-4'>".$counter." - ".$quest_enum."</p>";
                            echo "<input type='hidden' id='".$counter."_id' name='".$counter."_id' value='".$quest_id."'>";
                            if($quest_tipo === "discursiva"){
                                echo "  <textarea class='form-control' 
                                            name='".$counter."' 
                                            id='".$counter."'  
                                            rows='3' placeholder='R:' required></textarea>";
                            }else{
                                $result_alts = $conn->query("SELECT id, descricao FROM alternativas WHERE id_questao = ".$quest_id.";");
                                if($result_alts->num_rows > 0){
                                    $letras_index = 0;
                                    while ($linha_alts = $result_alts->fetch_assoc()){
                                        echo "  <div class='form-check'>
                                                    <input class='form-check-input' type='radio' 
                                                    name='".$counter."' 
                                                    id='".$counter.$letras[$letras_index]."' 
                                                    value='".$linha_alts["descricao"]."' required>
                                                    <label class='form-check-label' for='".$counter.$letras[$letras_index]."'>
                                                    ".$linha_alts["descricao"]."
                                                    </label>
                                                </div>";
                                        $letras_index++;
                                    }
                                }
                            }
                            $counter++;
                            echo "<br><br>";
                        }
                    }
                    $conn->close();
                ?>
                <input class="btn btn-primary" type="submit" value="Enviar">
            </form>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    </body>
</html>