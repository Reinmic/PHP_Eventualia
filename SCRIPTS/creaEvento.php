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

// Verifica si el organizador ha iniciado sesión
if (!isset($_SESSION["idOrganizador"])) {
    die("No ha iniciado sesión como organizador");
}

// Obtiene el id del organizador
$id_organizador = $_SESSION["idOrganizador"];

// Verifica si el organizador está en la tabla organizador
$consulta = "SELECT * FROM organizador WHERE idOrganizador = '$id_organizador'";
$resultado = $conexion->query($consulta);

if ($resultado->num_rows == 0) {
    die("No existe un organizador con ese ID");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["nombreEvent"]) && !empty($_POST["fechaEvent"]) && !empty($_POST["lugarEvent"]) && isset($_POST["activoEvent"])) {
    // Obtiene los datos del formulario
    $nombreEvent = $_POST["nombreEvent"];
    $fechaEvent = $_POST["fechaEvent"];
    $lugarEvent = $_POST["lugarEvent"];
    $activoEvent = $_POST["activoEvent"];

    // Inserta los datos en la base de datos
    $consulta = "INSERT INTO evento (nombreEvent, fechaEvent, lugarEvent, activoEvent, idOrg) 
                 VALUES ('$nombreEvent','$fechaEvent','$lugarEvent','$activoEvent','$id_organizador')";
    if (!$resultado = $conexion->query($consulta)) {
        echo "Lo sentimos. La aplicación no funciona<br>";
        echo "Error en la consulta: " . $consulta . "<br>";
        echo "Número de error: " . $conexion->errno . "<br>";
        echo "Error: " . $conexion->error . "<br>";
        exit;
    }

    echo "Los datos se han insertado correctamente en la base de datos";
}
?>



<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Agregar evento</title>
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
    <h1>Agregar evento</h1>
    <table>
        <form action="creaEvento.php" method="post">
            <tr>
                <th><label for="idEvento">Código:</label></th>
                <td><input type="text" id="idEvento" name="idEvento" value="Este campo se asigna automaticamente." disabled></td>
            </tr>
            <tr>
                <th><label for="nombreEvent">Nombre:</label></th>
                <td><input type="text" id="nombreEvent" name="nombreEvent" required></td>
            </tr>
            <tr>
                <th><label for="fechaEvent">Fecha y Hora:</label></th>
                <td><input type="datetime-local" id="fechaEvent" name="fechaEvent" required></td>
            </tr>
            <tr>
                <th><label for="lugarEvent">Lugar:</label></th>
                <td><input type="text" id="lugarEvent" name="lugarEvent" required></td>
            </tr>
            <tr>
                <th><label for="activoEvent">Activo:</label></th>
                <td>
                    <select id="activoEvent" name="activoEvent" required>
                        <option value="">Seleccionar</option>
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;"><input type="submit" value="Agregar evento"></td>
            </tr>
        </form>
    </table>
    <div class="botones">
        <a href="menuOrganizadores.php"><button>Volver</button></a>
    </div>

</body>

</html>