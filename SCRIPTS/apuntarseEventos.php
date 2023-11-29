<?php
require_once('constantes.php');

// Iniciar sesión
session_start();

mysqli_report(MYSQLI_REPORT_ERROR);

// Establecer la conexión a la base de datos
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

// Verificar si la conexión ha sido exitosa
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Obtener los eventos activos
$sql = "SELECT * FROM evento WHERE activoEvent = 1";
$result = mysqli_query($conn, $sql);

// Mostrar los eventos en pantalla
if (mysqli_num_rows($result) > 0) {
    echo "<table>";
    echo "<tr>";
    echo "<th>Evento</th>";
    echo "<th>Lugar</th>";
    echo "<th>Fecha</th>";
    echo "<th></th>";
    echo "</tr>";
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["nombreEvent"] . "</td>";
        echo "<td>" . $row["lugarEvent"] . "</td>";
        echo "<td>" . $row["fechaEvent"] . "</td>";
        echo "<td>";
        echo "<form method='post'>";
        echo "<input type='hidden' name='idEvento' value='" . $row["idEvento"] . "' />";
        echo "<input type='submit' name='btnApuntarme' value='Apuntarme' />";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No hay eventos activos";
}

if(isset($_POST["btnApuntarme"])) {
    $idEvento = $_POST["idEvento"];
    $idAsistente = $_SESSION['idAsistente'];
    $sql = "SELECT * FROM registro WHERE idEvent = $idEvento AND idAsist = $idAsistente";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0) {
        echo "<p>Ya estás apuntado a este evento</p>";
    } else {
        $sql = "INSERT INTO registro (idEvent, idAsist) VALUES ($idEvento, $idAsistente)";
        if(mysqli_query($conn, $sql)) {
            echo "<p>Te has apuntado correctamente al evento</p>";
        } else {
            echo "Error al apuntarte al evento: " . mysqli_error($conn);
        }
    }
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Bienvenido a Eventualia</title>
	<link rel="stylesheet" type="text/css" href="estilos.css">
</head>
<body>
	<div class="botones">
		<a href="mainPage.php"><button>Volver</button></a>
	</div>
</body>
</html>