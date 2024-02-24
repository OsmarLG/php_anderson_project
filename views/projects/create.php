<?php
    session_start();

    if (!isset($_SESSION['user'])) {
        header('Location: ../auth/login.php');
        exit();
    }

    // Verificar si se envió un formulario de creación de proyecto
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Verificar si el nombre del proyecto se envió correctamente
        if (isset($_POST['name'])) {
            // Obtener el nombre del proyecto enviado por el formulario
            $name = $_POST['name'];

            // Conectar a la base de datos
            $connection = mysqli_connect("localhost", "root", "", "crud_prueba");

            // Verificar si la conexión fue exitosa
            if ($connection) {
                // Preparar la consulta SQL para insertar el nuevo proyecto en la base de datos
                $sql = "INSERT INTO projects (name) VALUES ('$name')";

                // Ejecutar la consulta SQL
                $result = mysqli_query($connection, $sql);

                // Verificar si la consulta se ejecutó correctamente
                if ($result) {
                    // Obtener el ID del proyecto recién creado
                    $id_proyecto = mysqli_insert_id($connection);

                    // Imprimir el ID del proyecto como respuesta
                    echo $id_proyecto;

                    // Terminar el script
                    exit();
                } else {
                    // Manejar cualquier error en la ejecución de la consulta SQL
                    echo "Error al crear el proyecto: " . mysqli_error($connection);
                }
            } else {
                // Manejar cualquier error en la conexión a la base de datos
                echo "Error de conexión a la base de datos: " . mysqli_connect_error();
            }
        } else {
            // Manejar el caso en que no se envió el nombre del proyecto
            echo "Error: name del proyecto no enviado.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Proyecto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include '../components/menu.php'; ?>

    <div class="container mt-5">
        <h2>Crear Nuevo Proyecto</h2>
        <form id="crearProyectoForm">
            <div class="form-group">
                <label for="name">Nombre del Proyecto:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Crear Proyecto</button>
        </form>
    </div>

    <script src="../../js/jquery-3.7.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#crearProyectoForm').submit(function(event) {
            event.preventDefault(); // Evita que el formulario se envíe de manera convencional
            
            // Obtener los datos del formulario
            var formData = $(this).serialize();

            // Realizar la solicitud AJAX para crear el proyecto
            $.ajax({
                type: 'POST',
                url: 'create.php', // Cambia 'crear_proyecto.php' por la ruta correcta al archivo PHP que maneja la creación del proyecto
                data: formData,
                success: function(response) {
                    // Log de la respuesta para depuración
                    console.log(response);
                    
                    // Redireccionar al usuario a la página de proyectos después de crear el proyecto
                    window.location.href = 'show.php?id=' + response; // Utiliza el ID del proyecto devuelto por la respuesta AJAX
                },
                error: function(xhr, status, error) {
                    // Manejar cualquier error que ocurra durante la solicitud AJAX
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>

</body>
</html>
