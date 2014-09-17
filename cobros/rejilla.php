<?php
include ("../conectar.php");
include ("../funciones/fechas.php");

$codcliente=$_POST["codcliente"];
$estado=$_POST["cboEstados"];
$fechainicio=$_POST["fechainicio"];
if ($fechainicio<>"") { $fechainicio=explota($fechainicio); }
$fechafin=$_POST["fechafin"];
if ($fechafin<>"") { $fechafin=explota($fechafin); }

$cadena_busqueda=$_POST["cadena_busqueda"];

$where="1=1";
if ($codcliente <> "") { $where.=" AND facturas.codcliente='$codcliente'"; }
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

$where.=" ORDER BY facturas.codfactura DESC";
$query_busqueda="SELECT count(*) as filas FROM facturas LEFT JOIN cobros ON facturas.codfactura=cobros.codfactura INNER JOIN clientes ON facturas.codcliente=clientes.codcliente 
WHERE facturas.borrado=0 AND facturas.tipo=1 AND ".$where;

$rs_busqueda=mysql_query($query_busqueda);
$filas=mysql_result($rs_busqueda,0,"filas");

$query_busqueda="SELECT SUM( totalfactura ) AS Total FROM facturas LEFT JOIN cobros ON facturas.codfactura=cobros.codfactura INNER JOIN clientes ON facturas.codcliente=clientes.codcliente 
WHERE facturas.borrado=0 AND facturas.tipo=1 AND facturas.moneda=1 AND ".$where;
$rs_busqueda=mysql_query($query_busqueda);

while ($Datos = mysql_fetch_array($rs_busqueda)){
       $TotalPesos= number_format($Datos["Total"] ,2,",",".");
    } 

$query_busqueda="SELECT SUM( totalfactura ) AS Total FROM facturas LEFT JOIN cobros ON facturas.codfactura=cobros.codfactura INNER JOIN clientes ON facturas.codcliente=clientes.codcliente 
WHERE facturas.borrado=0 AND facturas.tipo=1 AND facturas.moneda=2 AND ".$where;
$rs_busqueda=mysql_query($query_busqueda);

while ($Datos = mysql_fetch_array($rs_busqueda)){
       $TotalDolar= number_format($Datos["Total"] ,2,",",".");
    } 

