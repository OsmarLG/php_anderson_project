<?php
    session_start();

    if (!isset($_SESSION['user'])) {
        header('Location: ../auth/login.php');
        exit();
    }

    $id_proyecto = null;
    $connection = mysqli_connect("localhost", "root", "", "crud_prueba");

    if (isset($_GET['id'])) {
        $id_proyecto = $_GET['id'];
    
        if ($connection) {
            $sql = "SELECT * FROM projects WHERE id = $id_proyecto";
            $result = mysqli_query($connection, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                $proyecto = mysqli_fetch_assoc($result);
            } else {
                echo "Error: El proyecto no fue encontrado.";
                exit();
            }
        } else {
            echo "Error de conexión a la base de datos: " . mysqli_connect_error();
            exit();
        }
    } else {
        echo "Error: ID del proyecto no especificado.";
        exit();
    }

    if (isset($_POST['name'])) {
        $nombre_proyecto = $_POST['name'];

        $sql = "UPDATE projects SET name = '$nombre_proyecto' WHERE id = $id_proyecto";

        $result = mysqli_query($connection, $sql);

        if ($result) {
            echo "success";
        } else {
            echo "error";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Proyecto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include '../components/menu.php'; ?>

    <div class="container mt-5">
        <h2>Editar Proyecto</h2>
        <form id="editarProyectoForm">
            <div class="form-group">
                <label for="name">Nombre del Proyecto:</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo $proyecto['name']; ?>" required autocomplete="off">
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <button id="cancelar" class="btn btn-warning text-white">Cancelar</button>
        </form>
    </div>

    <script src="../../js/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#cancelar').click(function(event){
                window.location.href = '../projects/show.php?id=<?php echo $proyecto['id']; ?>';
            });

            $('#editarProyectoForm').submit(function(event) {
                event.preventDefault();
            
                var formData = $(this).serialize();

                $.ajax({
                    type: 'POST',
                    url: 'edit.php?id=<?php echo $proyecto['id']; ?>',
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: 'El proyecto ha sido actualizado correctamente.',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = 'show.php?id=<?php echo $proyecto['id']; ?>';
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Hubo un error al actualizar el proyecto. Por favor, inténtalo de nuevo más tarde.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            });
        });
    </script>

    <?php include '../../views/components/footer.php'; ?>

</body>
</html>
