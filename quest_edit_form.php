<?php
    require_once "connect.php";
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $questao_id = $_POST["questao_id"];

        $result = $conn->query("SELECT enunciado, tipo, id_form FROM questoes WHERE id = ".$questao_id.";");
        if ($result->num_rows > 0) {
            while ($linha = $result->fetch_assoc()){
                $questao_enunciado = $linha["enunciado"];
                $questao_tipo = $linha["tipo"];
                $formulario_id = $linha["id_form"];
                $alternativas = ["", "", "", "", ""];

                if($questao_tipo === "multipla-escolha"){
                    $result_alts = $conn->query("SELECT id, descricao FROM alternativas WHERE id_questao = ".$questao_id.";");
                    if($result_alts->num_rows > 0){
                        $alternativas_index = 0;
                        while ($linha_alts = $result_alts->fetch_assoc()){
                            $alternativas[$alternativas_index] = $linha_alts["descricao"];
                            $alternativas_index++;
                        }
                    }
                }
            }
        }
    }
    $conn->close();
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
            <form style="min-width: 1000px;" action="quest_edit.php" method="post">
                <input type="hidden" id="questao_id" name="questao_id" value=<?php echo $questao_id?>>
                <input type="hidden" id="formulario_id" name="formulario_id" value=<?php echo $formulario_id?>>
                <textarea class="form-control" name="enunciado" id="enunciado" rows="3" required><?php echo htmlspecialchars($questao_enunciado); ?></textarea><br><br>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="radioTipoQuest" id="radioTipoQuest1" value="discursiva" onchange="updateChoice(this)" 
                    <?php if($questao_tipo === "discursiva"){echo "checked";}?>>
                    <label class="form-check-label" for="radioTipoQuest1">Discursiva</label>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="radio" name="radioTipoQuest" id="radioTipoQuest2" value="multipla-escolha" onchange="updateChoice(this)"
                    <?php if($questao_tipo === "multipla-escolha"){echo "checked";}?>>
                    <label class="form-check-label" for="radioTipoQuest2">Multipla Escolha</label>
                </div>
                <div id="radioSelectTipoQuest" class="mb-3" <?php if($questao_tipo === "discursiva"){echo "style='display: none;'";}?>>
                    <div class="input-group mb-1">
                        <span class="input-group-text" id="marcador1">A)</span>
                        <input type="text" class="form-control" aria-describedby="marcador1" name="escolha_a" id ="escolha_a" value=<?php echo $alternativas[0]?>>
                    </div>
                    <div class="input-group mb-1">
                        <span class="input-group-text" id="marcador2">B)</span>
                        <input type="text" class="form-control" aria-describedby="marcador2" name="escolha_b" id ="escolha_b" value=<?php echo $alternativas[1]?>>
                    </div>
                    <div class="input-group mb-1">
                        <span class="input-group-text" id="marcador3">C)</span>
                        <input type="text" class="form-control" aria-describedby="marcador3" name="escolha_c" id ="escolha_c" value=<?php echo $alternativas[2]?>>
                    </div>
                    <div class="input-group mb-1">
                        <span class="input-group-text" id="marcador4">D)</span>
                        <input type="text" class="form-control" aria-describedby="marcador4" name="escolha_d" id ="escolha_d" value=<?php echo $alternativas[3]?>>
                    </div>
                    <div class="input-group mb-1">
                        <span class="input-group-text" id="marcador5">E)</span>
                        <input type="text" class="form-control" aria-describedby="marcador5" name="escolha_e" id ="escolha_e" value=<?php echo $alternativas[4]?>>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-3">
                    <input class="btn btn-primary" type="submit" value="Salvar">
                    <a class="btn btn-secondary" href="quest_create.php">Voltar</a>
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    </body>
</html>