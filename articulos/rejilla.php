<?php
include ("../conectar.php");

$codigobarras=$_POST["codigobarras"];
$descripcion=$_POST["descripcion"];
$codfamilia=$_POST["cboFamilias"];
$referencia=$_POST["referencia"];
$codproveedor=$_POST["cboProveedores"];
$codubicacion=$_POST["cboUbicacion"];

$i="";

$cadena_busqueda=$_POST["cadena_busqueda"];

$where="1=1";
if ($codigobarras <> "") { $where.=" AND codigobarras='".$codigobarras."'"; }
if ($descripcion <> "") { $where.=" AND descripcion like '%".$descripcion."%'"; }
if ($codfamilia > "0") { $where.=" AND codfamilia='$codfamilia'"; }
if ($codproveedor > "0") { $where.=" AND (codproveedor1='$codproveedor' OR codproveedor2='$codproveedor')"; }
if ($codubicacion > "0") { $where.=" AND codubicacion='$codubicacion'"; }
if ($referencia <> "") { $where.=" AND referencia like '%".$referencia."%'"; }

$where.=" ORDER BY codarticulo ASC";
$query_busqueda="SELECT count(*) as filas FROM articulos WHERE borrado=0 AND ".$where;
$rs_busqueda=mysql_query($query_busqueda);
$filas=mysql_result($rs_busqueda,0,"filas");

?>
<html>
	<head>
		<title>Articulos</title>
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
		
		function ver_articulo(codarticulo) {
			var url="ver_articulo.php?codarticulo=" + codarticulo + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			window.parent.OpenNote(url);
		}
		
		function modificar_articulo(codarticulo) {
			var url="modificar_articulo.php?codarticulo=" + codarticulo + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			window.parent.OpenNote(url);
		}
		
		function eliminar_articulo(codarticulo) {
			var url="eliminar_articulo.php?codarticulo=" + codarticulo + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
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
				<div class="header">Listado de ARTICULOS </div>
			<table class="fuente8" width="100%" cellspacing=0 cellpadding=2 border=0 ID="Table1">
						<tr class="cabeceraTabla">
							<td width="6%" >ITEM</td>
							<td width="21%" >CODIGO</td>
							<td width="8%" align="left">REFERENCIA</td>
							<td width="35%" align="left">DESCRIPCION </td>
							<td width="11%" align="left">FAMILIA</td>
							<td width="11%" align="left">PRECIO T.</td>
							<td width="11%" align="left">MONEDA</td>
							<td width="8%" align="left">STOCK</td>
							<td colspan="3">ACCIÓN</td>
						</tr>
			<input type="hidden" name="numfilas" id="numfilas" value="<?php echo $filas?>">
				<?php $iniciopagina=$_POST["iniciopagina"];
				if (empty($iniciopagina)) { $iniciopagina=$_GET["iniciopagina"]; } else { $iniciopagina=$iniciopagina-1;}
				if (empty($iniciopagina)) { $iniciopagina=0; }
				if ($iniciopagina>$filas) { $iniciopagina=0; }
					if ($filas > 0) { ?>
						<?php $sel_resultado="SELECT * FROM articulos WHERE borrado=0 AND ".$where;
						   $sel_resultado=$sel_resultado."  limit ".$iniciopagina.",10";
						   $res_resultado=mysql_query($sel_resultado);
						   $contador=0;
						   
						   while ($contador < mysql_num_rows($res_resultado)) { 
						   		if ($contador % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla";	}?>
						<tr class="<?php echo $fondolinea?>">
							<td class="aCentro" width="6%"><?php echo $contador+1;?></td>
							<td width="8%"><div align="center"><?php echo mysql_result($res_resultado,$contador,"codigobarras")?></div></td>
							<td width="21%"><div align="left"><?php echo mysql_result($res_resultado,$contador,"referencia")?></div></td>
							<td width="35%"><div align="left"><?php echo mysql_result($res_resultado,$contador,"descripcion")?></div></td>
							<td width="11%"><div align="left">
							<?php $codfamilia=mysql_result($res_resultado,$contador,"codfamilia");
							$query_familia="SELECT nombre FROM familias WHERE codfamilia='$codfamilia'";
							$rs_familia=mysql_query($query_familia);
							$nombre_familia=mysql_result($rs_familia,0,"nombre");
							echo $nombre_familia;			
							?>
							</div></td>
							<td class="aCentro" width="11%"><div align="center"><?php echo mysql_result($res_resultado,$contador,"precio_tienda")?></div></td>
							
							<td class="aCentro" width="11%"><div align="center">
							<?php
							$tipomon = array( 0=>"Selecione uno", 1=>"Pesos", 2=>"U\$S");
							 echo $tipomon[mysql_result($res_resultado,$contador,"moneda")];
							 ?>
							
							</div></td>
							<td class="aCentro" width="8%"><?php echo mysql_result($res_resultado,$contador,"stock")?></td>

							<td ><div align="center"><a href="#"><img id="botonBusqueda" src="../img/modificar.png" width="16" height="16" border="0" onClick="modificar_articulo(<?php echo mysql_result($res_resultado,$contador,"codarticulo")?>)" title="Modificar"></a></div></td>
							<td ><div align="center"><a href="#"><img id="botonBusqueda" src="../img/ver.png" width="16" height="16" border="0" onClick="ver_articulo(<?php echo mysql_result($res_resultado,$contador,"codarticulo")?>)" title="Visualizar"></a></div></td>
							<td ><div align="center"><a href="#"><img id="botonBusqueda" src="../img/eliminar.png" width="16" height="16" border="0" onClick="eliminar_articulo(<?php echo mysql_result($res_resultado,$contador,"codarticulo")?>)" title="Eliminar"></a></div></td>

						</tr>
						<?php $contador++;
							}
						?>			
					</table>
					<?php } else { ?>
					<table class="fuente8" width="87%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="100%" class="mensaje"><?php echo "No hay ning&uacute;n art&iacute;culo que cumpla con los criterios de b&uacute;squeda";?></td>
					    </tr>
					</table>					
					<?php } ?>					
				</div>
			</div>
		  </div>			
		</div>
	</body>
</html>
