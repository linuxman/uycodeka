<?php
header('Cache-Control: no-cache');
header('Pragma: no-cache'); 

define("UPLOAD_DIR", "../fotos/");

include ("../conectar.php"); 
include ("../funciones/fechas.php"); 

include("../barcode/barcode.php");


$accion=$_POST["accion"];
if (!isset($accion)) { $accion=$_GET["accion"]; }

$referencia=$_POST["Areferencia"];
$codfamilia=$_POST["AcboFamilias"];
$descripcion=$_POST["Adescripcion"];
$codimpuesto=$_POST["AcboImpuestos"];
$codproveedor1=$_POST["acboProveedores1"];
$codproveedor2=$_POST["acboProveedores2"];
$descripcion_corta=$_POST["Adescripcion_corta"];
$codubicacion=$_POST["AcboUbicacion"];
$stock_minimo=$_POST["nstock_minimo"];
$stock=$_POST["nstock"];
$aviso_minimo=$_POST["aaviso_minimo"];
$datos=$_POST["adatos"];
$fecha=$_POST["fecha"];
$fechalis=$fecha;
if ($fecha<>"") { $fecha=explota($fecha); } else { $fecha="0000-00-00"; }
$codembalaje=$_POST["AcboEmbalaje"];
$unidades_caja=$_POST["nunidades_caja"];
$precio_ticket=$_POST["aprecio_ticket"];
$modif_descrip=$_POST["amodif_descrip"];
$observaciones=$_POST["aobservaciones"];
$moneda=$_POST["amoneda"];
$precio_compra=$_POST["qprecio_compra"];
$precio_almacen=$_POST["qprecio_almacen"];
$precio_tienda=$_POST["qprecio_tienda"];
//$pvp=$_POST["qpvp"];
$precio_iva=$_POST["qprecio_iva"];

$codigobarras=$_POST["codigobarras"];

$fileerror="";

