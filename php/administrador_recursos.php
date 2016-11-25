<?php
		session_start();
		if(!isset($_SESSION["usu_id"])) {
			header("location:../index.php?nolog=2");
		}
		//realizamos la conexión
		//require_once('conexion.php');
		require_once('functions.php');
		$conexion=pro3_conexion();
		//session_start();
		//Cogemos el nombre de usuario y la imagen de forma dinámica en la BD
		$con =	"SELECT * FROM `tbl_usuario` WHERE `usu_id` = '". $_SESSION["usu_id"] ."'";
		//echo $con;
		//Lanzamos la consulta a la BD
		$result	=	mysqli_query($conexion,$con);
		while ($fila = mysqli_fetch_row($result)) 
			{
				$usu_nickname	=	$fila[1];
				$usu_img	=	$fila[6];
			}
		$sql = "SELECT * FROM tbl_tiporecurso ORDER BY tr_id";

		$disponible =	" SELECT * FROM tbl_recurso INNER JOIN tbl_tiporecurso ON tbl_tiporecurso.tr_id = tbl_recurso.rec_tipoid WHERE rec_estado='disponible' ";

		$ocupado =	" SELECT * FROM tbl_recurso INNER JOIN tbl_tiporecurso ON tbl_tiporecurso.tr_id = tbl_recurso.rec_tipoid WHERE rec_estado='ocupado' ";
		$incidencia = " SELECT * FROM tbl_recurso INNER JOIN tbl_tiporecurso ON tbl_tiporecurso.tr_id = tbl_recurso.rec_tipoid INNER JOIN tbl_incidencia ON tbl_incidencia.inc_recursoid = tbl_recurso.rec_id INNER JOIN tbl_tipoinc ON tbl_incidencia.inc_tipinc = tbl_tipoinc.ti_id WHERE rec_estado='incidencia'";
		extract($_REQUEST);

		if(isset($enviar)){
		 	if($tr_id>0){
		 		$disponible .= " AND rec_tipoid='$tr_id '";
		 		$ocupado .= " AND rec_tipoid='$tr_id' ";
		 		$incidencia .=" AND rec_tipoid= '$tr_id'";
		 	}
		}

		if(isset($action)){
			$url="http://".$_SERVER['HTTP_HOST']."/proyecto3/php/administrador_recursos.php";
			switch ($action) {
				case '1':
					//echo $id;die;
					$del_recu=pro3_disable_recu($id);
					if($del_recu==true){
						echo "El recurso ha sido eliminado correctamente";
						echo "<meta http-equiv='refresh' content='2;URL=".$url."'>";die;
					}
						else{
							echo "no se ha podido eliminar correctamente";
							echo "<meta http-equiv='refresh' content='2;URL=".$url."'>";die;
						}
					
					break;
				case'3':
					$upd_recu=pro3_update_recu($id,$new_nombre,$new_descripcion,$new_estado,$new_imagen,$new_type);
					if($upd_recu==true)
					{
						echo "El recurso ha sido modifcado correctamente";
						echo "<meta http-equiv='refresh' content='2;URL=".$url."'>";die;
					}
					else{
						echo "el recurso no ha podido ser modificado";
						echo "<meta http-equiv='refresh' content='2;URL=".$url."'>";die;
					}
					break;
				case '4':
					$create_recu=pro3_create_recu($add_nombre,$add_descripcion,$add_estado,$add_imagen,$add_type);
					if($create_recu==true)
					{
						echo "El recurso ha sido creado";
						echo "<meta http-equiv='refresh' content='2;URL=".$url."'>";die;
					}
					else{
						echo "El recurso no ha sido creado";
						echo "<meta http-equiv='refresh' content='2;URL=".$url."'>";die;
					}
					break;
				default:
					# code...
					break;
			}
		}
		//echo $incidencia;die;
		$tipos = mysqli_query($conexion, $sql);
		$recursos = mysqli_query($conexion, $disponible);
		$recursos1 = mysqli_query($conexion, $ocupado);
		$recursos2 = mysqli_query($conexion, $incidencia);

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../css/recursos.css">
	<title>Recursos</title>
	<script type="text/javascript">

		function del()
		{
			var respuesta = confirm("¿Está seguro que desea eliminar el recurso y todas sus reservas activas?");
			if(respuesta){
				return true;
			}
			else{
				return false;
			}
		}
		function destroy(){
			var respuesta = confirm("¿Está seguro que desea liberaresta incidencia?");
			if(respuesta){
				return true;
			}
			else{
				return false;
			}
			
		}
		function logout()
		{
			var login_respuesta = confirm("¿Está seguro que desea cerrar la sesión?");
			if(login_respuesta){
				return true;
			}
			else{
				return false;
			}
		}
	</script>
