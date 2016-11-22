<?php
		
		//TODO: MEJORAR PLANTEAMIENTO CONTROL DE HORAS

		extract($_REQUEST);
		session_start();
		//Creamos la variable err_fechin y la ponemos a 0 para controlar los conflictos en la fecha inicial
		$err_fechin=0;
		//Creamos la variable err_fechfin y la ponemos a 0 para controlar los conflictos con la fecha final
		$err_fechfin=0;
		//incluimos la funcionalidad de conexión de php
		require_once('conexion.php');
		$usuario = $_SESSION['usu_id'];
			//$fecha_ini = date("Y-m-d H:i:s");
			//Montamos las fechas de inicio y las fechas finales
			$fecha_ini = $fecha_inicial. " " . $hora_inicial . ":00";
			$fecha_fin = $fecha_final. " " . $hora_final . ":00";
			//echo $fecha_ini . "Y fecha final: " . $fecha_fin;
		$now = date("Y-m-d H:i:s");
		//Seleccionamos todas las reservas del recurso que queremos reservar
		$search_rec = "SELECT * FROM `tbl_recurso` LEFT JOIN `tbl_reserva` ON tbl_recurso.rec_id=tbl_reserva.res_recursoid WHERE tbl_recurso.`rec_id` = $rec_id";
		$search_status = mysqli_query($conexion,$search_rec);
		//Recorremos el array de los valores devueltos
		while ($status = mysqli_fetch_array($search_status))
		{
			//Si nuestra fecha inicial coincide con otra fecha inicio de la BD, significa que el recurso estará ocupado, asi que subimos en 1 el err_fechin
			if(isset($status['res_fechainicio']))
			{
				if($fecha_ini == $status['res_fechainicio'])
				{

					$err_fechin++;
				}
			}
			//SI nuestra fecha final es menor a otra fecha final Y mayor a la fecha inicio significa que el recurso estará ocupado, así que subimos en 1 el err_fechfin.
			if(isset($status['res_fechafinal']))
			{
				if($fecha_fin<$status['res_fechafinal'] AND $fecha_fin>$status['res_fechainicio'])
					{	
						$err_fechfin++;
					}
			}
		}//END WHILE
		//Si las variables err_fechin y err_fechfin están a0 significa que el recurso estará disponible para esa hora.
		if($err_fechin==0 AND $err_fechfin==0)
		{
			//Lo insertamos
				$insertar_reserva = "INSERT INTO tbl_reserva (res_fechainicio, res_fechafinal, res_recursoid, res_usuarioid) VALUES ('$fecha_ini', '$fecha_fin', '$rec_id' , '$usuario')";
				$reservar_producto = mysqli_query($conexion, $insertar_reserva);
				//Si la fecha inicial es la actual, o la fecha final es superior a la actual
				if($fecha_ini==$now OR $fecha_fin>$now)
				{
					$sql = "UPDATE tbl_recurso SET rec_estado='ocupado' WHERE rec_id='$rec_id'";
					$reservar_producto = mysqli_query($conexion, $sql); 
				}

		}
		else
		{
			header('location: recursos.php?err_recu=1');
		}
		header('location: recursos.php');
?>