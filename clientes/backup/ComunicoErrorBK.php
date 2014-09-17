<?php
/*
include("../../conexion.php");
require("class/class.phpmailer.php"); 
*/

include("/var/www/html/servicemcc/conexion.php");
include("/var/www/html/servicemcc/clientes/backup/class/class.phpmailer.php");
include("/var/www/html/servicemcc/funciones/fechas.php"); 


date_default_timezone_set('America/Montevideo');

 $search = explode(",","á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,Ã¡,Ã©,Ã­,Ã³,Ãº,Ã±,ÃÃ¡,ÃÃ©,ÃÃ­,ÃÃ³,ÃÃº,ÃÃ±");
 $replace = explode(",","á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ");

function extract_unit($string, $start, $end)
{
	$pos = stripos($string, $start);
	$str = substr($string, $pos);
	$str_two = substr($str, strlen($start));
	$second_pos = stripos($str_two, $end);
	$str_three = substr($str_two, 0, $second_pos);
	$unit = trim($str_three); /*/ remove whitespaces*/
	return $unit;
}

function GetFileName($file_name)
{
        $newfile = basename($file_name);
        if (strpos($newfile,'\\') !== false)
        {
                $tmp = preg_split("[\\\]",$newfile);
                $newfile = $tmp[count($tmp) - 1];
                return($newfile);
        }
        else
        {
                return($file_name);
        }
}

echo "Mando recordatorio<br>";
$total='';

	$Qdia=date("w", strtotime(date("Y-m-d")));


if ($Qdia==0) {
	$hoy=date('Y-m-d',time()-(48*60*60));
} elseif ($Qdia==1) {
	$hoy=date('Y-m-d',time()-(72*60*60));
} else {
	$hoy=date('Y-m-d',time()-(24*60*60));
}

	echo "dia ". $dia=date("w");
			
