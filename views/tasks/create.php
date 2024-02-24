<?php
    session_start();

    if (!isset($_SESSION['user'])) {
        header('Location: ../auth/login.php');
        exit();
    }

    $id_proyecto = null;

    if (isset($_GET['id'])) {
        $id_proyecto = $_GET['id'];
    } else {
        echo "Error: ID del proyecto no especificado.";
        exit();
    }

    if (isset($_POST['name']) && isset($_POST['description'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
    
        $connection = mysqli_connect("localhost", "root", "", "crud_prueba");
    
        if ($connection) {
            $sql = "INSERT INTO tasks (name, description, project_id) VALUES ('$name', '$description', '$id_proyecto')";

            $result = mysqli_query($connection, $sql);

            if ($result) {
                echo "success";
            } else {
                echo "error";
            }
        } else {
            echo "Error de conexión a la base de datos: " . mysqli_connect_error();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Nueva Tarea</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include '../components/menu.php'; ?>

    <div class="container mt-5">
        <h2>Agregar Nueva Tarea</h2>
        <form id="agregarTareaForm">
            <input type="hidden" id="project_id" name="project_id" value="<?php echo $id_proyecto; ?>">
            <div class="form-group">
                <label for="name">Nombre de la Tarea:</label>
                <input type="text" id="name" name="name" class="form-control" required autocomplete="off">
            </div>
            <div class="form-group">
                <label for="description">Descripción:</label>
                <textarea id="description" name="description" class="form-control" required autocomplete="off"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Agregar Tarea</button>
            <button id="cancelar" class="btn btn-warning text-white">Cancelar</button>
        </form>
    </div>

    <?php include '../../views/components/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#cancelar').click(function(event){
                window.location.href = '../projects/show.php?id=<?php echo $id_proyecto; ?>';
            });

            $('#agregarTareaForm').submit(function(event) {
                event.preventDefault();

                var formData = $(this).serialize();
            
                $.ajax({
                    type: 'POST',
                    url: 'create.php?id=<?php echo $id_proyecto; ?>',
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Tarea Agregada',
                            text: 'La tarea se ha agregado correctamente.',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = '../projects/show.php?id=<?php echo $id_proyecto; ?>';
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Hubo un error al agregar la tarea. Por favor, inténtalo de nuevo más tarde.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
