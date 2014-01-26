<?php
	class Conexion{
		var $host='localhost';
		var $user='root';
		var $pass='';
		//var $user='desarrollo';
		//var $pass='lfd247#';
		var $db='benbetesh';
		var $c_servidor='Se conecto con el servidor correctamente';
		var $i_servidor='no se conecto con el servidor';
		var $c_DB='Se conecto correctamente la BD';
		var $d_BD='No se desconecto la BD';
		var $i_DB='No se selecciono la DB';
		var $link;
		
		function Conectar(){
			$this->link = mysql_connect($this->host,$this->user,$this->pass);
			if(!$this->link){
				print $this->i_servidor;
			}
			else{
				if(!@mysql_select_db($this->db)){
					print $this->i_DB;
				}
			}
		}
		function Desconectar(){	
			if(!mysql_close($this->link))
				print $this->d_DB;
		}
	}
?>