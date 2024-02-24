<?php
    session_start();

    if (!isset($_SESSION['user'])) {
        header('Location: ../auth/login.php');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['name'])) {
            $name = $_POST['name'];
        
            $connection = mysqli_connect("localhost", "root", "", "crud_prueba");
        
            if ($connection) {
                $sql = "INSERT INTO projects (name) VALUES ('$name')";

                $result = mysqli_query($connection, $sql);

                if ($result) {
                    $id_proyecto = mysqli_insert_id($connection);
                    echo $id_proyecto;
                    exit();
                } else {
                    echo "Error al crear el proyecto: " . mysqli_error($connection);
                }
            } else {
                echo "Error de conexión a la base de datos: " . mysqli_connect_error();
            }
        } else {
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
                <input type="text" id="name" name="name" class="form-control" required autocomplete="off">
            </div>
            <button type="submit" class="btn btn-primary">Crear Proyecto</button>
            <button id="cancelar" class="btn btn-warning text-white">Cancelar</button>
        </form>
    </div>

    <script src="../../js/jquery-3.7.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#cancelar').click(function(event){
            window.location.href = '../../index.php';
        });

        $('#crearProyectoForm').submit(function(event) {
            event.preventDefault();
        
            var formData = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: 'create.php',
                data: formData,
                success: function(response) {
                    console.log(response);
                    window.location.href = 'show.php?id=' + response;
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
<?php include '../../views/components/footer.php'; ?>

</body>
</html>
