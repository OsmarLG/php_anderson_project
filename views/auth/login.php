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

            echo json_encode(['success' => true]);
            exit();
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Nombre de usuario o contraseña incorrectos']);
            exit();
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al conectar con la base de datos: ' . $e->getMessage()]);
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 100px;
        }

        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Iniciar sesión</h2>
                        <form id="loginForm" method="post">
                            <div class="form-group">
                                <label for="username">Usuario:</label>
                                <input type="text" id="username" name="username" class="form-control" required autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="password">Contraseña:</label>
                                <input type="password" id="password" name="password" class="form-control" required autocomplete="off">
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Iniciar sesión</button>
                            <div class="text-center mt-3">
                                <p class="mb-0">¿No tienes cuenta? <a href="register.php">Regístrate aquí</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="../../js/jquery-3.7.1.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#loginForm').submit(function(event) {
            event.preventDefault(); 
            $.ajax({
                type: 'POST',
                url: 'login.php', 
                data: $(this).serialize(), 
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Inicio de sesión exitoso!',
                            text: '¡Bienvenido!',
                            timer: 800, 
                            showConfirmButton: false 
                        }).then(function() {
                            window.location.href = '../../index.php';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.error
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un problema al iniciar sesión. Por favor, inténtalo de nuevo más tarde.'
                    });
                }
            });
        });
    });
    </script>

<?php include '../../views/components/footer.php'; ?>

</body>
</html>
