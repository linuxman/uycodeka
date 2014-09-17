<?php

  include("config.php"); 
  header('Content-Type: text/html; charset=UTF-8'); 

  $conexion=mysql_connect($Servidor,$Usuario,$Password) or die("Error: El servidor no puede conectar con la base de datos");
  $descriptor=mysql_select_db($BaseDeDatos,$conexion);
	mysql_query("SET NAMES utf8"); //Soluciona el tema de las ñ y los tildes

?>
