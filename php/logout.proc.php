<?php 
session_start();
	unset($_SESSION["usu_id"]);
	//echo $_SESSION["usu_id"];
session_destroy();
header("location:../index.php")
 ?>