<?php 
include ("../../conectar.php"); 
include ("../../funciones/fechas.php"); 

$codequipo=$_GET["codequipo"];
	$e=$_GET['e'];

$query="SELECT * FROM equipos WHERE codequipo='$codequipo'";
$rs_query=mysql_query($query);

?>
<html>
	<head>
		<title>Principal</title>
		<link href="../../estilos/estilos.css" type="text/css" rel="stylesheet">
		<link href="../../calendario/calendar-blue.css" rel="stylesheet" type="text/css">
		<script type="text/JavaScript" language="javascript" src="../../calendario/calendar.js"></script>
		<script type="text/JavaScript" language="javascript" src="../../calendario/lang/calendar-sp.js"></script>
		<script type="text/JavaScript" language="javascript" src="../../calendario/calendar-setup.js"></script>		
		<script type="text/javascript" src="../../funciones/validar.js"></script>
		<script language="javascript">
		
		function cancelar() {
			var e=document.getElementById("e").value;
			location.href="index.php?e="+e;
		}
		
		var cursor;
		if (document.all) {
		/*/ Está utilizando EXPLORER*/
		cursor='hand';
		} else {
		/*/ Está utilizando MOZILLA/NETSCAPE*/
		cursor='pointer';
		}
		
		function limpiar() {
			document.getElementById("formulario").reset();
		}
	
		</script>
	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">MODIFICAR equipo </div>
				<div id="frmBusqueda">
				<form id="formulario" name="formulario" method="post" action="guardar_equipo.php">
				
				<table class="fuente8"><tr><td valign="top">
					<table class="fuente8" cellspacing=0 cellpadding=2 border=0>
						<tr>
							<td>Fecha&nbsp;de&nbsp;alta</td>
							<td><input NAME="fecha" type="text" class="cajaPequena" id="fecha" size="10" maxlength="10" value="<?php echo implota(mysql_result($rs_query,0,"fecha"));?>" readonly> 
							<img src="../../img/calendario.png" name="Image1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'">
        <script type="text/javascript">
					Calendar.setup(
					  {
					inputField : "fecha",
					ifFormat   : "%d/%m/%Y",
					button     : "Image1"
					  }
					);
		</script></td>
					    </tr>
					    <tr>
						  <td>Nº</td>
						  <td colspan="3"><input id="numero" type="text" autocomplete="off" class="cajaPequena" NAME="znumero" value="<?php echo mysql_result($rs_query,0,"numero");?>" maxlength="15"></td>
				      </tr>
						<tr>
							<td>Descripción </td>
							<td colspan="3"><input NAME="Adescripcion" type="text" class="cajaGrande" id="descripcion" size="45" value="<?php echo mysql_result($rs_query,0,"descripcion");?>" maxlength="45"></td>
					    </tr>
						<tr>
							<td>Alias </td>
							<td colspan="3"><input NAME="aalias" type="text" class="cajaGrande" id="alias" size="45" value="<?php echo mysql_result($rs_query,0,"alias");?>" maxlength="45"></td>
					    </tr>					</table></td><td>						
						<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td>Service</td>
							<td><SELECT type=text size=1 name="aservice" id="service" class="comboMedio">
							<?php
							 $tipo = array("Sin&nbsp;definir", "Sin&nbsp;Servicio","Con&nbsp;Mantenimiento", "Mantenimiento&nbsp;y&nbsp;Respaldos");
							$xx=0;
							foreach($tipo as $key => $tpo) {
								if ($key==mysql_result($rs_query,0,"service")){
							      echo "<option value='$xx' selected>$tpo</option>";
								} else {
							      echo "<option value='$xx'>$tpo</option>";
							   }
							$xx++;
							}
							?>
							</select></td>
						  </tr>	
						<tr>
							<td valign="top">Detalles</td>
						    <td><textarea name="adetalles" cols="41" rows="4" id="detalles" class="areaTexto"><?php echo mysql_result($rs_query,0,"detalles");?></textarea></td>
					    </tr>
					</table>
				</td></tr></table>				
				
			  </div>
				<div>
					<img id="botonBusqueda" src="../../img/botonaceptar.jpg" width="85" height="22" onClick="cancelar()" border="1" onMouseOver="style.cursor=cursor">
					<input id="e" name="e" value="<?php echo mysql_result($rs_query,0,"codcliente");?>" type="hidden">
			  </div>
			  </form>
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
