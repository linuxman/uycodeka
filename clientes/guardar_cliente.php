<?php
include ("../conectar.php"); 
include ('../class/Encryption.php');

$accion=$_POST["accion"];
if (!isset($accion)) { $accion=$_GET["accion"]; }

$nombre=$_POST["Anombre"];
$apellido=$_POST["aapellido"];
$empresa=$_POST["aempresa"];
$nif=$_POST["anif"];
$direccion=$_POST["adireccion"];
$localidad=$_POST["alocalidad"];
$codprovincia=$_POST["cboProvincias"];
$codformapago=$_POST["cboFPago"];
$codentidad=$_POST["cboBanco"];
$cuentabanco=$_POST["acuentabanco"];
$codpostal=$_POST["acodpostal"];
$telefono=$_POST["atelefono"];
$movil=$_POST["amovil"];
$email=$_POST["aemail"];
$web=$_POST["aweb"];
$tipo=$_POST["Ttipo"];
$horas=$_POST["nhoras"];
$contrasenia=$_POST["contrasenia"];
$cadena_busqueda=$_POST["cadena_busqueda"];


$converter = new Encryption;
$contrasenia = $converter->encode($contrasenia );

$secQ=$_POST["secQ"];
$secA=$_POST["secA"];
$service=$_POST["service"];


if ($accion=="alta") {
	$query_operacion="INSERT INTO clientes (codcliente, nombre, apellido, empresa, nif, direccion, codprovincia, localidad, 
	codformapago, codentidad, cuentabancaria, codpostal, telefono, movil,
	 email, web, tipo, contrasenia, sessionid, secQ, secA, service, horas, borrado) VALUES 
	 ('', '$nombre', '$apellido', '$empresa', '$nif', '$direccion', '$codprovincia', '$localidad',
	 '$codformapago', '$codentidad', '$cuentabanco', '$codpostal', '$telefono', '$movil',
	  '$email', '$web', '$tipo', '$contrasenia', '$sessionid', '$secQ', '$secA', '$service', '$horas', '0')";					
	$rs_operacion=mysql_query($query_operacion);
	if ($rs_operacion) { $mensaje="El cliente ha sido dado de alta correctamente"; }
	$cabecera1="Inicio >> Clientes &gt;&gt; Nuevo Cliente ";
	$cabecera2="INSERTAR CLIENTE ";
	$sel_maximo="SELECT max(codcliente) as maximo FROM clientes";
	$rs_maximo=mysql_query($sel_maximo);
	$codcliente=mysql_result($rs_maximo,0,"maximo");
}

if ($accion=="modificar") {
	
	if (!empty($contrasenia)) {
	$pass="contrasenia='".$contrasenia."',";	
	} else {
	$pass='';
	}	
	
	$codcliente=$_POST["codcliente"];
	$query="UPDATE clientes SET nombre='$nombre', apellido='$apellido', empresa='$empresa', nif='$nif', direccion='$direccion', codprovincia='$codprovincia', 
	localidad='$localidad', codformapago='$codformapago', codentidad='$codentidad',
	 cuentabancaria='$cuentabanco', codpostal='$codpostal', telefono='$telefono', 
	 movil='$movil', email='$email', web='$web',
	 tipo='$tipo', ".$pass." sessionid='$sessionid', secQ='$secQ', secA='$secA', service='$service', horas='$horas',
	  borrado=0 WHERE codcliente='$codcliente'";
	$rs_query=mysql_query($query);
	if ($rs_query) { $mensaje="Los datos del cliente han sido modificados correctamente"; }
	$cabecera1="Inicio >> Clientes &gt;&gt; Modificar Cliente ";
	$cabecera2="MODIFICAR CLIENTE ";
}

if ($accion=="baja") {
	$codcliente=$_GET["codcliente"];
	$query="UPDATE clientes SET borrado=1 WHERE codcliente='$codcliente'";
	$rs_query=mysql_query($query);
	if ($rs_query) { $mensaje="El cliente ha sido eliminado correctamente"; }
	$cabecera1="Inicio >> Clientes &gt;&gt; Eliminar Cliente ";
	$cabecera2="ELIMINAR CLIENTE ";
	$query_mostrar="SELECT * FROM clientes WHERE codcliente='$codcliente'";
	$rs_mostrar=mysql_query($query_mostrar);
	$nombre=mysql_result($rs_mostrar,0,"nombre");
	$apellido=mysql_result($rs_mostrar,0,"apellido");
	$empresa=mysql_result($rs_mostrar,0,"empresa");
	$nif=mysql_result($rs_mostrar,0,"nif");
	$direccion=mysql_result($rs_mostrar,0,"direccion");
	$localidad=mysql_result($rs_mostrar,0,"localidad");
	$codprovincia=mysql_result($rs_mostrar,0,"codprovincia");
	$codformapago=mysql_result($rs_mostrar,0,"codformapago");
	$codentidad=mysql_result($rs_mostrar,0,"codentidad");
	$cuentabanco=mysql_result($rs_mostrar,0,"cuentabancaria");
	$codpostal=mysql_result($rs_mostrar,0,"codpostal");
	$telefono=mysql_result($rs_mostrar,0,"telefono");
	$movil=mysql_result($rs_mostrar,0,"movil");
	$email=mysql_result($rs_mostrar,0,"email");
	$web=mysql_result($rs_mostrar,0,"web");
	$service=mysql_result($rs_mostrar,0,"service");
	$tipo=mysql_result($rs_mostrar,0,"tipo");
	$horas=mysql_result($rs_mostrar,0,"horas");
}

?>

