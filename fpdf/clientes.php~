<?php
include ("../conectar.php");  
include ("../funciones/fechas.php");

define('FPDF_FONTPATH','font/');
require('mysql_table.php');
require('mc_table.php');

include("comunes.php");

header("Content-Type: text/html; charset=iso-8859-1 ");
mysql_query("SET NAMES utf8"); //Soluciona el tema de las ñ y los tildes

$codcliente=$_GET["e"];
$title="Listado de Clientes";
$subtitle="Detalles ";

$where= "1 = 1 ";

$ww=array(50,25,80,20);
/*/Ttulos de las columnas*/
$header=array('Nombre','RUT','Dirección','Teléfono');

$pdf=new PDF();
/*$pdf->Open();*/
$pdf->AliasNbPages();
$pdf->AddPage();


$w=array(50,25,80,20);
for($i=0;$i<count($header);$i++)
	$pdf->Cell($w[$i],7,$header[$i],1,0,'C',1);
$pdf->Ln();
$pdf->SetFont('Arial','',8);
$sel_resultado="SELECT * FROM clientes WHERE borrado=0 AND ".$where;
$res_resultado=mysql_query($sel_resultado);
$contador=0;
while ($contador < mysql_num_rows($res_resultado)) {
	if (!empty(mysql_result($res_resultado,$contador,"empresa"))) {
	$pdf->Cell($w[0],5,mysql_result($res_resultado,$contador,"empresa"),'LRTB',0,'L');
	} else {
	$pdf->Cell($w[0],5,mysql_result($res_resultado,$contador,"nombre").' '.mysql_result($res_resultado,$contador,"apellido"),'LRTB',0,'L');
	}
	$pdf->Cell($w[1],5,mysql_result($res_resultado,$contador,"nif"),'LRTB',0,'C');
	$pdf->Cell($w[2],5,mysql_result($res_resultado,$contador,"direccion"),'LRTB',0,'L');
	$pdf->Cell($w[3],5,mysql_result($res_resultado,$contador,"telefono"),'LRTB',0,'C');
	$pdf->Ln();
	$contador++;
};
$nombre='../copias/'.$nombre.'.pdf';
		
$pdf->Output($nombre,'F');
?> 

			<html>
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<link rel="stylesheet" type="text/css" href="css/SearchBox.css" media="screen"/>
			<link rel="stylesheet" type="text/css" href="css/login1.css" title="default" media="screen" />
			<link rel="stylesheet" type="text/css" href="css/table.css" media="screen" />
			
			<link href="css/impresora.css" media="print" type="text/css" rel="stylesheet">
			
			<style media="screen,projection" type="text/css">
			    /* backslash hack hides from IEmac \*/
				    @import url(css/base.css);
			    /* end hack */
			</style>
			<STYLE type="text/css"> 
			
			A:link {text-decoration:none;color:#0000cc;} 
			A:visited {text-decoration:none;color:#ffcc33;} 
			A:active {text-decoration:none;color:#ff0000;} 
			A:hover {text-decoration:underline;color:#999999;} 
			</STYLE> 
			
			    <script type="text/javascript" src="js/pdfobject.js"></script>
			    <script type="text/javascript">
			      window.onload = function (){
			        var success = new PDFObject({ url: "<?php echo $nombre;?>" }).embed();
			      };
			    </script>
			<title>Estudios</title>
			</head>
<body marginwidth="0" topmargin="0" leftmargin="0">
	<center>
	<div style="position: relative; width: 300px; height: 50px; background-color: #E63C1E; color: #000; padding: 15px;z-index: 2;">
	<p>Por lo visto no tiene instalado Adobe Reader o soporte para PDF en su navegador web. <br>
	<a href="<?php echo $nombre;?>">Click para descargar el PDF</a></p>  
	</div>
	</center>
			
</body></html>