<?php
header('Cache-Control: no-cache');
header('Pragma: no-cache'); 

include ("../conectar.php");

$cadena_busqueda=$_GET["cadena_busqueda"];

if (!isset($cadena_busqueda)) { $cadena_busqueda=""; } else { $cadena_busqueda=str_replace("",",",$cadena_busqueda); }

if ($cadena_busqueda<>"") {
	$array_cadena_busqueda=split("~",$cadena_busqueda);
	$codbarras=$array_cadena_busqueda[1];
	$referencia=$array_cadena_busqueda[2];
	$codfamilia=$array_cadena_busqueda[3];
	$descripcion=$array_cadena_busqueda[4];
	$codproveedor=$array_cadena_busqueda[5];
	$codubicacion=$array_cadena_busqueda[6];
} else {
	$codbarras="";
	$referencia="";
	$codfamilia="";
	$descripcion="";
	$codproveedor="";
	$codubicacion="";
}

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

		$(".callbacks").colorbox({
			iframe:true, width:"99%", height:"99%",
			onCleanup:function(){ window.location.reload();	}
		});

});
</script>
<script type="text/javascript">
function OpenNote(noteId){

	$.colorbox({
	   	href: noteId, open:true,
			iframe:true, width:"99%", height:"99%",
			onCleanup:function(){ document.getElementById("form_busqueda").submit(); }
	});

}
function OpenList(noteId){

	$.colorbox({
	   	href: noteId, open:true,
			iframe:true, width:"99%", height:"99%",
			onCleanup:function(){ document.getElementById("form_busqueda").submit(); }
	});

}

function pon_prefijo(referencia,odbarras,descripcion) {
	$("#referencia").val(referencia);
	$("#codbarras").val(odbarras);
	$("#descripcion").val(descripcion);
	$('idOfDomElement').colorbox.close();
	document.getElementById("form_busqueda").submit();

}

