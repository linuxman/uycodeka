<?php
header('Cache-Control: no-cache');
header('Pragma: no-cache'); 

include ("../conectar.php");

$codfactura=$_GET["codfacturatmp"];
$numlinea=$_GET["numlinea"];
$codservice=$_GET['codservice'];

if($codservice!='') {
		$sel_actualiza="UPDATE service SET service.factura='0' WHERE service.codservice='$codservice'";
		$rs_actualiza = mysql_query($sel_actualiza);
}

$consulta = "DELETE FROM factulineatmp WHERE codfactura ='".$codfactura."' AND numlinea='".$numlinea."'";
$rs_consulta = mysql_query($consulta);
echo "<script>parent.location.href='frame_lineas.php?codfacturatmp=".$codfactura."';</script>";

?>