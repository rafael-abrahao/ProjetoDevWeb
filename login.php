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
        <div class="container d-flex justify-content-center align-items-center form-container mt-5">
            <form class="border p-4 rounded shadow bg-light" style="min-width: 300px;" action="" method="post">
                <div class="mb-3">
                    <label for="user" class="form-label">Usuário</label>
                    <input type="text" class="form-control" id="usuario" name="usuario" maxlength="20" required>
                </div>
                <div class="mb-3">
                    <label for="pass" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="senha" name="senha" maxlength="20" required>
                </div>
                <button type="submit" class="btn btn-primary mb-3">Entrar</button>
                <?php
                    if ($_SERVER["REQUEST_METHOD"] === "POST") {
                        $usuario = $_POST["usuario"];
                        $senha = $_POST["senha"];

                        // Consulta o usuário no banco
                        $stmt = $conn->prepare("SELECT id, senha FROM usuarios WHERE usuario = ?");
                        $stmt->bind_param("s", $usuario);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $erro = false;

                        if ($result->num_rows === 1) {
                            $dados = $result->fetch_assoc();

                            // Verifica a senha
                            if (password_verify($senha, $dados["senha"])) {
                                // Autenticado com sucesso
                                $_SESSION["usuario_id"] = $dados["id"];
                                $_SESSION["usuario_nome"] = $usuario;

                                header("Location: index.php");
                                exit;
                            } else {
                                $erro = true;
                            }
                        } else {
                            $erro = true;
                        }
                        if($erro){
                            echo "<br><span class='text-danger'>Usuário ou senha incorretos.</span>";
                        }

                        $stmt->close();
                    }

                    $conn->close();
                ?>
            </form>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    </body>
</html>