</script>
		
		
		<script language="javascript">
		
		var cursor;
		if (document.all) {
		/*/ Está utilizando EXPLORER*/
		cursor='hand';
		} else {
		/*/ Está utilizando MOZILLA/NETSCAPE*/
		cursor='pointer';
		}
		
		function inicio() {
			document.getElementById("firstdisab").style.display = 'block';
			document.getElementById("prevdisab").style.display = 'block';
			document.getElementById("last").style.display = 'block';
			document.getElementById("next").style.display = 'block';
			document.getElementById("form_busqueda").submit();			
		}
	
		function nuevo_articulo() {
			$.colorbox({href:"nuevo_articulo.php",
			iframe:true, width:"99%", height:"99%",
			onCleanup:function(){ window.location.reload();	}
		});	
		}
		
		function imprimir() {
			var codbarras=document.getElementById("codbarras").value;
			var referencia=document.getElementById("referencia").value;
			var descripcion=document.getElementById("descripcion").value;
			var proveedores=document.getElementById("cboProveedores").value;			
			var familia=document.getElementById("cboFamilias").value;
			var ubicacion=document.getElementById("cboUbicacion").value;
			window.open("../fpdf/articulos.php?codbarras="+codbarras+"&referencia="+referencia+"&descripcion="+descripcion+"&proveedores="+proveedores+"&familia="+familia+"&ubicacion="+ubicacion);
		}
		
		function limpiar() {
			document.getElementById("form_busqueda").reset();
			document.getElementById("form_busqueda").submit();			
		}		
		
		function buscar() {
			var cadena;
			cadena=hacer_cadena_busqueda();
			document.getElementById("cadena_busqueda").value=cadena;
			if (document.getElementById("iniciopagina").value=="") {
				document.getElementById("iniciopagina").value=1;
			} else {
				document.getElementById("iniciopagina").value=document.getElementById("paginas").value;
			}
			document.getElementById("form_busqueda").submit();
		}
		
		function paginar() {
			document.getElementById("iniciopagina").value=document.getElementById("paginas").value;
			document.getElementById("form_busqueda").submit();
		}

		function firstpage() {
			document.getElementById("iniciopagina").value=document.getElementById("firstpagina").value;
			document.getElementById("form_busqueda").submit();
		}
		function prevpage() {
			document.getElementById("iniciopagina").value=document.getElementById("prevpagina").value;
			document.getElementById("form_busqueda").submit();
		}
		function nextpage() {
			document.getElementById("iniciopagina").value=document.getElementById("nextpagina").value;
			document.getElementById("form_busqueda").submit();
		}
		function lastpage() {
			document.getElementById("iniciopagina").value=document.getElementById("lastpagina").value;
			document.getElementById("form_busqueda").submit();
		}
		
		function hacer_cadena_busqueda() {
			var codbarras=document.getElementById("codbarras").value;
			var referencia=document.getElementById("referencia").value;
			var descripcion=document.getElementById("descripcion").value;
			var proveedores=document.getElementById("coProveedores").value;			
			var familia=document.getElementById("cboFamilias").value;
			var ubicacion=document.getElementById("cboUbicacion").value;
			var cadena="";
			cadena="~"+codbarras+"~"+referencia+"~"+familia+"~"+descripcion+"~"+proveedores+"~"+ubicacion+"~";
			return cadena;
			}
		
		function ventanaArticulos(){
			$.colorbox({
	   	href: "ventana_articulos.php", open:true,
			iframe:true, width:"99%", height:"99%"
			});
			
		}
		</script>
	</head>
	<body onLoad="inicio()">
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">Buscar ARTICULO </div>
				<div id="frmBusqueda">
				<form id="form_busqueda" name="form_busqueda" method="post" action="rejilla.php" target="frame_rejilla">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0><tr><td valign="top" width="50%">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>										
						<tr>
							<td width="16%">Referencia</td>
							<td width="68%"><input id="referencia" type="text" class="cajaPequena" NAME="referencia" maxlength="15" value="<?php echo $referencia;?>" readonly="yes">
							 <img id="botonBusqueda" src="../img/ver.png" width="16" height="16" onClick="ventanaArticulos();" onMouseOver="style.cursor=cursor" title="Buscar articulos"></td>
							<td width="5%">&nbsp;</td>
							<td width="5%">&nbsp;</td>
							<td width="6%" align="right"></td>
						</tr>
						<tr>
							<td>C&oacute;digo&nbsp;de&nbsp;barras</td>
							<td><input id="codbarras" name="codbarras" type="text" class="cajaGrande" maxlength="20" value="<?php echo $codbarras;?>"></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<?php
					  	$query_familias="SELECT * FROM familias ORDER BY nombre ASC";
						$res_familias=mysql_query($query_familias);
						$contador=0;
					  ?>
						<tr>
							<td>Familia</td>
							<td><select id="cboFamilias" name="cboFamilias" class="comboMedio">
							<option value="0">Todas las familias</option>
								<?php
								while ($contador < mysql_num_rows($res_familias)) { 
									if ( mysql_result($res_familias,$contador,"codfamilia") == $familia) { ?>
								<option value="<?php echo mysql_result($res_familias,$contador,"codfamilia")?>" selected><?php echo mysql_result($res_familias,$contador,"nombre")?></option>
								<?php } else { ?> 
								<option value="<?php echo mysql_result($res_familias,$contador,"codfamilia")?>"><?php echo mysql_result($res_familias,$contador,"nombre")?></option>
								<?php }
								$contador++;
								} ?>				
								</select>							</td>
					    </tr>
							</table></td><td valign="top" width="50%">
					  		<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>					    
						<tr>
							<td>Descripci&oacute;n</td>
							<td><input id="descripcion" name="descripcion" type="text" class="cajaGrande" maxlength="60" value="<?php echo $descripcion?>"></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<?php
					  	$query_proveedores="SELECT codproveedor,nombre,nif FROM proveedores WHERE borrado=0 ORDER BY nombre ASC";
						$res_proveedores=mysql_query($query_proveedores);
						$contador=0;
					  ?>
						<tr>
							<td>Proveedor</td>
							<td><select id="cboProveedores" name="cboProveedores" class="comboGrande">
							<option value="0">Todos los proveedores</option>
								<?php
								while ($contador < mysql_num_rows($res_proveedores)) { 
									if ( mysql_result($res_proveedores,$contador,"codproveedor") == $proveedor) { ?>
								<option value="<?php echo mysql_result($res_proveedores,$contador,"codproveedor")?>" selected><?php echo mysql_result($res_proveedores,$contador,"nif")?> -- <?php echo mysql_result($res_proveedores,$contador,"nombre")?></option>
								<?php } else { ?> 
								<option value="<?php echo mysql_result($res_proveedores,$contador,"codproveedor")?>"><?php echo mysql_result($res_proveedores,$contador,"nif")?> -- <?php echo mysql_result($res_proveedores,$contador,"nombre")?></option>
								<?php }
								$contador++;
								} ?>				
								</select>							</td>
					    </tr>
					<?php
					  	$query_ubicacion="SELECT codubicacion,nombre FROM ubicaciones WHERE borrado=0 ORDER BY nombre ASC";
						$res_ubicacion=mysql_query($query_ubicacion);
						$contador=0;
					  ?>
						<tr>
							<td>Ubicaci&oacute;n</td>
							<td><select id="cboUbicacion" name="cboUbicacion" class="comboGrande">
							<option value="0">Todas las ubicaciones</option>
								<?php
								while ($contador < mysql_num_rows($res_ubicacion)) { 
									if ( mysql_result($res_ubicacion,$contador,"codubicacion") == $ubicacion) { ?>
								<option value="<?php echo mysql_result($res_ubicacion,$contador,"codubicacion")?>" selected><?php echo mysql_result($res_ubicacion,$contador,"nombre")?></option>
								<?php } else { ?> 
								<option value="<?php echo mysql_result($res_ubicacion,$contador,"codubicacion")?>"><?php echo mysql_result($res_ubicacion,$contador,"nombre")?></option>
								<?php }
								$contador++;
								} ?>				
								</select>							</td>
					    </tr>
					</table></td></tr></table>
			  </div>
					<div>
			  <div id="lineaResultado">
			  <table class="fuente8" width="90%" cellspacing=0 cellpadding=3 border=0>
			  	<tr>
				<td width="30%" align="left">Nº de articulos encontrados <input id="filas" type="text" class="cajaPequena" NAME="filas" maxlength="5" readonly></td>
				<td width="50%" align="center"><div>
						<img id="botonBusqueda" src="../img/botonbuscar.jpg" width="69" height="22" border="1" onClick="buscar()" onMouseOver="style.cursor=cursor">
			 	  		<img id="botonBusqueda" src="../img/botonlimpiar.jpg" width="69" height="22" border="1" onClick="limpiar()" onMouseOver="style.cursor=cursor">
					 	<img id="botonBusqueda" src="../img/botonnuevoarticulo.jpg" width="111" height="22" border="1" onClick="nuevo_articulo()" onMouseOver="style.cursor=cursor">
						<img id="botonBusqueda" src="../img/botonimprimir.jpg" width="79" height="22" border="1" onClick="imprimir()" onMouseOver="style.cursor=cursor"></div>				
				</td>
				<td width="20%" align="right">
					<table class="fuente8" cellspacing=1 cellpadding=1 border=0>
		<td>				
		<input type="hidden" id="firstpagina" name="firstpagina" value="1">
		<img style="display: none;" src="../img/paginar/first.gif" id="first" border="0" height="13" width="13" onClick="firstpage()" onMouseOver="style.cursor=cursor">
		<img style="display: none;" src="../img/paginar/firstdisab.gif" id="firstdisab" border="0" height="13" width="13"></td>
		<td>
		<input type="hidden" id="prevpagina" name="prevpagina" value="">
		<img style="display: none;" src="../img/paginar/prev.gif" id="prev" border="0" height="13" width="13" onClick="prevpage()" onMouseOver="style.cursor=cursor">
		<img style="display: none;" src="../img/paginar/prevdisab.gif" id="prevdisab" border="0" height="13" width="13">
		</td><td>
		<input id="currentpage" type="text" class="cajaMinima" >
		</td><td>
		<input type="hidden" id="nextpagina" name="nextpagina" value="">
		<img style="display: none;" src="../img/paginar/next.gif" id="next" border="0" height="13" width="13" onClick="nextpage()" onMouseOver="style.cursor=cursor">
		<img style="display: none;" src="../img/paginar/nextdisab.gif" id="nextdisab" border="0" height="13" width="13"></td>
		<td>
		<input type="hidden" id="lastpagina" name="lastpagina" value="">
		<img style="display: none;" src="../img/paginar/last.gif" id="last" border="0" height="13" width="13" onClick="lastpage()" onMouseOver="style.cursor=cursor">
		<img style="display: none;" src="../img/paginar/lastdisab.gif" id="lastdisab" border="0" height="13" width="13"></td>
		<td>Mostrados</td><td> <select name="paginas" id="paginas" onChange="paginar();">
		          </select></td>      
		</table>	</td>
			  </table>
				</div>
				<input type="hidden" id="iniciopagina" name="iniciopagina">
				<input type="hidden" id="cadena_busqueda" name="cadena_busqueda">
			</form>
				<div id="lineaResultado">
					<iframe width="100%" height="340" id="frame_rejilla" name="frame_rejilla" frameborder="0">
						<ilayer width="100%" height="340" id="frame_rejilla" name="frame_rejilla"></ilayer>
					</iframe>
				</div>
			</div>
		  </div>			
		</div>
	</body>
</html>
