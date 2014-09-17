<?php
include ("../conectar.php");
include ("../funciones/fechas.php");

$codproveedor=$_POST["codproveedor"];
$estado=$_POST["cboEstados"];
$fechainicio=$_POST["fechainicio"];
if ($fechainicio<>"") { $fechainicio=explota($fechainicio); }
$fechafin=$_POST["fechafin"];
if ($fechafin<>"") { $fechafin=explota($fechafin); }

$cadena_busqueda=$_POST["cadena_busqueda"];

$where="1=1";
if ($codproveedor <> "") { $where.=" AND facturasp.codproveedor='$codproveedor'"; }
if ($estado <> 0) { $where.=" AND estado='$estado'"; }
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

$where.=" ORDER BY facturasp.fecha DESC";
$query_busqueda="SELECT count(*) as filas FROM facturasp LEFT JOIN pagos ON facturasp.codfactura=pagos.codfactura AND facturasp.codproveedor=pagos.codproveedor INNER JOIN proveedores ON facturasp.codproveedor=proveedores.codproveedor WHERE facturasp.borrado=0 AND ".$where;

$rs_busqueda=mysql_query($query_busqueda);
$filas=mysql_result($rs_busqueda,0,"filas");

?>
<html>
	<head>
		<title>Proveedores</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		
		
		
		<script language="javascript">

		function ver_cobros(codfactura,codproveedor) {
			var url="ver_cobros.php?codfactura=" + codfactura + "&codproveedor=" + codproveedor + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			window.parent.OpenNote(url);
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

	<body onload="inicio()";>	
		<div id="pagina">
			<div id="zonaContenido">
			<div align="center">
				<div class="header">relacion de FACTURAS</div>			
			<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
						<tr class="cabeceraTabla">
							<td width="8%">ITEM</td>
							<td width="12%">N. FACTURA</td>
							<td width="17%">PROVEEDOR </td>							
							<td width="9%">MODENA</td>
							<td width="9%">IMPORTE</td>
							<td width="10%">PENDIENTE</td>
							<td width="10%">FECHA</td>
							<td width="10%">ESTADO</td>
							<td width="10%">FECHA PAGO</td>
							<td width="5%">&nbsp;</td>
						</tr>
			
			
			<form name="form1" id="form1">
			<input type="hidden" name="numfilas" id="numfilas" value="<?php echo $filas?>">
				<?php $iniciopagina=$_POST["iniciopagina"];
				$tipomon = array( 0=>"&nbsp;", 1=>"Pesos", 2=>"U\$S");
				if (empty($iniciopagina)) { $iniciopagina=$_GET["iniciopagina"]; } else { $iniciopagina=$iniciopagina-1;}
				if (empty($iniciopagina)) { $iniciopagina=0; }
				if ($iniciopagina>$filas) { $iniciopagina=0; }
			
					if ($filas > 0) { ?>
						<?php $sel_resultado="SELECT distinct facturasp.codfactura,facturasp.fecha,facturasp.moneda as fecha,totalfactura,estado,facturasp.fechapago,facturasp.moneda,proveedores.nombre 
						as nombre,proveedores.codproveedor FROM facturasp LEFT JOIN pagos ON facturasp.codfactura=pagos.codfactura 
						AND facturasp.codproveedor=pagos.codproveedor INNER JOIN proveedores ON facturasp.codproveedor=proveedores.codproveedor 
						WHERE facturasp.borrado=0 AND ".$where." limit ".$iniciopagina.",10";
						
						   $res_resultado=mysql_query($sel_resultado);
						   $contador=0;					   
						   while ($contador < mysql_num_rows($res_resultado)) {
						   		$moneda=$tipomon[mysql_result($res_resultado,$contador,"moneda")];
						   		if (mysql_result($res_resultado,$contador,"estado") == 1) { $estado="Sin pagar"; } else { $estado="Pagada"; } 
								if ($contador % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }?>
						<tr class="<?php echo $fondolinea?>">
							<td class="aCentro" width="8%"><?php echo $contador+1;?></td>
							<td width="12%"><div align="center"><?php echo mysql_result($res_resultado,$contador,"codfactura")?></div></td>
							<td width="17%"><div align="left"><?php echo mysql_result($res_resultado,$contador,"nombre")?></div></td>							
							<td width="9%"><div align="center"><?php echo $moneda;?></div></td>
							<td width="9%"><div align="center"><?php echo number_format(mysql_result($res_resultado,$contador,"totalfactura"),2,",",".")?></div></td>
							<?php $sel_cobros="SELECT sum(importe) as aportaciones FROM pagos WHERE codfactura='".mysql_result($res_resultado,$contador,"codfactura")."' AND codproveedor='".mysql_result($res_resultado,$contador,"codproveedor")."'";
								$rs_cobros=mysql_query($sel_cobros);
								$aportaciones=mysql_result($rs_cobros,0,"aportaciones"); 
								$pendiente=mysql_result($res_resultado,$contador,"totalfactura") - $aportaciones; ?>
							<td class="aDerecha" width="10%"><div align="center"><?php echo number_format($pendiente,2,",",".")?></div></td>
							<td class="aDerecha" width="10%"><div align="center"><?php echo implota(mysql_result($res_resultado,$contador,"fecha"))?></div></td>
							<td class="aDerecha" width="10%"><div align="center"><?php echo $estado?></div></td>							
							<td width="10%"><div align="center"><?php echo implota(mysql_result($res_resultado,$contador,"fechapago"));?></div></td>
							<td width="5%"><div align="center"><a href="#">
							<img id="botonBusqueda" src="../img/dinero.jpg" width="16" height="16" border="0" 
							onClick="ver_cobros('<?php echo mysql_result($res_resultado,$contador,"codfactura")?>',<?php echo mysql_result($res_resultado,$contador,"codproveedor")?>)" 
							title="Ver Pagos"></a></div></td>
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
					</form>				
				</div>
			</div>
		  </div>			
		</div>
	</body>
</html>
