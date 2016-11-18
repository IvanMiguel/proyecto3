<?php 
	session_start();
		if(!isset($_SESSION["usu_id"])) {
			header("location:../index.php?nolog=2");
		}
 ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../css/recursos.css">
		<title>Mis recursos</title>
		<script type="text/javascript">
		function destroy(){
			var respuesta = confirm("¿Está seguro que desea liberar este recurso?");
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
	<?php
	//session_start();
	$mysqli = new mysqli("localhost", "root", "", "bd_proyecto2");
	$acentos = mysqli_query($mysqli, "SET NAMES 'utf8'");

	//Cogemos el nombre de usuario y la imagen de forma dinámica en la BD
	$con =	"SELECT * FROM `tbl_usuario` WHERE `usu_id` = '". $_SESSION["usu_id"] ."'";
	//echo $con;
		//Lanzamos la consulta a la BD
		$result	=	mysqli_query($mysqli,$con);
		while ($fila = mysqli_fetch_row($result)) 
			{
				$usu_nickname	=	$fila[1];
				$usu_img	=	$fila[6];
				
			}
			
	?>
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
				<li class="li"><a href="recursos.php">Recursos</a></li>
				<li class="li"><a href="#">Mis recursos</a></li>
				<li class="li"><a href="historial_recursos.php">Historial de recursos</a></li>
			</ul>
		</nav>
		<div class="container">
			<p class="reserved"> Tus recursos reservados són:</p> <br/>
			
				
			</div>
			<?php
				//Seleccionamos todas las reservas que tiene asignado nuestro usuario
				$con 	=	"SELECT * FROM `tbl_reserva` WHERE `res_usuarioid` = " . $_SESSION["usu_id"] . " AND `res_fechafinal` IS NULL";
	
				$result 	=	mysqli_query($mysqli,$con);

				while($fila	=	mysqli_fetch_row($result)){

					//Extraemos los ID de los recursos
					//echo $fila[3] . "<br/>";
						//Seleccionamos los recursos correspodientes a las reservas del usuario
						$con_rec	=	"SELECT * FROM `tbl_recurso` WHERE `rec_id` = ".$fila[3]."";
						$result_rec 	=	mysqli_query($mysqli,$con_rec);
						echo "<div class='content_rec'>";
							while($fila_rec	=	mysqli_fetch_row($result_rec)){
									//echo $fila[0]
								echo "<table border>";
									echo "<tr>";
										echo "<td colspan='2'>" . $fila_rec[1] . "</td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td rowspan='3'><img class='img_recu' src='../img/recursos/".$fila_rec[2]."' width='100'></td>";
										echo "<td>".$fila_rec[3]."</td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td>Fecha de inicio: " . date("d-m-Y",strtotime($fila[1])) . "</td>";
									echo "</tr>";
									echo "<tr>";
										if(isset($fila[2])){
										echo "<td>Fecha de Final: ".date("d-m-Y",strtotime($fila[2]))."</td>";
									}
									else{
										echo "<td>En curso</td>";
									}
									echo "</tr>";
										//echo $fila[2];
									echo "<tr>";
													
											echo "<td colspan='1'> <a class='free_recu' href='incidencia.php?rec_id=" .$fila[3]. "'>REPORTAR INCIDENCIA </a></td>";

											echo "<td colspan='2'> <a class='free_recu' href='liberar.proc.php?recu_id=" .$fila_rec[0]. "&res_id=" .$fila[0]. "' onclick='return destroy();'>LIBERAR RECURSO </a></td>";
										echo "</tr>";
								echo "</table>";
							}
						echo "</div>";
					
				}

			?>
	</body>
</html>