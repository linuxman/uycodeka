<?php


define('FPDF_FONTPATH','font/');
require('mysql_table.php');
include("comunes_factura.php");
include ("../conectar.php");
include ("../funciones/fechas.php"); 

$pdf=new FPDF('L','mm',array(148,210));
$pdf->Open();
$pdf->SetMargins(4, 6 , 4);
$pdf->AddPage('L');

$pdf->SetAutoPageBreak(auto ,3);
//$pdf->Ln(10);


//include ("../conectar.php");
  
$codfactura=$_GET["codfactura"];
  
$consulta = "Select * from facturas,clientes where facturas.codfactura='$codfactura' and facturas.codcliente=clientes.codcliente";
$resultado = mysql_query($consulta, $conexion);
$lafila=mysql_fetch_array($resultado);


/*Fijo la línea de comienzo*/

if ($lafila["moneda"]==2){
$moneda="dolar.png";
} else {
$moneda="pesos.png";
}
	$pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','I',9);
    	
    $pdf->Cell(5); /* ubicación inicial de la celdas */
	$pdf->Cell(50,4,'FERNANDO GÁMBARO ÁLVAREZ',0,0,'C',1);
			
$pdf->Image('logo/logo_simple.jpg',12,10,-140);
    $pdf->Cell(100); /* ubicación inicial de la celdas */
    $pdf->SetFont('Arial','',8);
	$pdf->Cell(50,4,'RUT 214355250015',0,0,'C',1);		


    $pdf->Ln(10);					

/* Establezco el color de las celdas y tipo de letra */
    $pdf->SetFillColor(134,16,16);
    $pdf->SetTextColor(255);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',8);
	
    $pdf->Cell(162); /* ubicación inicial de la celdas */
	$pdf->Cell(20,4,"Nº",1,0,'C',1);
	$pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','',8);	
	$pdf->Cell(20,4,$codfactura,1,0,'C',1);		
	$pdf->Ln(4);


/* --------- */
	$pdf->Cell(162); /* ubicación inicial de la celdas*/

    $pdf->SetFillColor(134,16,16);
    $pdf->SetTextColor(255);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',8);    	
	$pdf->Cell(20,4,"Fecha",1,0,'C',1);
	
	$pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','',8);
    $fecha = implota($lafila["fecha"]);
	$pdf->Cell(20,4,$fecha,1,0,'C',1);	
	$pdf->Ln(4);


/* --------- */

    $pdf->Cell(1); /* ubicación inicial de la celdas*/
//    $pdf->SetFillColor(255,191,116);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(0);
    $pdf->SetFont('Arial','B',8);
	
	$pdf->Cell(15,4,"Cliente:",0,0,'L',1);

	$pdf->Cell(90,4,$lafila["codcliente"]. ' | '.$lafila["empresa"],'B',0,'L',0);

    $pdf->Cell(56); /* ubicación inicial de la celdas*/
    $pdf->SetFillColor(134,16,16);
    $pdf->SetTextColor(255);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',6);
	
   $pdf->Cell(30,4,"RUT COMPRADOR",1,0,'C',1);
   $pdf->Cell(10,4,"C. FINAL",1,0,'C',1);

	$pdf->Ln(4);



    $pdf->Cell(1); /* ubicación inicial de la celdas*/
