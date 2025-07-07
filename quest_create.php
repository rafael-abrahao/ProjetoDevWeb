<?php
    require_once "connect.php";
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if(!isset($_SESSION["form_id_create"])){
        header("Location: index.php");
    }
    $form_id = $_SESSION["form_id_create"];
    $form_nome = $_SESSION["form_nome_create"];

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        $enunciado = $_POST["enunciado"];
        $tipo = $_POST["radioTipoQuest"];

        $stmt = $conn->prepare("INSERT INTO questoes (id_form, enunciado, tipo) VALUES (?, ?, ?)");
        if ($stmt === false) {
            die("Erro na preparação: " . $conn->error);
        }
        $stmt->bind_param("iss", $form_id, $enunciado, $tipo);
        $stmt->execute();
        $questao_id = $conn->insert_id;
        $stmt->close();

        if($tipo == "multipla-escolha"){
            $alternativas = ["escolha_a", "escolha_b", "escolha_c", "escolha_d", "escolha_e"];
            foreach($alternativas as $alternativa){
                if(isset($_POST[$alternativa]) && $_POST[$alternativa] !== ""){
                    $stmt = $conn->prepare("INSERT INTO alternativas (descricao, id_form, id_questao) VALUES (?, ?, ?)");
                    if ($stmt === false) {
                        die("Erro na preparação: " . $conn->error);
                    }
                    $stmt->bind_param("sii", $_POST[$alternativa], $form_id, $questao_id);
                    $stmt->execute();
                    $stmt->close();
                }
            }
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
    </head>
    <body class="bg-dark-subtle">
        <?php include "navbar.php";?>
        <div class="container d-flex align-items-center justify-content-center flex-column mt-3">
            <?php
                if(isset($_SESSION["mensagem"])){
                    if($_SESSION["mensagem"] === "sucesso_delete"){
                        echo "  <div class='alert alert-danger' role='alert' style='width: 1000px;'>
                                    Questão apagada.
                                </div>";
                    }else if($_SESSION["mensagem"] === "sucesso_edit"){
                        echo "  <div class='alert alert-warning' role='alert' style='width: 1000px;'>
                        Questão editada.
                        </div>";
                    }
                    unset($_SESSION["mensagem"]);
                }
            ?>
        </div>
        <div class="container d-flex justify-content-center align-items-center form-container mt-5 bg-light p-4">
            <form style="min-width: 1000px;" action="" method="post">
                <p class="fs-5 mb-5"><?php echo $form_nome ?></p>
                <label for="enunciado" class="form-label">Enunciado</label>
                <textarea class="form-control" name="enunciado" id="enunciado" rows="3" placeholder="Digite sua pergunta" required></textarea><br><br>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="radioTipoQuest" id="radioTipoQuest1" value="discursiva" onchange="updateChoice(this)" checked>
                    <label class="form-check-label" for="radioTipoQuest1">Discursiva</label>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="radio" name="radioTipoQuest" id="radioTipoQuest2" value="multipla-escolha" onchange="updateChoice(this)">
                    <label class="form-check-label" for="radioTipoQuest2">Multipla Escolha</label>
                </div>
                <div id="radioSelectTipoQuest" class="mb-3" style="display: none;">
                    <div class="input-group mb-1">
                        <span class="input-group-text" id="marcador1">A)</span>
                        <input type="text" class="form-control" aria-describedby="marcador1" name="escolha_a" id ="escolha_a">
                    </div>
                    <div class="input-group mb-1">
                        <span class="input-group-text" id="marcador2">B)</span>
                        <input type="text" class="form-control" aria-describedby="marcador2" name="escolha_b" id ="escolha_b">
                    </div>
                    <div class="input-group mb-1">
                        <span class="input-group-text" id="marcador3">C)</span>
                        <input type="text" class="form-control" aria-describedby="marcador3" name="escolha_c" id ="escolha_c">
                    </div>
                    <div class="input-group mb-1">
                        <span class="input-group-text" id="marcador4">D)</span>
                        <input type="text" class="form-control" aria-describedby="marcador4" name="escolha_d" id ="escolha_d">
                    </div>
                    <div class="input-group mb-1">
                        <span class="input-group-text" id="marcador5">E)</span>
                        <input type="text" class="form-control" aria-describedby="marcador5" name="escolha_e" id ="escolha_e">
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-3">
                    <input class="btn btn-primary" type="submit" value="Adicionar">
                    <a class="btn btn-secondary" href="publish.php">Finalizar</a>
                </div>
                <script>
                    function updateChoice(radio) {
                        if(radio.value == "multipla-escolha"){
                            document.getElementById("radioSelectTipoQuest").style.display = "block";
                            document.getElementById("escolha_a").required = true;
                            document.getElementById("escolha_b").required = true;
                        }else{
                            document.getElementById("radioSelectTipoQuest").style.display = "none";
                            document.getElementById("escolha_a").required = false;
                            document.getElementById("escolha_b").required = false;
                        }
                    }
                </script>
            </form>
        </div>
        <div class="container d-flex justify-content-center align-items-center mb-3 mt-5">
            <div class="d-flex align-items-start flex-column">
                <?php
                    $result = $conn->query("SELECT id, enunciado, tipo FROM questoes WHERE id_form=".$form_id.";");
                    $counter = 1;
                    if ($result->num_rows > 0) {
                        while ($linha = $result->fetch_assoc()) {
                            
                            echo "  <div class='container bg-light p-4 mb-1'>
                                    <form class='d-flex justify-content-between mb-2' style='min-width: 1000px;'>
                                        <input type='hidden' id='questao_id' name='questao_id' value='".$linha["id"]."'>
                                        <h5 class='mb-0'><span class='fw-semibold'>".$counter."</span> - ".$linha["enunciado"]."</h5>
                                        <div>
                                            <input type='submit' class='btn btn-success' formaction='quest_edit_form.php' formmethod='post' value='Editar'>
                                            <input type='submit' class='btn btn-danger' formaction='quest_delete_confirm.php' formmethod='post' value='Deletar'>
                                        </div>
                                    </form>";
                            if($linha["tipo"] === "multipla-escolha"){
                                echo "<div class='d-flex align-items-start flex-column mb-2'>";
                                $letras = ["a", "b", "c", "d", "e"];
                                $letras_index = 0;
                                $result_alts = $conn->query("SELECT id, descricao FROM alternativas WHERE id_questao = ".$linha["id"].";");
                                if($result_alts->num_rows > 0){
                                    while ($linha_alts = $result_alts->fetch_assoc()){
                                        echo "<p class='fs-5'>".$letras[$letras_index].") ".$linha_alts["descricao"]."</p>";
                                        $letras_index++;
                                    }
                                }
                                echo "</div>";
                            }
                            $counter++;
                            echo "</div>";
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