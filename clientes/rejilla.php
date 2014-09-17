<?php
include ("../conectar.php");

$codcliente=$_POST["codcliente"];
$nombre=$_POST["nombre"];

$nif=$_POST["nif"];
$codprovincia=$_POST["cboProvincias"];
$localidad=$_POST["localidad"];
$telefono=$_POST["telefono"];
$cadena_busqueda=$_POST["cadena_busqueda"];

$where="1=1";
if ($codcliente <> "") { $where.=" AND codcliente='$codcliente'"; }
if ($nombre <> "") { $where.=" AND(empresa like '%".$nombre."%' or nombre like '%".$nombre."%' or apellido like '%".$nombre."%' )"; }
if ($nif <> "") { $where.=" AND nif like '%".$nif."%'"; }
if ($codprovincia > "0") { $where.=" AND codprovincia='$codprovincia'"; }
if ($localidad <> "") { $where.=" AND localidad like '%".$localidad."%'"; }
if ($telefono <> "") { $where.=" AND telefono like '%".$telefono."%'"; }

$where.=" ORDER BY service DESC, empresa ASC, nombre ASC";
$query_busqueda="SELECT count(*) as filas FROM clientes WHERE borrado=0 AND ".$where;
$rs_busqueda=mysql_query($query_busqueda);
$filas=mysql_result($rs_busqueda,0,"filas");

?>
<html>
	<head>
		<title>Clientes</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script src="js/jquery.min.js"></script>
		<link rel="stylesheet" href="js/colorbox.css" />
		<script src="js/jquery.colorbox.js"></script>
<script type="text/javascript">
$(document).ready( function()
{
$("form:not(.filter) :input:visible:enabled:first").focus();

var headID = window.parent.document.getElementsByTagName("head")[0];         
var newScript = window.parent.document.createElement('script');
newScript.type = 'text/javascript';
newScript.src = 'js/jquery.colorbox.js';
headID.appendChild(newScript);
});

