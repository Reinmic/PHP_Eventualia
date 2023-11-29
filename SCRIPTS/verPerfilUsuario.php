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

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['idAsistente'])) {
    // Redireccionar a la página de inicio de sesión o mostrar un mensaje de error
    header("Location: index.php");
    exit();
}

// Obtener el ID del usuario de la sesión
$idUsuario = $_SESSION['idAsistente'];

// Consulta para obtener los datos del usuario y los eventos a los que se ha apuntado
$sql = "SELECT asistente.nombreAsist, asistente.usernameAsist, evento.nombreEvent
        FROM asistente
        INNER JOIN registro ON asistente.idAsistente = registro.IdAsist
        INNER JOIN evento ON evento.idEvento = registro.idEvent
        WHERE asistente.idAsistente = $idUsuario";

$result = $conexion->query($sql);

// Consultar los datos del usuario
$sql = "SELECT nombreAsist, usernameAsist FROM asistente WHERE idAsistente = $idUsuario";
$result = $conexion->query($sql);

if ($result->num_rows > 0) {
    echo "<br>";
    echo "<table>";
    
    // Mostrar los datos del usuario
    $row = $result->fetch_assoc();
    echo "<tr>";
    echo "<th>Nombre Usuario</th>";
    echo "<td>" . $row['nombreAsist'] . "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<th>Username</th>";
    echo "<td>" . $row['usernameAsist'] . "</td>";
    echo "</tr>";
    
    // Consultar los eventos a los que se ha apuntado el usuario
    $sql = "SELECT e.idEvento, e.nombreEvent FROM evento e INNER JOIN registro r ON e.idEvento = r.idEvent WHERE r.IdAsist = $idUsuario";
    $result = $conexion->query($sql);

    if ($result->num_rows > 0) {
        echo "<tr>";
        echo "<th>ID Evento</th>";
        echo "<th>Nombre Evento</th>";
        echo "<th>Acción</th>";
        echo "</tr>";
        
        // Mostrar los eventos y agregar el botón para desapuntarse
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['idEvento'] . "</td>";
            echo "<td>" . $row['nombreEvent'] . "</td>";
            echo "<td><button onclick=\"desapuntarse(" . $row['idEvento'] . ")\">Desapuntarse</button></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='3'>No se han encontrado eventos apuntados.</td></tr>";
    }

    echo "</table>";
} else {
    echo "<tr><td colspan='2'></td></tr>";
}

// Verificar si se recibió el ID del evento desde la solicitud
if (isset($_GET['idEvent'])) {
    $eventId = $_GET['idEvent'];

    // Verificar si el usuario ha iniciado sesión
    if (!isset($_SESSION['idAsistente'])) {
        // Redireccionar a la página de inicio de sesión o mostrar un mensaje de error
        header("Location: index.php");
        exit();
    }

    // Eliminar el registro del usuario en el evento
    $sql = "DELETE FROM registro WHERE idEvent = $eventId AND IdAsist = $idUsuario";
    if ($conexion->query($sql) === TRUE) {
        echo "Te has desapuntado del evento con éxito.";
    } else {
        echo "Error al desapuntarte del evento: " . $conexion->error;
    }
}

// Cerrar la conexión
$conexion->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Formulario</title>
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
    </style>
    <script>
        function desapuntarse(eventId) {
            // Enviar una solicitud AJAX para desapuntarse del evento
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Actualizar la página o mostrar un mensaje de éxito
                    location.reload(); // Actualizar la página
                    //alert("Te has desapuntado del evento con éxito.");
                }
            };
            xhttp.open("GET", "desapuntarse.php?idEvent=" + eventId, true);
            xhttp.send();
        }
    </script>
</head>

<body>
    <form action="mainPage.php" method="post">
        <tr>
            <td colspan="2" style="text-align: center;"><input type="submit" value="Volver"></td>
        </tr>
    </form>
</body>

</html>