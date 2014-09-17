<?php
define(PW_SALT,'(+3%_');

function checkUNEmail($uname,$email)
{
	global $mySQL;
	$error = array('status'=>false,'userID'=>0);
	if (isset($email) && trim($email) != '') {
		//email was entered
		if ($SQL = $mySQL->prepare("SELECT `oid` FROM `contactos` WHERE `email` = ? LIMIT 1"))
		{
			$SQL->bind_param('s',trim($email));
			$SQL->execute();
			$SQL->store_result();
			$numRows = $SQL->num_rows();
			$SQL->bind_result($userID);
			$SQL->fetch();
			$SQL->close();
			if ($numRows >= 1) return array('status'=>true,'userID'=>$userID);
		} else { return $error; }
	} elseif (isset($uname) && trim($uname) != '') {
		//username was entered
		if ($SQL = $mySQL->prepare("SELECT `oid` FROM `contactos` WHERE usuario = ? LIMIT 1"))
		{
			$SQL->bind_param('s',trim($uname));
			$SQL->execute();
			$SQL->store_result();
			$numRows = $SQL->num_rows();
			$SQL->bind_result($userID);
			$SQL->fetch();
			$SQL->close();
			if ($numRows >= 1) return array('status'=>true,'userID'=>$userID);
		} else { return $error; }
	} else {
		//nothing was entered;
		return $error;
	}
}
function getSecurityQuestion($userID)
{
	global $mySQL;
	$questions = array();
	$questions[1] = "¿En que ciudad nació?";
	$questions[2] = "¿Cúal es su color favorito?";
	$questions[3] = "¿En qué año se graduo de la facultad?";
	$questions[4] = "¿Cual es el segundo nombre de su novio/novia/marido/esposa?";
	$questions[5] = "¿Cúal es su auto favorito?";
	$questions[6] = "¿Cúal es el nombre de su madre?";
	if ($SQL = $mySQL->prepare("SELECT `secQ` FROM `contactos` WHERE `oid` = ? LIMIT 1"))
	{
		$SQL->bind_param('i',$userID);
		$SQL->execute();
		$SQL->store_result();
		$SQL->bind_result($secQ);
		$SQL->fetch();
		$SQL->close();
		return $questions[$secQ];
	} else {
		return false;
	}
}

function checkSecAnswer($userID,$answer)
{
	global $mySQL;
	if ($SQL = $mySQL->prepare("SELECT `usuario` FROM `contactos` WHERE `oid` = ? AND LOWER(`secA`) = ? LIMIT 1"))
	{
		$answer = strtolower($answer);
		$SQL->bind_param('is',$userID,$answer);
		$SQL->execute();
		$SQL->store_result();
		$numRows = $SQL->num_rows();
		$SQL->close();
		if ($numRows >= 1) { return true; }
	} else {
		return false;
	}
}