?>
<html>
	<head>
		<title>Clientes</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script language="javascript">

		function ver_cobros(codfactura) {
			var url="ver_cobros.php?codfactura=" + codfactura + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			window.parent.OpenNote(url);
		}		
		
		function inicio() {
			
			parent.document.getElementById("TotalPesos").value = document.getElementById("TotalPesos").value;
			parent.document.getElementById("TotalDolar").value = document.getElementById("TotalDolar").value;
			
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

				<div class="header">	LISTADO DE FACTURAS</div>
			
			<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
						<tr class="cabeceraTabla">
							<td width="8%">N.&nbsp;FACTURA</td>
							<td width="5%">TIPO</td>
							<td width="30%">CLIENTE </td>							
							<td width="7%">MONEDA</td>
							<td width="9%">IMPORTE</td>
							<td width="10%">PENDIENTE</td>
							<td width="10%">FECHA</td>
							<td width="10%">ESTADO</td>
							<td width="10%">FECHA&nbsp;VTO.</td>
							<td width="5%">&nbsp;</td>
						</tr>
			<form name="form1" id="form1">
			<input type="hidden" name="numfilas" id="numfilas" value="<?php echo $filas;?>">
			<input type="hidden" name="TotalPesos" id="TotalPesos" value="<?php echo $TotalPesos;?>">
			<input type="hidden" name="TotalDolar" id="TotalDolar" value="<?php echo $TotalDolar;?>">
				<?php $iniciopagina=$_POST["iniciopagina"];
						$tipo = array( 0=>"Contado", 1=>"Credito", 2=>"Nota Credito");
						$moneda = array(1=>"\$", 2=>"U\$S");

				if (empty($iniciopagina)) { $iniciopagina=$_GET["iniciopagina"]; } else { $iniciopagina=$iniciopagina-1;}
				if (empty($iniciopagina)) { $iniciopagina=0; }
				if ($iniciopagina>$filas) { $iniciopagina=0; }
					if ($filas > 0) { ?>
						<?php $sel_resultado="SELECT distinct facturas.codfactura,facturas.fecha,facturas.tipo,facturas.moneda as fecha,facturas.moneda,totalfactura,estado,fechavencimiento,clientes.nombre
						 as nombre,clientes.apellido,clientes.empresa,facturas.tipo FROM facturas LEFT JOIN cobros ON facturas.codfactura=cobros.codfactura INNER JOIN clientes ON facturas.codcliente=clientes.codcliente 
						 WHERE facturas.borrado=0 AND facturas.tipo=1 AND ".$where;
							$sel_resultado=$sel_resultado."  limit ".$iniciopagina.",10";
						   $res_resultado=mysql_query($sel_resultado);
						   $contador=0;					   
						   while ($contador < mysql_num_rows($res_resultado)) { 
						   		if (mysql_result($res_resultado,$contador,"estado") == 1) { $estado="Sin cobrar"; } else { $estado="Cobrada"; } 
								if ($contador % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }
								$tipoc=$tipo[mysql_result($res_resultado,$contador,"tipo")];
								
								?>
						<tr class="<?php echo $fondolinea?>">
							<td width="8%"><div align="center"><?php echo mysql_result($res_resultado,$contador,"codfactura");?></div></td>
							<td width="5%"><div align="center"><?php echo $tipoc;?></div></td>
							<?php if (!empty(mysql_result($res_resultado,$contador,"empresa"))) {?>
							<td width="30%"><div align="left"><?php echo mysql_result($res_resultado,$contador,"empresa")?></div></td>
							<?php } elseif (empty(mysql_result($res_resultado,$contador,"apellido"))) {?>
							<td width="30%"><div align="left"><?php echo mysql_result($res_resultado,$contador,"nombre");?></div></td>
							<?php } else { ?>
							<td width="30%"><div align="left"><?php echo mysql_result($res_resultado,$contador,"nombre");?>
							 <?php echo mysql_result($res_resultado,$contador,"apellido")?></div></td>
							<?php } ?>

							<td width="7%"><div align="center"><?php echo $moneda[mysql_result($res_resultado,$contador,"moneda")];?></div></td>
					<td width="9%"><div align="center"><?php echo number_format(mysql_result($res_resultado,$contador,"totalfactura"),2,",",".");?></div></td>
							<?php $sel_cobros="SELECT sum(importe) as aportaciones FROM cobros WHERE codfactura='".mysql_result($res_resultado,$contador,"codfactura")."'";
								$rs_cobros=mysql_query($sel_cobros);
								$aportaciones=mysql_result($rs_cobros,0,"aportaciones"); 
								$pendiente=mysql_result($res_resultado,$contador,"totalfactura") - $aportaciones; ?>
							<td class="aDerecha" width="10%"><div align="center"><?php echo number_format($pendiente,2,",",".");?></div></td>
							<td class="aDerecha" width="10%"><div align="center"><?php echo implota(mysql_result($res_resultado,$contador,"fecha"));?></div></td>
							<td class="aDerecha" width="10%"><div align="center"><?php echo $estado?></div></td>							
							<td width="10%"><div align="center"><?php echo implota(mysql_result($res_resultado,$contador,"fechavencimiento"));?></div></td>
							<td width="5%"><div align="center"><a href="#">
							<img id="botonBusqueda" src="../img/dinero.jpg" width="16" height="16" border="0" onClick="ver_cobros(<?php echo mysql_result($res_resultado,$contador,"codfactura")?>)" title="Ver Cobros"></a></div></td>
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
