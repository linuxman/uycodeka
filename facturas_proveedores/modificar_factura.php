<?php 
include ("../conectar.php"); 
include ("../funciones/fechas.php"); 

$codfactura=$_GET["codfactura"];
$codproveedor=$_GET["codproveedor"];
$sel_alb="SELECT * FROM facturasp WHERE codfactura='$codfactura' AND codproveedor='$codproveedor'";
$rs_alb=mysql_query($sel_alb);
$codproveedor=mysql_result($rs_alb,0,"codproveedor");
$iva=mysql_result($rs_alb,0,"iva");
$fecha=mysql_result($rs_alb,0,"fecha");
$moneda=mysql_result($rs_alb,0,"moneda");

$sel_cliente="SELECT nombre,nif FROM proveedores WHERE codproveedor='$codproveedor'";
$rs_cliente=mysql_query($sel_cliente);
$nombre=mysql_result($rs_cliente,0,"nombre");
$nif=mysql_result($rs_cliente,0,"nif");


$fechahoy=date("Y-m-d");
$sel_albaran="INSERT INTO facturasptmp (codfactura,fecha,moneda) VALUE ('','$fechahoy','$moneda')";
$rs_albaran=mysql_query($sel_albaran);
$codfacturatmp=mysql_insert_id();

$sel_lineas="SELECT * FROM factulineap WHERE codfactura='$codfactura' AND codproveedor='$codproveedor' ORDER BY numlinea ASC";
$rs_lineas=mysql_query($sel_lineas);
$contador=0;
while ($contador < mysql_num_rows($rs_lineas)) {
	$codfamilia=mysql_result($rs_lineas,$contador,"codfamilia");
	$codigo=mysql_result($rs_lineas,$contador,"codigo");
	$cantidad=mysql_result($rs_lineas,$contador,"cantidad");
	$precio=mysql_result($rs_lineas,$contador,"precio");
	$importe=mysql_result($rs_lineas,$contador,"importe");
	$baseimponible=$baseimponible+$importe;
	$dcto=mysql_result($rs_lineas,$contador,"dcto");
	$sel_tmp="INSERT INTO factulineaptmp (codfactura,numlinea,codfamilia,codigo,cantidad,precio,importe,dcto) VALUES ('$codfacturatmp','','$codfamilia','$codigo','$cantidad','$precio','$importe','$dcto')";
	$rs_tmp=mysql_query($sel_tmp);
	$contador++;
}

$baseimpuestos=$baseimponible*($iva/100);
$preciototal=$baseimponible+$baseimpuestos;
//$preciototal=number_format($preciototal,2);
?>
<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
    <script src="../calendario/jscal2.js"></script>
    <script src="../calendario/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="../calendario/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/border-radius.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/win2k/win2k.css" />		

		<script src="js/jquery.min.js"></script>
		<link rel="stylesheet" href="js/colorbox.css" />
		<script src="js/jquery.colorbox.js"></script>
				
		<script type="text/javascript">
$(document).ready( function()
{
$("form:not(.filter) :input:visible:enabled:first").focus();

		$(".callbacks").colorbox({
			iframe:true, width:"720px", height:"98%",
			onCleanup:function(){ window.location.reload();	}
		});

});
</script>		