//    $pdf->SetFillColor(255,191,116);
	$pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(0);
    $pdf->SetFont('Arial','B',8);
	
	$pdf->Cell(15,4,"Dirección:",0,0,'L',1);

	$pdf->Cell(90,4,$lafila["direccion"],'B',0,'L',0);

	
	$pdf->Cell(56);
	$pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','',8);
	
	
   if (empty($lafila["nif"])){
   	$consumo="X";
   	} else {
   	$nif=$lafila["nif"];
   }
   $pdf->Cell(30,4,$nif,1,0,'C',1);
	$pdf->Cell(10,4,$consumo,1,0,'C',1);		
	
	
	//ahora mostramos las líneas de la factura
	$pdf->Ln(9);		
	$pdf->Cell(1);
	
	$pdf->SetFillColor(134,16,16);
    $pdf->SetTextColor(255);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',8);
	
    $pdf->Cell(20,4,"Referencia",1,0,'C',1);
	$pdf->Cell(131,4,"Descripcion",1,0,'C',1);
	$pdf->Cell(15,4,"Cantidad",1,0,'C',1);	
	$pdf->Cell(15,4,"Precio",1,0,'C',1);
	$pdf->Cell(20,4,"Importe",1,0,'C',1);
	$pdf->Ln(4);
			
			
	$pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','',8);

	
	$consulta2 = "Select * from factulinea where codfactura='$codfactura' order by numlinea";
    $resultado2 = mysql_query($consulta2, $conexion);
    
	$contador=0;
	while ($row=mysql_fetch_array($resultado2))
	{
	$pos=0;

	  $pdf->Cell(1);
	  //$contador++;
	  $codarticulo=mysql_result($resultado2,$lineas,"codigo");
	  $codfamilia=mysql_result($resultado2,$lineas,"codfamilia");

	  $detallesA=$detalles=mysql_result($resultado2,$lineas,"detalles");
		$largo_ini=strlen($detalles);
		if($largo_ini>100) {
			$largo=1;
			$texto_corto = substr($detalles, 0, 100);
			$pos = strripos($texto_corto,' ');
			if ($pos !== false) { 
    			$acotado = substr($detallesA, 0, $pos);
    			$resta=substr($detallesA, $pos, $largo_ini-$pos );
    			$pos=$pos+1;
			} else {	
				$acotado = substr($detallesA, 0, 100);
    			$resta=substr($detallesA, $pos, $largo_ini-$pos );
			}
		} else {
			$largo=0;
			$acotado = substr($detallesA, 0, 100);
		}
	  $sel_articulos="SELECT * FROM articulos WHERE codarticulo='$codarticulo' AND codfamilia='$codfamilia'";
	  $rs_articulos=mysql_query($sel_articulos);
	  $pdf->Cell(20,4,mysql_result($rs_articulos,0,"referencia"),'LR',0,'L');
	  
	  
	  $pdf->Cell(131,4,$acotado,'LR',0,'L');
	  
	  $pdf->Cell(15,4,mysql_result($resultado2,$lineas,"cantidad"),'LR',0,'C');	

	  $importe2= number_format(mysql_result($resultado2,$lineas,"importe"),2,",",".");	  
	  
	  $precio2= number_format(mysql_result($resultado2,$lineas,"importe")/mysql_result($resultado2,$lineas,"cantidad"),2,",",".");	 
	   
	  $pdf->Cell(15,4,$precio2,'LR',0,'R');
	  	  
	  $importe2= number_format(mysql_result($resultado2,$lineas,"importe"),2,",",".");	  
	  
	  $pdf->Cell(20,4,$importe2,'LR',0,'R');
	  $pdf->Ln(4);
	  	
		$ini=$pos;
		while ($largo==1){
			$largo_ini=strlen($resta);
			if($largo_ini>100) {
				$largo=1;
				$texto_corto = substr($resta, 0, 100);
				$pos = strripos($texto_corto,' ');
				if ($pos !== false) { 
	    			$acotado = substr($detallesA, $ini, $pos);
	    			$resta=substr($detallesA, $ini+$pos, $largo_ini-$pos );
	    			$pos=$pos+1;
				} else {	
					$acotado = substr($detallesA, $ini, 100);
	    			$resta=substr($detallesA, $ini+$pos, $largo_ini-$pos );
				}
			} else {
				$largo=0;
				$acotado = substr($detallesA, $ini, 100);
			}
			$ini=$ini+$pos;	
		   $pdf->Cell(1);
			$pdf->Cell(20,4,' ','LR',0,'L');
			$pdf->Cell(131,4,$acotado,'LR',0,'L');
			$pdf->Cell(15,4,' ','LR',0,'C');	
	  		$pdf->Cell(15,4,' ','LR',0,'R');
	  		$pdf->Cell(20,4,' ','LR',0,'R');
			$pdf->Ln(4);
		  $contador++;
		}
	  //vamos acumulando el importe
	  $importe=$importe + mysql_result($resultado2,$lineas,"importe");
	  $contador++;
	  $lineas=$lineas + 1;	  
	};
	
	
	
	while ($contador<21)
	{
	  $pdf->Cell(1);
      $pdf->Cell(20,4,"",'LR',0,'C');
      $pdf->Cell(131,4,"",'LR',0,'C');
	  $pdf->Cell(15,4,"",'LR',0,'C');	
	  $pdf->Cell(15,4,"",'LR',0,'C');
	  $pdf->Cell(20,4,"",'LR',0,'C');
	  $pdf->Ln(4);	
	  $contador=$contador +1;
	}

	  $pdf->Cell(1);
      $pdf->Cell(20,4,"",'LRB',0,'C');
      $pdf->Cell(131,4,"",'LRB',0,'C');
	  $pdf->Cell(15,4,"",'LRB',0,'C');	
	  $pdf->Cell(15,4,"",'LRB',0,'C');
	  $pdf->Cell(20,4,"",'LRB',0,'C');
	  $pdf->Ln(1);	


	$pdf->Ln(5);		
	$pdf->Cell(19);
	
	 $pdf->SetFillColor(255,255,255);
    //$pdf->SetTextColor(0);
    //$pdf->SetDrawColor(0,0,0);
    //$pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','',8);
   $pdf->Cell(40,4,"Este documento no sustituye a la factura",0,0,'L',1);


	//ahora mostramos los detalles de importes
		
	$pdf->Cell(90);
	
	 $pdf->SetFillColor(255,255,255);
    //$pdf->SetTextColor(0);
    //$pdf->SetDrawColor(0,0,0);
    //$pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',8);
   $pdf->Cell(20,4,"SUB - TOTAL",0,0,'L',1);

