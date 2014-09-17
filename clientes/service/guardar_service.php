<?php
include ("../../conectar.php"); 
include ("../../funciones/fechas.php"); 

$accion=$_POST["accion"];
if (!isset($accion)) { $accion=$_GET["accion"]; }

$codservice=$_POST["codservice"];
$codequipo=$_POST["acodequipo"];
$fecha=$_POST["fecha"];
if ($fecha<>"") { $fecha=explota($fecha); } else { $fecha="0000-00-00"; }
$facturado=$_POST["facturado"];
if ($facturado<>"") { $facturado=explota($facturado); } else { $facturado="0000-00-00"; }


$service=$_POST["aservice"];
$solicito=$_POST["asolicito"];
$estado=$_POST["aestado"];
$tipo=$tiposervice=$_POST["atipo"];
$horas=$_POST["horas"];
$detalles=$_POST["Adetalles"];
$realizado=$_POST["arealizado"];
$importe=$_POST["nimporte"];

$codcliente=$_POST['e'];


if ($accion=="alta") {
	$query_operacion="INSERT INTO service (`codservice` ,`codcliente` ,`fecha` ,`codequipo` ,`tipo` ,`detalles` ,`realizado`, `solicito`,
	`horas`, `estado`, `facturado`, `importe`, `borrado`) VALUES 
	('', '$codcliente', '$fecha', '$codequipo', '$tipo', '$detalles', '$realizado', '$solicito',
	'$horas', '$estado', '$facturado', '$importe', '0')";					
	$rs_operacion=mysql_query($query_operacion);
	if ($rs_operacion) { $mensaje="El service ha sido dado de alta correctamente"; }
	$cabecera1="Inicio >> Service &gt;&gt; Nuevo Service ";
	$cabecera2="INSERTAR SERVICE ";
	$sel_maximo="SELECT max(codservice) as maximo FROM service";
	$rs_maximo=mysql_query($sel_maximo);
	$codservice=mysql_result($rs_maximo,0,"maximo");
}

if ($accion=="modificar") {
	$query="UPDATE service SET codequipo='$codequipo', `codcliente`='$codcliente', `fecha`='$fecha', `codequipo`='$codequipo',
 `tipo`='$tipo', `detalles`='$detalles', `realizado`='$realizado', `solicito`='$solicito',
	`horas`='$horas', `estado`='$estado', `facturado`='$facturado', `importe`='$importe', borrado=0 WHERE `codservice`='$codservice'";
	$rs_query=mysql_query($query);
	if ($rs_query) { $mensaje="Los datos del service han sido modificados correctamente"; } else {
	 $mensaje="Error al modificar datos";	}
	$cabecera1="Inicio >> Service &gt;&gt; Modificar Service ";
	$cabecera2="MODIFICAR SERVICE ";
}

if ($accion=="baja") {
	$codservice=$_GET["codservice"];
	$query="UPDATE service SET borrado=1 WHERE codservice='$codservice'";
	$rs_query=mysql_query($query);
	if ($rs_query) { $mensaje="El service ha sido eliminado correctamente"; }
	$cabecera1="Inicio >> Service &gt;&gt; Eliminar Service ";
	$cabecera2="ELIMINAR SERVICE ";
	$query_mostrar="SELECT * FROM service WHERE codservice='$codservice'";
	$rs_mostrar=mysql_query($query_mostrar);
	
	$codequipo=mysql_result($rs_mostrar,0,"codequipo");
	$fecha=mysql_result($rs_mostrar,0,"fecha");
	$fecha=explota($fecha);
	$service=mysql_result($rs_mostrar,0,"service");
	$solicito=mysql_result($rs_mostrar,0,"solicito");
	$horas=mysql_result($rs_mostrar,0,"horas");
	$estado=mysql_result($rs_mostrar,0,"estado");
	$descripcion=mysql_result($rs_mostrar,0,"descripcion");
	$realizado=mysql_result($rs_mostrar,0,"realizado");
	$importe=mysql_result($rs_mostrar,0,"importe");

}

$tipo = array("Sin&nbsp;definir", "Sin&nbsp;Servicio","Con&nbsp;Mantenimiento", "Mantenimiento&nbsp;y&nbsp;Respaldos");

	$consulta="SELECT * FROM equipos WHERE borrado=0 AND `codequipo`='".$codequipo."'";
	$res_resultado=$rs_tabla = mysql_query($consulta);
	$nrs=mysql_num_rows($rs_tabla);

$service=$tipo[mysql_result($res_resultado,$contador,"service")];
$desc=mysql_result($res_resultado,$contador,"alias")." - ".mysql_result($res_resultado,$contador,"descripcion");

?>

