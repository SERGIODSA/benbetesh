<?php
	include('conexion.php');
	class Queries{
		private $Cnx;
		function __construct(){
			$this->Cnx = new Conexion;
			$this->Cnx->Conectar();
		}
		function Desconectar(){
			$this->Cnx->Desconectar();
		}
		private function truncate_this($text,$chars){
			$text = substr($text,0,$chars);
			$text = substr($text,0,strrpos($text,' '));
			$text = $text." ...";
			return $text;
		}
		function IndexSlideshow($Idioma){
			$Slideshow = null;
			$sql = 'SELECT slideshow1_url,slideshow2_url,slideshow3_url,slideshow4_url,slideshow5_url FROM ss_index WHERE id_idioma='.$Idioma.' LIMIT 1';         
			$query = mysql_query($sql);			
			if(mysql_num_rows($query)>0){
				while($row = mysql_fetch_assoc($query)){
					$Slideshow[0] = $row['slideshow1_url'];
					$Slideshow[1] = $row['slideshow2_url'];
					$Slideshow[2] = $row['slideshow3_url'];
					$Slideshow[3] = $row['slideshow4_url'];
					$Slideshow[4] = $row['slideshow5_url'];
				}	
			}
			return $Slideshow;
		}
		function IndexMarcasLogos($Idioma){
			$Logos['AMIGABLE'] = $Logos['LOGO'] = null;
			$i = 0;
			$sql = "SELECT amigable,logo_url FROM marcas WHERE id_idioma = '".$Idioma."' AND amigable NOT LIKE 'ben-betesh' AND amigable NOT LIKE 'benbetesh'";
			$query = mysql_query($sql);			
			while($row = mysql_fetch_assoc($query)){
				$Logos['AMIGABLE'][$i] = $row['amigable'];
				$Logos['LOGO'][$i] = 'subidas/'.$row['logo_url'];
				$i++;
			}
			return $Logos;
		}
		function IndexMarcasTiendas($Idioma){
			$Tiendas['AMIGABLE'] = $Tiendas['IMAGEN'] = null;
			$i = 0;
			$sql = "SELECT amigable,tienda1_url FROM marcas WHERE id_idioma='".$Idioma."' AND amigable NOT LIKE 'ben-betesh' AND amigable NOT LIKE 'benbetesh'";
			$query = mysql_query($sql);			
			while(($row = mysql_fetch_assoc($query))&&($i<9)){
				$Tiendas['AMIGABLE'][$i] = $row['amigable'];
				$Tiendas['IMAGEN'][$i] = 'subidas/'.$row['tienda1_url'];
				$i++;
			}
			if($i<9){
				$sql = "SELECT amigable,tienda2_url FROM marcas WHERE id_idioma='".$Idioma."' AND amigable NOT LIKE 'ben-betesh' AND amigable NOT LIKE 'benbetesh'";
				$query = mysql_query($sql);			
				while(($row = mysql_fetch_assoc($query))&&($i<9)){
					$Tiendas['AMIGABLE'][$i] = $row['amigable'];
					$Tiendas['IMAGEN'][$i] = 'subidas/'.$row['tienda2_url'];
					$i++;
				}
			}
			if($i<9){
				$sql = "SELECT amigable,tienda3_url FROM marcas WHERE id_idioma='".$Idioma."' AND amigable NOT LIKE 'ben-betesh' AND amigable NOT LIKE 'benbetesh'";
				$query = mysql_query($sql);			
				while(($row = mysql_fetch_assoc($query))&&($i<9)){
					$Tiendas['AMIGABLE'][$i] = $row['amigable'];
					$Tiendas['IMAGEN'][$i] = 'subidas/'.$row['tienda3_url'];
					$i++;
				}
			}
			return $Tiendas;
		}
		function IndexBenbetesh($Idioma){
			$BenBetesh = null;
			$sql = 'SELECT imagen_url FROM benbetesh WHERE id_idioma = '.$Idioma.' LIMIT 1';
			$query = mysql_query($sql);			
			while($row = mysql_fetch_assoc($query))
				$BenBetesh = 'subidas/'.$row['imagen_url'];
			return $BenBetesh;
		}
		function IndexNosotros($Idioma){
			$Nosotros['IMAGEN'] = $Nosotros['DESCRIPCION'] = null;
			$sql = "SELECT LEFT(descripcion1,128) AS descripcion, imagen1_url FROM nosotros WHERE id_idioma='".$Idioma."' LIMIT 1";
			$query = mysql_query($sql);			
			while($row = mysql_fetch_assoc($query)){
				$Nosotros['IMAGEN'] = 'subidas/'.$row['imagen1_url'];
				$Nosotros['DESCRIPCION'] = $this->truncate_this($row['descripcion'],'128');
				$Nosotros['DESCRIPCION'] = strip_tags($Nosotros['DESCRIPCION']);
			}
			return $Nosotros;
		}
		function IndexNoticias($Idioma){
			$Noticias['IMAGEN'] = $Noticias['DESCRIPCION'] = $Noticias['AMIGABLE'] = null;
			$i = 0;
			$sql = "SELECT LEFT(descripcion,131) AS descripcion,imagen_url,amigable FROM noticias WHERE id_idioma='".$Idioma."' ORDER BY id_noticia DESC LIMIT 2";
			$query = mysql_query($sql);			
			while($row = mysql_fetch_assoc($query)){
				$Noticias['IMAGEN'][$i] = 'subidas/'.$row['imagen_url'];
				$Noticias['DESCRIPCION'][$i] = $this->truncate_this($row['descripcion'],'131');
				$Noticias['AMIGABLE'][$i] = $row['amigable'];
				$i++;
			}
			return $Noticias;
		}
		function MenuPrincipal($Idioma){
			$Menu['DESCRIPCION'] = $Menu['AMIGABLE'] = $Menu['FACEBOOK'] = $Menu['TWITTER'] = $Menu['YOUTUBE'] = null;
			$i = 0;
			$sql = "SELECT descripcion,amigable FROM menu WHERE id_idioma='".$Idioma."' LIMIT 7 ";
			$query = mysql_query($sql);			
			while($row = mysql_fetch_assoc($query)){
				$Menu['DESCRIPCION'][$i] = $row['descripcion'];
				$Menu['AMIGABLE'][$i] = $row['amigable'];
				$i++;
			}
			$sql = "SELECT url_facebook,url_twitter,url_youtube FROM marcas WHERE (amigable LIKE 'benbetesh' OR amigable LIKE 'ben-betesh') AND id_idioma='".$Idioma."' LIMIT 1";
			$query = mysql_query($sql);	
			while($row = mysql_fetch_assoc($query)){
				$Menu['FACEBOOK'] = $row['url_facebook'];
				$Menu['TWITTER'] = $row['url_twitter'];
				$Menu['YOUTUBE'] = $row['url_youtube'];
			}
			return $Menu;
		}
		function Pie($Idioma){
			$i = 0;
			$Pie['TIENDAS'] = $Pie['MARCAS'] = $Pie['CORPORATIVO'] = $Pie['TITULO'] = $Pie['FACEBOOK'] = $Pie['TWITTER'] = $Pie['YOUTUBE'] = null;
			$sql = "SELECT nombre,amigable FROM marcas WHERE marcas_pie=1 AND id_idioma='".$Idioma."' LIMIT 8 ";
			$query = mysql_query($sql);			
			while($row = mysql_fetch_assoc($query)){
				$Pie['MARCAS']['NOMBRE'][$i] = $row['nombre'];
				$Pie['MARCAS']['AMIGABLE'][$i] = $row['amigable'];
				$i++;
			}
			$sql = "SELECT nombre,amigable FROM marcas WHERE tiendas_pie=1 AND id_idioma='".$Idioma."' LIMIT 8 ";
			$i = 0;
			$query = mysql_query($sql);			
			while($row = mysql_fetch_assoc($query)){
				$Pie['TIENDAS']['NOMBRE'][$i] = $row['nombre'];
				$Pie['TIENDAS']['AMIGABLE'][$i] = $row['amigable'];
				$i++;
			}
			$sql = "SELECT titulo1,titulo2,titulo3 FROM nosotros WHERE id_idioma='".$Idioma."' LIMIT 1";
			$query = mysql_query($sql);			
			while($row = mysql_fetch_assoc($query)){
				$Pie['CORPORATIVO'][0] = $row['titulo1'];
				$Pie['CORPORATIVO'][1] = $row['titulo2'];
				$Pie['CORPORATIVO'][2] = $row['titulo3'];
			}
			$sql = "SELECT titulo FROM ss_nosotros WHERE id_idioma='".$Idioma."' LIMIT 3";
			$i = 3;
			$query = mysql_query($sql);			
			while($row = mysql_fetch_assoc($query)){
				$Pie['CORPORATIVO'][$i] = $row['titulo'];
				$i++;
			}
			$sql = "SELECT descripcion_form,telefono,url_facebook,url_twitter,url_youtube FROM marcas WHERE (amigable LIKE 'benbetesh' OR amigable LIKE 'ben-betesh') AND id_idioma='".$Idioma."' LIMIT 1";
			$query = mysql_query($sql);	
			while($row = mysql_fetch_assoc($query)){
				$Pie['CORREO'] = $row['descripcion_form'];
				$Pie['TELEFONO'] = $row['telefono'];
				$Pie['YOUTUBE'] = $row['url_youtube'];
				$Pie['FACEBOOK'] = $row['url_facebook'];
				$Pie['TWITTER'] = $row['url_twitter'];
				$Pie['YOUTUBE'] = $row['url_youtube'];
			}
			return $Pie;
		}
		function Contactenos($Idioma){
			$Oficinas['TITULO'] = $Oficinas['DIRECCION'] = $Oficinas['TELEFONO'] = null;
			$sql = "SELECT direccion,lugar_titulo,telefono,fax,tienda,correo FROM oficinas WHERE id_idioma='".$Idioma."' LIMIT 6";
			$i = 0;
			$query = mysql_query($sql);			
			while($row = mysql_fetch_assoc($query)){
				$Oficinas['TITULO'][$i] = $row['lugar_titulo'];
				$Oficinas['DIRECCION'][$i] = $row['direccion'];
				$Oficinas['TELEFONO'][$i] = $row['telefono'];
				$Oficinas['FAX'][$i] = $row['fax'];
				$Oficinas['TIENDA'][$i] = $row['tienda'];
				$Oficinas['CORREO'][$i] = $row['correo'];
				$Oficinas['TITULO'][$i] = strip_tags($Oficinas['TITULO'][$i]);
				$Oficinas['DIRECCION'][$i] = strip_tags($Oficinas['DIRECCION'][$i]);
				$Oficinas['TELEFONO'][$i] = strip_tags($Oficinas['TELEFONO'][$i]);
				$Oficinas['FAX'][$i] = strip_tags($Oficinas['FAX'][$i]);
				$Oficinas['TIENDA'][$i] = strip_tags($Oficinas['TIENDA'][$i]);
				$Oficinas['CORREO'][$i] = strip_tags($Oficinas['CORREO'][$i]);
				$i++;
			}
			return $Oficinas;
		}
		function Tiendas($Idioma,$Tienda){
			$Tiendas = null;
			$sql = "SELECT logo_url,slideshow1_url,slideshow2_url,slideshow3_url,slideshow4_url,slideshow5_url,tienda1_url,tienda2_url,tienda3_url,
					titulo1,descripcion1,titulo2,descripcion2,titulo3,descripcion3,descripcion_form,telefono,url_facebook,url_twitter,url_youtube
					FROM marcas WHERE id_idioma = '".$Idioma."' AND amigable LIKE '".$Tienda."' LIMIT 1";
			$query = mysql_query($sql);			
			while($row = mysql_fetch_assoc($query)){
				$Tiendas['LOGO'] = $row['logo_url'];
				$Tiendas['SLIDE1'] = $row['slideshow1_url'];
				$Tiendas['SLIDE2'] = $row['slideshow2_url'];
				$Tiendas['SLIDE3'] = $row['slideshow3_url'];
				$Tiendas['SLIDE4'] = $row['slideshow4_url'];
				$Tiendas['SLIDE5'] = $row['slideshow5_url'];
				$Tiendas['TIENDA1'] = $row['tienda1_url'];
				$Tiendas['TIENDA2'] = $row['tienda2_url'];
				$Tiendas['TIENDA3'] = $row['tienda3_url'];
				$Tiendas['TITULO1'] = $row['titulo1'];
				$Tiendas['TITULO2'] = $row['titulo2'];
				$Tiendas['TITULO3'] = $row['titulo3'];
				$Tiendas['DESCRIPCION1'] = $row['descripcion1'];
				$Tiendas['DESCRIPCION2'] = $row['descripcion2'];
				$Tiendas['DESCRIPCION3'] = $row['descripcion3'];
				$Tiendas['FORM'] = $row['descripcion_form'];
				$Tiendas['TELEFONO'] = $row['telefono'];
				$Tiendas['FACEBOOK'] = $row['url_facebook'];
				$Tiendas['TWITTER'] = $row['url_twitter'];
				$Tiendas['YOUTUBE'] = $row['url_youtube'];
			}
			return $Tiendas;
		}
		function BenBetesh($Idioma){
			$BenBetesh['IMAGEN'] = $BenBetesh['TITULO'] = $BenBetesh['HORARIO'] = $BenBetesh['TELEFONO'] = $BenBetesh['DIAS'] = null;
			$sql = "SELECT imagen_url,titulo,horario,telefono,dias FROM benbetesh WHERE id_idioma='".$Idioma."'";
			$i = 0;
			$query = mysql_query($sql);	
			while($row = mysql_fetch_assoc($query)){
				$BenBetesh['IMAGEN'][$i] = $row['imagen_url'];
				$BenBetesh['TITULO'][$i] = $row['titulo']; 
				$BenBetesh['HORARIO'][$i] = $row['horario']; 
				$BenBetesh['TELEFONO'][$i] = $row['telefono']; 
				$BenBetesh['DIAS'][$i] = $row['dias']; 
				$i++;
			}
			return $BenBetesh;
		}
		function Contacto($Idioma){
			$Contacto['TELEFONO'] = $Contacto['CORREO'] = null;
			$sql = "SELECT descripcion_form,telefono FROM marcas WHERE (amigable LIKE 'benbetesh' OR amigable LIKE 'ben-betesh') AND id_idioma='".$Idioma."' LIMIT 1";
			$query = mysql_query($sql);			
			while($row = mysql_fetch_assoc($query)){
				$Contacto['TELEFONO'] = $row['telefono'];
				$Contacto['CORREO'] = $row['descripcion_form'];
			}
			return $Contacto;
		}
		function Marcas($Idioma){
			$Marcas['LOGO'] = $Marcas['IMAGEN'] = $Marcas['AMIGABLE'] = null;
			$sql = "SELECT logo_url,imagen_url,amigable FROM marcas WHERE id_idioma='".$Idioma."' AND amigable NOT LIKE 'ben-betesh' AND amigable NOT LIKE 'benbetesh'";
			$query = mysql_query($sql);		
			$i = 0;
			while($row = mysql_fetch_assoc($query)){
				$Marcas['LOGO'][$i] = $row['logo_url'];
				$Marcas['IMAGEN'][$i] = $row['imagen_url'];
				$Marcas['AMIGABLE'][$i] = $row['amigable'];
				$i++;
			}
			return $Marcas;
		}
		function Formulario($Idioma,$Nombre,$Empresa,$Email,$Telefono,$Pais,$Mensaje){
			$sql = "INSERT INTO form_contacto (id_idioma,nombre,empresa,email,telefono,pais,mensaje,fecha_creacion) 
					VALUES ('".$Idioma."','".$Nombre."','".$Empresa."','".$Email."','".$Telefono."','".$Pais."','".$Mensaje."',CONCAT(CURDATE(),' ',CURTIME()))";
			$query = mysql_query($sql);	
		}
		function Solicitud($Idioma,$Nombre,$Telefono,$Email,$Archivo,$Mensaje){
			$sql = "INSERT INTO form_empleo (id_idioma,nombre,telefono,email,archivo,mensaje,fecha) 
					VALUES ('".$Idioma."','".$Nombre."','".$Telefono."','".$Email."','".$Archivo."','".$Mensaje."',CONCAT(CURDATE(),' ',CURTIME()))";
			$query = mysql_query($sql);	
		}
		function Nosotros($Idioma){
			$Nosotros = null;
			$sql = "SELECT imagen1_url,imagen2_url,titulo1,descripcion1,titulo2,descripcion2,titulo3,descripcion3 FROM nosotros WHERE id_idioma='".$Idioma."' LIMIT 1";
			$query = mysql_query($sql);		
			while($row = mysql_fetch_assoc($query)){
				$Nosotros['IMAGEN1'] = $row['imagen1_url'];
				$Nosotros['IMAGEN2'] = $row['imagen2_url'];
				$Nosotros['TITULO1'] = $row['titulo1'];
				$Nosotros['DESCRIPCION1'] = $row['descripcion1'];
				$Nosotros['TITULO2'] = $row['titulo2'];
				$Nosotros['DESCRIPCION2'] = $row['descripcion2'];
				$Nosotros['TITULO3'] = $row['titulo3'];
				$Nosotros['DESCRIPCION3'] = $row['descripcion3'];
			}
			return $Nosotros;
		}
		function SS_Nosotros($Idioma){
			$Slide['IMAGEN'] = $Slide['TITULO'] = $Slide['DESCRIPCION'] = null;
			$sql = "SELECT imagen_url,titulo,descripcion FROM ss_nosotros WHERE id_idioma='".$Idioma."'";
			$query = mysql_query($sql);	
			$i = 0;
			while($row = mysql_fetch_assoc($query)){
				$Slide['IMAGEN'][$i] = $row['imagen_url'];
				$Slide['TITULO'][$i] = $row['titulo'];
				$Slide['DESCRIPCION'][$i] = $row['descripcion'];
				$i++;
			}
			return $Slide;
		}
		function Noticias($Idioma,$limit){
			$Noticias['IMAGEN'] = $Noticias['TITULO'] = $Noticias['DESCRIPCION'] = $Noticias['AMIGABLE'] = null;
			$sql = "SELECT titulo,LEFT(descripcion,157) AS descripcion,imagen_url,amigable FROM noticias WHERE id_idioma='".$Idioma."' ORDER BY id_noticia DESC ".$limit;
			$query = mysql_query($sql);	
			$i = 0;
			while($row = mysql_fetch_assoc($query)){
				$Noticias['IMAGEN'][$i] = $row['imagen_url'];
				$Noticias['TITULO'][$i] = $row['titulo'];
				$Noticias['DESCRIPCION'][$i] = $this->truncate_this(strip_tags($row['descripcion']),'157');
				$Noticias['AMIGABLE'][$i] = $row['amigable'];
				$i++;
			}
			return $Noticias;
		}
		function Noticia($Idioma,$Nombre){
			$Noticia = null;
			$sql = "SELECT titulo,descripcion,imagen_url FROM noticias WHERE id_idioma='".$Idioma."' AND amigable LIKE '".$Nombre."'";
			$query = mysql_query($sql);	
			while($row = mysql_fetch_assoc($query)){
				$Noticia['IMAGEN'] = $row['imagen_url'];
				$Noticia['TITULO'] = $row['titulo'];
				$Noticia['DESCRIPCION'] = $row['descripcion'];
			}
			return $Noticia;
		}
		function Num_Noticias($Idioma){
			$cantidad = 0;
			$sql = "SELECT COUNT(*) AS cantidad FROM noticias WHERE id_idioma='".$Idioma."'";
			$query = mysql_query($sql);	
			while($row = mysql_fetch_assoc($query))
				$cantidad =  $row['cantidad'];
			return $cantidad;
		}
		function Empleo($Idioma){
			$Empleo = null;
			$sql = "SELECT titulo1,descripcion1,titulo2,descripcion2 FROM trabajo WHERE id_idioma='".$Idioma."' LIMIT 1";
			$query = mysql_query($sql);	
			while($row = mysql_fetch_assoc($query)){
				$Empleo['TITULO1'] = $row['titulo1'];
				$Empleo['DESCRIPCION1'] = $row['descripcion1'];
				$Empleo['TITULO2'] = $row['titulo2'];
				$Empleo['DESCRIPCION2'] = $row['descripcion2'];
			}
			return $Empleo;
		}
		function Buscador($Idioma,$Cadena){
			$Busqueda['MARCAS'] = $Busqueda['NOTICIAS'] = null;
			$i = 0;
			$sql = "SELECT amigable,nombre,imagen_url,LEFT(descripcion1,280) AS descripcion1 FROM marcas WHERE (nombre LIKE '%".mysql_real_escape_string($Cadena)."%'
					OR descripcion1 LIKE '%".mysql_real_escape_string($Cadena)."%' OR descripcion2 LIKE '%".mysql_real_escape_string($Cadena)."%'  
					OR descripcion3 LIKE '%".mysql_real_escape_string($Cadena)."%') AND id_idioma='".$Idioma."' AND amigable NOT LIKE 'ben-betesh' AND amigable NOT LIKE 'benbetesh'";
			$query = mysql_query($sql);	
			while($row = mysql_fetch_assoc($query)){
				$Busqueda['MARCAS']['AMIGABLE'][$i] = $row['amigable'];
				$Busqueda['MARCAS']['TITULO'][$i] = $row['nombre'];
				$Busqueda['MARCAS']['IMAGEN'][$i] = $row['imagen_url'];
				$Busqueda['MARCAS']['DESCRIPCION'][$i] = $this->truncate_this(strip_tags($row['descripcion1']),'280');
				$i++;
			}
			$i = 0;
			$sql = "SELECT amigable,titulo,imagen_url,titulo,LEFT(descripcion,280) AS descripcion FROM noticias WHERE (titulo LIKE '%".mysql_real_escape_string($Cadena)."%' 
					OR descripcion LIKE '%".mysql_real_escape_string($Cadena)."%') AND id_idioma='".$Idioma."' AND amigable NOT LIKE 'ben-betesh' AND amigable NOT LIKE 'benbetesh' 
					ORDER BY id_noticia DESC";
			$query = mysql_query($sql);	
			while($row = mysql_fetch_assoc($query)){
				$Busqueda['NOTICIAS']['AMIGABLE'][$i] = $row['amigable'];
				$Busqueda['NOTICIAS']['TITULO'][$i] = $row['titulo'];
				$Busqueda['NOTICIAS']['IMAGEN'][$i] = $row['imagen_url'];
				$Busqueda['NOTICIAS']['DESCRIPCION'][$i] = $this->truncate_this(strip_tags($row['descripcion']),'280');
				$i++;
			}
			return $Busqueda;
		}
	}
?>