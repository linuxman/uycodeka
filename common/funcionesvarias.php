<?php

date_default_timezone_set("America/Montevideo");

function array_envia($array) {
    $tmp = serialize($array);
    $tmp = urlencode($tmp);
    return $tmp;
}
function array_recibe($url_array) {
    $tmp = stripslashes($url_array);
    $tmp = urldecode($tmp);
    $tmp = unserialize($tmp);
   return $tmp;
}
//Libreria de funciones
function par($numero)
{
	$resto = $numero%2;
   if (($resto==0) && ($numero!=0)) {
        return 1;
   } else {
        return 0 ;
   }
}

//mysql_query("SET NAMES 'utf8'");

header("Content-Type: text/html;charset=utf-8");

function logger($USERID, $msg)
{
include("conexion.php");
if( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '' )
{ $logIP=$_SERVER['HTTP_X_FORWARDED_FOR']; }
else { $logIP=$_SERVER['REMOTE_ADDR']; }

$sql_log="INSERT INTO `log` (`logid`, `usuarioid`, `logdate`, `loghace`, `logip`) VALUES (NULL, '$USERID', CURRENT_TIMESTAMP, '$msg', '$logIP')";
$result=mysql_query($sql_log, $conectar) or die(mysql_error());
}

function mes($num){
   if($num>0) {
    /**
     * Creamos un array con los meses disponibles.
     * Agregamos un valor cualquiera al comienzo del array para que los números coincidan
     * con el valor tradicional del mes. El valor "Error" resultará útil
     **/
    $meses = array('Error', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
 
    /**
     * Si el número ingresado está entre 1 y 12 asignar la parte entera.
     * De lo contrario asignar "0"
     **/
    $num_limpio = $num >= 1 && $num <= 12 ? intval($num) : 0;
    return $meses[$num_limpio];
   } else {
    return "-";
   }
}

function mes_avre($num){
   if($num>0) {
    /**
     * Creamos un array con los meses disponibles.
     * Agregamos un valor cualquiera al comienzo del array para que los números coincidan
     * con el valor tradicional del mes. El valor "Error" resultará útil
     **/
    $meses = array('Error', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun',
        'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
 
    /**
     * Si el número ingresado está entre 1 y 12 asignar la parte entera.
     * De lo contrario asignar "0"
     **/
    $num_limpio = $num >= 1 && $num <= 12 ? intval($num) : 0;
    return $meses[$num_limpio];
   } else {
    return "-";
   }
}


function fechaATexto($fecha, $formato = 'c') {
 
    // Validamos que la cadena satisfaga el formato deseado y almacenamos las partes
    if (ereg("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $partes)) {
        // $partes[0] contiene la cadena original
        // $partes[1] contiene el año
        // $partes[2] contiene el número de mes
        // $partes[3] contiene el número del día
        $mes = ' de ' . mes($partes[2]); // Corregido!
        if ($formato == 'u') {
            $mes = strtoupper($mes);
        } elseif ($formato == 'l') {
            $mes = strtolower($mes);
        }
        if ($formato == 'M') {
        return $partes[3] . $mes;
	} else {
        return $partes[3] . $mes . ' de ' . $partes[1];
	}
 
    } else {
        // Si hubo problemas en la validación, devolvemos false
        return false;
    }
}

function dia_semana($num)
{ 	
	$dias = array('Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado');
	return $dias[$num];
}
function dia_semana_avr($num)
{
	$dias = array('Dom.','Lun.','Mar.','Mié.','Jue.','Vie.','Sáb.');
	return $dias[$num];
}

   function datecheck($fecha)
    {
		$valor = $fecha;
		if (ereg( "([0-9]{4}-([0-9]{1,2})-([0-9]{1,2}))" , $valor, $regs)&&$regs[3]<=31 &&$regs[2]<=12)
		{ 
			return true;
		 }
		else { 
			return false;
		} 
	}


function Reemplaza_Acentos($Cadena){
 $Cadena = str_replace('á','&aacute;',$Cadena);
 $Cadena = str_replace('é','&eacute;',$Cadena);
 $Cadena = str_replace('í','&iacute;',$Cadena);
 $Cadena = str_replace('ó','&oacute;',$Cadena);
 $Cadena = str_replace('ú','&uacute;',$Cadena);
 $Cadena = str_replace('Á','&Aacute;',$Cadena);
 $Cadena = str_replace('É','&Eacute;',$Cadena);
 $Cadena = str_replace('Í','&Iacute;',$Cadena);
 $Cadena = str_replace('Ó','&Oacute;',$Cadena);
 $Cadena = str_replace('Ú','&Uacute;',$Cadena);
 $Cadena = str_replace('ñ','&ntilde;',$Cadena);
 $Cadena = str_replace('Ñ','&Ntilde;',$Cadena);
 $Cadena = str_replace('ä','&auml;',$Cadena);
 $Cadena = str_replace('ë','&euml;',$Cadena);
 $Cadena = str_replace('ï','&iuml;',$Cadena);
 $Cadena = str_replace('ö','&ouml;',$Cadena);
 $Cadena = str_replace('ü','&uuml;',$Cadena);
 $Cadena = str_replace('Ä','&Auml;',$Cadena);
 $Cadena = str_replace('Ë','&Euml;',$Cadena);
 $Cadena = str_replace('Ï','&Iuml;',$Cadena);
 $Cadena = str_replace('Ö','&Ouml;',$Cadena);
 $Cadena = str_replace('Ü','&Uuml;',$Cadena);
 $Cadena = str_replace('²','&sup2;',$Cadena);
 $Cadena = str_replace('ñ','&ntilde;',$Cadena);
 $Cadena = str_replace('Ñ','&Ntilde;',$Cadena);
 return $Cadena;
}

function conectado($estado, $USERID, $USERNOM)
{
include("conexion.php");
$result = mysql_query("SELECT * FROM `CONECTADO` WHERE `CONTACTOSID`=$USERID", $conectar);
$num_rows = mysql_num_rows($result);

   if ($estado==1){
      if ($num_rows==0) {
      $sql="INSERT INTO  `CONECTADO` (`CONTACTOSID` ,`CONTACTOSNOMBRE`)VALUES ('$USERID',  '$USERNOM')";
      $result=mysql_query($sql, $conectar) or die(mysql_error());
      }
   }
   if ($estado==0){
      if ($num_rows!=0) {
      $sql="DELETE FROM `CONECTADO` WHERE `CONTACTOSID` = $USERID";
      $result=mysql_query($sql, $conectar) or die(mysql_error());
      }
   }
}
function difmes($mesini, $anioini, $mesfin, $aniofin, $diff) {
//Esta funcion debuelve true si la diferencia entre los meses es mayor a la diferencia consultada
if ($aniofin==$anioini) {
   if (($mesfin - $mesini) > $diff){
      return true;
   } else {
      return false;
   }

} elseif ($aniofin>$anioini) {
   if ( (12 - $mesini +$mesfin)>$diff ) {
      return true;
   } else {
      return false;
   }
}

}

function asigno_session($tabla){
include("conexion.php");

//MOSTRAMOS TODAS LAS TABLAS
   $Sql ="SHOW TABLES";
   $result = mysql_query( $Sql ) or die("No se puede ejecutar la consulta: ".mysql_error());
   while($Rs = mysql_fetch_array($result)) {
   $Sql2 ="DESCRIBE ".$Rs[0];
      if ($Rs[0]==$tabla) {
	 $result2 = mysql_query( $Sql2 ) or die("No se puede ejecutar la consulta 2: ".mysql_error());
	 while($Rs2 = mysql_fetch_array($result2)) {
      $campo=$Rs2['Field'];
      $_SESSION[$campo]="";
	 }
     }
   }
}

function datos_session($tabla, $valor){
include("conexion.php");

   //MOSTRAMOS TODAS LAS TABLAS
      $Sql ="SHOW TABLES";
      $result = mysql_query( $Sql ) or die("No se puede ejecutar la consulta: ".mysql_error());
      while($Rs = mysql_fetch_array($result)) {
	 if ($Rs[0]==$tabla) {
            $Sql2 ="DESCRIBE ".$Rs[0];
	    $result2 = mysql_query( $Sql2 ) or die("No se puede ejecutar la consulta 2: ".mysql_error());
	    $alca=1;
	    while($Rs2 = mysql_fetch_array($result2)) {
	    $field=$Rs2['Field'];
	       if ($alca ==1 ) {
	       $campos[$alca]=$field;
	       $index=$field;
	       }
	       if ($alca>1) {
	       $campos[$alca]=$field;
	       }
	    $alca++;
	    }
         }
      }
      $sql_data="select * from $tabla where `$index` = '$valor'";

      $cons = mysql_query($sql_data);
      $_SESSION[$index]=$valor;
   if ($linea = mysql_fetch_array($cons)) {
      foreach ($campos as $numero)
      {
      $_SESSION[$numero]=$linea[$numero];
//echo $numero."....".$_SESSION[$numero]."<br>";
      }
   }
return;
}

function borrar($tabla, $valor){
include("conexion.php");
   //MOSTRAMOS TODAS LAS TABLAS
      $Sql ="SHOW TABLES";
      $result = mysql_query( $Sql ) or die("No se puede ejecutar la consulta: ".mysql_error());
      while($Rs = mysql_fetch_array($result)) {
      $Sql2 ="DESCRIBE ".$Rs[0];
	 if ($Rs[0]==$tabla) {
	    $result2 = mysql_query( $Sql2 ) or die("No se puede ejecutar la consulta 2: ".mysql_error());
	    $alca=1;
	    while($Rs2 = mysql_fetch_array($result2)) {
	    $field=$Rs2['Field'];
	       if ($alca ==1 ) {
	       $index=$field;
	       }
            $alca++;
	    }
         }
      }
   mysql_query("BEGIN");
   $sql="delete from $tabla where $index ='$valor' limit 1";
//echo $sql;
   $res=mysql_query($sql) or die ("No borra");
      if ($res==false) {
	    echo "ERROR al Borrar";
	    mysql_query("ROLLBACK");
      } else {
	    echo "Borrado exitoso, ".$tabla;
	    mysql_query("COMMIT");
      }
}

function compara_fechas($fecha1,$fecha2)
{
	$dia1="";
	$mes1="";
	$anio1="";
	$dia2="";
	$mes2="";
	$anio2="";

$fecha1 = str_replace("/", "-", $fecha1);
$fecha2 = str_replace("/", "-", $fecha2);
	
	$dias	= (strtotime($fecha1)-strtotime($fecha2))/86400;
	$dias	= abs($dias); $dias = floor($dias);		
	if (strtotime($fecha2) < strtotime($fecha1)) {
		$dias=-$dias;
	}
	return $dias;	
	
}

// function remove_accents()
/**
* Unaccent the input string string. An example string like `ÀØėÿᾜὨζὅБю`
* will be translated to `AOeyIOzoBY`. More complete than :
* strtr( (string)$str,
* "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ",
* "aaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn" );
*
* @param $str input string
* @param $utf8 if null, function will detect input string encoding
* @return string input string without accent
*/

function remove_accents( $str, $utf8=true )
{
$str = (string)$str;
if( is_null($utf8) ) {
if( !function_exists('mb_detect_encoding') ) {
$utf8 = (strtolower( mb_detect_encoding($str) )=='utf-8');
} else {
$length = strlen($str);
$utf8 = true;
for ($i=0; $i < $length; $i++) {
$c = ord($str[$i]);
if ($c < 0x80) $n = 0; /*# 0bbbbbbb*/
elseif (($c & 0xE0) == 0xC0) $n=1; /*# 110bbbbb*/
elseif (($c & 0xF0) == 0xE0) $n=2; /*# 1110bbbb*/
elseif (($c & 0xF8) == 0xF0) $n=3; /*# 11110bbb*/
elseif (($c & 0xFC) == 0xF8) $n=4; /*# 111110bb*/
elseif (($c & 0xFE) == 0xFC) $n=5; /*# 1111110b*/
else return false; /* Does not match any model*/
for ($j=0; $j<$n; $j++) { /*# n bytes matching 10bbbbbb follow ?*/
if ((++$i == $length)
|| ((ord($str[$i]) & 0xC0) != 0x80)) {
$utf8 = false;
break;
}
}
}
}
}
if(!$utf8)
$str = utf8_encode($str);
 
$transliteration = array(
'Ĳ' => 'I', 'Ö' => 'O','Œ' => 'O','Ü' => 'U','ä' => 'a','æ' => 'a',
'ĳ' => 'i','ö' => 'o','œ' => 'o','ü' => 'u','ß' => 's','ſ' => 's',
'À' => 'A','Á' => 'A','Â' => 'A','Ã' => 'A','Ä' => 'A','Å' => 'A',
'Æ' => 'A','Ā' => 'A','Ą' => 'A','Ă' => 'A','Ç' => 'C','Ć' => 'C',
'Č' => 'C','Ĉ' => 'C','Ċ' => 'C','Ď' => 'D','Đ' => 'D','È' => 'E',
'É' => 'E','Ê' => 'E','Ë' => 'E','Ē' => 'E','Ę' => 'E','Ě' => 'E',
'Ĕ' => 'E','Ė' => 'E','Ĝ' => 'G','Ğ' => 'G','Ġ' => 'G','Ģ' => 'G',
'Ĥ' => 'H','Ħ' => 'H','Ì' => 'I','Í' => 'I','Î' => 'I','Ï' => 'I',
'Ī' => 'I','Ĩ' => 'I','Ĭ' => 'I','Į' => 'I','İ' => 'I','Ĵ' => 'J',
'Ķ' => 'K','Ľ' => 'K','Ĺ' => 'K','Ļ' => 'K','Ŀ' => 'K','Ł' => 'L',
'Ñ' => 'N','Ń' => 'N','Ň' => 'N','Ņ' => 'N','Ŋ' => 'N','Ò' => 'O',
'Ó' => 'O','Ô' => 'O','Õ' => 'O','Ø' => 'O','Ō' => 'O','Ő' => 'O',
'Ŏ' => 'O','Ŕ' => 'R','Ř' => 'R','Ŗ' => 'R','Ś' => 'S','Ş' => 'S',
'Ŝ' => 'S','Ș' => 'S','Š' => 'S','Ť' => 'T','Ţ' => 'T','Ŧ' => 'T',
'Ț' => 'T','Ù' => 'U','Ú' => 'U','Û' => 'U','Ū' => 'U','Ů' => 'U',
'Ű' => 'U','Ŭ' => 'U','Ũ' => 'U','Ų' => 'U','Ŵ' => 'W','Ŷ' => 'Y',
'Ÿ' => 'Y','Ý' => 'Y','Ź' => 'Z','Ż' => 'Z','Ž' => 'Z','à' => 'a',
'á' => 'a','â' => 'a','ã' => 'a','ā' => 'a','ą' => 'a','ă' => 'a',
'å' => 'a','ç' => 'c','ć' => 'c','č' => 'c','ĉ' => 'c','ċ' => 'c',
'ď' => 'd','đ' => 'd','è' => 'e','é' => 'e','ê' => 'e','ë' => 'e',
'ē' => 'e','ę' => 'e','ě' => 'e','ĕ' => 'e','ė' => 'e','ƒ' => 'f',
'ĝ' => 'g','ğ' => 'g','ġ' => 'g','ģ' => 'g','ĥ' => 'h','ħ' => 'h',
'ì' => 'i','í' => 'i','î' => 'i','ï' => 'i','ī' => 'i','ĩ' => 'i',
'ĭ' => 'i','į' => 'i','ı' => 'i','ĵ' => 'j','ķ' => 'k','ĸ' => 'k',
'ł' => 'l','ľ' => 'l','ĺ' => 'l','ļ' => 'l','ŀ' => 'l','ñ' => 'n',
'ń' => 'n','ň' => 'n','ņ' => 'n','ŉ' => 'n','ŋ' => 'n','ò' => 'o',
'ó' => 'o','ô' => 'o','õ' => 'o','ø' => 'o','ō' => 'o','ő' => 'o',
'ŏ' => 'o','ŕ' => 'r','ř' => 'r','ŗ' => 'r','ś' => 's','š' => 's',
'ť' => 't','ù' => 'u','ú' => 'u','û' => 'u','ū' => 'u','ů' => 'u',
'ű' => 'u','ŭ' => 'u','ũ' => 'u','ų' => 'u','ŵ' => 'w','ÿ' => 'y',
'ý' => 'y','ŷ' => 'y','ż' => 'z','ź' => 'z','ž' => 'z','Α' => 'A',
'Ά' => 'A','Ἀ' => 'A','Ἁ' => 'A','Ἂ' => 'A','Ἃ' => 'A','Ἄ' => 'A',
'Ἅ' => 'A','Ἆ' => 'A','Ἇ' => 'A','ᾈ' => 'A','ᾉ' => 'A','ᾊ' => 'A',
'ᾋ' => 'A','ᾌ' => 'A','ᾍ' => 'A','ᾎ' => 'A','ᾏ' => 'A','Ᾰ' => 'A',
'Ᾱ' => 'A','Ὰ' => 'A','ᾼ' => 'A','Β' => 'B','Γ' => 'G','Δ' => 'D',
'Ε' => 'E','Έ' => 'E','Ἐ' => 'E','Ἑ' => 'E','Ἒ' => 'E','Ἓ' => 'E',
'Ἔ' => 'E','Ἕ' => 'E','Ὲ' => 'E','Ζ' => 'Z','Η' => 'I','Ή' => 'I',
'Ἠ' => 'I','Ἡ' => 'I','Ἢ' => 'I','Ἣ' => 'I','Ἤ' => 'I','Ἥ' => 'I',
'Ἦ' => 'I','Ἧ' => 'I','ᾘ' => 'I','ᾙ' => 'I','ᾚ' => 'I','ᾛ' => 'I',
'ᾜ' => 'I','ᾝ' => 'I','ᾞ' => 'I','ᾟ' => 'I','Ὴ' => 'I','ῌ' => 'I',
'Θ' => 'T','Ι' => 'I','Ί' => 'I','Ϊ' => 'I','Ἰ' => 'I','Ἱ' => 'I',
'Ἲ' => 'I','Ἳ' => 'I','Ἴ' => 'I','Ἵ' => 'I','Ἶ' => 'I','Ἷ' => 'I',
'Ῐ' => 'I','Ῑ' => 'I','Ὶ' => 'I','Κ' => 'K','Λ' => 'L','Μ' => 'M',
'Ν' => 'N','Ξ' => 'K','Ο' => 'O','Ό' => 'O','Ὀ' => 'O','Ὁ' => 'O',
'Ὂ' => 'O','Ὃ' => 'O','Ὄ' => 'O','Ὅ' => 'O','Ὸ' => 'O','Π' => 'P',
'Ρ' => 'R','Ῥ' => 'R','Σ' => 'S','Τ' => 'T','Υ' => 'Y','Ύ' => 'Y',
'Ϋ' => 'Y','Ὑ' => 'Y','Ὓ' => 'Y','Ὕ' => 'Y','Ὗ' => 'Y','Ῠ' => 'Y',
'Ῡ' => 'Y','Ὺ' => 'Y','Φ' => 'F','Χ' => 'X','Ψ' => 'P','Ω' => 'O',
'Ώ' => 'O','Ὠ' => 'O','Ὡ' => 'O','Ὢ' => 'O','Ὣ' => 'O','Ὤ' => 'O',
'Ὥ' => 'O','Ὦ' => 'O','Ὧ' => 'O','ᾨ' => 'O','ᾩ' => 'O','ᾪ' => 'O',
'ᾫ' => 'O','ᾬ' => 'O','ᾭ' => 'O','ᾮ' => 'O','ᾯ' => 'O','Ὼ' => 'O',
'ῼ' => 'O','α' => 'a','ά' => 'a','ἀ' => 'a','ἁ' => 'a','ἂ' => 'a',
'ἃ' => 'a','ἄ' => 'a','ἅ' => 'a','ἆ' => 'a','ἇ' => 'a','ᾀ' => 'a',
'ᾁ' => 'a','ᾂ' => 'a','ᾃ' => 'a','ᾄ' => 'a','ᾅ' => 'a','ᾆ' => 'a',
'ᾇ' => 'a','ὰ' => 'a','ᾰ' => 'a','ᾱ' => 'a','ᾲ' => 'a','ᾳ' => 'a',
'ᾴ' => 'a','ᾶ' => 'a','ᾷ' => 'a','β' => 'b','γ' => 'g','δ' => 'd',
'ε' => 'e','έ' => 'e','ἐ' => 'e','ἑ' => 'e','ἒ' => 'e','ἓ' => 'e',
'ἔ' => 'e','ἕ' => 'e','ὲ' => 'e','ζ' => 'z','η' => 'i','ή' => 'i',
'ἠ' => 'i','ἡ' => 'i','ἢ' => 'i','ἣ' => 'i','ἤ' => 'i','ἥ' => 'i',
'ἦ' => 'i','ἧ' => 'i','ᾐ' => 'i','ᾑ' => 'i','ᾒ' => 'i','ᾓ' => 'i',
'ᾔ' => 'i','ᾕ' => 'i','ᾖ' => 'i','ᾗ' => 'i','ὴ' => 'i','ῂ' => 'i',
'ῃ' => 'i','ῄ' => 'i','ῆ' => 'i','ῇ' => 'i','θ' => 't','ι' => 'i',
'ί' => 'i','ϊ' => 'i','ΐ' => 'i','ἰ' => 'i','ἱ' => 'i','ἲ' => 'i',
'ἳ' => 'i','ἴ' => 'i','ἵ' => 'i','ἶ' => 'i','ἷ' => 'i','ὶ' => 'i',
'ῐ' => 'i','ῑ' => 'i','ῒ' => 'i','ῖ' => 'i','ῗ' => 'i','κ' => 'k',
'λ' => 'l','μ' => 'm','ν' => 'n','ξ' => 'k','ο' => 'o','ό' => 'o',
'ὀ' => 'o','ὁ' => 'o','ὂ' => 'o','ὃ' => 'o','ὄ' => 'o','ὅ' => 'o',
'ὸ' => 'o','π' => 'p','ρ' => 'r','ῤ' => 'r','ῥ' => 'r','σ' => 's',
'ς' => 's','τ' => 't','υ' => 'y','ύ' => 'y','ϋ' => 'y','ΰ' => 'y',
'ὐ' => 'y','ὑ' => 'y','ὒ' => 'y','ὓ' => 'y','ὔ' => 'y','ὕ' => 'y',
'ὖ' => 'y','ὗ' => 'y','ὺ' => 'y','ῠ' => 'y','ῡ' => 'y','ῢ' => 'y',
'ῦ' => 'y','ῧ' => 'y','φ' => 'f','χ' => 'x','ψ' => 'p','ω' => 'o',
'ώ' => 'o','ὠ' => 'o','ὡ' => 'o','ὢ' => 'o','ὣ' => 'o','ὤ' => 'o',
'ὥ' => 'o','ὦ' => 'o','ὧ' => 'o','ᾠ' => 'o','ᾡ' => 'o','ᾢ' => 'o',
'ᾣ' => 'o','ᾤ' => 'o','ᾥ' => 'o','ᾦ' => 'o','ᾧ' => 'o','ὼ' => 'o',
'ῲ' => 'o','ῳ' => 'o','ῴ' => 'o','ῶ' => 'o','ῷ' => 'o','А' => 'A',
'Б' => 'B','В' => 'V','Г' => 'G','Д' => 'D','Е' => 'E','Ё' => 'E',
'Ж' => 'Z','З' => 'Z','И' => 'I','Й' => 'I','К' => 'K','Л' => 'L',
'М' => 'M','Н' => 'N','О' => 'O','П' => 'P','Р' => 'R','С' => 'S',
'Т' => 'T','У' => 'U','Ф' => 'F','Х' => 'K','Ц' => 'T','Ч' => 'C',
'Ш' => 'S','Щ' => 'S','Ы' => 'Y','Э' => 'E','Ю' => 'Y','Я' => 'Y',
'а' => 'A','б' => 'B','в' => 'V','г' => 'G','д' => 'D','е' => 'E',
'ё' => 'E','ж' => 'Z','з' => 'Z','и' => 'I','й' => 'I','к' => 'K',
'л' => 'L','м' => 'M','н' => 'N','о' => 'O','п' => 'P','р' => 'R',
'с' => 'S','т' => 'T','у' => 'U','ф' => 'F','х' => 'K','ц' => 'T',
'ч' => 'C','ш' => 'S','щ' => 'S','ы' => 'Y','э' => 'E','ю' => 'Y',
'я' => 'Y','ð' => 'd','Ð' => 'D','þ' => 't','Þ' => 'T','ა' => 'a',
'ბ' => 'b','გ' => 'g','დ' => 'd','ე' => 'e','ვ' => 'v','ზ' => 'z',
'თ' => 't','ი' => 'i','კ' => 'k','ლ' => 'l','მ' => 'm','ნ' => 'n',
'ო' => 'o','პ' => 'p','ჟ' => 'z','რ' => 'r','ს' => 's','ტ' => 't',
'უ' => 'u','ფ' => 'p','ქ' => 'k','ღ' => 'g','ყ' => 'q','შ' => 's',
'ჩ' => 'c','ც' => 't','ძ' => 'd','წ' => 't','ჭ' => 'c','ხ' => 'k',
'ჯ' => 'j','ჰ' => 'h'
);
$str = str_replace( array_keys( $transliteration ),
array_values( $transliteration ),
$str);
return $str;
}
//- remove_accents()

/*/ Calcula la edad (formato: año/mes/dia)*/
function edad($edad){
list($anio,$mes,$dia) = explode("-",$edad);
$anio_dif = date("Y") - $anio;
$mes_dif = date("m") - $mes;
$dia_dif = date("d") - $dia;
if ($dia_dif < 0 || $mes_dif < 0)
$anio_dif--;
return $anio_dif;
}

function extrae($cadena,$num_caracteres){
    //Extracto de los primeros numeros de caracteres definidos en $num_caracteres;
    $cadena_ext = substr($cadena,0, $num_caracteres);
    //Si el extracto ya viene con palabra completa no necesita buscar la siguiente palabra
    if($cadena[$num_caracteres] != " "){
        $sub_cadena = substr($cadena,$num_caracteres, ($tam_cadena - $num_caracteres));
        $miarray = explode (' ', $sub_cadena);
        $res_sub_cadena = $miarray[0];
    }
    $cad = $cadena_ext.$res_sub_cadena;            
    return $cad;   
}

function clean_special_chars( $s, $d=false )
{
    if($d) $s = utf8_decode( $s );

    $chars = array(
    '_' => '/`|´|\^|~|¨|ª|º|©|®/',
    'a' => '/à|á|â|ã|ä|å|æ/', 
    'e' => '/è|é|ê|ë/', 
    'i' => '/ì|í|î|ĩ|ï/',   
    'o' => '/ò|ó|ô|õ|ö|ø/', 
    'u' => '/ù|ú|û|ű|ü|ů/', 
    'A' => '/À|Á|Â|Ã|Ä|Å|Æ/', 
    'E' => '/È|É|Ê|Ë/', 
    'I' => '/Ì|Í|Î|Ĩ|Ï/',   
    'O' => '/Ò|Ó|Ô|Õ|Ö|Ø/', 
    'U' => '/Ù|Ú|Û|Ũ|Ü|Ů/', 
    'c' => '/ć|ĉ|ç/', 
    'C' => '/Ć|Ĉ|Ç/', 
    'n' => '/ñ/', 
    'N' => '/Ñ/', 
    'y' => '/ý|ŷ|ÿ/', 
    'Y' => '/Ý|Ŷ|Ÿ/'
    );

return preg_replace( $chars, array_keys( $chars ), $s );
}

function UTF8_ASCII($str)
{
    return strtr(
        utf8_decode($str),
        utf8_decode('ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ'),
        'SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy'
    );
}


function remove_accent_2($str){
    $search = explode(",","ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u");
    $replace = explode(",","c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,e,i,o,u");
    return str_replace($search, $replace, $str);
}

function remove_accent_vi($str) {
    if(!$str) return false;
    $utf8 = array(
                'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ|Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
                'd'=>'đ|Đ',
                'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ|É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
                'i'=>'í|ì|ỉ|ĩ|ị|Í|Ì|Ỉ|Ĩ|Ị',
                'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ|Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
                'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự|Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
                'y'=>'ý|ỳ|ỷ|ỹ|ỵ|Ý|Ỳ|Ỷ|Ỹ|Ỵ',
    );
    foreach($utf8 as $ascii=>$uni) $str = preg_replace("/($uni)/i",$ascii,$str);
    return $str;
}

?>

