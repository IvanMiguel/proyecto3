<?php
extract($_POST);
//Incluimos la funcionalidad que nos realiza la conexión con la bd
require_once('functions.php');
$conexion=pro3_conexion();
//Consulta de busqueda del usuario
	$con =	"SELECT * FROM `tbl_usuario` WHERE `usu_nickname` = '". $name ."' AND `usu_contrasena` = '" . $pass . "'";	
	//Lanzamos la consulta a la BD
	$result	=	mysqli_query($conexion,$con);
	//Contamos los resultados que nos devuelve
	$total  = mysqli_num_rows($result); 
	//Ponemos el condicional según el nombre de registros que nos devuelva
	if($total>=1){
		//Iniciamos sessión para las variables de sesion
		session_start();
		while ($fila = mysqli_fetch_row($result)) 
		{
			//Asignamos la variable de session "usu_id" al ID del usuario
			$_SESSION['usu_id']	=	$fila[0];
			$tipo=$fila[3];
			$_SESSION['user_type']	=	$tipo;
			//Redireccionamos
			$stat=pro3_stat_user($_SESSION['usu_id']);
			//echo $stat;die;
			if($stat==true){
				if ($tipo==1) {
				header("location:recursos.php");	
				}else{
				header("location:administrador_recursos.php");
				}
			}
			else{
				header("location: ../index.php?nolog=3");
			}
			
		}
	}
	//Si no nos devuelve registros significa que el usuario o contraseña han sido incorrectos.
	else{		
		header("location: ../index.php?nolog=1");
	}
?>