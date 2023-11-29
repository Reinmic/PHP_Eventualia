<?php
require_once('constantes.php');

// Inicia la sesión
session_start();

// Habilita las excepciones de MySQLi
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

// Obtiene el ID del evento desde el formulario
$id = $_POST['id'];

// Prepara la consulta para eliminar el evento
$sql = "DELETE FROM evento WHERE idEvento=" . $id;

// Ejecuta la consulta
if ($conexion->query($sql) === TRUE) {
    echo "El evento ha sido eliminado exitosamente.";
} else {
    echo "Error al eliminar el evento: " . $conexion->error;
}

// Cierra la conexión
$conexion->close();
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
    <div class="botones">
        <a href="eliminarEventoMenu.php"><button>Volver</button></a>
    </div>
</body>
</html>