<?php
    require_once "connect.php";
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Form Factory - Cadastrar</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    </head>
    <body class="bg-dark-subtle">
        <?php 
            include "navbar.php";
            include "connect.php";
        ?>
        <div class="container d-flex justify-content-center align-items-center form-container mt-5">
            <form class="border p-4 rounded shadow bg-light" style="min-width: 300px;" action="" method="post">
                <div class="mb-3">
                    <label for="user" class="form-label">Usuário</label>
                    <input type="text" class="form-control" id="user" name="user" maxlength="20" required>
                </div>
                <div class="mb-3">
                    <label for="pass" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="pass" name="pass" maxlength="20" required>
                </div>
                <button type="submit" class="btn btn-success mb-3">Cadastrar</button>
                <?php
                    if($_SERVER["REQUEST_METHOD"] == "POST"){

                        $username = $_POST["user"];
                        $password = password_hash($_POST["pass"], PASSWORD_DEFAULT);
                        
                        $stmt = $conn->prepare("INSERT INTO Usuarios (usuario, senha) VALUES (?, ?)");
                        if ($stmt === false) {
                            die("Erro na preparação: " . $conn->error);
                        }
                        $stmt->bind_param("ss", $username, $password);
                        if ($stmt->execute()) {
                            echo "<br><span class='text-success'>Cadastrado com sucesso!</span>";
                        } else {
                            if ($conn->errno == 1062) {
                                echo "<br><span class='text-danger'>Este usuário já existe.</span>";
                            } else {
                                echo "Erro: " . $conn->error;
                            }
                        }

                        // header("Location: " . $_SERVER["PHP_SELF"]);
                        $stmt->close();
                        $conn->close();
                    }
                ?>
            </form>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    </body>
</html>