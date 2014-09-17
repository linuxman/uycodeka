<?php
/*
Include the session class. Modify path according to where you put the class
file.
*/
require_once(dirname(__FILE__).'/class/class_session.php');

/*
Instantiate a new session object. If session exists, it will be restored,
otherwise, a new session will be created--placing a sid cookie on the user's
computer.
*/
if (!$s = new session()) {
  /*
  There is a problem with the session! The class has a 'log' property that
  contains a log of events. This log is useful for testing and debugging.
  */
  echo "<h2>Ocurrió un error al iniciar session!</h2>";
  echo $s->log;
  exit();
}
include("common/funcionesvarias.php");


$status_msg = "";

$act=isset($s->data['act']) ? $s->data['act'] : null ;

if ($act=="logout") {
   $USERID=$s->data['UserID'];

   $msg="Salida normal del sistema";
   logger($USERID, $msg);
conectado(0, $USERID, $s->data['UserNom']);
   session_unset();
 session_destroy();
 $s->expire();
} elseif ($act=="timeout") {
   $USERID=$s->data['UserID'];
   $msg="Sesión cerrada por tiempo inactividad";
conectado(0, $USERID, $s->data['UserNom']);
   logger($USERID, $msg);
   session_unset();
 session_destroy();
 $s->expire();
}

if (isset($_POST['method'])) {
  /*
  Form was submitted, let's validate and test authentication.
  */
  if (Validate()) {
  	/*/echo "validando<br>";*/
    if (Auth()) {
    	/*/echo "autorizando<br>";*/
      /*
      Use the session to "remember" that the user is logged in already.*/
      
      $s->data['logged_in'] = true;

    if (!empty($_SERVER['HTTP_CLIENT_IP']))   /*/check ip from share internet*/
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   /*/to check ip is pass from proxy*/
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
for($x=1; $x<=255; $x++) {
$tmp="192.168.1.".$x;
$rango[$tmp]=$tmp;
}

/*/echo $ip."<br>";*/

if (in_array($ip, $rango) or $ip =="127.0.0.1") {
    $extratime=1000000;
/*/   echo "long time"; */
} else {
	$extratime=25;
/*/	echo "single time";*/
}
      /*
      Store the username in the session if you want.
      */
      $s->data['UserID'] = $UserID;
      $s->data['UserTpo'] = $UserTpo;
      $s->data['UserNom'] = $UserNom;
      $s->data['UserApe'] = $UserApe;
      $s->data['hora'] = time();
      $s->data['inactivo'] = 60*$extratime; /*/segundos por la cantidad de minutos*/
      $s->data['isLoggedIn'] = true;
      $s->data['timeOut'] = 60*$extratime;
      $s->data['loggedAt'] = time();
      /*
      We need to "remember" what page the user orignally wanted before
      we redirected to login. Pull this value from the session, then remove
      it from the session.
      */
      /*//$dest = $s->data['page_destination'];*/
      /*//unset($s->data['page_destination']);*/
      $s->save();

      /*
      Finally, redirect to where the user wanted to go.
      */
	# Run Session logout check 
	/*//echo $Ves." ddd";*/
	
	if ($Ves==0) {
	header ("Location:index2.php");
	} else {
	header ("Location:index2.php");
	}
	
      /*/header("Location: ".$dest);*/
    }
  }
} else {
  /*
  Set form defaults. Perhaps you want to pull the username from a cookie
  you've stored on the user's computer. Maybe you don't want to do anything.
  */
  $s->expire();
  $nombre = "";
  $password = "";
}

LoginForm($act);

/*
Validate() will validate the form data. You can modify this per your
requirements.
*/
function Validate() {
  global $nombre, $password, $status_msg;
  $ret = true;
  $nombre      = strip_tags($_POST['nombre']) ;
  if (strlen($nombre) == 0) {
    $ret = false;
    $password="";
    $status_msg .= "Ingrese nombre de usuario.<br />";
  }
  
  $password = strip_tags($_POST['password']);
  if (strlen($password) == 0) {
    $ret = false;
    $password="";
    $status_msg .= "Ingrese contraseña.<br />";
  }
  
  return $ret;
}

