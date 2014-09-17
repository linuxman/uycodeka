<?php
include ("../conectar.php"); 
include ("../funciones/fechas.php"); 

$accion=$_POST["accion"];
if (!isset($accion)) { $accion=$_GET["accion"]; }

$fecha=$_POST["fecha"];
if ($fecha<>"") { $fecha=explota($fecha); } else { $fecha="0000-00-00"; }
$valor=$_POST["valor"];


if ($accion=="alta") {
	$query_operacion="INSERT INTO tipocambio (codtipocambio, fecha, valor) 
					VALUES (null, '$fecha', '$valor')";					
	$rs_operacion=mysql_query($query_operacion);
	if ($rs_operacion) { $mensaje="El tipo de cambio ha sido dado de alta correctamente"; }
	$cabecera1="Inicio >> tipo de cambio &gt;&gt; Nuevo tipo de cambio ";
	$cabecera2="INSERTAR tipo de cambio ";
	$sel_maximo="SELECT max(codtipocambio) as maximo FROM tipocambio";
	$rs_maximo=mysql_query($sel_maximo);
	$codtipocambio=mysql_result($rs_maximo,0,"maximo");
}

if ($accion=="modificar") {
	$codtipocambio=$_POST["codtipocambio"];
	$query="UPDATE tipocambio SET fecha='$fecha', valor='$valor' WHERE codtipocambio='$codtipocambio'";
	$rs_query=mysql_query($query);
	if ($rs_query) { $mensaje="Los datos del tipo de cambio han sido modificados correctamente"; }
	$cabecera1="Inicio >> tipo de cambio &gt;&gt; Modificar tipocambio ";
	$cabecera2="MODIFICAR tipo de cambio ";
}

if ($accion=="baja") {

	$codtipocambio=$_GET["codtipocambio"];
	$query_mostrar="SELECT * FROM tipocambio WHERE codtipocambio='$codtipocambio'";
	$rs_mostrar=mysql_query($query_mostrar);

	$fecha=mysql_result($rs_mostrar,0,"fecha");
	$valor=mysql_result($rs_mostrar,0,"valor");
	
	$query="DELETE FROM `codeka`.`tipocambio` WHERE `tipocambio`.`codtipocambio` = '$codtipocambio'";
	$rs_query=mysql_query($query);
	if ($rs_query) { $mensaje="El tipo de cambio ha sido eliminado correctamente"; }
	$cabecera1="Inicio >> tipo de cambio &gt;&gt; Eliminar tipocambio ";
	$cabecera2="ELIMINAR tipo de cambio ";
}

?>

<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script src="js/jquery.min.js"></script>
		<script language="javascript">
		
		var cursor;
		if (document.all) {
		// Está utilizando EXPLORER
		cursor='hand';
		} else {
		// Está utilizando MOZILLA/NETSCAPE
		cursor='pointer';
		}
		
		function aceptar() {
			parent.$('idOfDomElement').colorbox.close();
		}
		
		</script>
	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header"><?php echo $cabecera2?></div>
				<div id="frmBusqueda">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="15%">C&oacute;digo</td>
							<td width="85%" colspan="2"><?php echo $codtipocambio?></td>
					    </tr>
						<tr>
							<td width="15%">Fecha</td>
						    <td width="85%" colspan="2"><?php echo $fecha?></td>
					    </tr>
						<tr>
							<td width="15%">Valor</td>
							<td width="85%" colspan="2"><?php echo $valor?></td>
					    </tr>						
					</table>
			  </div>
				<div>
					<img id="botonBusqueda" src="../img/botonaceptar.jpg" width="85" height="22" onClick="aceptar()" border="1" onMouseOver="style.cursor=cursor">
			  </div>
			 </div>
		  </div>
		</div>
	</body>
</html>