if ($accion=="alta") {
	$sel_comp="SELECT * FROM articulos WHERE referencia='$referencia'";
	$rs_comp=mysql_query($sel_comp);
	if (mysql_num_rows($rs_comp) > 0) {
		?><script>
				alert ("No se puede dar de alta a este articulo, ya existe uno con esta referencia.");
				parent.$('idOfDomElement').colorbox.close();

			</script><?php
	} else {
		$consultaprevia = "SELECT max(codarticulo) as maximo FROM articulos";
		$rs_consultaprevia=mysql_query($consultaprevia);
		$codarticulo=mysql_result($rs_consultaprevia,0,"maximo");
		if ($codarticulo=="") { $codarticulo=0; }
		$codarticulo++;
		
		
$foto_name="";

   if ($_FILES["foto"]["error"] == 0)
    {
    	
 $mimetypes = array("image/jpeg", "image/pjpeg", "image/gif", "image/png");
  // Variables de la foto/*
  $name = $_FILES["foto"]["name"];
  $type = $_FILES["foto"]["type"];
  $tmp_name = $_FILES["foto"]["tmp_name"];
  $size = $_FILES["foto"]["size"];
  // Verificamos si el archivo es una imagen válida/*

  if(!in_array($type, $mimetypes)) { 
    $fileerror= " - El archivo que subio no es una imagen válida";
  } else {
        if (file_exists("../fotos/" ."foto".$codarticulo . $_FILES["foto"]["name"]))
        {
            $fileerror= " - foto".$codarticulo .$_FILES["foto"]["name"] . " existe. ";
        }
        else
        {
            mkdir("../fotos/".$title, 0700);
            move_uploaded_file($_FILES["foto"]["tmp_name"], "../fotos/"."foto".$codarticulo . $_FILES["foto"]["name"]);
            $foto_name= "foto".$codarticulo .$_FILES["foto"]["name"];
            //echo "Stored in: " ."../fotos/".$title.'/foto'.$codarticulo . $_FILES["foto"]["name"];
        }
      }
    }

		if (!empty($foto_name))
		{
		   $foto_name="imagen='".$foto_name."', ";
		};
		
		$query_operacion="INSERT INTO articulos (codarticulo, codfamilia, referencia, descripcion, impuesto, codproveedor1, codproveedor2, descripcion_corta, codubicacion, stock, stock_minimo, aviso_minimo, datos_producto, fecha_alta, codembalaje, unidades_caja, precio_ticket, modificar_ticket, observaciones, precio_compra, precio_almacen, precio_tienda, precio_iva, moneda, codigobarras, imagen, borrado) 
						VALUES ('', '$codfamilia', '$referencia', '$descripcion', '$codimpuesto', '$codproveedor1', '$codproveedor2', '$descripcion_corta', '$codubicacion', '$stock', '$stock_minimo', '$aviso_minimo', '$datos', '$fecha', '$codembalaje', '$unidades_caja', '$precio_ticket', '$modificar_ticket', '$observaciones', '$precio_compra', '$precio_almacen', '$precio_tienda', '$precio_iva', '$moneda', '$codigobarras', '$foto_name', '0')";				
		$rs_operacion=mysql_query($query_operacion);
		
		if (empty(trim($codigobarras))) {
			$codarticulo=mysql_insert_id();
			$codaux=$codarticulo;
			while (strlen($codaux)<6) {
				$codaux="0".$codaux;
			}
			/*/ el 0000 representa el código de la empresa*/
			$codigobarras="3773000".$codaux;
			$pares=$codigobarras[0] + $codigobarras[2] + $codigobarras[4] + $codigobarras[6] + $codigobarras[8] + $codigobarras[10];
			$impares=$codigobarras[1] + $codigobarras[3] + $codigobarras[5] + $codigobarras[7] + $codigobarras[9] + $codigobarras[11];
			$impares=$impares * 3;
			$total=$impares + $pares;
			$resto = $total % 10;
				if($resto == 0){
					$valor = 0;
				}else{
					$valor = 10 - $resto;
				}
			$codigobarras=$codigobarras."".$valor;
			$sel_actualizar="UPDATE articulos SET codigobarras='$codigobarras' WHERE codarticulo='$codarticulo'";
			$rs_actualizar=mysql_query($sel_actualizar);
		}
		if ($rs_operacion) { $mensaje="El articulo ha sido dado de alta correctamente"; }
		$cabecera1="Inicio >> Articulos &gt;&gt; Nuevo Articulo ";
		$cabecera2="INSERTAR ARTICULO ";
		}
}

if ($accion=="modificar") {
	$codarticulo=$_POST["id"];

$foto_name="";

   if ($_FILES["foto"]["error"] == 0)
    {
    	
 $mimetypes = array("image/jpeg", "image/pjpeg", "image/gif", "image/png");
  // Variables de la foto/*
  $name = $_FILES["foto"]["name"];
  $type = $_FILES["foto"]["type"];
  $tmp_name = $_FILES["foto"]["tmp_name"];
  $size = $_FILES["foto"]["size"];
  // Verificamos si el archivo es una imagen válida/*

  if(!in_array($type, $mimetypes)) { 
   $fileerror= " - El archivo que subio no es una imagen válida";
  } else {
        if (file_exists("../fotos/" ."foto".$codarticulo . $_FILES["foto"]["name"]))
        {
            $fileerror= " - foto".$codarticulo .$_FILES["foto"]["name"] . " ya existe. ";
        }
        else
        {
            mkdir("../fotos/".$title, 0700);
            move_uploaded_file($_FILES["foto"]["tmp_name"], "../fotos/"."foto".$codarticulo . $_FILES["foto"]["name"]);
            $foto_name= "foto".$codarticulo .$_FILES["foto"]["name"];
            //echo "Stored in: " ."../fotos/".$title.'/foto'.$codarticulo . $_FILES["foto"]["name"];
        }
      }
    } 
		if (!empty($foto_name))
		{
		   $foto_name="imagen='".$foto_name."', ";
		};

	$query="UPDATE  `codeka`.`articulos` SET codfamilia='$codfamilia', referencia='$referencia', descripcion='$descripcion', impuesto='$codimpuesto', codproveedor1='$codproveedor1', 
	codproveedor2='$codproveedor2', descripcion_corta='$descripcion_corta', codubicacion='$codubicacion', stock='$stock', stock_minimo='$stock_minimo', 
	aviso_minimo='$aviso_minimo', datos_producto='$datos', fecha_alta='$fecha', codembalaje='$codembalaje', unidades_caja='$unidades_caja', precio_ticket='$precio_ticket', 
	modificar_ticket='$modif_descrip', observaciones='$observaciones', precio_compra='$precio_compra', precio_almacen='$precio_almacen', precio_tienda='$precio_tienda', 
	precio_iva='$precio_iva', moneda='$moneda', codigobarras='$codigobarras', ".$foto_name." borrado=0 WHERE  `articulos`.`codarticulo` ='$codarticulo'";
	$rs_query=mysql_query($query);
	if ($rs_query) { $mensaje="Los datos del articulo han sido modificados correctamente"; }
	$cabecera1="Inicio >> Articulos &gt;&gt; Modificar Articulo ";
	$cabecera2="MODIFICAR ARTICULO ".$fileerror;
	$sel_img="SELECT imagen,codigobarras FROM articulos WHERE codarticulo='$codarticulo'";
	$rs_img=mysql_query($sel_img);
	$foto_name=mysql_result($rs_img,0,"imagen");
	$codigobarras=mysql_result($rs_img,0,"codigobarras");
}

