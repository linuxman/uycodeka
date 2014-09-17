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

if((!$s->data['isLoggedIn']) || !($s->data['isLoggedIn']))
{
	 /*/user is not logged in*/
         echo "<script>parent.changeURL('index.php' ); </script>";
/*/	 header("Location:index.html");	*/
} else {
   $loggedAt=$s->data['loggedAt'];
   $timeOut=$s->data['timeOut'];
   if(isset($loggedAt) && (time()-$loggedAt >$timeOut)){
   	$s->data['act']="timeout";
    	$s->save();  	
              echo "<script>window.parent.changeURL('index.php' ); </script>";
	       /*/header("Location:index.html");	*/
	       exit;
   }
   $s->data['loggedAt']= time();/*/ update last accessed time*/
   $s->save();
}

session_start();

$status_msg = "";

$USERID=$s->data['UserID'];
$USUARIONOM=$s->data['UserNom'];
$USUARIOAPE=$s->data['UserApe'];
$USERTIPO=$s->data['UserTpo'];

$_SESSION['USERID'] = $USERID;
$_SESSION['USERTIPO'] = $USERTIPO;

$ShowName=$USUARIONOM. " " .$USUARIOAPE;

require("common/funcionesvarias.php");
/*require("common/verificopermisos.php");*/

if ($USERID !=0)
logger($USERID, "Ingreso al sistema");
else
header ("Location:index.php?act=logout");

include("conectar.php");
?>
<html>
<head>
  <title>Codeka Facturacion Web</title>
  <script language="JavaScript" src="menu/JSCookMenu.js"></script>
  <link rel="stylesheet" href="menu/theme.css" type="text/css">
  <script language="JavaScript" src="menu/theme.js"></script>
<?php

if ($USERTIPO!=2){
?>
 <script language="JavaScript">
<!--
var MenuPrincipal = [
	[null,'Inicio','central2.php','principal','Inicio'],
	[null,'Equipos','./clientes/equipos/index.php','principal','Equipos'],
	[null,'Service','./clientes/service/index.php','principal','Service'],
	[null,'Respaldos','./clientes/backup/index.php','principal','Respaldos'],
	
	[null,'Creditos','creditos.php','principal','Creditos'],
	[null, 'Salir', 'index.php?act=logout',null, 'Salir']
];

--></script>
<?php
} else {
?>  
  <script language="JavaScript">
<!--
var MenuPrincipal = [
	[null,'Inicio','central2.php','principal','Inicio'],
	[null,'Inter. Comerciales',null,null,'Ventas clientes',
		[null,'Proveedores','./proveedores/index.php','principal','Proveedores'],
		[null,'Clientes','./clientes/index.php','principal','Clientes']
	],
	[null,'Productos',null,null,'Productos',
		[null,'Articulos','./articulos/index.php','principal','Articulos'],
		[null,'Familias','./familias/index.php','principal','Familias']
	],
	[null,'Ventas clientes',null,null,'Ventas clientes',
		[null,'Ventas Mostrador','./ventas_mostrador/index.php','principal','Ventas Mostrador'],
		[null,'Facturas','./facturas_clientes/index.php','principal','Facturas'],
		[null,'Albaranes/Remitos','./albaranes_clientes/index.php','principal','Albaranes'],
		[null,'Facturar albaranes/remitos','./lote_albaranes_clientes/index.php','principal','Facturar albaranes']
	],
	[null,'Compras proveedores',null,null,'Compras proveedores',
		[null,'Facturas','./facturas_proveedores/index.php','principal','Proveedores'],
		[null,'Albaranes/Remitos','./albaranes_proveedores/index.php','principal','Albaranes/Remitos'],
		[null,'Facturar albaranes/remotos','./lote_albaranes_proveedores/index.php','principal','Facturar albaranes/remotos'],
	],
	[null,'Tesoreria',null,null,'Tesoreria',
		[null,'Cobros','./cobros/index.php','principal','Cobros'],
		[null,'Pagos','./pagos/index.php','principal','Pagos'],
		[null,'Caja Diaria','./cerrarcaja/index.php','principal','Caja Diaria'],
		[null,'Libro Diario','./librodiario/index.php','principal','Libro Diario'],
	],
	[null,'Reportes',null,null,'Reportes',
		[null,'Ventas clientes','./ventas/index.php','principal','Ventas'],
		[null,'Compras','./compras/index.php','principal','Compras'],
		[null,'Cierre Mes','./cierremes/index.php','principal','Cierre Mes'],
		[null,'Libro Diario','./librodiario/index.php','principal','Libro Diario'],
	],
	[null,'Mantenimientos',null,null,'Mantenimientos',
		[null,'Etiquetas','./etiquetas/index.php','principal','Etiquetas'],
		[null,'Impuestos','./impuestos/index.php','principal','Impuestos'],
		[null,'Tipo cambio','./tipocambio/index.php','principal','Tipo cambio'],
		[null,'Entidades bancarias','./entidades/index.php','principal','Entidades bancarias'],
		[null,'Ubicaciones','./ubicaciones/index.php','principal','Ubicaciones'],
		[null,'Embalajes','./embalajes/index.php','principal','Embalajes'],
		[null,'Formas de pago','./formaspago/index.php','principal','Formas de pago'],
	],
	[null,'Copias Seguridad',null,null,'Copias de Seguridad',
		[null,'Hacer copia','./backup/hacerbak.php','principal','Hacer copia'],
		[null,'Restaurar copia','./backup/restaurarbak.php','principal','Restaurar copia'],
	],
	[null,'Creditos','creditos.php','principal','Creditos'],
	[null, 'Salir', 'index.php?act=logout',null, 'Salir']
];

--></script>
<?php
}
?>
<!--
//
//Redimenciono los iframes
//
-->
	<script type="text/javascript">
		function setIframeHeight(iframeName) {
		  //var iframeWin = window.frames[iframeName];
		  var iframeEl = document.getElementById? document.getElementById(iframeName): document.all? document.all[iframeName]: null;
		  if (iframeEl) {
		  iframeEl.style.height = "auto"; // helps resize (for some) if new doc shorter than previous
		  //var docHt = getDocHeight(iframeWin.document);
		  // need to add to height to be sure it will all show
		  var h = alertSize();
		  var new_h = (h-84);
		  iframeEl.style.height = new_h + "px";
		  //alertSize();
		  }
		}

		function alertSize() {
		  var myHeight = 0;
		  if( typeof( window.innerWidth ) == 'number' ) {
		    //Non-IE
		    myHeight = window.innerHeight;
		  } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
		    //IE 6+ in 'standards compliant mode'
		    myHeight = document.documentElement.clientHeight;
		  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
		    //IE 4 compatible
		    myHeight = document.body.clientHeight;
		  }
		  //window.alert( 'Height = ' + myHeight );
		  return myHeight;
		}
	</script>


  <style type="text/css">
  body { background-color: rgb(255, 255,255);
    background-image: url(images/superior.png);
    background-repeat: no-repeat;
	margin: 0px;
    }

  #MenuAplicacion { margin-left: 10px;
    margin-top: 0px;
    }


  </style>
