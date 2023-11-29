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

// Verificar si se recibió el ID del evento desde la solicitud
if (isset($_GET['idEvent'])) {
    $eventId = $_GET['idEvent'];

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