<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script language="javascript">
		
		function aceptar() {
			parent.$('idOfDomElement').colorbox.close();
			/*location.href="index.php";*/
		}
		
		var cursor;
		if (document.all) {
		/*/ Está utilizando EXPLORER*/
		cursor='hand';
		} else {
		/*/ Está utilizando MOZILLA/NETSCAPE*/
		cursor='pointer';
		}
		
		</script>
	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header"><?php echo $cabecera2?></div>
				<div id="frmBusqueda">
				<table class="fuente8">
				
						<tr>

							<td width="85%" colspan="2" class="mensaje"><?php echo $mensaje;?></td>
					    </tr>
				
				<tr><td valign="top">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="15%">C&oacute;digo</td>
							<td width="85%" colspan="3"><?php echo $codcliente?></td>
					    </tr>
						<tr>
							<td width="15%">Nombre</td>
						    <td width="85%" colspan="3"><?php echo $nombre?></td>
					    </tr>
						<tr>
							<td width="15%">Apellido</td>
						    <td width="85%" colspan="3"><?php echo $apellido?></td>
					    </tr>
						<tr>
							<td width="15%">Empresa</td>
						    <td width="85%" colspan="3"><?php echo $empresa?></td>
					    </tr> 						<tr>
						  <td>CI&nbsp;/&nbsp;RUT</td>
						  <td ><?php echo $nif?></td>
							<td>Tipo</td>
							<td>
							<?php
								$tipos = array("Cliente", "MCC",);
								echo $tipos[$tipo];
							?>
							</td>
				      </tr>
						<tr>
							<td>Tel&eacute;fono</td>
							<td><?php echo $telefono?></td>

							<td>M&oacute;vil</td>
							<td width="50%"><?php echo $movil?></td>
					    </tr>
						<tr>					    
						<tr>
							<td>Correo&nbsp;electr&oacute;nico  </td>
							<td colspan="2"><?php echo $email?></td>
						</tr>
						<tr><td colspan="3">
							<?php
								$questions = array();
								$questions[0] = "Seleccione una Pregunta";
								$questions[1] = "¿En que ciudad nació?";
								$questions[2] = "¿Cúal es su color favorito?";
								$questions[3] = "¿En qué año se graduo de la facultad?";
								$questions[4] = "¿Cual es el segundo nombre de su novio/novia/marido/esposa?";
								$questions[5] = "¿Cúal es su auto favorito?";
								$questions[6] = "¿Cúal es el nombre de su madre?";
							      echo $questions[$secQ];
							?>
						</td>
						</tr><tr><td colspan="3"><?php echo $secA?>			
						</td></tr>
						
						</table></td><td>						
							<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td>Direcci&oacute;n&nbsp;web </td>
							<td colspan="2"><?php echo $web?></td>
						</tr>
						<tr>
						  <td>Direcci&oacute;n</td>
						  <td colspan="2"><?php echo $direccion?></td>
					  </tr>
						<tr>
							<td>C&oacute;digo&nbsp;postal</td>
							<td colspan="2"><?php echo $codpostal?></td>
						</tr>					  
						<tr>
						  <td>Localidad</td>
						  <td colspan="2"><?php echo $localidad?></td>
					  </tr>
					  <?php
					  	if ($codprovincia<>0) {
							$query_provincias="SELECT * FROM provincias WHERE codprovincia='$codprovincia'";
							$res_provincias=mysql_query($query_provincias);
							$nombreprovincia=mysql_result($res_provincias,0,"nombreprovincia");
						} else {
							$nombreprovincia="Sin determinar";						
						}
					  ?>
						<tr>
							<td width="15%">Departamento</td>
							<td width="85%" colspan="2"><?php echo $nombreprovincia?></td>
					    </tr>
						<?php
						if ($codformapago<>0) {
							$query_formapago="SELECT * FROM formapago WHERE codformapago='$codformapago'";
							$res_formapago=mysql_query($query_formapago);
							$nombrefp=mysql_result($res_formapago,0,"nombrefp");
						} else {
							$nombrefp="Sin determinar";
						}
					  ?>
						<tr>
							<td width="15%">Forma&nbsp;de&nbsp;pago</td>
							<td width="85%" colspan="2"><?php echo $nombrefp;?></td>
					    </tr>
						<?php
						if ($codentidad<>0) {
							$query_entidades="SELECT * FROM entidades WHERE codentidad='$codentidad'";
							$res_entidades=mysql_query($query_entidades);
							$nombreentidad=mysql_result($res_entidades,0,"nombreentidad");
						} else {
							$nombreentidad="Sin determinar";
						}
					  ?>
						<tr>
							<td width="15%">Entidad&nbsp;Bancaria</td>
							<td width="85%" colspan="2"><?php echo $nombreentidad;?></td>
					    </tr>
						<tr>
							<td>Cuenta&nbsp;bancaria</td>
							<td colspan="2"><?php echo $cuentabanco;?></td>
						</tr>
 						<tr>
							<td>Abonado/Service</td>
							<td colspan="3">
							<?php
								$services = array("Seleccione un tipo", "Común","Abonado A", "Abonado B",);
								echo $services[$service];
							?>
							</td>
				      </tr>
				      <tr><td>Horas&nbsp;Asig./Mes:</td>
				      <td><?php echo $horas;?></td>
				      </tr>					      
					</table>
					</td></tr></table>
			  </div>
				<div>
					<img id="botonBusqueda" src="../img/botonaceptar.jpg" width="85" height="22" onClick="aceptar()" border="1" onMouseOver="style.cursor=cursor">
			  </div>
			 </div>
		  </div>
		</div>
	</body>
</html>
