<?php
header('Cache-Control: no-cache');
header('Pragma: no-cache'); 
?>
<html>
<head>
<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
</head>
<script language="javascript">

function pon_prefijo(codfamilia,pref,nombre,precio,codarticulo,moneda) {
	parent.pon_prefijo_Fb(codfamilia,pref,nombre,precio,codarticulo,moneda);
}

</script>
<?php include ("../conectar.php"); 
$codbarras=$_GET["codbarras"];

$where="1=1";

if ($codbarras<>'') { $where.=" AND articulos.codigobarras='$codbarras'"; }

 ?>
<body >
<?php
	$tipomon = array( 0=>"Selecione uno", 1=>"Pesos", 2=>"U\$S");
	$consulta="SELECT articulos.*,familias.nombre as nombrefamilia FROM articulos,familias WHERE ".$where." AND articulos.codfamilia=familias.codfamilia AND articulos.borrado=0 ORDER BY articulos.codfamilia ASC,articulos.descripcion ASC";
	$rs_tabla = mysql_query($consulta);
	$nrs=mysql_num_rows($rs_tabla);
 if ($nrs>0) {
			for ($i = 0; $i < mysql_num_rows($rs_tabla); $i++) {
				$codfamilia=mysql_result($rs_tabla,$i,"codfamilia");
				$codigobarras=mysql_result($rs_tabla,$i,"codigobarras");
				$nombrefamilia=mysql_result($rs_tabla,$i,"nombrefamilia");
				$referencia=mysql_result($rs_tabla,$i,"referencia");
				$codarticulo=mysql_result($rs_tabla,$i,"codarticulo");				
				$descripcion=mysql_result($rs_tabla,$i,"descripcion");

				$moneda=mysql_result($rs_tabla,$i,"moneda");				
				$precio=mysql_result($rs_tabla,$i,"precio_tienda");

	}
?>
<script language="javascript">
pon_prefijo ( '<?php echo $codfamilia;?>','<?php echo $codigobarras;?>','<?php echo addslashes(str_replace('"','&quot;',$descripcion));?>','<?php echo $precio;?>','<?php echo $codarticulo;?>','<?php echo $moneda;?>');
</script>

<?php
} else {
?>
<span style="position: absolute; left:45%; top: 50%;" >
No Existe ese código de barras
</span>
<?php
}
?>
</body>
</html>
