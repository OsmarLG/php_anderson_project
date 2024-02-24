<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Registro</h2>
        <form id="registerForm">
            <div class="form-group">
                <label for="name">Nombre:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="username">Usuario:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirmar contraseña:</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="form-group">
                <label for="rol">Rol:</label>
                <select name="rol" id="rol">
                    <option value="ADMIN">ADMIN</option>
                    <option value="EDITOR">EDITOR</option>
                    <option value="WORKER">WORKER</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Registrarse</button>
        </form>
        <p class="mt-3">¿Ya tienes una cuenta? <a href="login.php">Inicia sesión</a></p>
    </div>

    <script src="../../js/jquery-3.7.1.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#registerForm').submit(function(event) {
            event.preventDefault(); 

            $.ajax({
                type: 'POST',
                url: '../../actions/register_process.php',
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
