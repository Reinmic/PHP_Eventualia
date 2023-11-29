<?php
require_once('constantes.php');

// Inicia la sesión
session_start();

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

// Consulta los eventos del organizador que ha iniciado sesión
$consulta = "SELECT * FROM evento WHERE idOrg = ?"; // Usamos un marcador de posición en la consulta
$stmt = $conexion->prepare($consulta);
$stmt->bind_param("s", $idOrganizador);
$stmt->execute();
$resultado = $stmt->get_result();

if (!$resultado) {
    echo "Lo sentimos. La aplicación no funciona<br>";
    echo "Error en la consulta: " . $consulta . "<br>";
    echo "Número de error: " . $conexion->errno . "<br>";
    echo "Error: " . $conexion->error . "<br>";
    exit;
}

// Verifica si se ha enviado el formulario de edición
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["idEvento"]) && isset($_POST["nombreEvent"]) && isset($_POST["fechaEvent"]) && isset($_POST["lugarEvent"]) && isset($_POST["activoEvent"])) {
    // Obtiene los datos del formulario
    $idEvento = $_POST["idEvento"];
    $nombreEvent = $_POST["nombreEvent"];
    $fechaEvent = $_POST["fechaEvent"];
    $lugarEvent = $_POST["lugarEvent"];
    $activoEvent = $_POST["activoEvent"];

    // Actualiza los datos en la base de datos
    $consulta = "UPDATE evento SET nombreEvent = ?, fechaEvent = ?, lugarEvent = ?, activoEvent = ? WHERE idEvento = ? AND idOrganizador = ?"; // Usamos marcadores de posición en la consulta
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("ssssss", $nombreEvent, $fechaEvent, $lugarEvent, $activoEvent, $idEvento, $idOrganizador);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Los datos se han actualizado correctamente en la base de datos";
    } else {
        echo "No se realizaron cambios en la base de datos";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Editar Eventos</title>
    <meta charset="UTF-8">
    <style>
        body {
            background-color: #F5F5F5;
            font-family: Arial, sans-serif;
            font-size: 16px;
        }

        h1 {
            text-align: center;
            color: #a0c28f;
            margin-top: 30px;
        }

        table {
            margin: 0 auto;
            width: 60%;
            border-collapse: collapse;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        th,
        td {
            text-align: left;
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #a0c28f;
            color: #FFFFFF;
        }

        input[type=text],
        input[type=date],
        select {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            box-sizing: border-box;
            border: 2px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        input[type=submit] {
            background-color: #a0c28f;
            color: #000;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            padding: 15px 30px;
            font-size: 16px;
            margin-top: 20px;
        }

        input[type=submit]:hover {
            background-color: #4CAF50;
        }

        .botones {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 2rem;
        }

        button {
            background-color: #a0c28f;
            color: #ffffff;
            font-size: 1.2rem;
            font-weight: bold;
            padding: 1rem 2rem;
            border: none;
            border-radius: 0.5rem;
            margin: 0 1rem;
            cursor: pointer;
            box-shadow: 2px 2px #000000;
            transition: all 0.2s ease-in-out;
        }

        button:hover {
            transform: scale(1.05);
            box-shadow: 3px 3px #4CAF50;
        }
    </style>
</head>

<body>
    <h1>Eventos del Organizador</h1>
    <table>
        <tr>
            <th>ID Evento</th>
            <th>Nombre Evento</th>
            <th>Fecha</th>
            <th>Lugar</th>
            <th>Activo</th>
            <th>Acciones</th>
        </tr>
        <?php
        while ($row = $resultado->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['idEvento'] . "</td>";
            echo "<td>" . $row['nombreEvent'] . "</td>";
            echo "<td>" . $row['fechaEvent'] . "</td>";
            echo "<td>" . $row['lugarEvent'] . "</td>";
            echo "<td>" . $row['activoEvent'] . "</td>";
            echo "<td><a href='editarEventoScript.php?idEvento=" . $row['idEvento'] . "'>Editar</a></td>";
            echo "</tr>";
        }
        ?>
    </table>

    <?php
    // Verificar si se recibió el ID del evento desde la solicitud
    if (isset($_GET['idEvento'])) {
        $idEvento = $_GET['idEvento'];

        // Consultar los datos del evento
        $consulta = "SELECT * FROM evento WHERE idEvento = ? AND idOrg = ?"; // Usamos marcadores de posición en la consulta
        $stmt = $conexion->prepare($consulta);
        $stmt->bind_param("ss", $idEvento, $idOrganizador);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $evento = $resultado->fetch_assoc();
    ?>
            <h2>Editar Evento</h2>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="hidden" name="idEvento" value="<?php echo $evento['idEvento']; ?>">
                <label for="nombreEvent">Nombre del Evento:</label>
                <input type="text" name="nombreEvent" value="<?php echo $evento['nombreEvent']; ?>"><br><br>

                <label for="fechaEvent">Fecha:</label>
                <input type="text" name="fechaEvent" value="<?php echo $evento['fechaEvent']; ?>"><br><br>
                <label for="lugarEvent">Lugar:</label>
                <input type="text" name="lugarEvent" value="<?php echo $evento['lugarEvent']; ?>"><br><br>
                <label for="activoEvent">Activo:</label>
                <input type="text" name="activoEvent" value="<?php echo $evento['activoEvent']; ?>"><br><br>
                <input type="submit" value="Guardar cambios">
            </form>
    <?php
        } else {
            echo "El evento no existe o no tienes permiso para editarlo.";
        }
    }
    ?>
    <div class="botones">
        <a href="MenuOrganizadores.php"><button>Volver</button></a>
    </div>
</body>
</html>