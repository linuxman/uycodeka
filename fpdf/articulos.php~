<?php


define('FPDF_FONTPATH','font/');
require('mysql_table.php');

include("sin_comunes.php");

include ("../conectar.php");  

$pdf=new PDF();
$pdf->Open();
$pdf->AddPage();

//Nombre del Listado
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',16);
$pdf->SetY(25);
$pdf->SetX(0);
    
$pdf->MultiCell(290,6,"Listado de Articulos",0,C,0);

$pdf->Ln();    
	
//Restauracin de colores y fuentes

    $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','B',7);


$codarticulo=$_GET["codarticulo"];
$descripcion=$_GET["descripcion"];
$codfamilia=$_GET["cboFamilias"];
$referencia=$_GET["referencia"];
$codproveedor=$_GET["cboProveedores"];
$codubicacion=$_GET["cboUbicacion"];

$where="1=1";
if ($codarticulo <> "") { $where.=" AND codarticulo='$codarticulo'"; }
if ($descripcion <> "") { $where.=" AND descripcion like '%".$descripcion."%'"; }
if ($codfamilia > "0") { $where.=" AND codfamilia='$codfamilia'"; }
if ($codproveedor > "0") { $where.=" AND (codproveedor1='$codproveedor' OR codproveedor2='$codproveedor')"; }
if ($codubicacion > "0") { $where.=" AND codubicacion='$codubicacion'"; }
if ($referencia <> "") { $where.=" AND referencia like '%".$referencia."%'"; }


$header=array('Familia','Referencia','Descripcion','P. Tienda','Stock');

//Colores, ancho de lnea y fuente en negrita
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',8);
	
//Cabecera
$w=array(40,30,80,20,20);
for($i=0;$i<count($header);$i++)
	$pdf->Cell($w[$i],7,$header[$i],1,0,'C',1);
$pdf->Ln();
$pdf->SetFont('Arial','',8);
$sel_resultado="SELECT * FROM articulos LEFT JOIN familias ON articulos.codfamilia=familias.codfamilia WHERE articulos.borrado=0 AND ".$where;
$res_resultado=mysql_query($sel_resultado);
$contador=0;
while ($contador < mysql_num_rows($res_resultado)) {
	$pdf->Cell($w[0],5,mysql_result($res_resultado,$contador,"familias.nombre"),'LRTB',0,'L');
	$pdf->Cell($w[1],5,mysql_result($res_resultado,$contador,"referencia"),'LRTB',0,'C');
	$pdf->Cell($w[2],5,mysql_result($res_resultado,$contador,"descripcion_corta"),'LRTB',0,'L');
	$pdf->Cell($w[3],5,mysql_result($res_resultado,$contador,"precio_tienda"),'LRTB',0,'R');
	$pdf->Cell($w[4],5,mysql_result($res_resultado,$contador,"stock"),'LRTB',0,'R');
	$pdf->Ln();
	$contador++;
};
			
$nombre='../copias/'.$codarticulo.'.pdf';			
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