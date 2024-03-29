<?php
    // Incluye la conexión a la base de datos
    include 'php/database.php';

    session_start();

    if (!isset($_SESSION['user'])) {
        header('Location: views/auth/login.php');
        exit();
    }

    // Query para seleccionar todos los proyectos
    $sql = "SELECT * FROM projects";
    $result = mysqli_query($connection, $sql);

    // Array para almacenar los proyectos
    $proyectos = [];

    // Si hay resultados, los almacenamos en el array
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $proyectos[] = $row;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Projects & Tasks</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .navbar {
            background-color: #007bff;
        }
        .navbar-brand, .navbar-text {
            color: #000;
        }
    </style>
</head>
<body>
    <?php include 'views/components/menu.php'; ?>

    <div class="container mt-5">
        <h2>Proyectos</h2>
        <br>
        <?php if ($_SESSION['user']['rol'] == 'ADMIN'): ?>
            <a href="views/projects/create.php" class="btn btn-primary">Crear Nuevo Proyecto</a>
            <br>
            <br>
        <?php endif; ?>
        
        <?php if (count($proyectos) > 0): ?>
            <div class="row">
                <?php foreach ($proyectos as $proyecto): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $proyecto['name']; ?></h5>
                                <a href="views/projects/show.php?id=<?php echo $proyecto['id']; ?>" class="btn btn-primary">Ver Detalles</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center">No hay proyectos creados.</p>
        <?php endif; ?>
    </div>

    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <?php include 'views/components/footer.php'; ?>
</body>
</html>
