<?php
	include_once('sqlqueries.php');
	$Queries = new Queries;
	$Pagina[1] = Array(1 => 'NOTICIAS', 2 => '>> M&Aacute;S', 3 => 'Anterior', 4 => 'Siguiente'); 
	$Pagina[2] = Array(1 => 'NEWS', 2 => '>> MORE', 3 => 'Previous', 4 => 'Next');
	$num_rows = $Queries->Num_Noticias($_SESSION['idioma']);
	// COMPRUEBO SI HICIERON CLICK EN ALGUNA PÁGINA, SI NO DIGO QUE ES LA PRIMERA PÁGINA
	(isset($_GET['id'])) ? $page = $_GET['id']:$page = 1;
	// ACA SE DECIDE CUANTOS RESULTADOS MOSTRAR POR PÁGINA
	$rows_per_page = 6;
	// CALCULO LA ULTIMA PÁGINA
	$lastpage = ceil($num_rows / $rows_per_page);
	// COMPRUEBO QUE EL VALOR DE LA PÁGINA SEA CORRECTO Y SI ES LA ULTIMA PÁGINA
	$page = (int)$page;
	($page > $lastpage) ? $page = $lastpage:'';
	($page < 1) ? $page=1:'';
	// CREO LA SENTENCIA LIMIT PARA AÑADIR A LA CONSULTA DEFINITIVA
	$limit= 'LIMIT '. ($page -1) * $rows_per_page . ',' .$rows_per_page;
	$Noticias = $Queries->Noticias($_SESSION['idioma'],$limit);
	$Conexion = $Queries->Desconectar();
?>
<link rel="stylesheet" href="estilos/cuerponoticias.css" type="text/css">
<link rel="stylesheet" href="estilos/paginacion.css" type="text/css">
<div class="noticias">
	<div class="notititulo">
		<?php print($Pagina[$_SESSION['idioma']][1]); ?>
	</div>
	<div class="noticontenido">
		<?php
		for($i=0;$i<count($Noticias['TITULO']);$i++){
			($i%2==0) ? print '<div class="notibloque">':'';
			print '<div class="notiarticulo">
				<div class="articulo">
					<div class="notifoto"><img src="subidas/'.$Noticias['IMAGEN'][$i].'" width="400px" height="272px"></div>
					<div class="notisubtitulo">'.$Noticias['TITULO'][$i].'</div>
					<div class="notitexto">'.$Noticias['DESCRIPCION'][$i].'</div>
					<div class="notimas"><a href="http://'.$_SERVER['HTTP_HOST'].'/noticia/'.$Noticias['AMIGABLE'][$i].'/" class="vermas">'.$Pagina[$_SESSION['idioma']][2].'</a></div>
				</div>
			</div>';
			($i%2==0) ? print '<div class="notidivision"><div class="notiraya"></div></div>':'';
			(($i%2==1)||(($i+1)==count($Noticias['TITULO']))) ? print '</div>':'';
		}
		//UNA VEZ Q MUESTRO LOS DATOS TENGO Q MOSTRAR EL BLOQUE DE PAGINACIÓN SIEMPRE Y CUANDO HAYA MÁS DE UNA PÁGINA
		if($num_rows != 0){
			$nextpage= $page +1;
			$prevpage= $page -1;
			print '<div class="paginacion"><ul id="pagination-clean" class="menu">';
			//SI ES LA PRIMERA PÁGINA DESHABILITO EL BOTON DE PREVIOUS, MUESTRO EL 1 COMO ACTIVO Y MUESTRO EL RESTO DE PÁGINAS
			if ($page == 1){
				print '<li class="previous-off">&laquo; '.$Pagina[$_SESSION['idioma']][3].'</li>';
				print '<li class="active">1</li>';
				for($i= $page+1; $i<= $lastpage ; $i++){
					print '<li><a href="http://'.$_SERVER['HTTP_HOST'].'/noticias/'.$i.'/">'.$i.'</a></li>';
				}
				// Y SI LA ULTIMA PÁGINA ES MAYOR QUE LA ACTUAL MUESTRO EL BOTON NEXT O LO DESHABILITO
				if($lastpage > $page){   
					print '<li class="next"><a href="http://'.$_SERVER['HTTP_HOST'].'/noticias/'.$nextpage.'/" >'.$Pagina[$_SESSION['idioma']][4].' &raquo;</a></li>';
				}
				else{
					print '<li class="next-off">'.$Pagina[$_SESSION['idioma']][4].' &raquo;</li>';
				}
			}
			// EN CAMBIO SI NO ESTAMOS EN LA PÁGINA UNO HABILITO EL BOTON DE PREVIOUS Y MUESTRO LAS DEMÁS
			else{
				print '<li class="previous"><a href="http://'.$_SERVER['HTTP_HOST'].'/noticias/'.$prevpage.'/">&laquo; '.$Pagina[$_SESSION['idioma']][3].'</a></li>';
				for($i= 1; $i<= $lastpage ; $i++){
                    // COMPRUEBO SI ES LA PÁGINA ACTIVA O NO
					if($page == $i){
						print '<li class="active">'.$i.'</li>';
					}
					else{
						print '<li><a href="http://'.$_SERVER['HTTP_HOST'].'/noticias/'.$i.'/" >'.$i.'</a></li>';
					}
				}
				// Y SI NO ES LA ÚLTIMA PÁGINA ACTIVO EL BOTON NEXT    
				if($lastpage > $page){
					print '<li class="next"><a href="http://'.$_SERVER['HTTP_HOST'].'/noticias/'.$nextpage.'/">'.$Pagina[$_SESSION['idioma']][4].' &raquo;</a></li>';
				}
				else{
					print '<li class="next-off">'.$Pagina[$_SESSION['idioma']][4].' &raquo;</li>';
				}
			}    
			print '</ul></div>';
			print '<div class="divautoajustable"></div>';
		}
		?>
	</div>
</div>