if ($accion=="baja") {
	$codarticulo=$_GET["codarticulo"];
	$query="UPDATE articulos SET borrado=1 WHERE codarticulo='$codarticulo'";
	$rs_query=mysql_query($query);
	if ($rs_query) { $mensaje="El articulo ha sido eliminado correctamente"; }
	$cabecera1="Inicio >> Articulos &gt;&gt; Eliminar Articulo ";
	$cabecera2="ELIMINAR ARTICULO ";
	$query_mostrar="SELECT * FROM articulos WHERE codarticulo='$codarticulo'";
	$rs_mostrar=mysql_query($query_mostrar);
	$codarticulo=mysql_result($rs_mostrar,0,"codarticulo");
	$referencia=mysql_result($rs_mostrar,0,"referencia");
	$codfamilia=mysql_result($rs_mostrar,0,"codfamilia");
	//$descripcion=mysql_result($rs_mostrar,0,"descripcion");
	$codimpuesto=mysql_result($rs_mostrar,0,"impuesto");
	$codproveedor1=mysql_result($rs_mostrar,0,"codproveedor1");
	$codproveedor2=mysql_result($rs_mostrar,0,"codproveedor2");
	$descripcion_corta=mysql_result($rs_mostrar,0,"descripcion_corta");
	$codubicacion=mysql_result($rs_mostrar,0,"codubicacion");
	$stock_minimo=mysql_result($rs_mostrar,0,"stock_minimo");
	$stock=mysql_result($rs_mostrar,0,"stock");
	$aviso_minimo=mysql_result($rs_mostrar,0,"aviso_minimo");
	$datos=mysql_result($rs_mostrar,0,"datos_producto");
	$fecha=mysql_result($rs_mostrar,0,"fecha_alta");
	if ($fecha<>"0000-00-00") { $fechalis=implota($fecha); }
	$codembalaje=mysql_result($rs_mostrar,0,"codembalaje");
	$unidades_caja=mysql_result($rs_mostrar,0,"unidades_caja");
	$precio_ticket=mysql_result($rs_mostrar,0,"precio_ticket");
	$modif_descrip=mysql_result($rs_mostrar,0,"modificar_ticket");
	$observaciones=mysql_result($rs_mostrar,0,"observaciones");
	$moneda=mysql_result($rs_mostrar,0,"moneda");
	$precio_compra=mysql_result($rs_mostrar,0,"precio_compra");
	$precio_almacen=mysql_result($rs_mostrar,0,"precio_almacen");
	$precio_tienda=mysql_result($rs_mostrar,0,"precio_tienda");
	/*/$pvp=mysql_result($rs_mostrar,0,"precio_pvp");*/
	$precio_iva=mysql_result($rs_mostrar,0,"precio_iva");
	$foto_name=mysql_result($rs_mostrar,0,"imagen");
	$codigobarras=trim(mysql_result($rs_mostrar,0,"codigobarras"));
}

