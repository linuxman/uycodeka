<?php include ("../conectar.php"); ?>
<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script type="text/javascript" src="../funciones/validar.js"></script>
		<script language="javascript">
		
		function cancelar() {
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
		
		function limpiar() {
			document.getElementById("formulario").reset();
		}
		
		</script>
	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">INSERTAR CLIENTE </div>
				<div id="frmBusqueda">
				<form id="formulario" name="formulario" method="post" action="guardar_cliente.php">
				<table class="fuente8"><tr><td valign="top">
					<table class="fuente8" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td>Nombre</td>
						    <td colspan="3"><input NAME="Anombre" autocomplete="off" type="text" class="cajaGrande" id="nombre" size="45" maxlength="45"></td>
						</tr>
						<tr>
							<td>Apellido</td>
						    <td colspan="3"><input NAME="aapellido" autocomplete="off" type="text" class="cajaGrande" id="nombre" size="45" maxlength="45"></td>
						</tr>
						<tr>
							<td>Empresa</td>
						    <td colspan="3"><input NAME="aempresa" autocomplete="off" type="text" class="cajaGrande" id="empresa" size="45" maxlength="45"></td>
						</tr>
						<tr>
						  <td>RUT</td>
						  <td ><input id="nif" type="text" autocomplete="off" class="cajaPequena" NAME="anif" maxlength="15"></td>
							<td>Tipo</td>
							<td><select type=text size=1 name="Ttipo" id="tipo" class="comboMedio">
							<?php
								$tipo = array("Seleccione uno", "Cliente", "Empresa",);
							$xx=0;
							foreach($tipo as $tpo) {
							      echo "<option value='$xx'>$tpo</option>";
							$xx++;
							}
							?>
							</select></td>
						  
				      </tr>
						<tr>
							<td>Tel&eacute;fono</td>
							<td><input id="telefono" name="atelefono" autocomplete="off" type="text" class="cajaPequena" maxlength="14"></td>

							<td>M&oacute;vil</td>
							<td width="50%"><input id="movil" name="amovil" type="text" class="cajaPequena" maxlength="14"></td>
					    </tr>
						<tr>
							<td>Correo electr&oacute;nico  </td>
							<td colspan="3"><input NAME="aemail" type="text" class="cajaGrande" id="email" size="35" maxlength="35"></td>
					    </tr>
						<tr>
							<td>Contraseña  </td>
							<td colspan="3"><input NAME="contrasenia" type="password" class="cajaGrande" id="contrasenia" size="35" maxlength="35"></td>
					    </tr>
						<tr><td colspan="4"><select id="secQ" NAME="secQ" type="text" class="comboGrande">
							<?php
								$questions = array();
								$questions[0] = "Seleccione una Pregunta";
								$questions[1] = "¿En que ciudad nació?";
								$questions[2] = "¿Cúal es su color favorito?";
								$questions[3] = "¿En qué año se graduo de la facultad?";
								$questions[4] = "¿Cual es el segundo nombre de su novio/novia/marido/esposa?";
								$questions[5] = "¿Cúal es su auto favorito?";
								$questions[6] = "¿Cúal es el nombre de su madre?";
							$xx=0;
							foreach($questions as $pregunta) {
							   if ($xx==$_SESSION['secQ']) {
							      echo "<option value='$xx' selected>$pregunta</option>";
							   } else {
							      echo "<option value='$xx'>$pregunta</option>";
							   }
							$xx++;
							}
							?>
						</select></td>
						</tr><tr><td colspan="4">
						<input type="text" size="26" NAME="secA" id="secA" class="cajaGrande" ></input>
						
						</td></tr>
					</table></td>
					
					        <td rowspan="14" align="left" valign="top">
				        
					        
					        
					        </td>
					
					<td>						
						<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>	
						<tr>
							<td>Direcci&oacute;n&nbsp;web </td>
							<td><input NAME="aweb" type="text" class="cajaGrande" id="web" size="45" maxlength="45"></td>
					    </tr>						<tr>
						  <td>Direcci&oacute;n</td>
						  <td><input NAME="adireccion" type="text" class="cajaGrande" id="direccion" size="45" maxlength="45"></td>
				      </tr>
						<tr>
							<td>C&oacute;digo&nbsp;postal </td>
							<td><input id="codpostal" type="text" class="cajaPequena" NAME="acodpostal" maxlength="5"></td>
					    </tr>
						<tr>
						  <td>Localidad</td>
						  <td><input NAME="alocalidad" type="text" class="cajaGrande" id="localidad" size="35" maxlength="35"></td>
				      </tr>
					  <?php
					  	$query_provincias="SELECT * FROM provincias ORDER BY nombreprovincia ASC";
						$res_provincias=mysql_query($query_provincias);
						$contador=0;
					  ?>
						<tr>
							<td width="15%">Departamento</td>
							<td width="43%"><select id="cboProvincias" name="cboProvincias" class="comboGrande">
							<option value="0">Seleccione una provincia</option>
								<?php
								while ($contador < mysql_num_rows($res_provincias)) { ?>
								<option value="<?php echo mysql_result($res_provincias,$contador,"codprovincia")?>"><?php echo mysql_result($res_provincias,$contador,"nombreprovincia")?></option>
								<?php $contador++;
								} ?>				
								</select>							</td>
				        </tr>
						<?php
					  	$query_formapago="SELECT * FROM formapago WHERE borrado=0 ORDER BY nombrefp ASC";
						$res_formapago=mysql_query($query_formapago);
						$contador=0;
					  ?>
						<tr>
							<td width="15%">Forma&nbsp;de&nbsp;pago</td>
							<td width="43%"><select id="cboFPago" name="cboFPago" class="comboGrande">
							<option value="0">Seleccione una forma de pago</option>
								<?php
								while ($contador < mysql_num_rows($res_formapago)) { ?>
								<option value="<?php echo mysql_result($res_formapago,$contador,"codformapago")?>"><?php echo mysql_result($res_formapago,$contador,"nombrefp")?></option>
								<?php $contador++;
								} ?>	
											</select>							</td>
				        </tr>
						<?php
					  	$query_entidades="SELECT * FROM entidades WHERE borrado=0 ORDER BY nombreentidad ASC";
						$res_entidades=mysql_query($query_entidades);
						$contador=0;
					  ?>
						<tr>
							<td width="15%">Entidad&nbsp;Bancaria</td>
							<td width="43%"><select id="cboBanco" name="cboBanco" class="comboGrande">
							<option value="0">Seleccione una entidad bancaria</option>
									<?php
								while ($contador < mysql_num_rows($res_entidades)) { ?>
								<option value="<?php echo mysql_result($res_entidades,$contador,"codentidad")?>"><?php echo mysql_result($res_entidades,$contador,"nombreentidad")?></option>
								<?php $contador++;
								} ?>
											</select>							</td>
				        </tr>
						<tr>
							<td>Cuenta&nbsp;bancaria</td>
							<td><input id="cuentabanco" type="text" class="cajaGrande" NAME="acuentabanco" maxlength="20"></td>
					    </tr>
						<tr>
							<td>Abonado/Service</td>
							<td colspan="3"><SELECT type=text size=1 name="service" id="service" class="comboMedio">
							<?php
								$tipo = array("Seleccione un tipo", "Común","Abonado A", "Abonado B",);
							$xx=0;
							foreach($tipo as $tpo) {
							      echo "<option value='$xx'>$tpo</option>";
							$xx++;
							}
							?>
							</select></td>
						  
				      </tr>	
				      <tr><td>Horas&nbsp;Asig./Mes:</td>
				      <td><input id="horas" type="text" class="cajaPequena" NAME="nhoras" maxlength="5"></td>
				      </tr>					
					    
					</table>
				</td></tr></table>
			  </div>
				<div>
					<img id="botonBusqueda" src="../img/botonaceptar.jpg" width="85" height="22" onClick="validar(formulario,true)" border="1" onMouseOver="style.cursor=cursor" class="boton">
					<img id="botonBusqueda" src="../img/botonlimpiar.jpg" width="69" height="22" onClick="limpiar()" border="1" onMouseOver="style.cursor=cursor" class="boton">
					<img id="botonBusqueda" src="../img/botoncancelar.jpg" width="85" height="22" onClick="cancelar()" border="1" onMouseOver="style.cursor=cursor" class="boton">
					<input id="accion" name="accion" value="alta" type="hidden">
					<input id="id" name="Zid" value="" type="hidden">
			  </div>
			  </form>
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
