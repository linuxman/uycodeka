<?php
include ("../conectar.php"); 

$codfamilia=$_GET["codfamilia"];
$cadena_busqueda=$_GET["cadena_busqueda"];

$query="SELECT * FROM familias WHERE codfamilia='$codfamilia'";
$rs_query=mysql_query($query);

?>

<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script language="javascript">
		
		function aceptar() {
			parent.$('idOfDomElement').colorbox.close();
		}
		
		var cursor;
		if (document.all) {
		// Está utilizando EXPLORER
		cursor='hand';
		} else {
		// Está utilizando MOZILLA/NETSCAPE
		cursor='pointer';
		}
		
		</script>
	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">VER FAMILIA </div>
				<div id="frmBusqueda">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="15%">C&oacute;digo</td>
							<td width="85%" colspan="2"><?php echo $codfamilia?></td>
					    </tr>
						<tr>
							<td width="15%">Nombre</td>
						    <td width="85%" colspan="2"><?php echo mysql_result($rs_query,0,"nombre")?></td>
					    </tr>						
					</table>
			  </div>
				<div>
					<img id="botonBusqueda" src="../img/botonaceptar.jpg" width="85" height="22" onClick="aceptar()" border="1" onMouseOver="style.cursor=cursor">
			  </div>
			 </div>
		  </div>
		</div>
		<div id="ErrorBusqueda" class="fuente8">
 <ul id="lista-errores" style="display:none; 
	clear: both; 
	max-height: 75%; 
	overflow: auto; 
	position:relative; 
	top: 85px; 
	margin-left: 30px; 
	z-index:999; 
	padding-top: 10px; 
	background: #FFFFFF; 
	width: 585px; 
	-moz-box-shadow: 0 0 5px 5px #888;
	-webkit-box-shadow: 0 0 5px 5px#888;
 	box-shadow: 0 0 5px 5px #888; 
 	bottom: 10px;"></ul>	
 
 	</div>		
	</body>
</html>
