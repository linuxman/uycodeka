<?php
include ("../conectar.php"); 
include ("../funciones/fechas.php"); 

$codtipocambio=$_GET["codtipocambio"];

$query="SELECT * FROM tipocambio WHERE codtipocambio='$codtipocambio'";
$rs_query=mysql_query($query);

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
		
		function aceptar(codtipocambio) {
			location.href="guardar_tipocambio.php?codtipocambio=" + codtipocambio + "&accion=baja";
		}
		
		function cancelar() {
			parent.$('idOfDomElement').colorbox.close();
		}
		
		</script>
	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">ELIMINAR IMPUESTO </div>
				<div id="frmBusqueda">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="15%">C&oacute;digo</td>
							<td width="85%" colspan="2"><?php echo $codimpuesto?></td>
					    </tr>
						<tr>
							<td width="15%">Fecha</td>
						    <td width="85%" colspan="2"><?php echo mysql_result($rs_query,0,"fecha")?>
						    </td>
					    </tr>
						<tr>
							<td width="15%">Valor</td>
						    <td width="85%" colspan="2"><?php echo mysql_result($rs_query,0,"valor")?></td>
					    </tr>
					</table>
			  </div>
				<div>
					<img id="botonBusqueda" src="../img/botonaceptar.jpg" width="85" height="22" onClick="aceptar(<?php echo $codtipocambio;?>)" border="1" onMouseOver="style.cursor=cursor">
					<img id="botonBusqueda" src="../img/botoncancelar.jpg" width="85" height="22" onClick="cancelar()" border="1" onMouseOver="style.cursor=cursor">
			  </div>
			 </div>
		  </div>
		</div>
	</body>
</html>