</script>		
		<script language="javascript">
		
		function ver_cliente(codcliente) {
			var url="ver_cliente.php?codcliente=" + codcliente + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			window.parent.OpenNote(url);
			
			/*parent.location.href="ver_cliente.php?codcliente=" + codcliente + "&cadena_busqueda=<?php echo $cadena_busqueda?>";*/
		}
		
		function modificar_cliente(codcliente) {
			var url="modificar_cliente.php?codcliente=" + codcliente + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			window.parent.OpenNote(url);
		}
		
		function eliminar_cliente(codcliente) {
			var url="eliminar_cliente.php?codcliente=" + codcliente + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			window.parent.OpenNote(url);
		}

		function listar_equipos(codcliente) {
			var url="equipos/index.php?e=" + codcliente ;
			window.parent.OpenList(url);
		}

		function listar_service(codcliente) {
			var url="service/index.php?e=" + codcliente ;
			window.parent.OpenList(url);
		}

		function listar_backup(codcliente) {
			var url="backup/index.php?e=" + codcliente ;
			window.parent.OpenList(url);
		}


		function inicio() {
			var numfilas=document.getElementById("numfilas").value;
			var indi=parent.document.getElementById("iniciopagina").value;
			var contador=1;
			var indice=0;
			if (parseInt(indi)>parseInt(numfilas)) { 
				indi=1; 
			}
			if (parseInt(numfilas) <= 10) {
					parent.document.getElementById("nextdisab").style.display = 'block';
					parent.document.getElementById("lastdisab").style.display = 'block';
					parent.document.getElementById("last").style.display = 'none';
					parent.document.getElementById("next").style.display = 'none';
			}
			parent.document.form_busqueda.filas.value=numfilas;
			parent.document.form_busqueda.paginas.innerHTML="";

			parent.document.getElementById("prevpagina").value = contador-10;
			parent.document.getElementById("currentpage").value = indice+1;
			parent.document.getElementById("nextpagina").value = contador + 10;

			while (contador<=numfilas) {
				if (parseInt(contador+9)>numfilas) {
					
				}
				texto=contador + " al " + parseInt(contador+9);
				if (parseInt(indi)==parseInt(contador)) {
					if (indi==1) {
					parent.document.getElementById("first").style.display = 'none';
					parent.document.getElementById("prev").style.display = 'none';
					parent.document.getElementById("firstdisab").style.display = 'block';
					parent.document.getElementById("prevdisab").style.display = 'block';
					} else {
					parent.document.getElementById("first").style.display = 'block';
					parent.document.getElementById("prev").style.display = 'block';
					parent.document.getElementById("firstdisab").style.display = 'none';
					parent.document.getElementById("prevdisab").style.display = 'none';
					}
					parent.document.getElementById("prevpagina").value = contador-10;
					parent.document.getElementById("currentpage").value = indice + 1;
					parent.document.getElementById("nextpagina").value = contador + 10;

					parent.document.form_busqueda.paginas.options[indice]=new Option (texto,contador);
					parent.document.form_busqueda.paginas.options[indice].selected=true;
					indiaux=	indice;				
					
				} else {

					parent.document.form_busqueda.paginas.options[indice]=new Option (texto,contador);
					parent.document.getElementById("lastpagina").value = contador;
				}
				indice++;
				contador=contador+10;
			}	

					if (parseInt(indiaux) == parseInt(indice)-1 ) {
					parent.document.getElementById("nextdisab").style.display = 'block';
					parent.document.getElementById("lastdisab").style.display = 'block';
					parent.document.getElementById("last").style.display = 'none';
					parent.document.getElementById("next").style.display = 'none';
					} else {
					parent.document.getElementById("nextdisab").style.display = 'none';
					parent.document.getElementById("lastdisab").style.display = 'none';
					parent.document.getElementById("last").style.display = 'block';
					parent.document.getElementById("next").style.display = 'block';
					}

		}	
		</script>
	</head>

	<body onload="inicio();">	
		<div id="pagina">
			<div id="zonaContenido">
			<div align="center">
			<div class="header">Listado de CLIENTES </div>
			<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
						<tr class="cabeceraTabla">
							<td>EQ.</td>
							<td colspan="2" >HIST.</td>
							<td width="38%" align="left" colspan="2">&nbsp;NOMBRE/EMPRESA </td>
							<td width="13%">RUT</td>
							<td width="13%">EMAIL</td>
							<td width="19%">TELEFONO</td>
							<td width="19%">Abonado</td>
							<td width="19%">Horas</td>
							<td width="50px" colspan="3">&nbsp;Acción</td>
						</tr>
			<input type="hidden" name="numfilas" id="numfilas" value="<?php echo $filas;?>">
				<?php 
				$iniciopagina=isset($_POST['iniciopagina']) ? $_POST['iniciopagina'] : null ;

				if (empty($iniciopagina)) { $iniciopagina=@$_GET["iniciopagina"]; } else { $iniciopagina=$iniciopagina-1;}
				if (empty($iniciopagina)) { $iniciopagina=0; }
				if ($iniciopagina>$filas) { $iniciopagina=0; }
					if ($filas > 0) { ?>
						<?php $sel_resultado="SELECT * FROM clientes WHERE borrado=0 AND ".$where;
						   $sel_resultado=$sel_resultado."  limit ".$iniciopagina.",10";
						   $res_resultado=mysql_query($sel_resultado);
						   $contador=0;
						   while ($contador < mysql_num_rows($res_resultado)) { 
								 if ($contador % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }?>
						<tr class="<?php echo $fondolinea?>">
						
							<td >
							<a href='#'>
							<img id="botonBusqueda" src="../img/equipo.png" width="16" height="16" border="0" onClick="listar_equipos(<?php echo mysql_result($res_resultado,$contador,"codcliente")?>)" title="Listar equipos">
							</a></td>
							<td>
							<a href='#'>
							<img id="botonBusqueda" src="../img/service.png" width="16" height="16" border="0" onClick="listar_service(<?php echo mysql_result($res_resultado,$contador,"codcliente")?>)" title="Listar services">
							</a></td>
							<td width="120px">
							<?php if (mysql_result($res_resultado,$contador,"service")==2) { ?>
							<a href='#'>
							<img id="botonBusqueda" src="../img/backup.png" width="16" height="16" border="0" onClick="listar_backup(<?php echo mysql_result($res_resultado,$contador,"codcliente")?>)" title="Listar backups">
							</a>
							<?php } else { ?>
							<img src="../img/blank.png" width="22" height="16" border="0" >
							<?php } ?>							
							</td>
							<?php if (mysql_result($res_resultado,$contador,"empresa")!='') {?>
							<td width="38%" colspan="2"><div align="left"><?php echo mysql_result($res_resultado,$contador,"empresa")?></div></td>
							<?php } elseif (empty(mysql_result($res_resultado,$contador,"apellido"))) {?>
							<td width="38%" colspan="2"><div align="left"><?php echo mysql_result($res_resultado,$contador,"nombre")?></div></td>
							<?php } else { ?>
							<td width="38%" colspan="2"><div align="left"><?php echo mysql_result($res_resultado,$contador,"nombre")?>
							 <?php echo mysql_result($res_resultado,$contador,"apellido")?></div></td>
							<?php } ?>
							<td class="aDerecha" width="13%"><div align="center"><?php echo mysql_result($res_resultado,$contador,"nif")?></div></td>
							<td class="aDerecha" width="13%"><div align="center"><?php echo mysql_result($res_resultado,$contador,"email")?></div></td>
							<td class="aDerecha" width="19%"><div align="center"><?php echo mysql_result($res_resultado,$contador,"telefono")?>
							<?php if (!empty(mysql_result($res_resultado,$contador,"movil"))  ){ echo " / ". mysql_result($res_resultado,$contador,"movil");}?>
							</div></td>
							<td class="aDerecha" width="19%"><div align="center">
							<?php
							 $tipo = array("Sin&nbsp;definir", "Común","Abonado A", "Abonado B");
echo $tipo[mysql_result($res_resultado,$contador,"service")];?>
							</div></td>
							<td class="aDerecha" width="19%"><div align="center"><?php echo mysql_result($res_resultado,$contador,"horas")?>
							</div></td>
							<td width="18px"><div align="center"><a href="#"><img id="botonBusqueda" src="../img/modificar.png" width="16" height="16" border="0" onClick="modificar_cliente(<?php echo mysql_result($res_resultado,$contador,"codcliente")?>)" title="Modificar"></a></div></td>
							<td width="18px"><div align="center"><a href="#"><img id="botonBusqueda" src="../img/ver.png" width="16" height="16" border="0" onClick="ver_cliente(<?php echo mysql_result($res_resultado,$contador,"codcliente")?>)" title="Visualizar"></a></div></td>
							<td width="18px"><div align="center"><a href="#"><img id="botonBusqueda" src="../img/eliminar.png" width="16" height="16" border="0" onClick="eliminar_cliente(<?php echo mysql_result($res_resultado,$contador,"codcliente")?>)" title="Eliminar"></a></div></td>
						</tr>
						<?php $contador++;
							}
						?>			
					</table>
					<?php } else { ?>
					<table class="fuente8" width="87%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="100%" class="mensaje"><?php echo "No hay ning&uacute;n cliente que cumpla con los criterios de b&uacute;squeda";?></td>
					    </tr>
					</table>					
					<?php } ?>					
				</div>
			</div>
		  </div>			
		</div>
	</body>
</html>
