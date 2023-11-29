<!DOCTYPE html>
<html>

<head>
    <title>Editar Evento</title>
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <style>
        body {
            background-color: #f2f2f2;
        }

        .container {
            margin: auto;
            width: 50%;
            padding: 10px;
            border: 1px solid #ccc;
            background-color: #fff;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        label {
            display: block;
            font-weight: bold;
            margin-top: 20px;
        }

        input[type="text"],
        input[type="datetime-local"] {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            box-sizing: border-box;
            border: 2px solid #ccc;
            border-radius: 4px;
        }

        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Editar Evento</h1>
        <?php
        require_once('constantes.php');

        // Inicia la sesión
        session_start();

        // Habilita las excepciones de MySQLi
        mysqli_report(MYSQLI_REPORT_ERROR);

        // Conecta a la base de datos
        $conexion = new mysqli($db_host, $db_user, $db_password, $db_name);

        // Verifica si hubo un error en la conexión
        if ($conexion->connect_error) {
            die("Error al conectar con la base de datos: " . $conexion->connect_error);
        }

        // Verifica si el usuario ha iniciado sesión como organizador
        if (!isset($_SESSION['idOrganizador'])) {
            header('Location: LogInOrganizador.php');
            exit;
        }

        $idOrganizador = $_SESSION['idOrganizador'];

        // Obtiene el ID del evento desde la cadena de consulta
        $id = $_GET['idEvento'];

        // Prepara la consulta para obtener los datos del evento
        $sql = "SELECT * FROM evento WHERE idEvento=" . $id;

        // Ejecuta la consulta
        $result = $conexion->query($sql);

        // Verifica si la consulta devolvió algún resultado
        if ($result->num_rows == 0) {
            die("No se encontró ningún evento con el ID especificado.");
        }

        $row = $result->fetch_assoc();
        ?>

        <form action="delete.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $row['idEvento']; ?>">
            <label>Title</label>
            <input type="text" name="title" value="<?php echo $row['nombreEvent']; ?>" readonly>
            <label>Date</label>
            <input type="datetime-local" name="date" value="<?php echo date('Y-m-d\TH:i:s', strtotime($row['fechaEvent'])); ?>" readonly>
            <label>Location</label>
            <input type="text" name="location" value="<?php echo $row['lugarEvent']; ?>" readonly>
            <button type="submit" class="btn">Eliminar</button>
        </form>
    </div>
    <div class="botones">
        <a href="eliminarEventoMenu.php"><button>Volver</button></a>
    </div>
</body>

</html>