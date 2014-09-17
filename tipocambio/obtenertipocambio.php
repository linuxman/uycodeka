<?php
include ("../conectar.php"); 
include ("../funciones/fechas.php"); 

//header('Content-Type: text/event-stream');
//header('Cache-Control: no-cache'); 

date_default_timezone_set("America/Montevideo"); 

 
$serverTime = time(); 
$nuevafecha='';
 
$fecha = date('Y-m-j');
/* Busco cual fue la última fecha donde se inserto cotización*/
    $sql_chek="SELECT * FROM `tipocambio` Order by fecha DESC LIMIT 0 , 1 ";
    $rs_chek=mysql_query($sql_chek);
   if (mysql_num_rows($rs_chek)!=0){ 
	$nuevafecha=mysql_result($rs_chek,0,"fecha");
	}
	if (empty($nuevafecha)){
	$nuevafecha="2014-01-01";	
	}
/*
$nuevafecha = strtotime ( '-1 month' , strtotime ( $fecha ) ) ;
$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
*/

function  nombrearchivo( $fecha ){
  ereg (  "([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})" ,  $fecha ,  $mifecha );
 $nombre = "oicot".$mifecha [ 3 ] . $mifecha [ 2 ]. substr( $mifecha [ 1 ], 2, 2).".txt";
return $nombre;
 }
 
 function dias_transcurridos($fecha_i,$fecha_f)
{
	$dias	= (strtotime($fecha_i)-strtotime($fecha_f))/86400;
	$dias 	= abs($dias); $dias = floor($dias);		
	return $dias;
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html lang="en">
<head>
    <title>Obtener Cotización del Dolar</title>
<style type="text/css">
#content {
   position : absolute;    
    width:500px;
    height:200px;
    left:50%;
    top:50%;
    margin-left:-250px;
    margin-top:-100px; 
}
</style>
</head>
<body><p><br></p>
<div style="text-align:center;"> Aguarde mientras obtenemos la/s última/s cotización del Dolar</div>

<div id="content">
<!-- Progress bar holder -->
<div id="progress" style="width:500px;border:1px solid #ccc;"></div>
<!-- Progress information -->
<div id="information" style="width"></div>

</div>
<?php 
 
sleep(1);
    
$fechaInicio=strtotime($nuevafecha)+86400;
$fechaFin=strtotime($fecha);

$total=dias_transcurridos($nuevafecha,$fecha);
$x=1;

for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
	$percent = intval(($x/$total) * 100)."%";
 	$x++;  
	$fecha=date("Y-m-d", $i);
	$name = nombrearchivo(date("Y-m-d", $i));
	/*Busco en el BCU los datos de las cotizaciones*/
	$salida = shell_exec('/var/www/html/servicemcc/tipocambio/tmp/cotiz '. $name)."\n";
    

$valor = file_get_contents('tmp/'. $name);
$Borro = shell_exec('rm /var/www/html/servicemcc/tipocambio/tmp/'.$name);
$valor = str_replace(",",".", $valor);

    if (!empty($valor) and $valor != 0){
    $sql_chek="SELECT * FROM `tipocambio` WHERE `fecha` = '".$fecha."' LIMIT 0 , 1 ";
    $rs_chek=mysql_query($sql_chek);
    if (mysql_num_rows($rs_chek)==0){
			$query_operacion="INSERT INTO tipocambio (codtipocambio, fecha, valor) VALUES (null, '$fecha', '$valor')";		
			$rs_operacion=mysql_query($query_operacion);
			if ($rs_operacion) { $mensaje="El tipo de cambio ha sido dado de alta correctamente"; }
		//echo $mensaje. "<br>";
		}
	}  

    // Javascript for updating the progress bar and information
    echo '<script language="javascript">
    document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-color:#ddd;\">&nbsp;</div>";
    document.getElementById("information").innerHTML="'. implota(date("Y-m-d", $i)).' finaliza el'. 
     implota(date("Y-m-d", $fechaFin)).' ";
    </script>';

// This is for the buffer achieve the minimum size in order to flush data
    echo str_repeat(' ',1024*64);

    
// Send output to browser immediately
    flush();

    
// Sleep one second so we can see the delay
    sleep(1);
	  
    
} 
// Tell user that the process is completed
echo '<script language="javascript">document.getElementById("information").innerHTML="Proceso completado"</script>';
sleep(3);
echo "<script language=\"javascript\"> parent.$('idOfDomElement').colorbox.close();</script>";
?>

</body>
</html>
