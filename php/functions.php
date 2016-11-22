<?php
	//header("location:../index.php?nolog=2");
	/**
	/* Archivo que incluyen todas las funciones necesarias para el funcionamiento de la administración de usuarios 
	**/
	/*function pro3_add_user()
	{

	}*/
	/**
	* Función que nos devuelve la conexión con la BD
	**/
	function pro3_conexion()
	{
		$conexion = new mysqli("localhost", "root", "", "7138_proyecto3");
		if (!$conexion) {
		    echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
		    echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
		    echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
		    exit;
		}
		//Codificamos los campos
		$acentos = mysqli_query($conexion, "SET NAMES 'utf8'");
		return ($conexion);
	}

	/**
	* Función que nos deshabilita el usuario
	* Devuelve true si ha podido ser deshabilitado o False si no ha podido deshabilitarlo
	**/
	function pro3_disable_user($id)
	{
		$conexion=pro3_conexion();

		$disable_con="UPDATE `tbl_usuario` SET `usu_estado` = 'inactivo' WHERE `tbl_usuario`.`usu_id` = $id";
		mysqli_query($conexion,$disable_con);
		if(mysqli_affected_rows($conexion)==1){
			return true;
		}
		else{
			return false;
		}
	}
	/**
	* Función que noshabilita el usuario
	* Devuelve true si ha podido ser habilitado o False si no ha podido habilitarlo
	**/
	function pro3_enabled_user($id)
	{
		$conexion=pro3_conexion();

		$enable_con="UPDATE `tbl_usuario` SET `usu_estado` = 'activo' WHERE `tbl_usuario`.`usu_id` = $id";
		mysqli_query($conexion,$enable_con);
		if(mysqli_affected_rows($conexion)==1){
			return true;
		}
		else{
			return false;
		}
	}
	/**
	* Funcion que nos modifica el usuario
	* Devuelve True si ha podido ser modificado o false si no ha podido
	**/
	function pro3_update_user($id,$new_nick,$new_name,$new_lastname,$new_type,$new_mail,$new_cargo,$new_pass ,$new_foto)
	{
		$conexion=pro3_conexion();
		$update_con="UPDATE `tbl_usuario` SET `usu_nickname` = '".$new_nick."', `usu_nombre` = '".$new_name."', `usuti_id` = '".$new_type."', `usu_apellido` = '".$new_lastname."', `usu_correo` = '".$new_mail."', `usu_cargo` = '".$new_cargo."', `usu_foto` = '".$new_foto."', `usu_contrasena` = '".$new_pass."' WHERE `tbl_usuario`.`usu_id` = ".$id.";";
		mysqli_query($conexion,$update_con);
		if(mysqli_affected_rows($conexion)>0){
			return true;
		}
		else{
			return false;
		}
	}
?>