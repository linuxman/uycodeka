<?php
include("assets/php/database.php"); 
include("assets/php/functions.php");
include("../class/class.phpmailer.php");
include('../class/Encryption.php');

$show = 'emailForm'; //which form step to show by default
if ($_SESSION['lockout'] == true && (mktime() > $_SESSION['lastTime'] + 900))
{
	$_SESSION['lockout'] = false;
	$_SESSION['badCount'] = 0;
}
if (isset($_POST['subStep']) && !isset($_GET['a']) && $_SESSION['lockout'] != true)
{
	switch($_POST['subStep'])
	{
		case 1:
			//we just submitted an email or username for verification
			$result = checkUNEmail($_POST['uname'],$_POST['email']);
			if ($result['status'] == false )
			{
				$error = true;
				$show = 'userNotFound';
			} else {
				$error = false;
				$show = 'securityForm';
				$securityUser = $result['userID'];
			}
		break;
		case 2:
			//we just submitted the security question for verification
			if ($_POST['userID'] != "" && $_POST['answer'] != "")
			{
				$result = checkSecAnswer($_POST['userID'],$_POST['answer']);
				if ($result == true)
				{
					//answer was right
					$error = false;
					$show = 'successPage';
					$passwordMessage = sendPasswordEmail($_POST['userID']);
					$_SESSION['badCount'] = 0;
				} else {
					//answer was wrong
					$error = true;
					$show = 'securityForm';
					$securityUser = $_POST['userID'];
					$_SESSION['badCount']++;
				}
			} else {
				$error = true;
				$show = 'securityForm';
			}
		break;
		case 3:
			//we are submitting a new password (only for encrypted)
			if ($_POST['userID'] == '' || $_POST['key'] == '') header("location: ../index.php");
			if (strcmp($_POST['pw0'],$_POST['pw1']) != 0 || trim($_POST['pw0']) == '')
			{
				$error = true;
				$show = 'recoverForm';
			} else {
				$error = false;
				$show = 'recoverSuccess';
				updateUserPassword($_POST['userID'],$_POST['pw0'],$_POST['key']);
			}
		break;
	}
} elseif (isset($_GET['a']) && $_GET['a'] == 'recover' && $_GET['email'] != "") {
	$show = 'invalidKey';
	$result = checkEmailKey($_GET['email'],urldecode(base64_decode($_GET['u'])));
	if ($result == false)
	{
		$error = true;
		$show = 'invalidKey';
	} elseif ($result['status'] == true) {
		$error = false;
		$show = 'recoverForm';
		$securityUser = $result['userID'];
	}
}
if ($_SESSION['badCount'] >= 3)
{
	$show = 'speedLimit';
	$_SESSION['lockout'] = true;
	$_SESSION['lastTime'] = '' ? mktime() : $_SESSION['lastTime'];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Recuperar contraseña</title>
<link media="all" type="text/css" href="../css/login1.css" rel="stylesheet">
</head>
<body leftmargin="0" topmargin="0" class="login" id="login">
   <header><br>
      <div class="container2"><center><font color="White" size="+2"> <p id="msg"></p></font></center></div>
   </header>

<div id="holder">
	<div id="container">
		<div id="container2_hasLogo">
			<div id="logo"></div>

			<div id="page">


<?php switch($show) {
	case 'emailForm': ?>
	<h2>Recuperar contraseña</h2>
    <p>Ud. puede utilizar este formulario para recuperar su contraseña, para ello le estaremos enviando por mail un link para que genero una nueva. Ingrese su usuario o dirección de correo eléctronico en los campos siguientes.</p>
    <?php if ($error == true) { ?><span class="error">Debe ingresar un usuario o una dirección de correo para continuar.</span><?php } ?>
    <form action="<?= $_SERVER['PHP_SELF']; ?>" method="post">
		<ul id="UPDiv">
			<li>
        <label for="userLogin">Usuario</label>
					<div class="rhs"><input type="text" name="uname" id="uname" value="" maxlength="20"></div>
			</li>
					<div class="rhs"><input type="hidden" name="subStep" value="1" /></div>
			<li>
        <label for="userLogin">Email</label>
				<div class="rhs"><input type="text" name="email" id="email" value="" maxlength="255"></div>
			</li>
			<li>        
        <div class="rhs"><input type="submit" value="Recuperar" /></div>
			</li>
		</ul>
    </form>
    <?php break; case 'securityForm': ?>
    <h2>Recuperar contraseña</h2>
    <p>Tengase a bien responder la siguiente pregunta:</p>
    <?php if ($error == true) { ?><span class="error">Debe responder en forma correcta para recibir la nueva contraseña.</span><?php } ?>
    <form action="<?= $_SERVER['PHP_SELF']; ?>" method="post">
		<ul id="UPDiv">
			<li>
        <label for="userLogin">Pregunta</label>
				<label for="userLogin"><div class="rhs"><?= getSecurityQuestion($securityUser); ?></div></label>
			</li>
			<li><label for="userLogin">Respuesta</label>
			<div class="rhs"><input type="text" name="answer" id="answer" value="" maxlength="255"></div>
			</li>
			<li>
        <input type="hidden" name="subStep" value="2" />
        <input type="hidden" name="userID" value="<?= $securityUser; ?>" />
        <div class="rhs"><input type="submit" value="Submit" style="margin-left: 150px;" /></div>
        <div class="clear"></div>
			</li>
		</ul>
    </form>

	 <?php break; case 'userNotFound': ?>
    <h2>Recuperar contraseña</h2>
    <p>Lo sentimos pero el usuario o la dirección de correo que escribió no son correctos.<br /><br /><a href="?">click aquí</a> para intentar nuevamente.</p>
    <?php break; case 'successPage': ?>
    <h2>Recuperar contrseña</h2>
    <p>Un correo electrónico le ha sido enviado con las instrucciones para crear una nueva contraseña<br /><br /><a href="../index.php">Regresar</a> a la página de login. </p>

    <br>
    <div class="message"><?= $passwordMessage;?></div>

    <?php break; case 'recoverForm': ?>
    <h2>Recuperar contraseña</h2>
    <p>Bienvenido, <?= getUserName($securityUser=='' ? $_POST['userID'] : $securityUser); ?>.</p>
    <p>En el campo debajo, inserte una nueva contraseña.</p>
    <?php if ($error == true) { ?><span class="error">La nueva contraseña debe coincidir y no ser nula.</span><?php } ?>
    <form action="<?= $_SERVER['PHP_SELF']; ?>" method="post">
		<ul id="UPDiv">
			<li>
        <div class="rhs"><label for="password">Nueva&nbsp;contraseña</label><div class="field"><input type="password" class="input" name="pw0" id="pw0" value="" maxlength="20"></div></div>
			</li><li>
        <div class="rhs"><label for="password">Confirmar&nbsp;contraseña</label><div class="field"><input type="password" class="input" name="pw1" id="pw1" value="" maxlength="20"></div></div>
        <input type="hidden" name="subStep" value="3" />
        <input type="hidden" name="userID" value="<?= $securityUser=='' ? $_POST['userID'] : $securityUser; ?>" />
        <input type="hidden" name="key" value="<?= $_GET['email']=='' ? $_POST['key'] : $_GET['email']; ?>" />
				</li><li>
        <div class="rhs"><input type="submit" value="Submit" style="margin-left: 150px;" /></div>
        <div class="clear"></div>
				</li>
		</ul>
    </form>

    <?php break; case 'invalidKey': ?>
    <h2>Link invalido</h2>
    <p>El link que Ud. ingreso no es correcto, o bien copió mal la dirección que le enviamos, o el tiempo de vigencia de ese link caduco o actualmente ya realizo el cambio de contraseña<br /><br /><a href="../index.php">Volver</a> al login. </p>
    <?php break; case 'recoverSuccess': ?>
    <h2>Contraseña cambiada</h2>
    <p>Le felicitamos, Ud. ha cambiado su contraseña de forma exitosa.</p><br /><br /><a href="../index.php">Volver</a> al login. </p>
    <?php break; case 'speedLimit': ?>
    <h2>ATENCIÓN</h2>
    <p>La respuesta a que dio es incorrecta y supero la cantidad de intentos habilitados, Ud. será bloqueado por los próximos 15 minutos, luego de ello podrá intentar nuevamente.</p><br /><br /><a href="../index.php">Volver</a> al login. </p>
    <?php break; }
	ob_flush();
	$mySQL->close();
?>
</div>
	</div></div></div>


   <div id="copyright">
   <div class="container2">
      <a href="mailto:fgambaro@adinet.com.uy?subject=Consulta%20desde%20sistema%20web%20cediva">Fernando Gámbaro</a>
      <div class="copyright">
	 <p>
	 COPYRIGHT &copy; 2012 <a href="http://www.mcc.com.uy">MCC - Soporte Técnico</a>
	 </p>
	 <p class="trademarks">MCC.</p>
      </div>
   </div>
   </div>
</body>
</html>
