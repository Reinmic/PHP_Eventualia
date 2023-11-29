<?php
require_once('constantes.php');

// Iniciar sesión
session_start();

mysqli_report(MYSQLI_REPORT_ERROR);

// Usuario actual
$usuario = $_SESSION['idAsistente'];

// Conecta a la base de datos
$conexion = mysqli_connect($db_host, $db_user, $db_password, $db_name);

// Verifica si hubo un error en la conexión
if (mysqli_connect_errno()) {
    die("Error al conectar con la base de datos: " . mysqli_connect_error());
}

// Verifica si la cookie está definida
if (isset($_COOKIE[$cookie_usuario_id])) {
    // Obtiene el valor de la cookie
    $valorCookie = $_COOKIE[$cookie_usuario_id];
    
    // Escapa el valor de la cookie para evitar inyección de SQL
    $valorCookie = mysqli_real_escape_string($conexion, $valorCookie);
    
    // Crea la consulta SQL para insertar el valor de la cookie en la tabla de usuarios
    $consulta = "INSERT INTO registro (cookie) VALUES ('$valorCookie')";
    
    // Ejecuta la consulta SQL
    $resultado = mysqli_query($conexion, $consulta);
    
    // Verifica si hubo un error en la consulta SQL
    if (!$resultado) {
        echo "Error al insertar la cookie: " . mysqli_error($conexion);
    }
}

// Cierra la conexión a la base de datos
mysqli_close($conexion);

// Cierra la sesión
if (isset($_SESSION['idAsistente'])) {
    $usuario = $_SESSION['idAsistente'];
    $conexion = mysqli_connect($db_host, $db_user, $db_password, $db_name);
    $sql = "UPDATE asistente SET estado_sesion = 0 WHERE usernameAsist = '$usuario'";
    mysqli_query($conexion, $sql);
    mysqli_close($conexion);
}

// Destruye la sesión y redirige al usuario a la página de inicio de sesión
session_destroy();
header('Location: index.php');
exit;
?>