</head>
<body>
	<body onload="setIframeHeight('principal');" onresize="setIframeHeight('principal');">

<div style="background:#EDEDED; margin:0 ; padding:0 10px 0 10px;">

<div id="MenuAplicacion" align="left"></div>
<script language="JavaScript">
<!--
	cmDraw ('MenuAplicacion', MenuPrincipal, 'hbr', cmThemeGray, 'ThemeGray');
-->
</script>
<div id="UserData" align="right" style="position: fixed;top:4px; right:20px;
 font-family: Tahoma; font-weight: bold; font-size: 11px;
  padding: 4px 10px 4px 10px;">Bienvenido <?php echo $ShowName;?></div>

<table cellpadding="0" cellspacing="0" align="center" width="100%"><tr> 
<td style="background:#EDEDED; border-radius:0px 0px 5px 5px; padding:5px 0px 5px 0px;" valign="top"> 
	
		<div style="background:#EDEDED; margin:0 7px 0 7px;
		border-left:1px solid #EFEFEF; border-right:1px solid #EFEFEF;
		border-bottom:1px solid #EFEFEF;padding:5px 5px 5px 5px;
      border-right:1px solid #C43303; border-bottom:1px solid #C43303;
      border-left:1px solid #C43303; border-top:1px solid #C43303; border-radius:5px;">

<iframe src="central2.php" name="principal" id="principal" title="principal" frameborder="0" width="100%" height="100%" marginheight="0" marginwidth="0" scrolling="auto" align="center" allowtransparency="true"></iframe>

	</div>
	
	</td>	
	</tr></table>
<div style="position: fixed; bottom: 0; left: 0; background:#2A2A2A; width:100%; height:37px; margin: 2px 2px 0 0; padding-buttom: 2px;" >
<div style="position: absolute; left: 0px; padding: 5px">&nbsp;&nbsp;
<font color="white"> MCC © 2014 <a href="http://www.mcc.com.uy" title="MCC - Soporte Técnico">MCC</a></font></div>
<div style="position: relative;" align="center">versión 1.2.0</div>

</div>

<div id="dialog-overlay"></div>
<div id="dialog-box">
<div style="background:#4B4B4B; margin:2px 2px 2px 2px; border-bottom:2px solid #000; border-radius:5px; position: static;
 padding-top: 7px; padding-left: 5px; background-image: url(images/bg-btn.png); background-repeat: repeat-x">
<div style="margin-bottom:3px;"> SOPORTE TÉCNICO</div>
<div align="right" style="margin-top:-25px; margin-right:2px"><button>X</button></div>
</div>
        <div class="dialog-content" style="background:#4B4B4B; margin:1px; border-radius:5px;">
        <div id="dialog-message"></div>   
    </div>
</div>
</div>
</body>
</html>
