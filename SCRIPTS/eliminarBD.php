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

// Borra todas las tablas de la base de datos
$sql = "DROP TABLE IF EXISTS registro, evento, organizador, asistente;";
if ($conn->multi_query($sql) === TRUE) {
    
} else {
    echo "Error borrando tablas: " . $conn->error;
}

// Borra la base de datos
$sql = "DROP DATABASE eventualia;";
if ($conn->query($sql) === TRUE) {
    echo "Base de datos destruira con exito";
} else {
    echo "Error destruyendo database: " . $conn->error;
}

// Cierra la conexion
$conn->close();

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
        <a href="index.php"><button>Volver a Crear la  base de Datos</button></a>
    </div>
</body>
</html>