function sendPasswordEmail($userID)
{
	global $mySQL;
	if ($SQL = $mySQL->prepare("SELECT `usuario`,`email`,`contrasenia` FROM `contactos` WHERE `oid` = ? LIMIT 1"))
	{
		$SQL->bind_param('i',$userID);
		$SQL->execute();
		$SQL->store_result();
		$SQL->bind_result($uname,$email,$pword);
		$SQL->fetch();
		$SQL->close();
		$expFormat = mktime(date("H"), date("i"), date("s"), date("m")  , date("d")+3, date("Y"));
		$expDate = date("Y-m-d H:i:s",$expFormat);
		$key = md5($uname . '_' . $email . rand(0,10000) .$expDate . PW_SALT);

		if ($SQL = $mySQL->prepare("INSERT INTO `recoverymail` (`usuario`,`key`,`expDate`) VALUES (?,?,?)"))
		{
			$SQL->bind_param('iss',$userID,$key,$expDate);
			$SQL->execute();
			$SQL->close();


	 // Instanciando el Objeto
	 $mail = new PHPMailer();
	 $mail->IsSMTP();
	 //Servidor SMTP - GMAIL usa SSL/TLS  
	 //como protocolo de comunicación/autenticación por un puerto 465.  
	 $mail->Host = 'ssl://smtp.gmail.com:465';  
	 // True para que verifique autentificación  
	 $mail->SMTPAuth = true;  
	 // Cuenta de E-Mail & Password  
	 $mail->Username = "webpiced@gmail.com";
	 $mail->Password = "piced[423].";

	 $mail->From = "webpiced@gmail.com";
	 $mail->FromName = "WebPiCed";
	 $mail->Subject = "Su nueva contraseña es";
	 // Cuenta de E-Mail Destinatario  
	 //$mail->AddAddress($email,$email);

	 $mail->AddAddress("fernandogambaro@gmail.com","Fernando Gambaro");
//	 $mail->WordWrap = 50;

				$passwordLink = "<a href=\"?a=recover&email=" . $key . "&u=" . urlencode(base64_encode($userID)) . "\">http://uymcc.dyndns.org/cediva/pw/forgotPass.php?a=recover&email=" . $key . "&u=" . urlencode(base64_encode($userID)) . "</a>";
				$message = "Estimado/a $uname,\r\n";
				$message .= "Para terminar el proceso de recuperación de contraseña visite el siguiente link:\r\n";
				$message .= "-----------------------\r\n";
				$message .= "$passwordLink\r\n";
				$message .= "-----------------------\r\n";
				$message .= "Asegurece de copiar la dirección en su navegador favorito, la misma exirará luego de 3 dias.\r\n\r\n";
				$message .= "Si Ud. no solicito el cambio de contraseña, ningun cambio se realizara almeno que visite el link de arriba. De todas formas le recomendamos que ingrese con su cuenta y cambie la contraseña por razones de seguridad.\r\n\r\n";
				$message .= "Gracias,\r\n";
				$message .= "El equipo de WebPiCed";

 	 $mail->Body = $message;  
	 $mail->Send();  
		 
	 // Notificamos al usuario del estado del mensaje  
	 if(!$mail->Send()){  
	 $message="El envío del mail fallo, pongace en contacto con el administrador! ($site_email)".$mail->ErrorInfo;  
	} else {
	$message="Revice su bandeja de entrada, le hemos enviado un mail con las instrucciones.\r\n";
	$message.="El equipo de WebPiCed";
	}

/*
			@mail($email,$subject,$message,$headers);
*/
			return str_replace("\r\n","<br/ >",$message);
		}

	}
}

function checkEmailKey($key,$userID)
{
	global $mySQL;
	$curDate = date("Y-m-d H:i:s");
	if ($SQL = $mySQL->prepare("SELECT `usuario` FROM `recoverymail` WHERE `key` = ? AND `usuario` = ? AND `expDate` >= ?"))
	{
		$SQL->bind_param('sis',$key,$userID,$curDate);
		$SQL->execute();
		$SQL->execute();
		$SQL->store_result();
		$numRows = $SQL->num_rows();
		$SQL->bind_result($userID);
		$SQL->fetch();
		$SQL->close();
		if ($numRows > 0 && $userID != '')
		{
			return array('status'=>true,'userID'=>$userID);
		}
	}
	return false;
}

function updateUserPassword($userID,$password,$key)
{
	global $mySQL;
	if (checkEmailKey($key,$userID) === false) return false;
	if ($SQL = $mySQL->prepare("UPDATE `contactos` SET `contrasenia` = ? WHERE `oid` = ?"))
	{
		$converter = new Encryption;
		$password = $converter->encode($password );
echo $password;
		//$password = md5(trim($password) . PW_SALT);
		$SQL->bind_param('si',$password,$userID);
		$SQL->execute();
		$SQL->close();
		$SQL = $mySQL->prepare("DELETE FROM `recoverymail` WHERE `Key` = ?");
		$SQL->bind_param('s',$key);
		$SQL->execute();

	}
}

function getUserName($userID)
{
	global $mySQL;
	if ($SQL = $mySQL->prepare("SELECT `usuario` FROM `contactos` WHERE `oid` = ?"))
	{
		$SQL->bind_param('i',$userID);
		$SQL->execute();
		$SQL->store_result();
		$SQL->bind_result($uname);
		$SQL->fetch();
		$SQL->close();
	}
	return $uname;
}