?>

<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script language="javascript">
		var cursor;
		if (document.all) {
		/*/ Está utilizando EXPLORER*/
		cursor='hand';
		} else {
		/*/ Está utilizando MOZILLA/NETSCAPE*/
		cursor='pointer';
		}
		
		function aceptar() {
			parent.$('idOfDomElement').colorbox.close();
		}
		
		</script>
	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="frmBusqueda">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0><tr>
					<td colspan="2" class="header">
							<?php echo $cabecera2?>
					</td></tr>
						<tr>
							<td colspan="2" class="mensaje"><?php echo $mensaje;?></td>
					    </tr>
		
					<tr>					
					<td valign="top" width="50%">
					<table class="fuente8" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="15%">C&oacute;digo</td>
							<td width="58%"><?php echo $codarticulo?></td>

						</tr>
						<tr>
							<td width="15%">Referencia</td>
							<td width="58%"><?php echo $referencia?></td>
				        </tr>
						<?php
					  	$query_familia="SELECT * FROM familias WHERE codfamilia='$codfamilia'";
						$res_familia=mysql_query($query_familia);
						$nombrefamilia=mysql_result($res_familia,0,"nombre");
					  ?>
						<tr>
							<td width="15%">Familia</td>
							<td width="58%"><?php echo $nombrefamilia?></td>
				        </tr>
						<tr>
							<td width="15%">Descripci&oacute;n</td>
						    <td width="58%"><?php echo $descripcion?></td>
				        </tr>
						<tr>
						  <td>Impuesto</td>
						  <td><?php echo $codimpuesto?> %</td>
				      </tr>
					  <?php
					  	if ($codproveedor1<>0) {
							$query_proveedor="SELECT * FROM proveedores WHERE codproveedor='$codproveedor1'";
							$res_proveedor=mysql_query($query_proveedor);
							$nombreproveedor=mysql_result($res_proveedor,0,"nombre");
						} else {
							$nombreproveedor="Sin determinar";
						}
					  ?>
						<tr>
							<td width="15%">Proveedor1</td>
							<td width="58%"><?php echo $nombreproveedor?></td>
				        </tr>
					<?php
					  	if ($codproveedor2<>0) {
							$query_proveedor="SELECT * FROM proveedores WHERE codproveedor='$codproveedor2'";
							$res_proveedor=mysql_query($query_proveedor);
							$nombreproveedor=mysql_result($res_proveedor,0,"nombre");
						} else {
							$nombreproveedor="Sin determinar";
						}
					  ?>
						<tr>
							<td width="15%">Proveedor2</td>
							<td width="58%"><?php echo $nombreproveedor?></td>
				        </tr>
						<tr>
							<td width="15%">Descripci&oacute;n&nbsp;corta</td>
						    <td width="58%"><?php echo $descripcion_corta?></td>
				        </tr>
						<?php
					  	if ($codubicacion<>0) {
							$query_ubicacion="SELECT * FROM ubicaciones WHERE codubicacion='$codubicacion'";
							$res_ubicacion=mysql_query($query_ubicacion);
							$nombreubicacion=mysql_result($res_ubicacion,0,"nombre");
						} else {
							$nombreubicacion="Sin determinar";
						}
					  ?>
						<tr>
							<td width="15%">Ubicaci&oacute;n</td>
							<td width="58%"><?php echo $nombreubicacion?></td>
				        </tr>
						<tr>
							<td>Stock</td>
							<td><?php echo $stock?> unidades</td>
					    </tr>
						<tr>
							<td>Stock&nbsp;minimo</td>
							<td><?php echo $stock_minimo?> unidades</td>
					    </tr>
						<tr>
							<td>Aviso&nbsp;M&iacute;nimo</td>
							<td colspan="2"><?php if ($aviso_minimo==0) { echo "No"; } else { echo "Si"; }?></td>
						</tr>
						<tr>
							<td width="15%">Fecha&nbsp;de&nbsp;alta</td>
							<td colspan="2"><?php echo $fechalis?></td>
					    </tr>
						<tr>
							<td>Codigo&nbsp;de&nbsp;barras</td>
							<td colspan="2"><?php echo "<img src='../barcode/barcode.php?encode=EAN-13&bdata=".$codigobarras."&height=45&scale=2&bgcolor=%FFFFFFFF&color=%23333366&type=jpg'>"; ?></td>
						</tr>

						</table></td><td width="500%" valign="top">
						<table class="fuente8" cellspacing=0 cellpadding=3 border=0>
						
						<tr>
							<td width="15%">Datos&nbsp;del&nbsp;producto</td>
							<td colspan="2"><?php echo $datos?></td>
					    </tr>
						<?php
					  	if ($codembalaje<>0) {
							$query_embalaje="SELECT * FROM embalajes WHERE codembalaje='$codembalaje'";
							$res_embalaje=mysql_query($query_embalaje);
							$nombreembalaje=mysql_result($res_embalaje,0,"nombre");
						} else {
							$nombreembalaje="Sin determinar";
						}
					  ?>
						<tr>
							<td width="15%">Embalaje</td>
							<td colspan="2"><?php echo $nombreembalaje?></td>
					    </tr>
						<tr>
							<td>Unidades&nbsp;por&nbsp;caja</td>
							<td colspan="2"><?php echo $unidades_caja?> unidades</td>
						</tr>
						<tr>
							<td>Preguntar&nbsp;precio&nbsp;ticket</td>
							<td colspan="2"><?php if ($precio_ticket==0) { echo "No"; } else { echo "Si"; }?></td>
						</tr>
						<tr>
							<td>Modificar&nbsp;descrip.&nbsp;ticket</td>
							<td colspan="2"><?php if ($modif_descrip==0) { echo "No"; } else { echo "Si"; }?></td>
						</tr>
						<tr>
							<td>Observaciones</td>
							<td colspan="2"><?php echo $observaciones?></td>
						</tr>
						<tr>
						<td>Moneda</td><td width="26%"> <select onchange="cambio();" name="amoneda" id="amoneda" class="cajaPequena2">
						<?php $tipof = array(  1=>"Pesos", 2=>"U\$S");
						if ($moneda==" ")
						{
						echo '<OPTION value="" selected>Selecione uno</option>';
						}
						foreach ($tipof as $key => $i ) {
						  	if ( $moneda==$key ) {
								echo "<OPTION value=$key selected>$i</option>";
							} else {
								echo "<OPTION value=$key>$i</option>";
							}
						}
						?>
						</select></td>
						</tr>
						<tr>
							<td>Precio&nbsp;de&nbsp;compra</td>
							<td colspan="2"><?php echo $precio_compra?></td>
						</tr>
						<tr>
							<td>Precio&nbsp;almac&eacute;n</td>
							<td colspan="2"><?php echo $precio_almacen?></td>
						</tr>												
						<tr>
							<td>Precio&nbsp;en&nbsp;tienda</td>
							<td colspan="2"><?php echo $precio_tienda?></td>
						</tr>
						<!--<tr>
							<td>Pvp</td>
							<td colspan="2"><?php echo $pvp?> &#8364;</td>
						</tr>-->
						<tr>
							<td>Precio con iva</td>
							<td colspan="2"><?php echo $precio_iva?></td>
						</tr>
						<tr><td width="27%" rowspan="11" align="center" valign="top" colspan="2">
					        <img src="../fotos/<?php echo $foto_name;?>" width="160px" height="140px" border="1"></td></tr>
					</table>
					</td></table>
			  </div>
				<div id="botonBusqueda">
					<img src="../img/botonaceptar.jpg" width="85" height="22" onClick="aceptar()" border="1" onMouseOver="style.cursor=cursor">
			  </div>
			 </div>
		  </div>
		</div>
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
