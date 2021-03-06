<?php

class PDF extends FPDF
{
/*/Cabecera de pgina*/
function Header()
{
global $title; 
global $subtitle;
global $codcliente;	
global $ww;
global $header;

   /*/Logo*/
    $this->Image('./logo/logo.jpg',10,8,190);
    $this->Ln(20);	
/*/Nombre del Listado*/
$this->SetFillColor(255,255,255);
$this->SetFont('Arial','B',16);
$this->SetY(25);
$this->SetX(0);
$this->MultiCell(290,6,$title,0,'C',0);

$this->Ln();    
$this->Ln();  

/*/Buscamos y listamos los equipos del cliente*/



$this->SetFillColor(255,255,255);
$this->SetFont('Arial','B',12);
$this->SetY(32);
$this->SetX(10);
$this->MultiCell(142,7,"Datos del Cliente",0,'C',0,0);

	

$this->SetFillColor(200,200,200);
$this->SetTextColor(0);
$this->SetDrawColor(0,0,0);
$this->SetLineWidth(.1);

 $w=array(12,50,16,20,14,16);
$w1=array(12,50,16,50);
$w2=array(12,17,15,18,36,30);


$sel_resultado="SELECT * FROM clientes WHERE borrado=0 AND codcliente='$codcliente'";
$res_resultado=mysql_query($sel_resultado);
$contador=0;
while ($contador < mysql_num_rows($res_resultado)) {

$this->SetFont('Arial','B',7);
	$this->Cell($w[0],5,'Nombre',1,0,'L',1);
$this->SetFont('Arial','',6);
	$this->Cell($w[1],5,mysql_result($res_resultado,$contador,"nombre").' '.mysql_result($res_resultado,$contador,"apellido"),'LRTB',0,'L');
$this->SetFont('Arial','B',7);
	$this->Cell($w[2],5,'Teléfono',1,0,'C',1);
$this->SetFont('Arial','',6);
	$this->Cell($w[3],5,mysql_result($res_resultado,$contador,"telefono"),'LRTB',0,'C');
$this->SetFont('Arial','B',7);
	$this->Cell($w[4],5,"Móvil",1,0,'C',1);
$this->SetFont('Arial','',6);
	$this->Cell($w[5],5,mysql_result($res_resultado,$contador,"movil"),'LRTB',0,'L');
	$this->Ln();
	
$this->SetFont('Arial','B',7);
	$this->Cell($w1[0],5,'Empresa',1,0,'L',1);
$this->SetFont('Arial','',6);
	$this->Cell($w1[1],5,mysql_result($res_resultado,$contador,"empresa"),'LRTB',0,'L');
$this->SetFont('Arial','B',7);
	$this->Cell($w1[2],5,'Email',1,0,'C',1);
$this->SetFont('Arial','',6);
	$this->Cell($w1[3],5,mysql_result($res_resultado,$contador,"email"),'LRTB',0,'C');
	$this->Ln();
	
$this->SetFont('Arial','B',7);
	$this->Cell($w2[0],5,'RUT',1,0,'L',1);
$this->SetFont('Arial','',6);
	$this->Cell($w2[1],5,mysql_result($res_resultado,$contador,"nif"),'LRTB',0,'L');
$this->SetFont('Arial','B',7);
	$this->Cell($w2[2],5,'Tipo cliente',1,0,'C',1);
$this->SetFont('Arial','',6);
$tipo = array("Seleccione uno", "Cliente","MCC");
	$this->Cell($w2[3],5,$tipo[mysql_result($res_resultado,$contador,"tipo")],'LRTB',0,'C');
$this->SetFont('Arial','B',7);
	$this->Cell($w2[4],5,"Abonado/Service",1,0,'L',1);
$this->SetFont('Arial','',6);
$tipo = array("Seleccione un tipo", "Común","Abonado A", "Abonado B");
	$this->Cell($w2[5],5,$tipo[mysql_result($res_resultado,$contador,"service")],'LRTB',0,'L');
	$contador++;
};
$this->Ln();
$this->Ln();
$this->SetFont('Arial','B',11);
$this->MultiCell(142,5,$subtitle,0,'C',0,0);


/*/Restauracin de colores y fuentes*/
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('Arial','B',7);


/*/Colores, ancho de línea y fuente en negrita*/
$this->SetFillColor(200,200,200);
$this->SetTextColor(0);
$this->SetDrawColor(0,0,0);
$this->SetLineWidth(.2);
$this->SetFont('Arial','B',8);
	
/*/Cabecera*/
for($i=0;$i<count($header);$i++)
	$this->Cell($ww[$i],6,$header[$i],1,0,'C',1);
$this->Ln();
    
}

/*/Pie de pgina*/
function Footer()
{
   $this->SetFont('Arial','',6);
	$this->SetY(-12);
	$this->Cell(0,10,'GESER WEB - MCC RUT 214355250015',0,0,'L');
	$this->SetY(-12);
   $this->Cell(0,10,'Pagina '.$this->PageNo()."/{nb}",0,0,'C');	
}

}
?>