</head>
<body>
	<div class="header">
			<div class="logo">
				<a href="#"></a>
			</div>
			<h1 align="center">Gestión de recursos</h1>
			<div class="profile">
			<p class="welcome">Hola bienvenido, <br /><b>
			<?php echo $usu_nickname; ?></b>
			
			</p>
			</div>
			<div class="logout">
				<a href="logout.proc.php" onclick="return logout();">
					<img class="img_logout" src="../img/logout_small.png" alt="Cerrar sesión">
				</a>
			</div>
		</div>
	<nav>
		<ul class="topnav">	
			<li class="li"><a href="#">Administrar recursos</a></li>
			<li class="li"><a href="administrador_reserva.php">Administrar reservas</a></li>
			<li class="li"><a href="administrador_usuarios.php">Administrar usuarios</a></li>
		</ul>
	</nav>
<div class="container">
	<?php
	if(mysqli_num_rows($tipos)>0){
		?>
	<form action="administrador_recursos.php" method="get" class="formtipo">
		Tipo de recurso:
		<select name="tr_id">
			<option value="0">-- Elegir tipo --</option>
			<?php
					while($tipo=mysqli_fetch_array($tipos)){
						echo "<option value=" . $tipo['tr_id'] . ">" . $tipo['tr_nombre'] . "</option>";
					}
				?>
		</select>
		<input type="submit" name="enviar" value="Filtrar">
	</form>
	<br/><br/>
		<h1>Añadir recurso </h1>
			<div class="add_recu">
			<form action='administrador_recursos.php?action=4' method= 'POST'>
				<table border>
					<tr>
						<td colspan='3'>Nombre del recurso: <input type='textarea' name='add_nombre' ></td>							
					</tr>
					<tr>
						<td colspan='2'>Descripcion: <input type='textarea' name='add_descripcion' ></td>
						<td>Estado: <input type='textarea' name='add_estado' ></td>
					</tr>
					<tr>
						<td colspan='2'>Imagen del recurso: <input type='textarea' name='add_imagen'></td>
						<td>Nuevo tipo: <input type='textarea' name='add_type'></td>
					</tr>
					<tr>
						<td colspan='3'><input type='submit' value="Añadir"></td>	
					</tr>
				</table>
			</form>
		</div>
	<h1>Recursos Disponibles</h1>
	<br/>
	<?php
		}
		if(mysqli_num_rows($recursos)>0){
			
								while($recurso	=	mysqli_fetch_array($recursos)){
									echo "<div class='content_rec'>";
										//echo $fila[0]
									echo "<table border>";
										echo "<tr>";
											echo "<td colspan='3'>" . $recurso['rec_nombre'] . "</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td rowspan='4'><img class='img_recu' src='../img/recursos/".$recurso['rec_foto']."' width='100'></td>";
											echo "<td colspan='2'>".$recurso['rec_descripcion']."</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td colspan='2'>Estado: " .$recurso['rec_estado']. "</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td><button onclick=\"document.getElementById('div_up-form".$recurso['rec_id']."').style.display='block';\"> Modificar recurso</button></td>";
											echo "<td><button onclick=\"document.getElementById('div_up-form".$recurso['rec_id']."').style.display='none';\"> [X]</button></td>";
										echo "</tr>";
											echo "<form action='administrador_recursos.php?action=1&id=".$recurso['rec_id']."' method='POST' onclick='return del();'>";
													echo "<td colspan='2'><input type='submit' id='eliminar' value='eliminar'></td>";
											echo "</form>";
										echo "</tr>";
									echo "</table>";
									echo "</div>";
									echo "<div id='div_up-form".$recurso['rec_id']."' class='div_up-form'style='display: none;'>";
										echo "<form action='administrador_recursos.php?action=3&id=".$recurso['rec_id']."' method='POST'>";
											echo "<table border>";
											echo "<tr>";
												echo"<td colspan='3'>Nombre del recurso: <input type='textarea'name='new_nombre' value=".$recurso['rec_nombre']."></td>";
												
											echo "</tr>";
											echo "<tr>";
												echo"<td colspan='2'>Descripcion: <input type='textarea' name='new_descripcion' value=".$recurso['rec_descripcion']."></td>";
												echo"<td>Estado: <input type='textarea' name='new_estado' value=".$recurso['rec_estado']."></td>";
											echo "</tr>";
											echo "<tr>";
												echo"<td colspan='2'>Imagen del recurso: <input type='textarea'name='new_imagen' value=".$recurso['rec_foto']."></td>";	
												echo "<td>Nuevo tipo: <input type='textarea' name='new_type' value=".$recurso['rec_tipoid']."></td>";
											echo "</tr>";
											echo "<tr>";
												echo"<td colspan='3'>Imagen del recurso: <input type='submit' value=Modificar recurso></td>";	
											echo "</tr>";
											echo "</table>";
										echo "</form>";
									echo "</div>";
									echo "</br>";
	 

								}

			} else {
				echo "No hay recursos disponibles";
			}

		?>

	<br/><br/>
	<h1>Recursos en uso</h1>
	<br/>
	<?php
		if(mysqli_num_rows($recursos1)>0){
			
								while($recurso1	=	mysqli_fetch_array($recursos1)){
									echo "<div class='content_rec'>";
										//echo $fila[0]
									echo "<table border>";
										echo "<tr>";
											echo "<td colspan='2'>" . $recurso1['rec_nombre'] . "</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td rowspan='3'><img class='img_recu' src='../img/recursos/".$recurso1['rec_foto']."' width='100'></td>";
											echo "<td>".$recurso1['rec_descripcion']."</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td>Estado: " .$recurso1['rec_estado']. "</td>";
										echo "</tr>";
											//echo $fila[2];
														
									echo "</table>";
									echo "</div>";
									echo "</br>";
	 

								}

			} else {
				echo "No hay recursos disponibles";
			}

		?>

		<br/><br/>
	<h1>Recursos con incidencia</h1>
	<br/>
	<?php
		if(mysqli_num_rows($recursos2)>0){
			
								while($recurso2	=	mysqli_fetch_array($recursos2)){
									echo "<div class='content_rec'>";
										//echo $fila[0]
									echo "<table border>";
										echo "<tr>";
											echo "<td colspan='2'>" . $recurso2['rec_nombre'] . "</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td rowspan='3'><img class='img_recu' src='../img/recursos/".$recurso2['rec_foto']."' width='100'></td>";
											echo "<td>".$recurso2['rec_descripcion']."</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td>Estado: " .$recurso2['rec_estado']. "</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td colspan='1'>Tipo incidencia: " .$recurso2['ti_nombre']. "</td>";
										echo "</tr>";
										if ($recurso2['inc_comentario']==""){

										} else {
											echo "<tr>";
											echo "<td colspan='2'>Comentario: " .$recurso2['inc_comentario']. "</td>";
											echo "</tr>";
										}
										echo "<tr>";
												echo "<td colspan='2'> <a class='free_recu' href='administrador_inc.proc.php?rec_id=" .$recurso2['rec_id']. " 'onclick='return destroy();'>LIBERAR INCIDENCIA</a></td>";
											echo "</tr>";
										
											//echo $fila[2];
														
									echo "</table>";
									echo "</div>";
									echo "</br>";
	 

								}

			} else {
				echo "No hay recursos disponibles";
			}

		?>
</div>
</body>
</html>