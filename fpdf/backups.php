<?php

include ("../conectar.php");  
include ("../funciones/fechas.php");

define('FPDF_FONTPATH','font/');
require('mysql_table.php');

include("comunes.php");

global $ww;

header("Content-Type: text/html; charset=iso-8859-1 ");

$codcliente=$_GET["e"];
$title="Detalle respaldos";
$subtitle="Listado de Respaldos";

$ww=array(14,35,30,20,20,20,20,20);
/*/Ttulos de las columnas*/
$header=array('Fecha','Tarea','Equipo/Usuario','Versión','Errores', 'Procesados', 'Respaldados','Tamaño');


$pdf=new PDF();
/*$pdf->Open();*/
$pdf->AliasNbPages();
$pdf->AddPage();



$pdf->SetFont('Arial','',6);
$contador=0;

$sel_resultado="SELECT * FROM respaldospc WHERE codcliente='".$codcliente."' order by `fecha` DESC LIMIT 0 , 40";
	
$res_resultado=mysql_query($sel_resultado);
$contador=0;

$w=array(14,35,30,20,20,20,20,20);

while ($contador < mysql_num_rows($res_resultado)) { 

	$pdf->Cell($w[0],5,implota(mysql_result($res_resultado,$contador,"fecha")),'LRB',0,'L');
	$pdf->Cell($w[1],5, mysql_result($res_resultado,$contador,"tarea"),'RB',0,'L');
	$pdf->Cell($w[2],5, mysql_result($res_resultado,$contador,"usuario"),'RB',0,'L');
	$pdf->Cell($w[3],5, mysql_result($res_resultado,$contador,"version"),'RB',0,'L');
	$pdf->Cell($w[4],5, mysql_result($res_resultado,$contador,"errores"),'RB',0,'L');
	$pdf->Cell($w[5],5, mysql_result($res_resultado,$contador,"procesados"),'RB',0,'L');
	$pdf->Cell($w[6],5, mysql_result($res_resultado,$contador,"respaldados"),'RB',0,'L');
	$pdf->Cell($w[7],5, mysql_result($res_resultado,$contador,"tamano"),'RB',0,'L');
	$pdf->Ln();	
	$contador++;
}



$nombre='../copias/backups.pdf';			
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