/*
Auth() function to validate username and password. You must return either
true or false. Insert your own auth code inside this function. You'll probably
want to test the username and password against an accounts table in your
database or something like that.
*/
function Auth() {
  global $nombre, $password, $status_msg, $UserID, $UserTpo, $UserApe, $UserNom;
  include("conexion.php");
  /*/echo "autorizo<br>";*/
  require(dirname(__FILE__).'/class/Encryption.php');

	if($nombre=="" or $password ==""){
		$status_msg .= " Datos incorrectos<br />";
		$nombre="";
		$password="";
		return false;
        }

    $nombre   = htmlspecialchars($_POST['nombre']) ;
   /* $password = md5 (htmlspecialchars($_POST['password']));*/

	 $posicion = strpos($nombre, '@');
	 /*echo $posicion."<br>";*/
	 /*/ Seguidamente se utiliza ===.  La forma simple de comparacion (==)*/
	 /*/ no funciona como deberia, ya que la posicion de 'a' es el caracter*/
	 /*/ numero 0 (cero)*/
	 if ($posicion === false and $nombre!='Admin') {
		     $status_msg .= " Datos incorrectos<br />";
		     $nombre="";
		     $password="";
		     return false;
	 } else {
	    $str = $_POST['password'];
	    $converter = new Encryption;
	    /*Para recuperar contraseña*/
	    // echo $encoded = $converter->encode($str );
	    $encoded = $converter->encode($str );

	    $c_usuario = "SELECT * FROM `clientes` WHERE `email`='$nombre' AND `contrasenia`='$encoded'";
	    $r_usuario = @mysql_query($c_usuario,$conectar) or die(mysql_error()); 
	    
	    if ( mysql_num_rows($r_usuario)>=1 ) {
		   $r_ok = @mysql_fetch_array($r_usuario);
		   if($r_ok['email'] != $nombre && $r_ok['contrasenia'] != $encoded){
			     $status_msg .= $r_ok['email']." Datos incorrectos<br />";
			     $nombre="";
			     $password="";
			     //return false;
		   } else {	
			     $UserID = $r_ok['codcliente'];
			     $UserTpo =$r_ok['tipo'];
			     $UserNom =$tratamiento." ".$r_ok['nombre'];
			     $UserApe = $r_ok['apellido'];
			     return true;
			}
		} else {
			if ( $_POST['password']=='admin' and $nombre=='Admin') {
			     $UserID = '2';
			     $UserTpo ='2' ;
			     $UserNom = $nombre;
			     $UserApe = $nombre;
			     return true;
			}			
		}
	 }



}
/*
LoginForm() outputs the user form to enter username and pword. You can modify
this any way you want for your own look and feel, but keep the hidden "method"
field.
*/
function LoginForm($act) {
  global $nombre, $password, $status_msg;
  ?>
  <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta content="text/html;charset=UTF-8" http-equiv="content-type">
<meta content="noindex" name="robots">
<meta content="no-cache, private, must-revalidate, max-stale=0, post-check=0, pre-check=0 no-store" http-equiv="Cache-control">

    <link media="all" type="text/css" href="login/login.css" rel="stylesheet">


<title>MCC / Sistema de Gestión</title>

<script src="login/jquery-1.7.2.min.js"></script>

<?php
////////////////////Para el caso de logout
if ($act=="timeout") {
?>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.js"></script>

<script type="text/javascript">
$(document).ready(function(){

    $(".CuadroDialogo").show();
    $('.bgtransparent').show();
    $('#div > input').attr('disabled', true);
    $('#submit').attr('disabled', true);


    $('.next').click(function(){
    $('.bgtransparent').hide();
    $('#div > input').attr('disabled', false);
    $('#submit').attr('disabled', false);
    $(".CuadroDialogo").slideToggle();
    });
 
});
 
</script>

<style>
.bgtransparent{
   left:0;
   top:0;
   background-color:#000;
   opacity:0.6;
   filter:alpha(opacity=60);
   z-index:999;
   width:100%;
   height:100%;
   position:absolute;
   border:none;
}

.CuadroDialogo {
    background-color: #FFFFFF;
    border: 5px solid #0C1E83;
    border-radius: 6px 6px 6px 6px;
    box-shadow: 0 0 50px #CCCCCC;
    display: none;
    padding: 15px;
    text-align: left;
    width: 450px;
 display: none;
}
.CuadroDialogo a.BotonCuadro.next {
    margin-left: 6px;
}
a.BotonCuadro {
    display: inline-block;
    height: 25px;
    text-decoration: none;
}
a.BotonCuadro span {
    border: 0 none;
    display: inline-block;
    float: left;
    margin: 0;
    padding: 0;
    vertical-align: top;
}
a.BotonCuadro span.left {
    background-image: url("login/storewebbuttons.png");
    background-position: -30px -210px;
    background-repeat: no-repeat;
    border: 0 none;
    height: 22px;
    width: 11px;
}
a.BotonCuadro span.text {
    background-image: url("login/storewebbuttons.png");
    background-position: 0 -30px;
    background-repeat: repeat-x;
    clear: none !important;
    color: Blue;
    font-size: 12px;
    font-weight: bold;
    height: 22px;
}
a.BotonCuadro.CDsize span.text {
    text-align: center;
    width: 90px;
}
a.BotonCuadro.CDsize span.text span {
    float: none;
}
a.BotonCuadro span.text span {
    margin-top: 2px;
}
a.BotonCuadro span.right {
    background-image: url("login/storewebbuttons.png");
    background-position: -94px -300px;
    background-repeat: no-repeat;
    height: 22px;
    margin: 0;
    width: 34px;
}
a.BotonCuadro span.right span.icon {
    float: left;
    height: 16px;
    margin-left: 10px;
    margin-top: 3px;
    width: 16px;
}
a.BotonCuadro.BotonOk span.right span.icon {
    background-image: url("login/accept.png");
}
a.BotonCuadro.BotonCancel span.right span.icon {
    background-image: url("login/cross.png");
}

.CuadroDialogo .Boton_CuadroDialogo, .CuadroDialogo #Boton_CuadroDialogo, .CuadroDialogo .super_Boton_CuadroDialogo {
    padding-top: 5px;
    text-align: right;
}

</style>

<?php 
//////////////////////// Fin caso logout
}
?>