<html>
	<head>
		<title>Principal</title>
		<link href="../../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script language="javascript">
		
		function aceptar() {
			parent.$('idOfDomElement').colorbox.close();
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

			<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0><tr><td valign="top" width="100%">
				<table class="fuente8" width="100%" cellspacing=2 cellpadding=3 border=0>
				  <tr>
					<td >Equipo</td>
					<td><input name="acodequipo" type="text" class="cajaPequena" id="codequipo" size="15" maxlength="15" value="<?php echo $codequipo;?>"> 
					<img id="botonBusqueda" style="float:right;" src="../../img/ver.png" width="16" height="16"  onMouseOver="style.cursor=cursor" title="Buscar equipos cliente">
					</td><td>Descripción</td>
					<td colspan="3"><input  type="text" class="cajaGrande" size="45" readonly value="<?php echo $desc;?>"></td>
					<td>Tipo&nbsp;Service</td>
					<td><input  type="text" class="cajaGrande" size="25" readonly value="<?php echo $service;?>"></td>
				  </tr>
				  </table>
				  
				  </td></tr>
				  <tr>
					  <td valign="top" width="100%">
						<table class="fuente8" cellspacing=0 cellpadding=3 border=0>
					  <tr>
							<td>Fecha&nbsp;de&nbsp;service</td>
							<td><input NAME="fecha" type="text" class="cajaPequena" id="fecha" size="10" maxlength="10" readonly value="<?php echo $fecha;?>"> 
							<img src="../../img/calendario.png" name="Image1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'">
							</td>
					<td>Solicitado&nbsp;por</td>
					<td><input NAME="asolicito" type="text" class="cajaMedia" id="solicito" size="10" maxlength="10" value="<?php echo $solicito;?>"></td>
					<td>Tipo</td>
					<td><SELECT size=1 name="atipo" id="tipo" class="comboMedio" >
			<?php
			$HistorialTipo = array( 0=>"Llamada", 1=>"Service", 2=>"Mantenimiento", 3=>"Consulta");
			$x=0;
			$NoEstado=0;
			foreach($HistorialTipo as $i) {
			  	if ( $x==$tiposervice)
				{
					echo "<OPTION value=$x selected>$i</option>";
					$NoEstado=1;
				}
				else
				{
					echo "<OPTION value=$x>$i</option>";
				}
				$x++;
			   }
			if ( $NoEstado!=1 or $tiposervice=="")
			{
			echo "<OPTION value=\"\" selected>Selecione uno</option>";
			}
			?></select>
			</td>
					<td>Estado</td>
					<td>
					
					<SELECT class="comboPequeno" size=1 name="aestado" id="estado">
					<?php
						$estadoarray = array(0=>"Pendiente", 1=>"Asignado", 2=>"Terminado");
					if ($estado==" ")
					{
					echo '<OPTION value="" selected>Selecione uno</option>';
					}
					$x=0;
					$NoEstado=0;
					foreach($estadoarray as $i) {
					  	if ( $x==$estado) {
							echo "<OPTION value=$x selected>$i</option>";
							$NoEstado=1;
						} else {
							echo "<OPTION value=$x>$i</option>";
						}
						$x++;
					}
					?>
					</select>
					</td>
		
					    </tr></table></td></tr>			    
					    <tr>
				  <td valign="top" width="100%">
				<table class="fuente8" cellspacing=0 cellpadding=3 border=0>
				  <tr>
<?php if (empty($horas)){ ?>
					<td valign="top" rowspan="3">Detalle&nbsp;solicitud</td>
					<td rowspan="3">
					<textarea name="Adetalles" cols="41" rows="4" id="detalles" class="areaTexto"><?php echo $detalles;?></textarea>	</td>
					<td valign="top" rowspan="3">Trabajo&nbsp;Realizado</td>
					<td rowspan="3">
					<textarea name="arealizado" cols="41" rows="4" id="realizado" class="areaTexto"><?php echo $realizado;?></textarea>	</td>
					<td width="5%">Importe</td>
					<td width="11%"><input NAME="nimporte" type="text" class="cajaPequena2" id="importe" size="10" maxlength="10" value="0" value="<?php echo $fecha;?>"></td>
<?php } else {
	
	$Tipo = array("0.5","1","1.5","2","2.5","3","3.5","4","4.5","5","5.5","6","6.5","7","7.5","8","8.5","9");
	$id=1;
	foreach ($Tipo as $t) {
		echo "<td align=\"center\" width=\"30px\">".$t."hr.</td>";
	}
		echo "<td align=\"center\" width=\"30px\">Total</td></tr><tr>";

        $defaultValue=0;

	foreach ($Tipo as $x) {

		if ( $x==$horas)
		{
		echo "<td ><input type=\"radio\" name=\"horas\" id=\"".$id."\" value=\"".$x."\"
		 checked onchange='suma(horas,".$id.");'></td>";
       $defaultValue=$x;
		} else {
		echo "<td><input type=\"radio\" name=\"horas\" id=\"".$id."\" value=\"".$x."\" 
		onchange='suma(horas,".$id.");'></td>";
		}
	}
?>
		<td><input type="text" id="total" value="<?php echo $defaultValue;?>" size='4' name='total' class="cajaPequena2"></td>
		</tr></table></td></tr><tr><td align="left" width="100%">
				<table class="fuente8" cellspacing=0 cellpadding=3 border=0 width="50%">
					<td valign="top" rowspan="3">Trabajo&nbsp;Realizado</td>
					<td rowspan="3">
					<textarea name="arealizado" cols="41" rows="4" id="realizado" class="areaTexto"><?php echo $realizado;?></textarea>	</td>
					<td width="5%">Importe</td>
					<td width="11%"><input NAME="nimporte" type="text" class="cajaPequena2" id="importe" size="10" maxlength="10" value="0"></td>

<?php } ?>		
				  </tr>
				</table></td></tr></table>
				</div>
				<div>
					<img id="botonBusqueda" src="../../img/botonaceptar.jpg" width="85" height="22" onClick="aceptar()" border="1" onMouseOver="style.cursor=cursor">
			  </div>
			 </div>
		  </div>
		</div>
	</body>
</html>
