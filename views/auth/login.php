<?php
    session_start();

    if (isset($_SESSION['user'])) {
        header('Location: ../../index.php');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $host = 'localhost';
        $dbname = 'crud_prueba';
        $db_username = 'root';
        $db_password = '';

        try {
            $db = new PDO("mysql:host=$host;dbname=$dbname", $db_username, $db_password);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$_POST['username']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($user && password_verify($_POST['password'], $user['password'])) {
        
                $_SESSION['user'] = [
                    'username' => $user['username'],
                    'name' => $user['name'],
                    'rol' => $user['rol']
                ];

                header('Location: ../../index.php');
                exit();
            } else {
        
                $error_message = 'Nombre de usuario o contraseña incorrectos';
            }
        } catch (PDOException $e) {
    
            echo 'Error al conectar con la base de datos: ' . $e->getMessage();
            exit();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mb-4">Iniciar sesión</h2>
                <form id="loginForm" method="post">
                    <div class="form-group">
                        <label for="username">Usuario:</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña:</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Iniciar sesión</button>
                    <div class="text-center mt-3">
                        <p class="mb-0">¿No tienes cuenta? <a href="register.php">Regístrate aquí</a></p>
                    </div>
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger mt-3" role="alert">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <script src="../../js/jquery-3.7.1.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#loginForm').submit(function(event) {
            event.preventDefault(); 
            $.ajax({
                type: 'POST',
                url: 'login.php', 
                data: $(this).serialize(), 
                success: function(response) {
                    console.log(response);
                    window.location.href = '../../index.php';
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
    </script>
</body>
</html>
