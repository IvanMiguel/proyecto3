<?php

		
		session_start();
			//incluimos la funcionalidad de conexión de php
		require_once('conexion.php');
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

		extract($_REQUEST);
		if(isset($com_res)){
			$url="http://".$_SERVER['HTTP_HOST']."/proyecto3/php/recursos.php";
			switch ($com_res) {
				case '1':
					echo "El rescurso estara ocupado a esa hora";
					echo "<META HTTP-EQUIV='REFRESH' CONTENT='5';URL='".$url."'>";die;
					break;
				case '2':
				echo "Reserva realizada";
					echo "<META HTTP-EQUIV='REFRESH' CONTENT='5';URL='".$url."'>";die;
				default:
					# code...
					break;
			}
		}
		if(isset($enviar)){
		 	if($tr_id>0){
		 		$disponible .= " AND rec_tipoid='$tr_id '";
		 		$ocupado .= " AND rec_tipoid='$tr_id' ";
		 	}
		}

		$tipos = mysqli_query($conexion, $sql);
		$recursos = mysqli_query($conexion, $disponible);
		$recursos1 = mysqli_query($conexion, $ocupado);

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../css/recursos.css">
	<title>Recursos</title>
	<script type="text/javascript">
		function mostrar(){
			return true;
		}
		function destroy(){
			var respuesta = confirm("¿Está seguro que desea reservar este recurso?");
			if(respuesta){
				return true;
			}
			else{
				return false;
			}
			
		}

		</script>
		<script type="text/javascript">
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
			<li class="li"><a href="#">Recursos</a></li>
			<li class="li"><a href="misrecursos.php">Mis recursos</a></li>
			<li class="li"><a href="historial_recursos.php">Historial de recursos</a></li>
		</ul>
	</nav>
<div class="container">
	
	<?php
	if(isset($err_recu))
		{
			echo "<div class='content error'>
				No has podido reservar el recurso
				</div>";
		}
	if(mysqli_num_rows($tipos)>0){
		?>
	<form action="recursos.php" method="get" class="formtipo">
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
											echo "<td colspan='2'>" . $recurso['rec_nombre'] . "</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td rowspan='3'><img class='img_recu' src='../img/recursos/".$recurso['rec_foto']."' width='100'></td>";
											echo "<td>".$recurso['rec_descripcion']."</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td>Estado: " .$recurso['rec_estado']. "</td>";
										echo "</tr>";
										echo "<tr>";
														
												echo "<td colspan='2'> <a href='#'' onclick='return mostrar();'>RESERVAR RECURSO </a></td>";
											
											echo "</tr>"; 
											
														
									echo "</table>";
									echo "<form id='reserved' class='reservedf' action=recursos.proc.php?rec_id=".$recurso['rec_id']." method='POST' >";
										echo "<table border>";
											echo "<tr><td>Fecha inicial de la reserva</td>";
											//echo "<td> <input type='date' id='fecha_inicial' name='fecha_inicial' maxlength='15'></td></tr>";
											echo "<td>dia<select name='dia_inicial'>";
												for($i=01;$i<=31;$i++){
													echo "<option value='".$i."'>".$i."</option>";
												}

												echo "</select>mes<select name='mes_inicial'>";
												for($i=01;$i<=12;$i++){
													echo "<option value='".$i."'>".$i."</option>";
												}
												echo "</select>año<select name='ano_inicial'>";
												for($i=2016;$i<=2099;$i++){
													echo "<option value='".$i."'>".$i."</option>";
												}
											echo "</select>";
											/*echo "<td><select name='fecha_inicial'>";
												for($i=1;$i>=31;$i++){
													echo "<option value"
												}*/
											echo "<tr><td>Seleccione hora inicial</td>";
											//echo "<td> <input type='time' name='hora_inicial' id='hora_inicial'></td>";
											echo "<td><select name='hora_inicial'>";
												for($i=0;$i<=24;$i++)
												{
													echo "<option value='".$i.":00:00'>".$i.":00</option>";
												}
											echo "</select></td>";
											echo "<tr><td>Fecha final de la reserva</td>";
											//echo "<td> <input type='date' id='fecha_final' name='fecha_final' maxlength='15'></td></tr>";
											echo "<td>dia<select name='dia_final'>";
												for($i=01;$i<=31;$i++){
													echo "<option value='".$i."'>".$i."</option>";
												}

												echo "</select>mes<select name='mes_final'>";
												for($i=01;$i<=12;$i++){
													echo "<option value='".$i."'>".$i."</option>";
												}
												echo "</select>año<select name='ano_final'>";
												for($i=2016;$i<=2099;$i++){
													echo "<option value='".$i."'>".$i."</option>";
												}
											echo "</select>";
											echo "<tr><td>Seleccione hora final</td>";
											//echo "<td> <input type='time' name='hora_final' id='hora_final'></td>";
											echo "<td><select name='hora_final'>";
												for($i=0;$i<=24;$i++)
												{
													echo "<option value='".$i.":00:00'>".$i.":00</option>";
												}
											echo "<tr><td colspan='2'> <input type='submit' onclick='return rvalidar()' value='reservar'>";
										echo "</table></form>";
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
</div>
</body>
</html>