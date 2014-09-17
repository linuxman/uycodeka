<?php
$hostname = "localhost";
$database = "codeka";
$username = "root";
$password = "password";
/*/Abrir la conexion:*/
/*/echo $database." ".$username." ".$password."<br>";*/
$conectar=@mysql_connect($hostname,$username,$password);
mysql_query("SET NAMES utf8"); //Soluciona el tema de las ñ y los tildes

if(!$conectar){
 echo "Error al intentar conectarse con el servidor, Provablemte no exista la base";
 exit();
}
/*/Elegir una BD:*/
if(!@mysql_select_db($database,$conectar)){
 echo "No se pudo conectar correctamente con la Base de datos";
 exit();
}
#$conectarCli = mysql_pconnect($hostnameCli, $usernameCli, $passwordCli) or trigger_error(mysql_error(),E_USER_ERROR);
#mysql_select_db($databaseCli, $conectarCli);
?>
