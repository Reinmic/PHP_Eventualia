<?php
require_once('CreaBD.php');
require_once('constantes.php');

// Iniciar sesión
session_start();

if (!isset($_COOKIE[$cookie_usuario_id])) {
	// Si la cookie no ha sido creada, crearla y requerir la creación de la base de datos
	setcookie($cookie_usuario_id, 'true', time() + 86400);
}

// Establecer la conexión a la base de datos
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

// Comprobar si la conexión es exitosa
if (!$conn) {
	die("La conexión a la base de datos falló: " . mysqli_connect_error());
}

// Comprobar si se ha enviado el formulario de inicio de sesión
if (isset($_POST['login'])) {
	$username = $_POST['username'];
	$password = $_POST['password'];

	// Consultar la base de datos para verificar las credenciales del usuario
	$sql = "SELECT * FROM asistente WHERE usernameAsist = '$username' AND contraAsist = '$password'";
	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) == 1) {
		// Si las credenciales son válidas, guardar el ID del usuario en la variable de sesión
		$row = mysqli_fetch_assoc($result);
		$_SESSION['idAsistente'] = $row['idAsistente'];

		// Si las credenciales son válidas, redirigir al usuario a la página de inicio
		header("Location: mainPage.php");
		exit;
	} else {
		// Si las credenciales son inválidas, mostrar un mensaje de error
		$error_msg = "Nombre de usuario o contraseña incorrectos, intenalo de nuevo o registrate si no lo estas.";
	}
}



// Comprobar si se ha enviado el formulario de registro
if (isset($_POST['registro'])) {
	$idasist = $_POST['idasist'];
	$nombre = $_POST['nombre'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$mailasist = $_POST['mailasist'];

	// Consultar la base de datos para verificar si el nombre de usuario ya está en uso
	$sql = "SELECT * FROM asistente WHERE usernameAsist = '$username'";
	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) == 0) {
		// Si el nombre de usuario no está en uso, insertar el nuevo usuario en la base de datos
		$sql = "INSERT INTO asistente (idAsistente, nombreAsist, usernameAsist, contraAsist, mailAsist, fechaRegistro) VALUES ('$idasist', '$nombre', '$username', '$password', '$mailasist', NOW())";
		mysqli_query($conn, $sql);

		// Redirigir al usuario a la página de inicio
		header("Location: index.php");
		exit;
	} else {
		// Si el nombre de usuario ya está en uso, mostrar un mensaje de error
		$error_msg = "El nombre de usuario ya está en uso. Por favor, elija otro.";
	}
}

// Comprobar si la base de datos y las tablas necesarias existen
$sql = "SHOW DATABASES LIKE '$db_name'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
	// Si la base de datos no existe, crearla
	$sql = "CREATE DATABASE $db_name";
	mysqli_query($conn, $sql);

	// Seleccionar la nueva base de datos
	mysqli_select_db($conn, $db_name);

	// Crear la tabla de usuarios
	$sql = "CREATE TABLE IF NOT EXISTS asistente (
    idAsistente INT PRIMARY KEY AUTO_INCREMENT,
    nombreAsist VARCHAR(200) NOT NULL,
    usernameAsist VARCHAR (12) NOT NULL,
    contraAsist VARCHAR(30) NOT NULL,
    mailAsist VARCHAR(60) NOT NULL,
	fechaRegistro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	estado_sesion tinyint(0) DEFAULT NULL
    );";
	mysqli_query($conn, $sql);
} else {
	// Si la base de datos ya existe, seleccionarla
	mysqli_select_db($conn, $db_name);

	// Comprobar si la tabla de usuarios existe
	$sql = "SHOW TABLES LIKE 'asistente'";
	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) == 0) {
		// Si la tabla de usuarios no existe, crearla
		$sql = "CREATE TABLE IF NOT EXISTS asistente (
        idAsistente INT PRIMARY KEY AUTO_INCREMENT,
        nombreAsist VARCHAR(200) NOT NULL,
        usernameAsist VARCHAR (12) NOT NULL,
        contraAsist VARCHAR(30) NOT NULL,
        mailAsist VARCHAR(60) NOT NULL,
		fechaRegistro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		estado_sesion tinyint(0) DEFAULT NULL
        );";
		mysqli_query($conn, $sql);
	}
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>

<head>
	<title>Iniciar sesión o registrarse</title>
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

		h2 {
			margin-top: 30px;
			margin-bottom: 10px;
			color: #a0c28f;
		}

		form {
			background-color: #a0c28f;
			border-radius: 5px;
			box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
			padding: 30px;
			max-width: 600px;
			margin: 0 auto;
		}

		label {
			display: block;
			margin-bottom: 5px;
			color: #333;
		}

		input[type="text"],
		input[type="password"] {
			background-color: #fff;
			width: 100%;
			padding: 10px;
			border-radius: 5px;
			border: none;
			margin-bottom: 20px;
		}

		input[type="submit"] {
			background-color: #fff;
			color: #333;
			padding: 10px 20px;
			border: none;
			border-radius: 5px;
			cursor: pointer;
		}

		input[type="submit"]:hover {
			background-color: #000;
		}

		.error-msg {
			color: red;
			font-weight: bold;
			margin-bottom: 20px;
		}
	</style>
</head>

<body>
	<h1>Iniciar sesión o registrarse</h1>

	<?php if (isset($error_msg)) { ?>
		<p class="error-msg"><?php echo $error_msg; ?></p>
	<?php } ?>

	<div class="container">
		<h2>Iniciar sesión</h2>
		<form method="POST" action="">
			<label for="username">Nombre de usuario:</label>
			<input type="text" name="username" required>

			<label for="password">Contraseña:</label>
			<input type="password" name="password" required>

			<input type="submit" name="login" value="Acceder">
		</form>
	</div>

	<div class="container">
		<h2>Registrarse</h2>
		<form method="POST" action="" onsubmit="return showSuccessMessage()">
			<label for="nombre">Nombre:</label>
			<input type="text" name="nombre" required>

			<label for="username">Nombre de usuario:</label>
			<input type="text" name="username" required>

			<label for="password">Contraseña:</label>
			<input type="password" name="password" required>

			<label for="mailasist">Mail:</label>
			<input type="text" name="mailasist" required>

			<input type="submit" name="registro" value="Registrarse">
		</form>
	</div>

	<script>
		function showSuccessMessage() {
			alert("¡Registro exitoso! Bienvenido a nuestro sitio.");
			return true;
		}
	</script>
</body>

</html>