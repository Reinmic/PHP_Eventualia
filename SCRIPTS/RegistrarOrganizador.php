<?php
mysqli_report(MYSQLI_REPORT_ERROR);
  
  $servidor="localhost";
  $usuario=$_REQUEST["organizador"];
  $clave=$_REQUEST["admispass"];
  $bd=$_REQUEST["eventualia"];
  
if (strlen($usuario)>0 && strlen($bd)>0 )
{ 
  @$mysqli= new mysqli($servidor,$usuario,$clave);

if ($mysqli->connect_errno)
{
  echo "Fallo al conectar a Mysql: ".$mysqli->connect_error." ".$mysqli->connect_errno;
  die ("SALIDA DEL PROGRAMA. Error BBDD");
  
}
else{
   echo ("$usuario Te has conectado a MYSQL <br>");
   if (!$mysqli->select_db($bd))
      echo ("No existe la BBDD  $bd");
   else
	echo ("Conectado a $bd");
   }
}
else echo ("ERROR: FALTA EL USUARIO O LA BASE DE DATOS");

?>