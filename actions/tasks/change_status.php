<?php
    if(isset($_POST['task_id'], $_POST['new_status'])) {

        $task_id = $_POST['task_id'];
        $new_status = $_POST['new_status'];

        $connection = mysqli_connect("localhost", "root", "", "crud_prueba");

        if ($connection) {
            $sql = "UPDATE tasks SET status = '$new_status' WHERE id = $task_id";

            $result = mysqli_query($connection, $sql);

            if ($result) {
                echo json_encode(array("success" => true, "message" => "Estado de la tarea actualizado correctamente"));
            } else {
                echo json_encode(array("success" => false, "message" => "Error al actualizar el estado de la tarea"));
            }
        } else {
            echo json_encode(array("success" => false, "message" => "Error de conexión a la base de datos"));
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Datos insuficientes para actualizar el estado de la tarea"));
    }
?>