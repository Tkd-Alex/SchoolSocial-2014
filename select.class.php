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
			include "settings.php";
			
			$this->conn = mysql_connect($server,$user,$pass) OR die("Impossibile connettersi al database");
			mysql_select_db($dbselected,$this->conn) OR die("Impossibile selezionare il database $dbselected");
			
			return TRUE;
		}
		
		public function ShowRegioni()
		{
			$sql = "SELECT * FROM regioni";
			$res = mysql_query($sql,$this->conn);
			$regioni = '<option value="0">Scegli</option>';
			
				while($row = mysql_fetch_array($res))
				{
					$regioni .= '<option value="' . $row['id_reg'] . '">' . utf8_encode($row['nome_regione']) . '</option>';
				}
				
			return $regioni;
		}
		
		public function ShowProvince()
		{
			$sql = "SELECT * FROM province WHERE id_reg=$_POST[id_reg]";
			$res = mysql_query($sql,$this->conn);
			$province = '<option value="0">Scegli</option>';
			
				while($row = mysql_fetch_array($res))
				{
					$province .= '<option value="' . $row['id_pro'] . '">' . utf8_encode($row['nome_provincia']) . '</option>';
				}
				
			return $province;
		}
		
//////////////////////////

		public function ShowProvince1($idreg)
		{
			$sql = "SELECT * FROM province WHERE id_reg=".$idreg."";
			$res = mysql_query($sql,$this->conn);
			$province = '<option value="0">Scegli</option>';
			
				while($row = mysql_fetch_array($res))
				{
					$province .= '<option value="' . $row['id_pro'] . '">' . utf8_encode($row['nome_provincia']) . '</option>';
				}
				
			return $province;
		} 
		
//////////////////////////
		
		
		public function ShowComuni()
		{
			$sql = "SELECT * FROM comuni WHERE id_pro=$_POST[id_pro]";
			$res = mysql_query($sql,$this->conn);
			$comuni = '<option value="0">Scegli</option>';
			
				while($row = mysql_fetch_array($res))
				{
					$comuni .= '<option value="' . $row['id_com'] . '">' . $row['cap'] . ' - ' . utf8_encode($row['comune']) . '</option>';
				}
				
			return $comuni;
		}
		
//////////////////////////

		public function ShowComuni1($idpro)
		{
			$sql = "SELECT * FROM comuni WHERE id_pro=".$idpro."";
			$res = mysql_query($sql,$this->conn);
			$comuni = '<option value="0">Scegli</option>';
			
				while($row = mysql_fetch_array($res))
				{
					$comuni .= '<option value="' . $row['id_com'] . '">' . $row['cap'] . ' - ' . utf8_encode($row['comune']) . '</option>';
				}
			$comuni.=$sql;
			return $comuni;
		}

//////////////////////////
		
		//Scuole
		
		public function ShowScuole()
		{
			$sql = "SELECT * FROM scuole";
			$res = mysql_query($sql,$this->conn);
			$scuole = '<option value="0">Scegli</option>';
			
				while($row = mysql_fetch_array($res))
				{
					$scuole .= '<option value="' . $row['id_scuola'] . '">' . utf8_encode($row['nome']) . '</option>';
				}
				
			return $scuole;
		}
		
		public function ShowIndirizzi()
		{
			$sql = "SELECT * FROM indirizzi WHERE scuola=$_POST[id_scuola]";
			$res = mysql_query($sql,$this->conn);
			$indirizzi = '<option value="0">Scegli</option>';
			
				while($row = mysql_fetch_array($res))
				{
					$indirizzi .= '<option value="' . $row['id_indirizzo'] . '">' . utf8_encode($row['indirizzo']) . '</option>';
				}
				
			return $indirizzi;
		}
		
//////////////////////////
		public function ShowIndirizzi1($idscu)
		{
			$sql = "SELECT * FROM indirizzi WHERE scuola=".$idscu."";
			$res = mysql_query($sql,$this->conn);
			$indirizzi = '<option value="0">Scegli</option>';
			
				while($row = mysql_fetch_array($res))
				{
					$indirizzi .= '<option value="' . $row['id_indirizzo'] . '">' . utf8_encode($row['indirizzo']) . '</option>';
				}
				
			return $indirizzi;
		}
/////////////////////////		
		
		public function ShowSezioni()
		{
			$sql = "SELECT * FROM sezioni WHERE indirizzo=$_POST[id_indirizzo]";
			$res = mysql_query($sql,$this->conn);
			$sezione = '<option value="0">Scegli</option>';
			
				while($row = mysql_fetch_array($res))
				{
					$sezione .= '<option value="' . $row['id_sezione'] . '">' . utf8_encode($row['nome_sezione']) . '</option>';
				}
				
			return $sezione;
		}
		
/////////////////////////	

		public function ShowSezioni1($id_indi)
		{
			$sql = "SELECT * FROM sezioni WHERE indirizzo=".$id_indi."";
			$res = mysql_query($sql,$this->conn);
			$sezione = '<option value="0">Scegli</option>';
			
				while($row = mysql_fetch_array($res))
				{
					$sezione .= '<option value="' . $row['id_sezione'] . '">' . utf8_encode($row['nome_sezione']) . '</option>';
				}
				
			return $sezione;
		}
/////////////////////////			
		
		public function ShowClassi()
		{
			$sql = "SELECT * FROM classi WHERE sezione=$_POST[id_sezione]";
			$res = mysql_query($sql,$this->conn);
			$classe = '<option value="0">Scegli</option>';
			
				while($row = mysql_fetch_array($res))
				{
					$classe .= '<option value="' . $row['id_classe'] . '">' . utf8_encode($row['classe']) . '</option>';
				}
				
			return $classe;
		}
		
/////////////////////////

		public function ShowClassi1($idsez)
		{
			$sql = "SELECT * FROM classi WHERE sezione=".$idsez."";
			$res = mysql_query($sql,$this->conn);
			$classe = '<option value="0">Scegli</option>';
			
				while($row = mysql_fetch_array($res))
				{
					$classe .= '<option value="' . $row['id_classe'] . '">' . utf8_encode($row['classe']) . '</option>';
				}
				
			return $classe;
		}
/////////////////////////
}


?>