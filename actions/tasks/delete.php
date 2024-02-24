<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login.php');
    exit();
}

if (isset($_POST['task_id'])) {
    $idtarea = $_POST['task_id'];

    $connection = mysqli_connect("localhost", "root", "", "crud_prueba");

    if ($connection) {
        $sql = "DELETE FROM tasks WHERE id = $idtarea";

        $result = mysqli_query($connection, $sql);
        
        if (!$result) {
            echo "Error al eliminar la tarea: " . mysqli_error($connection);
        }
    } else {
        echo "Error de conexión a la base de datos: " . mysqli_connect_error();
    }
} else {
    echo "Error: ID de la tarea no especificado.";
}
?>