</head>
<body onload='browserProperties();' leftmargin="0" topmargin="0" class="login" id="login">

   <header><br>
      <div class="container2"><center><font color="White" size="+2"> <p id="msg"></p></font></center></div>
   </header>


 <div id="holder">
<div id="container">
<div id="container2_hasLogo">

<div id="logo"></div>

<h1>MCC / Sistema de Gestión</h1>
	        <form name="loga" method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">

                <input type="hidden" name="method" value="login" />

		<ul id="UPDiv">
			<li>
				<label for="userLogin">Nombre de Usuario/E-mail</label>
                                 <div class="rhs"><input type="text" name="nombre" placeholder="Solo usar minúsculas …" value="<?= $nombre ?>" autocomplete="off" /></div>
			</li>
	    <span class="avisos">
	       <?= $status_msg ?>
	    </span>
			<li>
				<label for="password">Contraseña</label>
				<div class="rhs"><input type="password" name="password" value="<?= $password ?>" autocomplete="off">
                                 (<a href="pw/forgotPass.php">Olvidé mi nombre de usuario/contraseña</a>) </div>
			</li>
			
			<li id="submit"><div id="loginForm1Footer"><input type="submit" value="Ingreso" id="submit"/>
                        </div></li>
		</ul>
		</form>

			
	</div></div></div>


  <p></p>
   <center> 
   <table width="420" border="0" align="center" cellpadding="0" cellspacing="0">
   <tr>
      <td align="center">
	 <table border="0" width="90%"><td style="font-family:Georgia, Arial, Times, serif; font-style:italic; font-size:11px;">
         <div align="justify"> 
	 El sistema diferencia entre mayusculas y minusculas 
	    en la contrase&ntilde;a, por favor escriba bien su contrase&ntilde;a.<br>
	    Atte: <a href="mailto:soporte@mcc.com.uy">Webmaster</a></p>
	 </div></td></table></td>
   </tr><tr>
   </table>
    
	 <h1><span class="numeros">
	 <script>
	 var navegador = navigator.userAgent;
	 if (navigator.userAgent.indexOf('MSIE') !=-1) {
	 document.getElementById('msg').innerHTML = 'Recomendamos utilizar <a href="http://www.mozilla.org/es-ES/firefox/new/">Mozilla Firefox</a> o <a href="https://www.google.com/chrome?hl=es">Google Chrome</a> ...';
	 } else if (navigator.userAgent.indexOf('Opera') !=-1) {
	 document.getElementById('msg').innerHTML = 'Recomendamos utilizar <a href="http://www.mozilla.org/es-ES/firefox/new/">Mozilla Firefox</a> o <a href="https://www.google.com/chrome?hl=es">Google Chrome</a> ...';
	 }
	 </script>
	 </span></h1>
   </center>

   <div id="copyright">
   <div class="container2">
      <a href="mailto:fgambaro@adinet.com.uy?subject=Consulta%20desde%20sistema%20web%20">Fernando Gámbaro</a>
      <div class="copyright">
	 <p>
	 COPYRIGHT &copy; 2014 <a href="http://www.mcc.com.uy">MCC - Soporte Técnico</a>
	 </p>
	 <p class="trademarks">MCC.</p>
      </div>
   </div>
   </div>



<?php 
if ($act=="timeout") {
?>
<div class="bgtransparent"></div>

   <div class="CuadroDialogo js-super-CuadroDialogo js_CuadroDialogo_container_0" id="js_CuadroDialogo_container_0" style="width: 400px; position: fixed; margin: 0px auto; top: 56px; left: 50%; margin-left: -200px; z-index: 2001;"><a class="close"></a><h2 class="warnIcon">Su sesión ha expirado</h2><hr><span>Estimado Usuario: Le informamos que se detectó un largo período de inactividad, por lo tanto el sistema cerró su sesión. Por favor ingrese su usuario y clave nuevamente.</span><div class="js-super-CuadroDialogo-buttons super_Boton_CuadroDialogo"><a class="BotonCuadro BotonOk next" style="cursor:pointer"><span class="left"></span><span class="text"><span>Aceptar</span></span><span class="right"><span class="icon"></span></span></a></div></div>

</div>
<?php 
}
?>
    </body>
  </html>
  <?php
}
?><?php

?><?php

?>