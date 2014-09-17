<?php
header('Cache-Control: no-cache');
header('Pragma: no-cache'); 
?>
<html>
<head>
<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
</head>
<script language="javascript">

function pon_prefijo(codfamilia,pref,nombre,precio,codarticulo,moneda,codservice,detalles) {

	parent.pon_prefijo_a(codfamilia,pref,nombre,precio,codarticulo,moneda,codservice,detalles);
}

</script>
<?php 
include ("../conectar.php");
include ("../funciones/fechas.php");
 
$codcliente=$_POST["codcliente"];
$where="1=1";

if ($codcliente<>0) { $where.=" AND codcliente='$codcliente'"; }

?>
<body>
<?php
	$consulta="SELECT * FROM `service` WHERE ".$where." AND (service.factura=0 OR service.factura is null) AND service.borrado=0 ORDER BY service.fecha DESC ";
	$rs_tabla = mysql_query($consulta);
	$nrs=mysql_num_rows($rs_tabla);
?>
<div id="tituloForm2" class="header">
<form id="form1" name="form1">
		<div id="pagina">
			<div id="zonaContenido">
			<div align="center">
			<div class="header" style="width:100%;position: fixed;">	Listado de Service </div>

<?php if ($nrs>0) { ?>

<div class="fixed-table-container">
      <div class="header-background cabeceraTabla"> </div>      			
<div class="fixed-table-container-inner">
	
		<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0>
		<thead>
		  <tr>
			<th width="8%"><div align="center" class="th-inner"><b>Fecha</b></div></th>
			<th width="8%"><div align="center" class="th-inner"><b>Estado</b></div></th>
			<th width="15%"><div align="center" class="th-inner"><b>Tipo</b></div></th>
			<th width="60%"><div align="center" class="th-inner"><b>Descripci&oacute;n</b></div></th>
			<th width="10%"><div align="center" class="th-inner"><b>Horas</b></div></th>
			<th width="10%"><div align="center" class="th-inner"></th>
		  </tr>
		</thead>		
		<tbody>		  
		<?php
			for ($i = 0; $i < mysql_num_rows($rs_tabla); $i++) {
				$codservice=mysql_result($rs_tabla,$i,"codservice");
				$fecha=mysql_result($rs_tabla,$i,"fecha");
				$tipo=mysql_result($rs_tabla,$i,"tipo");
				$realizado=mysql_result($rs_tabla,$i,"realizado");
				$horas=mysql_result($rs_tabla,$i,"horas");
				$importe=mysql_result($rs_tabla,$i,"importe");

						$Tipo = array( 0=>"Llamada", 1=>"Service", 2=>"Mantenimiento", 3=>"Consulta");
						$estadoarray = array(0=>"Pendiente", 1=>"Asignado", 2=>"Terminado");
						$estadocolor = array(0=>"red", 1=>"blue", 2=>"green");

				 if ($i % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }?>
						<tr class="<?php echo $fondolinea?>">
					<td><div align="center"><?php echo implota($fecha);?></div></td>
					
					<td width="8%" style="background-color:<?php echo $estadocolor[mysql_result($rs_tabla,$i,"estado")];?>"><div align="center">
					<?php echo $estadoarray[mysql_result($rs_tabla,$i,"estado")];?></div></td>							
					
					<td><div align="left">
						<?php 
						 if (is_numeric(mysql_result($rs_tabla,$i,"tipo") )) {
							echo $Tipo[mysql_result($rs_tabla,$i,"tipo")];
						 } else {
							echo mysql_result($rs_tabla,$i,"tipo");
						 }							
						?>
					</div></td>
					<td><div align="center"><?php echo $realizado;?></div></td>
					<td><div align="center"><?php echo $horas;?></div></td>
					<td align="center"><div align="center">
					<a href="javascript:pon_prefijo('1','8400000000031','Servicio Técnico a Cliente','<?php echo $importe;?>',
					'3','1','<?php echo $codservice;?>','<?php echo implota($fecha).' | '.$horas.'hrs - '. addslashes(str_replace('"','&quot;',$realizado));?>');">
					
					<img id="botonBusqueda" src="../img/convertir.png" border="0" title="Seleccionar"></a></div></td>					
				</tr>
			<?php }
		?>
		</tbody>		
  </table>
		<?php 
		}  ?>
<iframe id="frame_datos" name="frame_datos" width="0%" height="0" frameborder="0">
	<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
</iframe>
<input type="hidden" id="accion" name="accion">
</form>
</div>
</body>
</html>