$pdf->Image('images/'.$moneda, 173,131,-110);

	$pdf->Cell(13);
   
   
    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','',8);  
   $importe4=number_format($importe,2,",",".");	
   $pdf->Cell(20,4,$importe4,1,0,'R',1);
	$pdf->Ln(4);

	$pdf->Cell(149);
	 $pdf->SetFillColor(255,255,255);
    //$pdf->SetTextColor(0);
    $pdf->SetDrawColor(255,255,255);
    //$pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',8);
	$pdf->Cell(20,4,"IVA ".$lafila["iva"]."%",1,0,'L',1);

$pdf->Image('images/'.$moneda, 173,135,-110);

	$pdf->Cell(13);
	
    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','',8); 

	$ivai=$lafila["iva"];
	$impo=$importe*($ivai/100);
	$impo=sprintf("%01.2f", $impo); 
	$impo=number_format($impo,2,",",".");

	$pdf->Cell(20,4,"$impo",1,0,'R',1);  
	$pdf->Ln(4);

	$pdf->Cell(149);
	$impo=$importe*($ivai/100);
	$total=round($importe+$impo,2); 
   $total=sprintf("%01.2f", $total);
	$total2= number_format($total,2,",",".");	

	 //$pdf->SetFillColor(134,16,16);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(255,255,255);
    //$pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',8);
	$pdf->Cell(20,4,"TOTAL",1,0,'L',1);
   
$pdf->Image('images/'.$moneda, 173,139,-110);

	$pdf->Cell(13);
   
    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','',8); 
	$pdf->Cell(20,4,"$total2",1,0,'R',1);


	$pdf->Ln(4);

	



      @mysql_free_result($resultado); 
      @mysql_free_result($query);
	  @mysql_free_result($resultado2); 
	  @mysql_free_result($query3);

$pdf->Output('Factura'.$codfactura,'I');
?> 
