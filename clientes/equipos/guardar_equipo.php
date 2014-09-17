<?php
include ("../../conectar.php"); 
include ("../../funciones/fechas.php"); 

$accion=$_POST["accion"];
if (!isset($accion)) { $accion=$_GET["accion"]; }

$fecha=$_POST["fecha"];
if ($fecha<>"") { $fecha=explota($fecha); } else { $fecha="0000-00-00"; }

$codequipo=$_POST["codequipo"];
$numero=$_POST["znumero"];
$service=$_POST["aservice"];
$alias=$_POST["aalias"];
$descripcion=$_POST["Adescripcion"];
$detalles=$_POST["adetalles"];
$codcliente=$_POST['e'];

if ($accion=="alta") {
	$query_operacion="INSERT INTO equipos (`codequipo` ,`codcliente` ,`fecha` ,`service` ,`numero` ,`descripcion` ,`detalles`, `alias`,
	`borrado`) VALUES ('', '$codcliente', '$fecha', '$service', '$numero', '$descripcion', '$detalles', '$alias', '0')";					
	$rs_operacion=mysql_query($query_operacion);
	if ($rs_operacion) { $mensaje="El equipo ha sido dado de alta correctamente"; }
	$cabecera1="Inicio >> Equipo &gt;&gt; Nuevo Equipo ";
	$cabecera2="INSERTAR CLIENTE ";
	$sel_maximo="SELECT max(codequipo) as maximo FROM equipos";
	$rs_maximo=mysql_query($sel_maximo);
	$codequipo=mysql_result($rs_maximo,0,"maximo");
}

if ($accion=="modificar") {
	$query="UPDATE equipos SET fecha='$fecha', service='$service', numero='$numero',
	 descripcion='$descripcion', detalles='$detalles', alias='$alias', borrado=0 WHERE codequipo='$codequipo'";
	$rs_query=mysql_query($query);
	if ($rs_query) { $mensaje="Los datos del equipo han sido modificados correctamente"; } else {
	 $mensaje="Error al modificar satos";	}
	$cabecera1="Inicio >> Equipo &gt;&gt; Modificar Equipo ";
	$cabecera2="MODIFICAR Equipo ";
}

if ($accion=="baja") {
	$codcliente=$_GET["codequipo"];
	$query="UPDATE equipos SET borrado=1 WHERE codequipo='$codequipo'";
	$rs_query=mysql_query($query);
	if ($rs_query) { $mensaje="El equipo ha sido eliminado correctamente"; }
	$cabecera1="Inicio >> Clientes &gt;&gt; Eliminar Equipo ";
	$cabecera2="ELIMINAR EQUIPO ";
	$query_mostrar="SELECT * FROM equipos WHERE codequipo='$codequipo'";
	$rs_mostrar=mysql_query($query_mostrar);
	
	$fecha=mysql_result($rs_mostrar,0,"fecha");
	$fecha=explota($fecha);
	$service=mysql_result($rs_mostrar,0,"service");
	$numero=mysql_result($rs_mostrar,0,"numero");
	$descripcion=mysql_result($rs_mostrar,0,"descripcion");
	$detalles=mysql_result($rs_mostrar,0,"detalles");
	$codcliente=mysql_result($rs_mostrar,0,"codcliente");
}

?>

<html>
	<head>
		<title>Principal</title>
		<link href="../../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script language="javascript">
		
		function aceptar() {
			location.href="index.php?e=<?php echo $codcliente;?>";
		}
		
		var cursor;
		if (document.all) {
		/*/ Está utilizando EXPLORER*/
		cursor='hand';
		} else {
		/*/ Está utilizando MOZILLA/NETSCAPE*/
		cursor='pointer';
		}
		
		</script>
	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header"><?php echo $cabecera2?></div>
				<div id="frmBusqueda">
				<table class="fuente8">
				
						<tr>

							<td width="85%" colspan="2" class="mensaje"><?php echo $mensaje;?></td>
					    </tr>
				
				<tr><td valign="top">
				<table class="fuente8"><tr><td valign="top">
					<table class="fuente8" cellspacing=0 cellpadding=2 border=0>
						<tr>
							<td>Fecha&nbsp;de&nbsp;alta</td>
							<td><input NAME="fecha" type="text" class="cajaPequena" id="fecha" size="10" maxlength="10" value="<?php echo $fecha;?>" readonly> 
							</td>
					    </tr>
					    <tr>
						  <td>Nº</td>
						  <td ><input id="numero" type="text" autocomplete="off" class="cajaPequena" NAME="anumero" value="<?php echo $numero;?>" maxlength="15"></td>
							<td>Service</td>
							<td>
							<?php
								$tipo = array("Sin&nbsp;definir", "Sin Servicio", "Con Mantenimiento");
							      echo $tipo[$service];
							?>
							</td>
				      </tr>
						<tr>
							<td>Descripción </td>
							<td colspan="3"><input NAME="adescripcion" type="text" class="cajaGrande" id="descripcion" size="45" value="<?php echo $descripcion;?>" maxlength="45"></td>
					    </tr>
						<tr>
							<td>Alias </td>
							<td colspan="3"><input NAME="aalias" type="text" class="cajaGrande" id="alias" size="45"  value="<?php echo $alias;?>" maxlength="45"></td>
					    </tr>					</table></td><td>						
					</table></td><td valign="top">						
						<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>	
						<tr>
							<td valign="top">Detalles</td>
						    <td><textarea name="adetalles" cols="41" rows="4" id="detalles" class="areaTexto"><?php echo $detalles;?></textarea></td>
					    </tr>
					</table>
				</td></tr></table>
				</div>
				<div>
					<img id="botonBusqueda" src="../../img/botonaceptar.jpg" width="85" height="22" onClick="aceptar()" border="1" onMouseOver="style.cursor=cursor">
			  </div>
			 </div>
		  </div>
		</div>
	</body>
</html>
