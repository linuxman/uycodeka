<?php
include ("../conectar.php"); 
include ("../funciones/fechas.php"); 


	if(isset($_GET['fecha'])) {
		$fecha=explota($_GET['fecha']);
		$query="SELECT * FROM tipocambio WHERE fecha<='$fecha' order by `fecha` DESC";
		$rs_query=mysql_query($query);
		echo mysql_result($rs_query,0,"valor");
	}
?>