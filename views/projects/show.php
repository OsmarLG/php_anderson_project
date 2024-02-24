<?php
    session_start();

    // Verificar si el usuario está autenticado
    if (!isset($_SESSION['user'])) {
        header('Location: ../auth/login.php');
        exit();
    }

    // Verificar si se recibió el ID del proyecto en la URL
    if (isset($_GET['id'])) {
        // Obtener el ID del proyecto desde la URL
        $id_proyecto = $_GET['id'];

        // Conectar a la base de datos
        $connection = mysqli_connect("localhost", "root", "", "crud_prueba");

        // Verificar si la conexión fue exitosa
        if ($connection) {
            // Preparar la consulta SQL para obtener los detalles del proyecto
            $sql = "SELECT * FROM projects WHERE id = $id_proyecto";

            // Ejecutar la consulta SQL
            $result = mysqli_query($connection, $sql);

            // Verificar si se encontró el proyecto
            if ($result && mysqli_num_rows($result) > 0) {
                // Obtener los datos del proyecto
                $proyecto = mysqli_fetch_assoc($result);
            } else {
                // Mostrar un mensaje de error si el proyecto no se encontró
                echo "Error: El proyecto no fue encontrado.";
                exit();
            }
        } else {
            // Mostrar un mensaje de error si no se pudo conectar a la base de datos
            echo "Error de conexión a la base de datos: " . mysqli_connect_error();
            exit();
        }
    } else {
        // Mostrar un mensaje de error si no se recibió el ID del proyecto en la URL
        echo "Error: ID del proyecto no especificado.";
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Proyecto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include '../components/menu.php'; ?>

    <div class="container mt-5">
        <h2>Detalles del Proyecto</h2>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?php echo $proyecto['name']; ?></h5>
                <!-- Aquí puedes mostrar más detalles del proyecto, como la descripción, fecha de creación, etc. -->
            </div>
        </div>
    </div>

    <script src="../../js/jquery-3.7.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
