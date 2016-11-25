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
		* Función que nos comprueba el estado de un usuario
		* Devuelve true si esta habilitado o False si no lo esta
	**/
	function pro3_stat_user($id){
		$conexion=pro3_conexion();
			$con_stat_user="SELECT * FROM `tbl_usuario` WHERE `usu_id`=".$id."";
			echo $con_stat_user;
			$user_stat=mysqli_query($conexion,$con_stat_user);
			while($stat=mysqli_fetch_array($user_stat)){
				if($stat['usu_estado']=="activo"){
					return true;
				}
				else{
					return false;
				}
			}
	}
	/**
		* Función que nos comprueba el estado de un recurso
		* Devuelve true si esta disponible o False si no lo esta
	**/
	function pro3_recu_stat($id)
	{
		$conexion=pro3_conexion();
		$con = "SELECT *  FROM `tbl_recurso` WHERE `rec_id` = ".$id."";
		$rec_estado=mysqli_query($conexion,$con);
		while($estado=mysqli_fetch_array($rec_estado))
		{
			if($estado['rec_estado']=="disponible")
			{
				return true;
			}
			else{
				return false;
			}
		}
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
		* Función que nos elimina un recurso y todas sus reservas cuya fecha final sean de hoy o superiores
		* Devuelve true si ha podido ser eliminarla o False si no ha podido eliminarla
	**/
	function pro3_disable_recu($id)
	{
		$cambio=0;
		$now = date("Y-m-d 00:00:00");
		$conexion=pro3_conexion();
			$del_con="UPDATE `tbl_recurso` SET `rec_estado` = 'Eliminado' WHERE `tbl_recurso`.`rec_id` = ".$id.";";
			
			$del_res="DELETE
						FROM `tbl_reserva`
						WHERE `res_fechafinal`>'".$now."' AND `res_recursoid`=".$id."";
			mysqli_query($conexion,$del_con);
			if(mysqli_affected_rows($conexion)!=0)
			{
				$cambio=1;
			}
			mysqli_query($conexion,$del_res);
		if($cambio==1)
		{
			return true;
		}
		else
		{
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
	/**
	* Funcion que nos modifica el recurso
	* Devuelve True si ha podido ser modificado o false si no ha podido
	**/
	function pro3_update_recu($id,$new_nombre,$new_descripcion,$new_estado,$new_imagen,$new_tipo){
		$conexion=pro3_conexion();
		$update_rec_con="UPDATE `tbl_recurso` SET `rec_nombre` = '".$new_nombre."', `rec_foto` = '".$new_imagen."', `rec_descripcion` = '".$new_descripcion."', `rec_tipoid` = '".$new_tipo."', `rec_estado` = '".$new_estado."' WHERE `tbl_recurso`.`rec_id` = ".$id.";";
		mysqli_query($conexion,$update_rec_con);
		if(mysqli_affected_rows($conexion)>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	/**
	* Funcion que nos crea  usuario
	* Devuelve True si ha podido ser modificado o false si no ha podido
	**/
	function pro3_create_user($add_nick,$add_name,$add_lastname,$add_type,$add_mail,$add_cargo,$add_pass ,$add_foto)
	{
		$conexion=pro3_conexion();
		$add_con="INSERT INTO `tbl_usuario` (`usu_nickname`, `usu_nombre`, `usuti_id`, `usu_apellido`, `usu_correo`, `usu_cargo`, `usu_foto`, `usu_contrasena`, `usu_estado`) VALUES ('".$add_name."', '".$add_name."', '".$add_type."', '".$add_lastname."', '".$add_mail."', '".$add_cargo."', '".$add_foto."', '".$add_pass."', 'Activo');";
		mysqli_query($conexion,$add_con);
		if(mysqli_affected_rows($conexion)>0){
			return true;
		}
		else{
			return false;
		}
	}

	function pro3_create_recu($add_nombre,$add_descripcion,$add_estado,$add_imagen,$add_type)
	{
		$conexion=pro3_conexion();
		$add_con_rec="INSERT INTO `tbl_recurso` (`rec_nombre`, `rec_foto`, `rec_descripcion`, `rec_tipoid`, `rec_estado`) VALUES ('".$add_nombre."', '".$add_imagen."', '".$add_descripcion."','".$add_type."','".$add_estado."');";
		mysqli_query($conexion,$add_con_rec);
		if(mysqli_affected_rows($conexion)>0){
			return true;
		}
		else{
			return false;
		}
	}
	/**
	* Funcion de eliminacion de reserva, poniendo la fecha actual como fecha final
	**/
	function pro3_del_res($id,$now)
	{
		$conexion=pro3_conexion();
		//$con_recu	=	"UPDATE `tbl_recurso` SET `rec_estado` = 'disponible' WHERE `tbl_recurso`.`rec_id` = ".$id_recu.";";
		$con_resu	=	"UPDATE `tbl_reserva` SET `res_fechafinal` = '".$now."' WHERE `tbl_reserva`.`res_id` =" . $id;
		//mysqli_query($conexion,$con_recu);
		mysqli_query($conexion,$con_resu);
		if(mysqli_affected_rows($conexion)>0){
			return true;
		}
		else{
			return false;
		}
	}

	function pro3_add_res($recurso,$fecha_ini,$fecha_final,$id_user)
	{
		//Comprobamos el restado del recurso, si el estado esta disponible seguimos
		$estado_rec=pro3_recu_stat($recurso);

		if($estado_rec=true)
		{
			$conexion=pro3_conexion();
			//Si fecha inicial es la actual, ponemos el estado como ocupado
			$now = date("Y-m-d H:00:00");
			if ($fecha_ini==$now)
			{
				$ch_rec_stat_con="UPDATE `tbl_recurso` SET `rec_estado` = 'ocupado' WHERE `tbl_recurso`.`rec_id` = ".$recurso.";";
				echo $ch_rec_stat_con;
				mysqli_query($conexion,$ch_rec_stat_con);
			}
		}//END if
		$create_res="INSERT INTO `tbl_reserva` (`res_fechainicio`, `res_fechafinal`, `res_recursoid`, `res_usuarioid`) VALUES ('".$fecha_ini."', '".$fecha_final."', '".$recurso."', '".$id_user."');";
		echo $create_res;
		mysqli_query($conexion,$create_res);
		if(mysqli_affected_rows($conexion)>0){
			return true;
		}
		else{
			return false;
		}

	}
?>