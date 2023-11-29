<?php
require_once('constantes.php');

// Iniciar sesión
session_start();

mysqli_report(MYSQLI_REPORT_ERROR);

// Conecta a la base de datos
$conexion = new mysqli($db_host, $db_user, $db_password, $db_name);

// Verifica si hubo un error en la conexión
if ($conexion->connect_error) {
	die("Error al conectar con la base de datos: " . $conexion->connect_error);
}

// Comprobar si se ha enviado el formulario de inicio de sesión
if (isset($_POST['login'])) {
	$username = $_POST['username'];
	$password = $_POST['password'];

	// Consultar la base de datos para verificar las credenciales del usuario
	$sql = "SELECT * FROM organizador WHERE usernameOrg = '$username' AND contraOrg = '$password'";
	$result = mysqli_query($conexion, $sql);

	if (mysqli_num_rows($result) == 1) {
		// Si las credenciales son válidas, guardar el ID del usuario en la variable de sesión
		$row = mysqli_fetch_assoc($result);
		$_SESSION['idOrganizador'] = $row['idOrganizador'];

		// Si las credenciales son válidas, redirigir al usuario a la página de inicio
		header("Location: menuOrganizadores.php");
		exit;
	} else {
		// Si las credenciales son inválidas, mostrar un mensaje de error
		$error_msg = "Nombre de usuario o contraseña incorrectos, contacta con nosotros si deseas ser un Organizador de Eventos.";
	}
}
?>

<!DOCTYPE html>
<html>

<head>
	<title>Iniciar sesión Organizadores</title>
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

		.btn-volver {
			background-color: #a0c28f;
			border: none;
			color: white;
			padding: 12px 24px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 16px;
			margin: 8px 16px;
			cursor: pointer;
			border-radius: 4px;
			transition-duration: 0.4s;
		}

		.btn-volver:hover {
			background-color: #3e8e41;
			color: white;
		}
	</style>
</head>

<body>
	<h1>Iniciar sesión Organizador/a</h1>

	<?php if (isset($error_msg)) { ?>
		<p class="error-msg"><?php echo $error_msg; ?></p>
	<?php } ?>

	<div class="container">
		<form method="POST" action="">
			<label for="username">Nombre de usuario:</label>
			<input type="text" name="username" required>

			<label for="password">Contraseña:</label>
			<input type="password" name="password" required>

			<input type="submit" name="login" value="Acceder">
		</form>
		<a href="mainPage.php">
			<button class="btn-volver">Volver</button>
		</a>
	</div>
</body>

</html>