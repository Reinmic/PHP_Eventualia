<?php
// Establecer la conexión a la base de datos
$db_host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "eventualia";
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

// Verificar si se ha establecido la conexión correctamente
if (!$conn) {
    die("Error al conectar con la base de datos: " . mysqli_connect_error());
}

// Iniciar la sesión
session_start();

// Obtener los datos enviados por GET
$idEvento = $_GET['idEvento'];
$idUsuario = $_GET['idUsuario'];

// Insertar el registro en la tabla "registro"
$sql = "INSERT INTO registro (idEvento, idAsist) VALUES ($idEvento, $idUsuario)";
if (mysqli_query($conn, $sql)) {
    echo "Registro guardado correctamente";
} else {
    echo "Error al guardar el registro: " . mysqli_error($conn);
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>