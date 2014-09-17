<?php
include ("../conectar.php");
include ("../funciones/fechas.php");

$codcliente=$_POST["codcliente"];
$nombre=$_POST["nombre"];
$numfactura=$_POST["numfactura"];
$estado=$_POST["cboEstados"];
$fechainicio=$_POST["fechainicio"];
if ($fechainicio<>"") { $fechainicio=explota($fechainicio); }
$fechafin=$_POST["fechafin"];
if ($fechafin<>"") { $fechafin=explota($fechafin); }

$cadena_busqueda=$_POST["cadena_busqueda"];

$where="1=1";
if ($codcliente <> "") { $where.=" AND facturas.codcliente='$codcliente'"; }
if ($nombre <> "") { $where.=" AND (clientes.nombre like '%".$nombre."%' or clientes.apellido like '%".$nombre."%' or clientes.empresa like '%".$nombre."%')"; }
if ($numfactura <> "") { $where.=" AND codfactura='$numfactura'"; }
if ($estado > "0") { $where.=" AND estado='$estado'"; }
if (($fechainicio<>"") and ($fechafin<>"")) {
	$where.=" AND fecha between '".$fechainicio."' AND '".$fechafin."'";
} else {
	if ($fechainicio<>"") {
		$where.=" and fecha>='".$fechainicio."'";
	} else {
		if ($fechafin<>"") {
			$where.=" and fecha<='".$fechafin."'";
		}
	}
}

$where.=" ORDER BY fecha DESC";
$query_busqueda="SELECT count(*) as filas FROM facturas,clientes WHERE facturas.borrado=0 AND facturas.codcliente=clientes.codcliente AND ".$where;
$rs_busqueda=mysql_query($query_busqueda);
$filas=mysql_result($rs_busqueda,0,"filas");

?>
<html>
	<head>
		<title>Clientes</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script language="javascript">
		
		function ver_factura(codfactura) {
			var url="ver_factura.php?codfactura=" + codfactura + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			window.parent.OpenNote(url);
		}
				
		function modificar_factura(codfactura,marcaestado) {
			if (marcaestado==1) {
				var url="modificar_factura.php?codfactura=" + codfactura + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
				window.parent.OpenNote(url);
			} else {
				alert ("No puede modificar una factura ya pagada.");
			}
		}
		
		function eliminar_factura(codfactura) {
			if (confirm("Atencion va a proceder a la eliminacion de una factura. Desea continuar?")) {
				var url="eliminar_factura.php?codfactura=" + codfactura + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
				window.parent.OpenNote(url);
			}
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
			<div class="header">	relaci&oacute;n de FACTURAS </div>
			<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
						<tr class="cabeceraTabla">
							<td width="8%">ITEM</td>
							<td width="10%">N. FACTURA</td>
							<td width="8%">Tipo</td>
							<td width="48%" align="left">&nbsp;CLIENTE </td>							
							<td width="8%">MONEDA</td>
							<td width="8%">IMPORTE</td>
							<td width="5%">FECHA</td>
							<td width="5%">ESTADO</td>
							<td colspan="3" >ACCIÓN</td>
						</tr>
			<input type="hidden" name="numfilas" id="numfilas" value="<?php echo $filas;?>">
				<?php $iniciopagina=$_POST["iniciopagina"];
				
						$tipo = array( 0=>"Contado", 1=>"Credito", 2=>"Nota Credito");
						$moneda = array(1=>"\$", 2=>"U\$S");

				if (empty($iniciopagina)) { $iniciopagina=$_GET["iniciopagina"]; } else { $iniciopagina=$iniciopagina-1;}
				if (empty($iniciopagina)) { $iniciopagina=0; }
				if ($iniciopagina>$filas) { $iniciopagina=0; }
					if ($filas > 0) { ?>
						<?php $sel_resultado="SELECT codfactura,clientes.nombre as nombre,facturas.fecha as fecha,totalfactura,estado,facturas.tipo,facturas.moneda,clientes.empresa,clientes.apellido
						 FROM facturas,clientes WHERE facturas.borrado=0 AND facturas.codcliente=clientes.codcliente AND ".$where;
						   $sel_resultado=$sel_resultado."  limit ".$iniciopagina.",10";
						   $res_resultado=mysql_query($sel_resultado);
						   $contador=0;
						   $marcaestado=0;						   
						   while ($contador < mysql_num_rows($res_resultado)) { 
								$marcaestado=mysql_result($res_resultado,$contador,"estado");
								if (mysql_result($res_resultado,$contador,"estado") == 1 and mysql_result($res_resultado,$contador,"tipo") != 0) { $estado="Sin&nbsp;pagar"; } else { $estado="Pagada"; } 
								if ($contador % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }
								$tipoc=$tipo[mysql_result($res_resultado,$contador,"tipo")];
								
								?>
						<tr class="<?php echo $fondolinea?>">
							<td class="aCentro" width="8%"><?php echo $contador+1;?></td>
							<td width="8%"><div align="center"><?php echo mysql_result($res_resultado,$contador,"codfactura");?></div></td>
							<td width="8%"><div align="center"><?php echo $tipoc;?></div></td>
							<?php if (!empty(mysql_result($res_resultado,$contador,"empresa"))) {?>
							<td width="45%" ><div align="left"><?php echo mysql_result($res_resultado,$contador,"empresa")?></div></td>
							<?php } elseif (empty(mysql_result($res_resultado,$contador,"apellido"))) {?>
							<td width="45%"><div align="left"><?php echo mysql_result($res_resultado,$contador,"nombre")?></div></td>
							<?php } else { ?>
							<td width="45%"><div align="left"><?php echo mysql_result($res_resultado,$contador,"nombre")?>
							 <?php echo mysql_result($res_resultado,$contador,"apellido")?></div></td>
							<?php } ?>

							<td width="7%"><div align="center"><?php echo $moneda[mysql_result($res_resultado,$contador,"moneda")];?></div></td>
							<td width="7%"><div align="center"><?php echo number_format(mysql_result($res_resultado,$contador,"totalfactura"),2,",",".");?></div></td>
							<td class="aDerecha" width="10%"><div align="center"><?php echo implota(mysql_result($res_resultado,$contador,"fecha"));?></div></td>
							<td class="aDerecha" width="17%"><div align="center"><?php echo $estado?></div></td>
							<td ><div align="center"><a href="#"><img id="botonBusqueda" src="../img/modificar.png" width="16" height="16" border="0" onClick="modificar_factura(<?php echo mysql_result($res_resultado,$contador,"codfactura")?>,<?php echo $marcaestado?>)" title="Modificar"></a></div></td>
							<td ><div align="center"><a href="#"><img id="botonBusqueda" src="../img/ver.png" width="16" height="16" border="0" onClick="ver_factura(<?php echo mysql_result($res_resultado,$contador,"codfactura")?>)" title="Visualizar"></a></div></td>
							<td ><div align="center"><a href="#"><img id="botonBusqueda" src="../img/eliminar.png" width="16" height="16" border="0" onClick="eliminar_factura(<?php echo mysql_result($res_resultado,$contador,"codfactura")?>)" title="Eliminar"></a></div></td>
						</tr>
						<?php $contador++;
							}
						?>			
					</table>
					<?php } else { ?>
					<table class="fuente8" width="87%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="100%" class="mensaje"><?php echo "No hay ninguna factura que cumpla con los criterios de b&uacute;squeda";?></td>
					    </tr>
					</table>					
					<?php } ?>					
				</div>
			</div>
		  </div>			
		</div>
	</body>
</html>
