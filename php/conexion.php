<?php
//Realizamos la conexión a la BD
$conexion = new mysqli("localhost", "root", "", "7138_proyecto3");
if (!$conexion) {
		    echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
		    echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
		    echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
		    exit;
		}
//Codificamos los campos
$acentos = mysqli_query($conexion, "SET NAMES 'utf8'");
//Consulta de busqueda del usuario
?>