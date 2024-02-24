<?php
    session_start();
    
    if (!isset($_SESSION['user'])) {
        header('Location: ../auth/login.php');
        exit();
    }

    if (isset($_GET['id'])) {
        $id_proyecto = $_GET['id'];
        $connection = mysqli_connect("localhost", "root", "", "crud_prueba");
        
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
                <?php if ($_SESSION['user']['rol'] == 'ADMIN'): ?>
                    <a href="edit.php?id=<?php echo $proyecto['id']; ?>" class="btn btn-primary mr-2">Editar</a>
                    <button onclick="eliminarProyecto(<?php echo $proyecto['id']; ?>)" class="btn btn-danger">Eliminar</button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="container mt-5">
    <h2>Tareas del Proyecto</h2>
    <?php if ($_SESSION['user']['rol'] == 'ADMIN' || $_SESSION['user']['rol'] == 'EDITOR'): ?>
        <a href="../tasks/create.php?id=<?php echo $id_proyecto; ?>" class="btn btn-primary mt-3">Agregar Nueva Tarea</a>
        <br>
        <br>
    <?php endif; ?>
    <div class="card">
        <div class="card-body">
            <h6 class="card-subtitle mb-2 text-muted">Tareas:</h6>
            <div class="row">
                <?php
                    
                    $sql_tasks = "SELECT * FROM tasks WHERE project_id = $id_proyecto";
                    $result_tasks = mysqli_query($connection, $sql_tasks);
                    if ($result_tasks && mysqli_num_rows($result_tasks) > 0) {
                        while ($task = mysqli_fetch_assoc($result_tasks)) {
                            echo "<div class='col-md-4 mb-3'>";
                            echo "<div class='task card'>";
                            echo "<div class='card-body'>";
                            echo "<h5 class='card-title'>{$task['name']}</h5>"; 
                            echo "<p class='card-text'><b>Estado:</b> {$task['status']}</p>"; 
                            echo "<p class='card-text'><b>Descripcion:</b> {$task['description']}</p>"; 
                            echo "<label>Cambiar Estado</label>"; 
                            echo "<select class='form-control estado-select' data-task-id='{$task['id']}'>";
                            echo "<option value='' selected disabled>--Selecciona una Opcion--</option>";
                            if ($_SESSION['user']['rol'] == 'ADMIN') {
                                echo "<option value='Nueva' " . ($task['status'] == 'Nueva' ? 'selected' : '') . ">Nueva</option>";
                            }                            
                            echo "<option value='En Proceso' " . ($task['status'] == 'En Proceso' ? 'selected' : '') . ">En Proceso</option>";
                            echo "<option value='Terminada' " . ($task['status'] == 'Terminada' ? 'selected' : '') . ">Terminada</option>";
                            if ($_SESSION['user']['rol'] == 'ADMIN') {
                                echo "<option value='Validado' " . ($task['status'] == 'Validado' ? 'selected' : '') . ">Validado</option>";
                            }                            
                            echo "</select>";
                            if ($_SESSION['user']['rol'] == 'ADMIN' || $_SESSION['user']['rol'] == 'EDITOR') {
                                echo "<br>";
                                echo "<a href='../tasks/edit.php?id={$task['id']}' class='btn btn-primary btn-sm mb-2 mr-2'>Editar</a>";
                                echo "<button onclick='eliminarTarea({$task['id']})' class='btn btn-danger btn-sm mb-2'>Eliminar</button>";                            
                            } 
                            echo "</div>"; 
                            echo "</div>"; 
                            echo "</div>"; 
                        }
                    } else {
                        echo "<div class='col'><p>No hay tareas asociadas a este proyecto.</p></div>";
                    }
                ?>
            </div>
        </div>
    </div>
</div>

    <script src="../../js/jquery-3.7.1.min.js"></script>
    <script src="../../js/projects/eliminar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
       $(document).ready(function() {
            $('.estado-select').change(function() {
                var taskId = $(this).data('task-id');
                var newStatus = $(this).val();

                $.ajax({
                    type: 'POST',
                    url: '../../actions/tasks/change_status.php', 
                    data: {
                        task_id: taskId,
                        new_status: newStatus
                    },
                    success: function(response) {
                        alert(response);
                        Swal.fire({
                            icon: 'success',
                            title: '¡Estado actualizado!',
                            text: 'El estado de la tarea ha sido actualizado correctamente.',
                            showConfirmButton: false,
                            timer: 1500 
                        }).then(() => {
                            window.location.href = 'show.php?id=<?php echo $proyecto['id']; ?>';
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Hubo un problema al actualizar el estado de la tarea. Por favor, inténtalo de nuevo más tarde.'
                        });
                    }
                });
            });
        });

    </script>

    <script>
        function eliminarTarea(taskId) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción eliminará la tarea. ¿Quieres continuar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar tarea',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: '../../actions/tasks/delete.php', // Ruta al archivo PHP que maneja la eliminación de la tarea
                        data: {
                            task_id: taskId
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Tarea eliminada',
                                text: 'La tarea ha sido eliminada correctamente.',
                                showConfirmButton: false,
                                timer: 1500 
                            }).then(() => {
                                // Recargar la página para reflejar los cambios
                                location.reload();
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Hubo un problema al eliminar la tarea. Por favor, inténtalo de nuevo más tarde.'
                            });
                        }
                    });
                }
            });
        }
    </script>

    <?php include '../../views/components/footer.php'; ?>

</body>
</html>
