<?php
		session_start();
		if(!isset($_SESSION["usu_id"])) {
			header("location:../index.php?nolog=2");
		}
		extract($_REQUEST);
		
		require_once('functions.php');
		//realizamos la conexión
		$conexion=pro3_conexion();
		//comprobamos hemos realizado alguna accion
		if(isset($id))
		{
			$url="http://".$_SERVER['HTTP_HOST']."/proyecto3/php/administrador_usuarios.php";
				switch ($action) {
					case '1':
						$deshab=pro3_disable_user($id);
							if($deshab==true)
							{
								echo "Usuario deshabilitado correctamente";
								echo "<meta http-equiv='refresh' content='2;URL=".$url."'>";die;
							}
							else
							{
								echo "El usuario no ha sido deshabilitado";
								echo "<meta http-equiv='refresh' content='2;URL=".$url."'>";die;
							}
						break;//END case1
					case '2':
							$hab=pro3_enabled_user($id);
							if($hab==true)
							{
								echo "Usuario ha sido habilitado correctamente";
								echo "<meta http-equiv='refresh' content='2;URL=".$url."'>";die;
							}
							else
							{
								echo "El usuario no ha sido habilitado";
								echo "<meta http-equiv='refresh' content='2;URL=".$url."'>";die;
							}
							break;//END case2
					case '3':
						//echo $new_nick. "<br/>".$new_name . "<br/>" . " " . $new_lastname. "<br/>". $new_type . "<br/>". $new_mail . "<br/>". $new_cargo . "<br/>". $new_pass . "<br/>". $new_foto. "<br/>";
						$upd=pro3_update_user($id,$new_nick,$new_name,$new_lastname,$new_type,$new_mail,$new_cargo,$new_pass ,$new_foto);
						if($upd==true)
							{
								echo "Usuario ha sido actualizado correctamente";
								echo "<meta http-equiv='refresh' content='2;URL=".$url."'>";die;
							}
							else
							{
								echo "El usuario no ha sido actualizado";
								echo "<meta http-equiv='refresh' content='2;URL=".$url."'>";die;
							}
							break;//END case3
					case '4':
						$add_nick=$id;
						$add=pro3_create_user($add_nick,$add_name,$add_lastname,$add_type,$add_mail,$add_cargo,$add_pass ,$add_foto);
						if($add==true)
							{
								echo "Usuario ha sido actualizado correctamente";
								echo "<meta http-equiv='refresh' content='2;URL=".$url."'>";die;
							}
							else
							{
								echo "El usuario no ha sido actualizado";
								echo "<meta http-equiv='refresh' content='2;URL=".$url."'>";die;
							}
							break;//END case4
					default:
						//echo "Seleccione una accion";
					break;
				}
			//Si hemos hecho la accion 1 (Deshabilitar usuario) llamamos a la funcion que lo deshabilita
		}
		
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
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../css/recursos.css">
	<title>Recursos</title>
	<script type="text/javascript">
		function destroy(){
			var respuesta = confirm("¿Está seguro que desea eliminar este usuario?");
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
			<li class="li"><a href="administrador_recursos.php">Administrar recursos</a></li>
			<li class="li"><a href="administrador_reserva.php">Administrar reservas</a></li>
			<li class="li"><a href="administrador_usuarios.php">Administrar usuarios</a></li>
		</ul>
	</nav>
<div class="container">
	<div class="anadir">
		<h1> Añadir usuario </h1>
		<form action="administrador_usuarios.php?action=4" method="POST"  class="form_add">
			<table border>
				<tr>
					<td>Nick: <input type='textarea' name='id'></td>
						<td>Nombre: <input type='textarea' name='add_name'>
							Apellido: <input type='textarea' name='add_lastname'>
						</td>
						<td>Tipo de usuario: <input type='textarea' name='add_type'></td>	
					</tr>
					<tr>
						<td>Correo: <input type='textarea' name='add_mail'></td>
						<td>Cargo: <input type='textarea' name='add_cargo'></td>
						<td>Contraseña: <input type='textarea' name='add_pass'></td>
					</tr>
					<tr>
						<td colspan='3'>foto: <input type='textarea' name='add_foto'></td>
					</tr>
					<tr>
						<td colspan='3'><input type='submit' id='update' name='update' value='Crear usuario'></td>
					</tr>
			</table>
		</form>
	</div>
	<h1>Usuarios</h1>
	<br/>
	<?php
		if($_SESSION['user_type']!=3)
		{
			//Si nuestro ID de tipo No es de super administrador, hacemos la consulta para que no nos muestre a los super administradores.
			$con_display_users="SELECT * FROM `tbl_usuario` WHERE `usuti_id`<3";
		}
		//Sino, qyue los muestre todos
		else{
			$con_display_users="SELECT * FROM `tbl_usuario`";
		}
		//Hacemos la consulta para extraer todos los datos de tipos de usuario
		$con_user_type="SELECT * FROM `tbl_tipo_usuario`";
		$query_user_type=mysqli_query($conexion,$con_user_type);
		//echo $display_users;die;
		$display_users=mysqli_query($conexion,$con_display_users);
		if(mysqli_num_rows($display_users)>0){
								while($print_user	=	mysqli_fetch_array($display_users))
								{
									
									//Vamos mostrando los usuarios
										echo "<div class='content_rec'>";
										echo "<table border>";
											echo "<tr>";
												echo "<td>" . $print_user['usu_nickname'] . "</td>";
												echo "<td>" . $print_user['usu_nombre'] ." " . $print_user['usu_apellido'] .  "</td>";
													//Dependiendo del id del tipo de usuario, así lo escribiremos.
													switch ($print_user['usuti_id']) {
														case '2':
															echo "<td>Usuario administrador</td>";
															break;
														case '3':
															echo "<td>Usuario super administrador</td>";
															break;
														default:
															echo "<td> Usuario estándar</td>";
															break;
													}
											echo "</tr>";
											echo "<tr>";
												echo "<td rowspan='3'><img class='img_recu' src='../img/recursos/".$print_user['usu_foto']."' width='100'></td>";
												echo "<td>".$print_user['usu_correo']."</td>";
												echo "<td>".$print_user['usu_cargo']."</td>";
											echo "</tr>";
											echo "<tr>";
												echo "<td>Estado: " .$print_user['usu_estado']. "</td>";
												switch ($print_user['usu_estado']) {
													case 'activo':
														echo "</form><form action='administrador_usuarios.php?action=1&id=".$print_user['usu_id']."' method='POST'>";
															echo "<td colspan='2'><input type='submit' id='deshabilitar'value='Deshabilitar'></td>";
														echo "</form>";
														break;
													
													default:
														echo "</form><form action='administrador_usuarios.php?action=2&id=".$print_user['usu_id']."' method='POST'>";
															echo "<td colspan='2'>
																<input type='submit' id='habilitar' name='habilitar' value='Habilitar'>
															</td>";
														echo "</form>";
														break;
												}

											echo "</tr>";
											echo "<tr>";
											//echo "<a href='#' onclick='document.getElementById('div_up-form". $print_user['usu_id']."').style.display = 'block;'> Modificar usuario</a>";	
											echo "<td><button onclick=\"document.getElementById('div_up-form".$print_user['usu_id']."').style.display='block';\"> Modificar usuario</button></td>";	
											echo "<td><button onclick=\"document.getElementById('div_up-form".$print_user['usu_id']."').style.display='none';\"> [X]</button></td>";	
										echo "</table>";
										echo "</div>";
									
										//Creamos el formulario de modificación en una capa nueva
									echo "<div id='div_up-form".$print_user['usu_id']."' class='div_up-form' style='display:none;'>";
											echo "<form action='administrador_usuarios.php?action=3&id=".$print_user['usu_id']."' method='POST'>";
												echo "<table border>";
												echo "<tr>";
													echo"<td>Nick: <input type='textarea'name='new_nick' value=".$print_user['usu_nickname']."></td>";
													echo"<td>Nombre: <input type='textarea'name='new_name' value=".$print_user['usu_nombre'].">
															 Apellido: <input type='textarea'name='new_lastname' value=".$print_user['usu_apellido'].">
														</td>";
													echo"<td>Tipo de usuario: <input type='textarea'name='new_type' value=".$print_user['usuti_id']."></td>";
													/*echo "<td> <select name='new_type'>";
													//pintamos los tipos de usuario y sus valores
														while($pr_user_type	=	mysqli_fetch_array($query_user_type))
														{
															echo "<option value=" . $pr_user_type['usuti_id'] . ">" . $pr_user_type['usuti_nombre'] . "</option>";
														}
													echo "</select></td>";*/
												echo "</tr>";
												echo "<tr>";
													echo"<td>Correo: <input type='textarea'name='new_mail' value=".$print_user['usu_correo']."></td>";
													echo"<td>Cargo: <input type='textarea'name='new_cargo' value=".$print_user['usu_cargo']."></td>";
													
													echo"<td>Nueva contraseña: <input type='textarea'name='new_pass' value=".$print_user['usu_contrasena']."></td>";
												echo "</tr>";
												echo "<tr>";
													echo"<td colspan='3'>foto: <input type='textarea'name='new_foto' value=".$print_user['usu_foto']."></td>";
												echo "</tr>";	
												echo "<tr>";
													echo"<td colspan='3'><input type='submit' id='update' name='update' value='Modificar'></td>";
											echo "</table>";
											echo "</br>";
									echo "</div>";
								}
							}

			
			else {
				echo "error de volcado";
			}

		?>
	<br/><br/>
</div>
</body>
</html>