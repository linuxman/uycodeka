<?php
header('Cache-Control: no-cache');
header('Pragma: no-cache'); 
?>
<html>
<head>
<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
</head>
<script language="javascript">

function pon_prefijo(codfamilia,pref,nombre,precio,moneda) {
	window.top.principal.pon_prefijo(codfamilia,pref,nombre,precio,moneda);
}

</script>
<?php include ("../conectar.php"); 
$familia=$_POST["cmbfamilia"];
$referencia=$_POST["referencia"];
$descripcion=$_POST["descripcion"];
$where="1=1";

if ($familia<>0) { $where.=" AND articulos.codfamilia='$familia'"; }
if ($referencia<>"") { $where.=" AND referencia like '%$referencia%'"; }
if ($descripcion<>"") { $where.=" AND descripcion like '%$descripcion%'"; } ?>
<body>
<?php
	$tipomon = array( 0=>"Selecione uno", 1=>"Pesos", 2=>"U\$S");
	$consulta="SELECT articulos.*,familias.nombre as nombrefamilia FROM articulos,familias WHERE ".$where." AND articulos.codfamilia=familias.codfamilia AND articulos.borrado=0 ORDER BY articulos.codfamilia ASC,articulos.descripcion ASC";
	$rs_tabla = mysql_query($consulta);
	$nrs=mysql_num_rows($rs_tabla);
?>
<div id="tituloForm2" class="header">
<form id="form1" name="form1">
<?php if ($nrs>0) { ?>
		<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
		  <tr>
			<td width="15%"><div align="center"><b>Familia</b></div></td>
			<td width="15%"><div align="center"><b>Referencia</b></div></td>
			<td width="40%"><div align="center"><b>Descripci&oacute;n</b></div></td>
			<td width="20%"><div align="center"><b>Precio</b></div></td>
			<td width="20%"><div align="center"><b>Moneda</b></div></td>
			<td width="10%"><div align="center"></td>
		  </tr>
		<?php
			for ($i = 0; $i < mysql_num_rows($rs_tabla); $i++) {
				$codfamilia=mysql_result($rs_tabla,$i,"codfamilia");
				$codigobarras=mysql_result($rs_tabla,$i,"codigobarras");
				$nombrefamilia=mysql_result($rs_tabla,$i,"nombrefamilia");
				$referencia=mysql_result($rs_tabla,$i,"referencia");
				$codarticulo=mysql_result($rs_tabla,$i,"codarticulo");				
				$descripcion=mysql_result($rs_tabla,$i,"descripcion");

				$moneda=mysql_result($rs_tabla,$i,"moneda");				
				$precio=mysql_result($rs_tabla,$i,"precio_tienda");
				 if ($i % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }?>
						<tr class="<?php echo $fondolinea?>">
					<td>
        <div align="center"><?php echo $nombrefamilia;?></div></td>
					<td>
        <div align="left"><?php echo $referencia;?></div></td>
					<td><div align="center"><?php echo $descripcion;?></div></td>
					<td><div align="center"><?php echo $precio;?></div></td>
					<td><div align="center"><?php echo $tipomon[$moneda];?></div></td>
					<td align="center"><div align="center">
					<a href="javascript:pon_prefijo(<?php echo $codfamilia;?>,'<?php echo $codigobarras;?>','<?php echo str_replace('','',$descripcion);?>','<?php echo $precio;?>','<?php echo $moneda;?>');">
					<img id="botonBusqueda" src="../img/convertir.png" border="0" title="Seleccionar"></a></div></td>					
				</tr>
			<?php }
		?>
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
