<?php
class SelectList
{
    protected $conn;

        public function __construct()
        {
            $this->DbConnect();
        }
 
        protected function DbConnect()
        {
            include "../db_config.php";
            $this->conn = mysql_connect('localhost','root','mcc123mcc') OR die("Unable to connect to the database");
            mysql_select_db('CEDIVA',$this->conn) OR die("can not select the database $db");
            return TRUE;
        }
 
        public function ShowCategory()
        {
        	mysql_query("SET NAMES 'utf8'");
			header("Content-Type: text/html;charset=utf-8");

            $sql = "SELECT * FROM `ProcedimientoGeneral` WHERE `nop`='".$_POST['id']."'";
            $res = mysql_query($sql,$this->conn);
            $category = '<option value="0">Seleccione uno...</option>';
            while($row = mysql_fetch_array($res))
            {
                $category .= '<option value="' . $row['oid'] . '">' . $row['descripcion'] . '</option>';
            }
            return $category;
        }
 
        public function ShowType()
        {
        	mysql_query("SET NAMES 'utf8'");
			header("Content-Type: text/html;charset=utf-8");
            $sql = "SELECT * FROM  `ProcedimientoParticular` WHERE `oidgeneral`='".$_POST['id']."'";
            $res = mysql_query($sql,$this->conn);
            $type = '<option value="0">Seleccione uno...</option>';
            while($row = mysql_fetch_array($res))
            {
                $type .= '<option value="' . $row['oid'] . '">' . $row['descripcion'] . '</option>';
            }
            return $type;
        }
        public function ShowWord()
        {
        	mysql_query("SET NAMES 'utf8'");
			header("Content-Type: text/html;charset=utf-8");
            $sql = "SELECT * FROM `ProcedimientoPablabraClave` WHERE `oidparticular`='".$_POST['id']."'";
            $res = mysql_query($sql,$this->conn);
            $type = '<option value="0">Seleccione uno...</option>';
            while($row = mysql_fetch_array($res))
            {
                $type .= '<option value="' . $row['oid'] . '">' . $row['descripcion'] . '</option>';
            }
            return $type;
        }
}
 
$opt = new SelectList();
?>
