<?php
    require_once "connect.php";
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $questao_id = $_POST["question_id"];
        $stmt = $conn->prepare("DELETE FROM questoes WHERE id = ?;");
        if ($stmt === false) {
            die("Erro na preparação: " . $conn->error);
        }
        $stmt->bind_param("i", $questao_id);
        $stmt->execute();

        $conn->close();
        $_SESSION["mensagem"] = "sucesso_delete";
        header("Location: quest_create.php");
    }
    $conn->close();
?>