/*Muesto los respaldos solo los días entre semana mas el sábado, al realizarce los 
respaldos en la madrugada, el aviso aparece al día siguiente */

	if ($dia <=6 and $dia > 1) {
	 	$sql="SELECT codcliente,service,email,nombre,apellido,empresa FROM `clientes` WHERE `service` = '2'";
		$rs = mysql_query($sql);

		$contador=0;
		while ($contador < mysql_num_rows($rs)) { 		
		$codcliente=mysql_result($rs,$contador,"codcliente");
		$nombre=mysql_result($rs,$contador,"empresa").' / '.mysql_result($rs,$contador,"nombre"). ' '. mysql_result($rs,$contador,"apellido");
		$topmessage='';
				
		$email=mysql_result($rs,$contador,"email");
		$topmessage='<TABLE CELLSPACING="0" CELLSPADING="1" COLS="6" BORDER="0"><COLGROUP SPAN="5" WIDTH="185"></COLGROUP><COLGROUP WIDTH="129"></COLGROUP>
	<TR><TD COLSPAN=5 ALIGN="LEFT" BGCOLOR="#FFFFFF"><B><FONT FACE="Geneva,Arial,Helvetica,sans-serif">'.$nombre.'<BR><p></FONT></B></TD>
		<TD ALIGN="LEFT"><BR></TD></TR><TR>
		<TD COLSPAN=6 ALIGN="LEFT" VALIGN=MIDDLE BGCOLOR="#FFFFFF"><FONT FACE="Geneva,Arial,Helvetica,sans-serif">Apreciado cliente este es un aviso automático de control de respaldo.</FONT></TD>
		</TR>
	<TR><TD COLSPAN=6 ALIGN="LEFT" VALIGN=MIDDLE BGCOLOR="#FFFFFF"><FONT FACE="Geneva,Arial,Helvetica,sans-serif">Se han encontrado <b>errores</b> al realizar el respaldo.</FONT><br>&nbsp;</TD>
		</TR>
	<TR>
		<TD HEIGHT="18" ALIGN="CENTER" VALIGN=MIDDLE BGCOLOR="#000000"><B><FONT FACE="Geneva,Arial,Helvetica,sans-serif" COLOR="#FFFFFF">Fecha</FONT></B></TD>
		<TD ALIGN="CENTER" VALIGN=MIDDLE BGCOLOR="#000000"><B><FONT FACE="Geneva,Arial,Helvetica,sans-serif" COLOR="#FFFFFF">Equipo</FONT></B></TD>
		<TD ALIGN="CENTER" VALIGN=MIDDLE BGCOLOR="#000000"><B><FONT FACE="Geneva,Arial,Helvetica,sans-serif" COLOR="#FFFFFF">Tarea</FONT></B></TD>
		<TD ALIGN="CENTER" VALIGN=MIDDLE BGCOLOR="#000000"><B><FONT FACE="Geneva,Arial,Helvetica,sans-serif" COLOR="#FFFFFF">Detalles</FONT></B></TD>
		<TD ALIGN="CENTER" VALIGN=TOP BGCOLOR="#000000"><B><FONT FACE="Geneva,Arial,Helvetica,sans-serif" COLOR="#FFFFFF"><BR></FONT></B></TD>
		<TD ALIGN="CENTER" VALIGN=TOP BGCOLOR="#000000"><B><FONT FACE="Geneva,Arial,Helvetica,sans-serif" COLOR="#FFFFFF"><BR></FONT></B></TD>
	</TR>	';
	
		$encontrado=0;
		
			$sqlequipo="SELECT codcliente,service,alias FROM `equipos` WHERE `codcliente` = '$codcliente' and `service`='3'";
		 	$rsequipo = mysql_query($sqlequipo);
			$contadoAux=0;
			while ($contado=mysql_fetch_array($rsequipo)) { 		
			$alias=trim($contado["alias"]);
				$sqlrespaldo="SELECT * FROM `respaldospc`
				 WHERE  `fecha`='$hoy' and `codcliente` = '$codcliente' and trim(`usuario`) like '".$alias."'";
			 	$rsrespaldo = mysql_query($sqlrespaldo);
			 	
				if (mysql_num_rows($rsrespaldo)>0){
				$contaAux=0;
				$errores='';
				$messageAux='';
				while ($conta=mysql_fetch_array($rsrespaldo)) { 
					$errores=$conta["errores"];
					if ( $errores >=1 and !empty($errores) and (int)$errores==$errores ) {
						if($conta['procesados'] > 0 and $conta['respaldados'] == 0 ){
							$messageAux.="El equipo donde se realiza el respaldo no está disponible.<br>";						
						} elseif ($conta['procesados'] > 0){
							if(strpos($conta['message'] , "No se pudo copiar el fichero" ) !== false) {
							$startI=strpos($conta['message'] , "No se pudo copiar el fichero" );
								$stopI=strpos($conta['message'] , ": No se ha encontrado la ruta de acceso de la red" );
								$vero=substr($conta['message'], $startI, $stopI-2);
								$Archivo=extract_unit($vero, "No se pudo copiar el fichero", " No se ha encontrado la ruta de acceso de la red");
								$Archivo=str_replace('"', "", GetFileName($Archivo));
								$Archivo=str_replace(':', "", $Archivo);
							$messageAux.="<b>Quedo algún programa abierto</b> utilizando archivos a respaldar.
							<br>Archivo: ".$Archivo."
							<br>Recuerde cerrar todos los programas y dejar encendido el equipo antes de retirarse. <br>";
							} elseif(strpos($conta['message'] , "No se pudo revertir el nombre de" ) !==false ) {
							$startI=strpos($conta['message'] , "usando" );
								$stopI=strpos($conta['message'] , ": No se puede crear un archivo que ya existe" );
								$vero=substr($conta['message'], $startI, $stopI-2);
								$Archivo=extract_unit($vero, "Respaldos", " No se puede crear un archivo que ya existe");
								$Archivo=str_replace('"', "", $Archivo);
								$Archivo=str_replace(':', "", $Archivo);
								
							$messageAux.="<b>Nombre del archivo a respaldar no es válido en origen o en destino</b>.
							<br>Archivo: ".$Archivo."
							<br>Pruebe a cambiarle el nombre al archivo. <br>";
							}
													
						}
						if (strpos($conta['message'] , "No se ha podido crear la carpeta de destino" ) !== false) {
							$messageAux.="<b>El equipo donde se realiza el respaldo esta inaccesible</b>.<br> O bien hay un 
							problema de red o el equipo esta apagado.
							<br>Recuerde verificar que el equipo de respaldo este encendido. <br>";						
						} elseif (strpos($conta['message'] , "No se pudo copiar el fichero" ) !== false) {
							$startI=strpos($conta['message'] , "No se pudo copiar el fichero" );
							
							if (strpos($conta['message'],"Acceso denegado") !== false) {
								$stopI=strpos($conta['message'] , ": Acceso denegado" );
								$vero=substr($conta['message'], $startI, $stopI-2);
								$Archivo=extract_unit($vero, "No se pudo copiar el fichero", ": Acceso denegado");
								$Archivo=str_replace('"', "", GetFileName($Archivo));
								$Archivo=str_replace(':', "", $Archivo);
								$messageAux.="Se denegó el acceso al archivo ".$Archivo." <b>esta siendo utilizado</b><br>";
							} elseif (strpos($conta['message'],"No se ha encontrado la ruta de acceso de la red") !== false) {
								$stopI=strpos($conta['message'] , ": No se ha encontrado la ruta de acceso de la red" );
								$vero=substr($conta['message'], $startI, $stopI-2);
								$Archivo=extract_unit($vero, "No se pudo copiar el fichero", " No se ha encontrado la ruta de acceso de la red");
								$Archivo=str_replace('"', "", GetFileName($Archivo));
								$Archivo=str_replace(':', "", $Archivo);
							$messageAux.=" Hay un problema de red o el equipo de respaldo está apagado. Archivo: ".$Archivo."<br>";					
							} elseif (strpos($conta['message'],"Se anuló la solicitud") !== false) {
								$stopI=strpos($conta['message'] , ": Se anuló la solicitud" );
								$vero=substr($conta['message'], $startI, $stopI-2);
								$Archivo=extract_unit($vero, "No se pudo copiar el fichero", ": Se anuló la solicitud");
								$Archivo=str_replace('"', "", GetFileName($Archivo));
								$Archivo=str_replace(':', "", $Archivo);
							$messageAux.=" <b>El nombre del archivo</b> ".$Archivo." no es válido o la profundida de directorio
							excede el máximo permitido.<br>";					
							}
						} elseif (strpos($conta['message'] , "no existe o no pudo ser accedida" ) !== false) {
							$messageAux.=" Cobian backup se está ejecutando desde un usuario sin prermisos administrativos.<br>";					

						}
						$BGCOLOR="#FFFFFF";
						$COLOR="#000000";
					
							$message.='<TR>
		<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" BGCOLOR="'.$BGCOLOR.'" HEIGHT="17" ALIGN="CENTER" SDVAL="41629" SDNUM="3082;0;DD/MM/AA">
		<FONT COLOR="'.$COLOR.'">'. implota($conta['fecha']).'</FONT></TD>
		<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" BGCOLOR="'.$BGCOLOR.'" ALIGN="CENTER" VALIGN=MIDDLE SDNUM="3082;0;@">
		<FONT COLOR="'.$COLOR.'">'.$alias.'</FONT></TD>
		<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" BGCOLOR="'.$BGCOLOR.'" ALIGN="LEFT" SDNUM="3082;0;@">
		<FONT COLOR="'.$COLOR.'">&nbsp;'.$conta["tarea"].'</FONT></TD>
		<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" BGCOLOR="'.$BGCOLOR.'" COLSPAN=4 ALIGN="LEFT" VALIGN=MIDDLE SDNUM="3082;0;@">
		<FONT COLOR="'.$COLOR.'">'.$messageAux.'</FONT></TD></TR>';
						
						$encontrado=1;
						$messageAux='';
					} elseif ( !is_numeric($errores) )  {
						$messageAux='';
						if (trim($conta["errores"]) == "ha fallado en el equipo") {
							$encontrado=1;
							$message.='<TR>
		<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" HEIGHT="16" ALIGN="CENTER" SDVAL="41629" SDNUM="3082;0;DD/MM/AA">
		' .implota($conta['fecha']).'</TD>
		<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN="LEFT" VALIGN=MIDDLE><B>
		
		&nbsp;'.$alias.'</B></TD>
		<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=4 ALIGN="LEFT" VALIGN=MIDDLE>
		&nbsp;Se han encontrado errores sin especificar al realizar el respaldo</TD></TR>	';
						}
						
					}
				$contaAux++;
				$errores='';
				}
			} else {
				$encontrado=1;
				$message.='

	<TR>
		<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" HEIGHT="16" ALIGN="CENTER" BGCOLOR="#CC0000" SDVAL="41629" SDNUM="3082;0;DD/MM/AA"><FONT COLOR="#FFFFFF">
		'.implota($hoy).'</FONT></TD>
		<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN="CENTER" VALIGN=MIDDLE BGCOLOR="#CC0000"><B><FONT COLOR="#FFFFFF">'.$alias.'</FONT></B></TD>
		<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=4 ALIGN="LEFT" VALIGN=MIDDLE BGCOLOR="#CC0000"><FONT COLOR="#FFFFFF">
		No se le realizó el respaldo<br>
				<strong><u>Es posible que el equipo donde se realiza el respaldo este inaccesible</u></strong></FONT></TD>
		</TR>		';		
			}	
			$contadoAux++;	
			$alias='';		
			}
			if ($encontrado==1 and ($dia<=6 and $dia>=0)) {
				echo $message=$topmessage.$message."</TABLE>";
				$total++;
			}
					if (!empty($email) and $encontrado==1 ) {
						
						echo "<p>Envio mail<br>";
					//$email=" fgambaro@adinet.com.uy";
					
							// Instanciando el Objeto  
							// Instanciando el Objeto  
							$mail = new PHPMailer(); 
							$mail->IsSMTP(); 
							 //Servidor SMTP - GMAIL usa SSL/TLS  
							 //como protocolo de comunicación/autenticación por un puerto 465.  
							 $mail->Host = 'ssl://mcc.com.uy:465';  
//							 $mail->Host = 'ssl://smtp.gmail.com:465';  
							 // True para que verifique autentificación  
							 $mail->SMTPAuth = true;  
							 // Cuenta de E-Mail & Password  
							 $mail->Username = "respaldos@mcc.com.uy";
							 $mail->Password = "mcc[423].";
/*							 $mail->Username = "webpiced@gmail.com";
							 $mail->Password = "piced[423].";
*/						
//							 $mail->From = "webpiced@gmail.com";
							 $mail->From = "respaldos@mcc.com.uy";
							 $mail->FromName = "MCC Soporte Técnico";
							 $mail->Subject = "Aviso sobre respaldos";
							 // Cuenta de E-Mail Destinatario  
							 //$mail->AddAddress($email,$email);
							 $mail->CharSet = "UTF-8";
							 $mail->IsHTML(true);
							 $mail->AddAddress($email,$nombre);
							 $mail->AddAddress("soporte@mcc.com.uy","Fernando Gámbaro");
						//	 $mail->WordWrap = 50;

								$message.="<p><br>\r\n\r\n Gracias, <br>\r\n MCC - Soporte Técnico";
								$message.="\r\n<br> 096261570 \r\n<p><br> <hr> <font size='-1'>
								Por favor considere el medio ambiente y no imprima este correo a menos que lo necesite.
								<p>
								El presente correo electrónico y cualquier posible archivo adjunto está dirigido 
								únicamente al destinatario del mismo y contiene información que puede ser confidencial. 
								Si Ud. no es el destinatario correcto por favor notifique al remitente respondiendo 
								este mensaje y elimine inmediatamente de su sistema, el correo electrónico y los posibles 
								archivos adjuntos al mismo. Está prohibida cualquier utilización, difusión o copia de 
								este correo electrónico por cualquier persona o entidad que no sean las específicas 
								destinatarias del mensaje. MCC - Soporte Técnico no acepta ninguna responsabilidad 
								con respecto a cualquier comunicación que haya sido emitida incumpliendo lo previsto 
								en la Ley 18.331 de Protección de Datos Personales.</font>";
						
						 	 $mail->Body = $message;  
/*							 $mail->Send();*/
							if($mail->Send()){
							    echo 'Aviso enviado con exito!';
							}else{
							    echo 'Fallo el envío!'. $mail->ErrorInfo;;
							}
							 
						} //Si tiene mail válido
		$message='';
		$contador++;
		}
	}

echo "<br><center> ------------- Total de fallas -------------------<br>
		------------------ ".$total." -----------------------</center>"; 
				

