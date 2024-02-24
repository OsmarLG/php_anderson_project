<?php
    session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $name = $_POST['name'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $rol = $_POST['rol'];

        if ($password !== $confirm_password) {
            echo 'Las contraseñas no coinciden';
            exit();
        }

        $host = 'localhost';
        $dbname = 'crud_prueba';
        $db_username = 'root';
        $db_password = '';

        try {
            $db = new PDO("mysql:host=$host;dbname=$dbname", $db_username, $db_password);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            $stmt = $db->prepare("INSERT INTO users (name, username, password, rol) VALUES (?, ?, ?, ?)");
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt->execute([$name, $username, $hashed_password, $rol]);
            
            $_SESSION['user'] = [
                'name' => $name,
                'username' => $username,
                'rol' => $rol
            ];

            echo 'Registro exitoso';

        } catch (PDOException $e) {
            echo 'Error al registrar el usuario: ' . $e->getMessage();
        }
    } else {
        echo 'Error: No se recibieron datos del formulario';
    }
?>
