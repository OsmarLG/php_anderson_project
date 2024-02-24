<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login.php');
    exit();
}

if (isset($_POST['id'])) {
    $idProyecto = $_POST['id'];

    $connection = mysqli_connect("localhost", "root", "", "crud_prueba");

    if ($connection) {
        $sql = "DELETE FROM projects WHERE id = $idProyecto";

        $result = mysqli_query($connection, $sql);
        
        if ($result) {
            echo "Proyecto eliminado exitosamente";
        } else {
            echo "Error al eliminar el proyecto: " . mysqli_error($connection);
        }
    } else {
        echo "Error de conexión a la base de datos: " . mysqli_connect_error();
    }
} else {
    echo "Error: ID del proyecto no especificado.";
}
?>
