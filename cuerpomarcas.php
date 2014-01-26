<?php
	include_once('sqlqueries.php');
	$Queries = new Queries;
	$Pie = $Queries->Pie($_SESSION['idioma']);
	$Marcas = $Queries->Marcas($_SESSION['idioma']);
	$Conexion = $Queries->Desconectar();
	$Pagina[1] = Array( 1 => 'MARCAS', 2 => '>> M&Aacute;S');
	$Pagina[2] = Array( 1 => 'BRANDS', 2 => '>> MORE');
?>
<link rel="stylesheet" href="estilos/cuerpomarcas.css" type="text/css">
<div class="marcastitulo"><?php print $Pagina[$_SESSION['idioma']][1]; ?></div>
<div class="marcascontenido"><br>
	<?php
		$c = 0;
		for($i=0;$i<count($Marcas['LOGO']);$i++){
			if($c==0)
				print '<div>';
			$c++;
			if($i%2==0)
				print '<div class="marcastiendasizq">';
			else
				print '<div class="marcastiendasder">';
			print '
				<div class="marcasimagen"><img src="subidas/'.$Marcas['IMAGEN'][$i].'" width="383px" height="212px"></div>
				<div class="marcasminiimagen"><img src="subidas/'.$Marcas['LOGO'][$i].'" width="133px" height="65px"></div>';
			if($i<4){	
				print '<div ';
				($_SESSION['idioma']==1)? print 'class="marcasenlacespa"':print 'class="marcasenlaceing"';
				print '><a href="http://'.$_SERVER['HTTP_HOST'].'/tienda/'.$Marcas['AMIGABLE'][$i].'/" class="menlace1">'.$Pagina[$_SESSION['idioma']][2].'</a></div>';
			}
			else{
				print '<div '; 
				($_SESSION['idioma']==1)? print 'class="marcasenlacespa"':print 'class="marcasenlaceing"';
				print '><a href="http://'.$_SERVER['HTTP_HOST'].'/tienda/'.$Marcas['AMIGABLE'][$i].'/" class="menlace2">'.$Pagina[$_SESSION['idioma']][2].'</a></div>';
			}
			print '</div>';
			if($c==2){
				$c = 0;
				print '</div>';
			}
		}
	?>
	<div class="divautoajustable"><div>
</div>
