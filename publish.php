<?php
    require_once "connect.php";
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if(isset($_SESSION["form_id_create"])){
        $conn->query("UPDATE formularios SET published = true WHERE id =".$_SESSION["form_id_create"].";");
        $conn->close();

        unset($_SESSION["form_id_create"]);
        $_SESSION["mensagem"] = "sucesso_create";

        header("Location: form_create.php");
    }
?>