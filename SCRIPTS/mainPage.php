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
	<div class="contenedor">
		<h1>Eventualia, tu portal de eventos de confianza</h1>
		<p>¿Qué quieres hacer?</p>
	</div>
	<div class="botones">
		<a href="apuntarseEventos.php"><button>Apuntarme a Eventos</button></a>
		<a href="verPerfilUsuario.php"><button>Ver Perfil Usuario</button></a>
		<a href="logInOrganizador.php"><button>Gestionar Eventos</button></a>
		<a href="cerrarSesion.php"><button>Cerrar Sesion</button></a>
	</div>
</body>
</html>
