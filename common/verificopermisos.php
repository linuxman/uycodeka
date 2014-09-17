<?php
function verificopermisos($seccion, $tipo, $USERID) {
include("conexion.php");
	$sql="select * from `permisos` where `seccion` = '$seccion' and `oidcontacto` = '$USERID'";
	$con=mysql_query($sql, $conectar) or die('Error consulta');
	while ($res=mysql_fetch_array($con)) {
		if($res[$tipo]==1) {
			return 'true';
		} else {
			return 'false';
		}
	}

}

?>