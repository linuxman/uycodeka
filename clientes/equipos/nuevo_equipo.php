<?php include ("../../conectar.php");
	$e=$_GET['e'];
 ?>
<html>
	<head>
		<title>Principal</title>
		<link href="../../estilos/estilos.css" type="text/css" rel="stylesheet">
    <script src="../../calendario/jscal2.js"></script>
    <script src="../../calendario/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="../../calendario/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../../calendario/css/border-radius.css" />
    <link rel="stylesheet" type="text/css" href="../../calendario/css/win2k/win2k.css" />		
		<script type="text/javascript" src="../../funciones/validar.js"></script>
		<script language="javascript">
		
		function cancelar() {
			location.href="index.php?e=<?php echo $e;?>";
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
				<div id="tituloForm" class="header">INSERTAR EQUIPO </div>
				<div id="frmBusqueda">
				<form id="formulario" name="formulario" method="post" action="guardar_equipo.php">
				<table class="fuente8"><tr><td valign="top">
					<table class="fuente8" cellspacing=0 cellpadding=2 border=0>
						<tr>
							<td>Fecha&nbsp;de&nbsp;alta</td>
							<td><input NAME="fecha" type="text" class="cajaPequena" id="fecha" size="10" maxlength="10" readonly> 
							<img src="../../img/calendario.png" name="Image1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'">
						<script type="text/javascript">//<![CDATA[
						   Calendar.setup({
						     inputField : "fecha",
						     trigger    : "Image1",
						     align		 : "Bl",
						     onSelect   : function() { this.hide() },
						     dateFormat : "%d/%m/%Y"
						   });
						//]]></script>							
						</td>
					    </tr>
					    <tr>
						  <td>Nº</td>
						  <td colspan="3"><input id="numero" type="text" autocomplete="off" class="cajaPequena" NAME="znumero" maxlength="15"></td>
				      </tr>
						<tr>
							<td>Descripción </td>
							<td colspan="3"><input NAME="Adescripcion" type="text" class="cajaGrande" id="descripcion" size="45" maxlength="45"></td>
					    </tr>
						<tr>
							<td>Alias </td>
							<td colspan="3"><input NAME="aalias" type="text" class="cajaGrande" id="alias" size="45" maxlength="45"></td>
					    </tr>					    
					</table></td><td>						
						<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td>Service</td>
							<td><SELECT type=text size=1 name="aservice" id="service" class="comboMedio">
							<?php
							 $tipo = array("Sin&nbsp;definir", "Sin&nbsp;Servicio","Con&nbsp;Mantenimiento", "Mantenimiento&nbsp;y&nbsp;Respaldos");
							$xx=0;
							foreach($tipo as $tpo) {
							      echo "<option value='$xx'>$tpo</option>";
							$xx++;
							}
							?>
							</select></td>
						  </tr>	
						<tr>
							<td valign="top">Detalles</td>
						    <td><textarea name="adetalles" cols="41" rows="4" id="detalles" class="areaTexto"></textarea></td>
					    </tr>
					</table>
				</td></tr></table>
			  </div>
				<div>
					<img id="botonBusqueda" src="../../img/botonaceptar.jpg" width="85" height="22" onClick="validar(formulario,true)" border="1" onMouseOver="style.cursor=cursor" class="boton">
					<img id="botonBusqueda" src="../../img/botonlimpiar.jpg" width="69" height="22" onClick="limpiar()" border="1" onMouseOver="style.cursor=cursor" class="boton">
					<img id="botonBusqueda" src="../../img/botoncancelar.jpg" width="85" height="22" onClick="cancelar()" border="1" onMouseOver="style.cursor=cursor" class="boton">
					<input id="accion" name="accion" value="alta" type="hidden">
					<input id="id" name="Zid" value="" type="hidden">
					<input id="id" name="e" value="<?php echo $e;?>" type="hidden">
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
