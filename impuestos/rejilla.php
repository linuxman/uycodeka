<?php
include ("../conectar.php");

$codimpuesto=$_POST["codimpuesto"];
$nombre=$_POST["nombre"];
$cadena_busqueda=$_POST["cadena_busqueda"];

$where="1=1";
if ($codimpuesto <> "") { $where.=" AND codimpuesto='$codimpuesto'"; }
if ($nombre <> "") { $where.=" AND nombre like '%".$nombre."%'"; }

$where.=" ORDER BY nombre ASC";
$query_busqueda="SELECT count(*) as filas FROM impuestos WHERE borrado=0 AND ".$where;
$rs_busqueda=mysql_query($query_busqueda);
$filas=mysql_result($rs_busqueda,0,"filas");

?>
<html>
	<head>
		<title>Impuestos</title>
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
		
		function ver_impuesto(codimpuesto) {
			url="ver_impuesto.php?codimpuesto=" + codimpuesto + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			window.parent.OpenNote(url);
		}
		
		function modificar_impuesto(codimpuesto) {
			url="modificar_impuesto.php?codimpuesto=" + codimpuesto + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			window.parent.OpenNote(url);
		}
		
		function eliminar_impuesto(codimpuesto) {
			url="eliminar_impuesto.php?codimpuesto=" + codimpuesto + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
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

	<body onload="inicio();">	
		<div id="pagina">
			<div id="zonaContenido">
			<div align="center">
			<div class="header">relacion de IMPUESTOS </div>
			<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
					<tr class="cabeceraTabla">
							<td width="12%">ITEM</td>
							<td width="20%">CODIGO</td>
							<td width="40%">NOMBRE </td>
							<td width="20%">VALOR </td>
							<td colspan="3">ACCION</td>
					</tr>
			<input type="hidden" name="numfilas" id="numfilas" value="<?php echo $filas?>">
				<?php $iniciopagina=$_POST["iniciopagina"];
				if (empty($iniciopagina)) { $iniciopagina=$_GET["iniciopagina"]; } else { $iniciopagina=$iniciopagina-1;}
				if (empty($iniciopagina)) { $iniciopagina=0; }
				if ($iniciopagina>$filas) { $iniciopagina=0; }
					if ($filas > 0) { ?>
						<?php $sel_resultado="SELECT * FROM impuestos WHERE borrado=0 AND ".$where;
						   $sel_resultado=$sel_resultado."  limit ".$iniciopagina.",10";
						   $res_resultado=mysql_query($sel_resultado);
						   $contador=0;
						   while ($contador < mysql_num_rows($res_resultado)) { 
								 if ($contador % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }?>
						<tr class="<?php echo $fondolinea?>">
							<td class="aCentro" width="12%"><?php echo $contador+1;?></td>
							<td width="20%"><div align="center"><?php echo mysql_result($res_resultado,$contador,"codimpuesto")?></div></td>
							<td width="30%"><div align="left"><?php echo mysql_result($res_resultado,$contador,"nombre")?></div></td>
							<td width="20%"><div align="center"><?php echo mysql_result($res_resultado,$contador,"valor")?></div></td>
							<td><div align="center"><a href="#"><img id="botonBusqueda" src="../img/modificar.png" width="16" height="16" border="0" onClick="modificar_impuesto(<?php echo mysql_result($res_resultado,$contador,"codimpuesto")?>)" title="Modificar"></a></div></td>
							<td><div align="center"><a href="#"><img id="botonBusqueda" src="../img/ver.png" width="16" height="16" border="0" onClick="ver_impuesto(<?php echo mysql_result($res_resultado,$contador,"codimpuesto")?>)" title="Visualizar"></a></div></td>
							<td><div align="center"><a href="#"><img id="botonBusqueda" src="../img/eliminar.png" width="16" height="16" border="0" onClick="eliminar_impuesto(<?php echo mysql_result($res_resultado,$contador,"codimpuesto")?>)" title="Eliminar"></a></div></td>
						</tr>
						<?php $contador++;
							}
						?>			
					</table>
					<?php } else { ?>
					<table class="fuente8" width="85%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="100%" class="mensaje"><?php echo "No hay ning&uacute;n impuesto que cumpla con los criterios de b&uacute;squeda";?></td>
					    </tr>
					</table>					
					<?php } ?>					
				</div>
			</div>
		  </div>			
		</div>
	</body>
</html>