<script type="text/javascript">
function OpenNote(noteId){
	$.colorbox({
	   	href: noteId, open:true,
			iframe:true, width:"90%", height:"80%",
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

function pon_prefijo(pref,nombre,nif) {
	$("#codproveedor").val(pref);
	$("#nombre").val(nombre);
	$("#nif").val(nif);
	$('idOfDomElement').colorbox.close();
}

function pon_prefijo_Fb (codfamilia,pref,nombre,descripcion_corta,precio,codarticulo,moneda) {
	var monArray = new Array();
	monArray[0]="Selecione uno";
	monArray[1]="Pesos";
	monArray[2]="U\$S";
	$("#codfamilia").val(codfamilia);
	$("#codbarras").val(pref);
	$("#detalles").val(nombre);
	$("#descripcion").val(descripcion_corta);
	$("#precio").val(precio);
	$("#moneda").val(moneda);
	$("#monedaShow").val(monArray[moneda]);
	$("#importe").val(precio);
	$("#codarticulo").val(codarticulo);
	$('idOfDomElement').colorbox.close();
	actualizar_importe();
}

</script>

		
		<script language="javascript">
		
		var cursor;
		if (document.all) {
		// Está utilizando EXPLORER
		cursor='hand';
		} else {
		// Está utilizando MOZILLA/NETSCAPE
		cursor='pointer';
		}
		
		function inicio() {
			document.getElementById("modif").value=1;
			document.formulario_lineas.submit();
			document.getElementById("modif").value=0;			
			var fecha=$("#fecha").val();
				$.post("busco_tipocambio.php?fecha="+fecha,  function(data){
				$("#tipocambio").val(data);
			})(jQuery);	
		}	
		function abreVentana(){
			var bi=document.getElementById("baseimponible").value;
			if (bi==0) {
				$.colorbox({
		   	href: "ver_proveedores.php", open:true,
				iframe:true, width:"99%", height:"99%"
				});			
			} else {
				alert ("Ha comenzado la factura. No puede cambiar de proveedor");
			}
		}
		
		function ventanaArticulos(){
			var codprov=document.getElementById("codproveedor").value;
			if (codprov=="") {
				alert ("Debe introducir antes el codigo de proveedor");
			} else {
				document.getElementById("codproveedor").ReadOnly=true;
				$.colorbox({href:"ver_articulos.php?codproveedor="+codprov,
				iframe:true, width:"95%", height:"95%",
				});				
			}
		}
		
		function validarproveedor(){
			var bi=document.getElementById("baseimponible").value;
			if (bi==10) {
				var codigo=document.getElementById("codproveedor").value;
				$.colorbox({href:"comprobarproveedor.php?codproveedor="+codigo,
				iframe:true, width:"95%", height:"95%",
				});				
			} 
		}	
		
		function comprobarestado() {
			var codpro=document.getElementById("codproveedor").value;
			var bi=document.getElementById("baseimponible").value;
			if (bi>0) {
				alert ("Ha comenzado la factura. No puede cambiar de proveedor");
				document.getElementById("codproveedor").blur();
			}
		}
		
		function cancelar() {
			parent.$('idOfDomElement').colorbox.close();
		}
		
		function limpiarcaja() {
			document.getElementById("nombre").value="";
			document.getElementById("nif").value="";
		}

function pon_baseimponible(baseimponible) {
	$("#baseimponible").val(baseimponible);
	cambio_iva();
}
		
		function actualizar_importe()
			{
				/*Si la factura es en peso y el articulo esta en dolares aplico el tipo de cambio*/
				var tipocambiofactura=document.formulario.amoneda.options[document.formulario.amoneda.selectedIndex].value;
				var tipocambioarcticulo=document.getElementById("moneda").value;
				if (tipocambiofactura==1 && tipocambioarcticulo == 2){
					var precio=document.getElementById("precio").value * parseFloat(document.getElementById("tipocambio").value);
				}
				if (tipocambiofactura==2 && tipocambioarcticulo == 1){
					var precio=document.getElementById("precio").value / parseFloat(document.getElementById("tipocambio").value);
				}
				if ((tipocambiofactura==1 && tipocambioarcticulo == 1) || (tipocambiofactura==2 && tipocambioarcticulo == 2)){
				var precio=document.getElementById("precio").value;
				}
			
				var cantidad=document.getElementById("cantidad").value;
				var descuento=document.getElementById("descuento").value;
				descuento=descuento/100;
				total=precio*cantidad;
				descuento=total*descuento;
				total=total-descuento;
				var original=parseFloat(total);
				var result=Math.round(original*100)/100 ;
				document.getElementById("importe").value=result;
			}
			
		function validar_cabecera()
			{
				var mensaje="";
				if (document.getElementById("nombre").value=="") mensaje+="  - Nombre\n";
				if (document.getElementById("fecha").value=="") mensaje+="  - Fecha\n";
				if (document.getElementById("cfactura").value=="") mensaje+="  - Cod. Factura\n";
				if (mensaje!="") {
					alert("Atencion, se han detectado las siguientes incorrecciones:\n\n"+mensaje);
				} else {
					document.getElementById("formulario").submit();
				}
			}	
		
		function validar() 
			{
				var mensaje="";
				var entero=0;
				var enteroo=0;
		
				if (document.getElementById("codbarras").value=="") mensaje="  - Artículo\n";
				if (document.getElementById("descripcion").value=="") mensaje+="  - Descripcion\n";
				if (document.getElementById("precio").value=="") { 
							mensaje+="  - Falta el precio\n"; 
				} else {
					if (isNaN(document.getElementById("precio").value)==true) {
						mensaje+="  - El precio debe ser numerico\n";
					}
				}
				if (document.getElementById("cantidad").value=="") 
						{ 
						mensaje+="  - Falta la cantidad\n";
						} else {
							enteroo=parseInt(document.getElementById("cantidad").value);
							if (isNaN(enteroo)==true) {
								mensaje+="  - La cantidad debe ser numerica\n";
							} else {
									document.getElementById("cantidad").value=enteroo;
								}
						}
				if (document.getElementById("descuento").value=="") 
						{ 
						document.getElementById("descuento").value=0 
						} else {
							entero=parseInt(document.getElementById("descuento").value);
							if (isNaN(entero)==true) {
								mensaje+="  - El descuento debe ser numerico\n";
							} else {
								document.getElementById("descuento").value=entero;
							}
						} 
				if (document.getElementById("importe").value=="") mensaje+="  - Falta el importe\n";
				
				if (mensaje!="") {
					alert("Atencion, se han detectado las siguientes incorrecciones:\n\n"+mensaje);
				} else {
					document.getElementById("baseimponible").value=parseFloat(document.getElementById("baseimponible").value) + parseFloat(document.getElementById("importe").value);	
					cambio_iva();
					document.getElementById("formulario_lineas").submit();
					document.getElementById("codbarras").value="";
					document.getElementById("descripcion").value="";
					document.getElementById("detalles").value="";
					document.getElementById("precio").value="";
					document.getElementById("moneda").value="";
					$("#monedaShow").val('');
					document.getElementById("cantidad").value=1;
					document.getElementById("importe").value="";
					document.getElementById("descuento").value=0;						
				}
			}
			
		function cambio_iva() {
			var original=parseFloat(document.getElementById("baseimponible").value);
			var result=Math.round(original*100)/100 ;
			document.getElementById("baseimponible").value=result;
	
			document.getElementById("baseimpuestos").value=parseFloat(result * parseFloat(document.getElementById("iva").value / 100));
			var original1=parseFloat(document.getElementById("baseimpuestos").value);
			var result1=Math.round(original1*100)/100 ;
			document.getElementById("baseimpuestos").value=result1;
			var original2=parseFloat(result + result1);
			var result2=Math.round(original2*100)/100 ;
			document.getElementById("preciototal").value=result2;
		}	
		function busco_tipocambio() {
			var fecha=$("#fecha").val();
				$.post("busco_tipocambio.php?fecha="+fecha,  function(data){
				$("#tipocambio").val(data);
			})(jQuery);			
	 		
		}		
		var tipoaux='';
		function cambio() {
			var Index = document.formulario.amoneda.options[document.formulario.amoneda.selectedIndex].value;
			var monArray = new Array();
			monArray[0]="Selecione uno";
			monArray[1]="Pesos";
			monArray[2]="U\$S";
			$("#monShow").val(monArray[Index]);
			$("#monSho").val(monArray[Index]);
			$("#monSh").val(monArray[Index]);

			var moneda=document.getElementById("moneda").value;
			var cantidad=document.getElementById("cantidad").value;
			var descuento=document.getElementById("descuento").value;

				if (moneda==1 && Index == 2){
					precio= $("#precio").val() / parseFloat($("#tipocambio").val());
					descuento=descuento/100;
					total=precio*cantidad;
					descuento=total*descuento;
					total=total-descuento;
					var original=parseFloat(total);
					var result=Math.round(original*100)/100 ;
					document.getElementById("importe").value=result;
				}
				if (moneda==2 && Index == 1){
					precio= $("#precio").val() * parseFloat($("#tipocambio").val());
					descuento=descuento/100;
					total=precio*cantidad;
					descuento=total*descuento;
					total=total-descuento;
					var original=parseFloat(total);
					var result=Math.round(original*100)/100 ;
					document.getElementById("importe").value=result;
				}
				if (moneda== Index){
					precio= $("#precio").val();
					descuento=descuento/100;
					total=precio*cantidad;
					descuento=total*descuento;
					total=total-descuento;
					var original=parseFloat(total);
					var result=Math.round(original*100)/100  ;
					document.getElementById("importe").value=result;
				}

				if (tipoaux==1 && Index == 2){
					document.getElementById("baseimponible").value=Math.round(( $("#baseimponible").val() / parseFloat($("#tipocambio").val())) * 100) / 100;
					document.getElementById("baseimpuestos").value=Math.round(( $("#baseimpuestos").val() / parseFloat($("#tipocambio").val())) * 100) / 100;
					document.getElementById("preciototal").value=Math.round(($("#preciototal").val() / parseFloat($("#tipocambio").val())) * 100) / 100;
				}
				if (tipoaux==2 && Index == 1){
					document.getElementById("baseimponible").value=Math.round(($("#baseimponible").val() * parseFloat($("#tipocambio").val())) * 10) / 10;
					document.getElementById("baseimpuestos").value=Math.round(($("#baseimpuestos").val() * parseFloat($("#tipocambio").val())) * 10) / 10;
					document.getElementById("preciototal").value=Math.round(($("#preciototal").val() * parseFloat($("#tipocambio").val())) * 10) / 10;
				}
			tipoaux=Index;
		}	
		</script>
	</head>
	<body onLoad="inicio()">
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">MODIFICAR FACTURA</div>
				<div id="frmBusqueda">
				<form id="formulario" name="formulario" method="post" action="guardar_factura.php">				
				<table class="fuente8" width="98%" cellspacing="0" cellpadding="3" border="0">
						<tr>
							<td>C&oacute;digo&nbsp;Proveedor </td>
					      <td><input NAME="codproveedor" type="text" class="cajaPequena" id="codproveedor" size="6" maxlength="5"  value="<?php echo $codproveedor?>"></input>
					        </td>					
						  <td>Cod.&nbsp;Factura</td>
						  <td><input NAME="cfactura" type="text" class="cajaMedia" id="cfactura" size="20" maxlength="20" value="<?php echo $codfactura?>"></input></td>
						<?php $hoy=date("d/m/Y"); ?>
							<td>Fecha</td>
						    <td width="27%"><input NAME="fecha" type="text" class="cajaPequena" id="fecha" size="10" maxlength="10" value="<?php echo implota($fecha)?>" readonly>
						    <img src="../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'">
								<script type="text/javascript">//<![CDATA[
						   Calendar.setup({
						     inputField : "fecha",
						     trigger    : "Image1",
						     align		 : "Bl",
						     onSelect   : function() { this.hide(); },
						     dateFormat : "%d/%m/%Y"
						   });
						//]]></script></td>		
				 		<td>Moneda</td><td width="26%">
						 <select onchange="cambio();" name="amoneda" id="amoneda" class="cajaPequena2">
							<?php if($moneda==1) { ?>						 
								<option value="1" selected="selected">Pesos</option>
								<option value="2">U$S</option>
							<?php } else { ?>
								<option value="1">Pesos</option>
								<option value="2" selected="selected">U$S</option>
							<?php } ?>						 

  							</select></td>											  
						</tr>
						<tr>
							<td>Nombre</td>
						    <td><input NAME="nombre" type="text" class="cajaGrande" id="nombre" size="45" maxlength="45" value="<?php echo $nombre?>" readonly></td>
				            <td>RUT</td>
				            <td><input NAME="nif" type="text" class="cajaMedia" id="nif" size="20" maxlength="15" value="<?php echo $nif?>" readonly></td>
							
				            <td>IVA</td>
				            <td><input NAME="iva" type="text" class="cajaPequena" id="iva" size="5" maxlength="5" value="<?php echo $iva;?>" onChange="cambio_iva();"> %</td>

							<td colspan="4">Tipo&nbsp;cambio
								<label>U$S -> $&nbsp;</label><span>
								<input NAME="tipocambio" type="text" class="cajaPequena2" id="tipocambio" size="5" maxlength="5" value="<?php echo $tipocambio; ?>" readonly=""></span>
							</td>
						</tr>
					</table>										
			  </div>
			  <input id="codfacturatmp" name="codfacturatmp" value="<?php echo $codfacturatmp?>" type="hidden">
			  <input id="codfactura" name="codfactura" value="<?php echo $codfactura?>" type="hidden">
			  <input id="baseimpuestos2" name="baseimpuestos" value="<?php echo $baseimpuestos?>" type="hidden">
			  <input id="baseimponible2" name="baseimponible" value="<?php echo $baseimponible?>" type="hidden">
			  <input id="preciototal2" name="preciototal" value="<?php echo $preciototal?>" type="hidden">
			  <input id="accion" name="accion" value="modificar" type="hidden">			  
			  </form>
			  <br>
				<div id="frmBusqueda">
				<form id="formulario_lineas" name="formulario_lineas" method="post" action="frame_lineas.php" target="frame_lineas">
				<table class="fuente8" width="98%" cellspacing=0 cellpadding=2 border=0>
				  <tr>
					<td width="11%">Codigo barras </td>
					<td valign="middle"><input NAME="codbarras" type="text" class="cajaMedia" id="codbarras" size="15" maxlength="15">
					 <img id="botonBusqueda" src="../img/calculadora.jpg" border="1" align="absmiddle" onClick="validarArticulo();" onMouseOver="style.cursor=cursor" title="Validar codigo de barras">
					 <img id="botonBusqueda" src="../img/ver.png" width="16" height="16" onClick="ventanaArticulos();" onMouseOver="style.cursor=cursor" title="Buscar articulo"></td>
					<td>Descripcion</td>
					<td colspan="5" ><input NAME="descripcion" type="text" class="cajaGrande" id="descripcion" size="30" maxlength="30" readonly></td>
					<td>Moneda</td>					 
					 <td colspan="2">
					 <input NAME="monedaShow" type="text" class="cajaPequena2" id="monedaShow" size="10" maxlength="10" readonly>
					 <input NAME="moneda" id="moneda" type="hidden" >
					 </td>
				  </tr>
				  <tr>
					<td valign="top">Detalles</td>
					<td><textarea name="detalles" rows="2" cols="50" class="areaTexto" id="detalles"> </textarea>
					</td>
					<td width="5%">Precio</td>
					<td width="11%"><input NAME="precio" type="text" class="cajaPequena2" id="precio" size="10" maxlength="10" onChange="actualizar_importe()"></td>
					<td width="5%">Cantidad</td>
					<td width="5%"><input NAME="cantidad" type="text" class="cajaMinima" id="cantidad" size="10" maxlength="10" value="1" onChange="actualizar_importe()"></td>
					<td width="4%">Dcto.</td>
					<td width="9%"><input NAME="descuento" type="text" class="cajaMinima" id="descuento" size="10" maxlength="10" onChange="actualizar_importe()"> %</td>
					<td width="5%">Importe</td>
					<td width="11%"><input NAME="importe" type="text" class="cajaPequena2" id="importe" size="10" maxlength="10" value="0" readonly></td>
					<td width="15%"><img id="botonBusqueda" src="../img/botonagregar.jpg" width="72" height="22" border="1" onClick="validar()" onMouseOver="style.cursor=cursor" title="Agregar articulo"></td>
				  </tr>
				</table>
				</div>
				<input type="hidden" name="codarticulo" id="codarticulo" value="<?php echo $codarticulo?>">
				<br>
				<div id="frmBusqueda">
				<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
						<tr class="cabeceraTabla">
							<td width="5%">ITEM</td>
							<td width="20%">REFERENCIA</td>
							<td width="39%">DESCRIPCION</td>
							<td width="8%">CANTIDAD</td>
							<td width="8%">PRECIO</td>
							<td width="7%">DCTO %</td>
							<td width="8%">IMPORTE</td>
							<td width="3%">&nbsp;</td>
						</tr>
				</table>
				<div id="lineaResultado">
					<iframe width="100%" height="200" id="frame_lineas" name="frame_lineas" frameborder="0">
						<ilayer width="100%" height="200" id="frame_lineas" name="frame_lineas"></ilayer>
					</iframe>
				</div>					
			  </div>
			  <div id="frmBusqueda">
			  

<div id="frmBusqueda">
			<table width="25%" border=0 align="right" cellpadding=3 cellspacing=0 class="fuente8">
			  <tr>
			    <td width="27%" class="busqueda">Sub-total</td>
				<td width="73%" align="right"><div align="center">
				 <input type="text" class="cajaPequena2" id="monShow" readonly>
			      <input class="cajaTotales" name="baseimponible" type="text" id="baseimponible" size="12" value=0 align="right" value="<?php echo number_format($baseimponible,2)?>" readonly> 
		        </div></td>
			  </tr>
			  <tr>
				<td class="busqueda">IVA</td>
				<td align="right"><div align="center">
				 <input type="text" class="cajaPequena2" id="monSho" readonly>
			      <input class="cajaTotales" name="baseimpuestos" type="text" id="baseimpuestos" size="12" align="right"  value="<?php echo number_format($baseimpuestos,2)?>" readonly>
		        </div></td>
			  </tr>
			  <tr>
				<td class="busqueda">Precio Total</td>
				<td align="right"><div align="center">
				 <input type="text" class="cajaPequena2" id="monSh" readonly>
			      <input class="cajaTotales" name="preciototal" type="text" id="preciototal" size="12" align="right" value="<?php echo number_format($preciototal,2)?>" readonly> 
		        </div></td>
			  </tr>
		</table>
			  </div>			  
			
			  </div>
				<div>					
				  <div align="center">
				    <img id="botonBusqueda" src="../img/botonaceptar.jpg" width="85" height="22" onClick="validar_cabecera()" border="1" onMouseOver="style.cursor=cursor">
					<img id="botonBusqueda" src="../img/botoncancelar.jpg" width="85" height="22" onClick="cancelar()" border="1" onMouseOver="style.cursor=cursor">
				    <input id="codfamilia" name="codfamilia" value="<?php echo $codfamilia?>" type="hidden">
				    <input id="codfacturatmp" name="codfacturatmp" value="<?php echo $codfacturatmp?>" type="hidden">
					<input id="modif" name="modif" value="0" type="hidden">				    
			      </div>
				</div>
			  		<iframe id="frame_datos" name="frame_datos" width="0" height="0" frameborder="0">
					<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
					</iframe>
			  </form>
			 </div>
		  </div>
		</div>
		<script type="text/javascript">
		cambio();
		</script>
		
		<div id="ErrorBusqueda" class="fuente8">
 <ul id="lista-errores" style="display:none; 
	clear: both; 
	max-height: 75%; 
	overflow: auto; 
	position:relative; 
	top: 85px; 
	margin-left: 30px; 
	z-index:999; 
	padding-top: 10px; 
	background: #FFFFFF; 
	width: 585px; 
	-moz-box-shadow: 0 0 5px 5px #888;
	-webkit-box-shadow: 0 0 5px 5px#888;
 	box-shadow: 0 0 5px 5px #888; 
 	bottom: 10px;"></ul>	
 
 	</div>		
	</body>
</html>
