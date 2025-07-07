<?php
    require_once "connect.php";
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $questao_id = $_POST["questao_id"];
        $form_id = $_POST["formulario_id"];
        $enunciado = $_POST["enunciado"];
        $tipo = $_POST["radioTipoQuest"];

        $stmt = $conn->prepare("UPDATE questoes SET enunciado = ?, tipo = ? WHERE id = ?;");
        if ($stmt === false) {
            die("Erro na preparação: " . $conn->error);
        }
        $stmt->bind_param("ssi", $enunciado, $tipo, $questao_id);
        $stmt->execute();

        if($tipo === "multipla-escolha"){
            $conn->query("DELETE FROM alternativas WHERE id_questao = ".$questao_id.";");
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


        $conn->close();
        $_SESSION["mensagem"] = "sucesso_edit";
        header("Location: quest_create.php");
    }
    $conn->close();
?>