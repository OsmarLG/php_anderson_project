<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login.php');
    exit();
}

$id_task = null;
$connection = mysqli_connect("localhost", "root", "", "crud_prueba");

if ($connection) {
    if (isset($_GET['id'])) {
        $id_task = mysqli_real_escape_string($connection, $_GET['id']);

        $sql = "SELECT * FROM tasks WHERE id = $id_task";
        $result = mysqli_query($connection, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $tarea = mysqli_fetch_assoc($result);
        } else {
            echo "Error: La tarea no fue encontrada.";
            exit();
        }
    } else {
        echo "Error: ID de tarea no especificado.";
        exit();
    }
} else {
    echo "Error de conexión a la base de datos: " . mysqli_connect_error();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['name']) && isset($_POST['description'])) {
        $nombre_tarea = mysqli_real_escape_string($connection, $_POST['name']);
        $descripcion_tarea = mysqli_real_escape_string($connection, $_POST['description']);

        $sql = "UPDATE tasks SET name = '$nombre_tarea', description = '$descripcion_tarea' WHERE id = $id_task";

        $result = mysqli_query($connection, $sql);

        if ($result) {
            echo "success";
        } else {
            echo "error";
        }
    } else {
        echo "Error: Datos de la tarea no especificados.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar tarea</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <?php include '../components/menu.php'; ?>

    <div class="container mt-5">
        <h2>Editar tarea</h2>
        <form id="editartareaForm">
            <div class="form-group">
                <label for="name">Nombre del tarea:</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($tarea['name']); ?>" required autocomplete="off">
            </div>
            <div class="form-group">
                <label for="description">Descripción:</label>
                <textarea id="description" name="description" class="form-control" required autocomplete="off"><?php echo htmlspecialchars($tarea['description']); ?></textarea>
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
            $('#cancelar').click(function(event) {
                window.location.href = '../projects/show.php?id=<?php echo htmlspecialchars($tarea['project_id']); ?>';
            });

            $('#editartareaForm').submit(function(event) {
                event.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    type: 'POST',
                    url: 'edit.php?id=<?php echo htmlspecialchars($tarea['id']); ?>',
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: 'La tarea ha sido actualizada correctamente.',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = '../projects/show.php?id=<?php echo htmlspecialchars($tarea['project_id']); ?>';
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Hubo un error al actualizar la tarea. Por favor, inténtalo de nuevo más tarde.',
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