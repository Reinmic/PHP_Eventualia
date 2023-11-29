<?php
$servername = "localhost";
$username = "root";
$password = "";

$conn = mysqli_connect($servername, $username, $password);


if (!$conn) {
    die("Error al conectarse a la base de datos: " . mysqli_connect_error());
}


$sql = "
    CREATE DATABASE IF NOT EXISTS eventualia;
    USE eventualia;
    
    CREATE TABLE IF NOT EXISTS organizador (
        idOrganizador INT PRIMARY KEY AUTO_INCREMENT,
        nombreOrg VARCHAR(200) NOT NULL,
        usernameOrg VARCHAR(12) NOT NULL,
        contraOrg VARCHAR(32) NOT NULL,
        mailOrg VARCHAR(100) NOT NULL
    );
    INSERT INTO organizador (idOrganizador, nombreOrg, usernameOrg, contraOrg, mailOrg)
    VALUES (0001, 'Fiestukitas Locas', 'Fiestukasas','fiesta123', 'fiestukis@lokis.com'),
           (0002, 'Paryseo', 'Paryseo', 'paryseo123', 'party@seo.com');

    CREATE TABLE IF NOT EXISTS evento (
        idEvento INT PRIMARY KEY AUTO_INCREMENT,
        nombreEvent VARCHAR(200) NOT NULL,
        fechaEvent DATETIME NOT NULL,
        lugarEvent VARCHAR(200) NOT NULL,
        activoEvent tinyint(1) DEFAULT NULL,
        idOrg INT(11) NOT NULL,
        FOREIGN KEY (idOrg) REFERENCES organizador (idOrganizador)
    );
        
        
    INSERT INTO evento (nombreEvent, fechaEvent, lugarEvent, activoEvent, idOrg) VALUES 
        ('La Fiestoneta', '2023-07-25 20:00:00', 'La Marina, 25, Valencia', 1, 1),
        ('Fiesta Power', '2023-08-20 20:00:00', 'Calle Perreo Intenso, 24, TuCorazon', 1, 2);


    CREATE TABLE IF NOT EXISTS asistente (
        idAsistente INT PRIMARY KEY AUTO_INCREMENT,
        nombreAsist VARCHAR(200) NOT NULL,
        usernameAsist VARCHAR (12) NOT NULL,
        contraAsist VARCHAR(30) NOT NULL,
        mailAsist VARCHAR(60) NOT NULL,
        fechaRegistro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        estado_sesion tinyint(0) DEFAULT NULL
    );

    CREATE TABLE IF NOT EXISTS registro (
        idEvent INT NOT NULL,
        IdAsist INT NOT NULL,
        PRIMARY KEY (idEvent, IdAsist),
        FOREIGN KEY (idEvent) REFERENCES evento(idEvento),
        FOREIGN KEY (IdAsist) REFERENCES Asistente(idAsistente)
    );

";

if (mysqli_multi_query($conn, $sql)) {
   
} else {
    die("Error al crear la base de datos y las tablas: " . mysqli_error($conn));
}


mysqli_close($conn);
