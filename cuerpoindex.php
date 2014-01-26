<?php
	include_once('sqlqueries.php');
	$Queries = new Queries;
	$Slideshow = $Queries->IndexSlideshow($_SESSION['idioma']);
	$Logos = $Queries->IndexMarcasLogos($_SESSION['idioma']);
	$Tiendas = $Queries->IndexMarcasTiendas($_SESSION['idioma']);
	$BenBetesh = $Queries->IndexBenbetesh($_SESSION['idioma']);
	$Nosotros = $Queries->IndexNosotros($_SESSION['idioma']);
	$Noticias = $Queries->IndexNoticias($_SESSION['idioma']);
	$Conexion = $Queries->Desconectar();
	// CONTENIDO DE LA PAGINA
	$Pagina[1] = Array(1 => 'MARCAS', 2 => 'TIENDAS BEN BETESH', 3 => '>> M&Aacute;S', 4 => 'NOSOTROS', 5 => 'TIENDAS DE MARCAS', 6 =>'NOTICIAS RECIENTES'); 
	$Pagina[2] = Array(1 => 'BRANDS', 2 => 'BEN BETESH STORES', 3 => '>> MORE', 4 => 'ABOUT US', 5 => 'BRAND STORES', 6 =>'RECENT NEWS'); 
?>
<link rel="stylesheet" href="estilos/cuerpoindex.css" type="text/css">
<link rel="stylesheet" type="text/css" href="js/jcarousel-0.3.0/index/jcarousel.basic.css">
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript" src="js/jcarousel-0.3.0/dist/jquery.jcarousel.js"></script>
<script type="text/javascript" src="js/jcarousel-0.3.0/index/jcarousel.basic.js"></script>
<div id="preload">
	<img src="<?php print($Slideshow[0]); ?>" width="1px" height="1px">
	<img src="<?php print($Slideshow[1]); ?>" width="1px" height="1px">
	<img src="<?php print($Slideshow[2]); ?>" width="1px" height="1px">
	<img src="<?php print($Slideshow[3]); ?>" width="1px" height="1px">
	<img src="<?php print($Slideshow[4]); ?>" width="1px" height="1px">
	<img src="<?php print($Nosotros['IMAGEN']); ?>" width="1px" height="1px">
	<img src="<?php print($BenBetesh); ?>" width="1px" height="1px">
	<?php
		for($i=0;$i<count($Logos['LOGO']);$i++){
			print '<img src="'.$Logos['LOGO'][$i].'" width="1px" height="1px"><br>';
		}
		for($i=0;$i<count($Noticias['IMAGEN']);$i++){ 
			print '<img src="'.$Noticias['IMAGEN'][$i].'" width="1px" height="1px"><br>';
		}
		for($i=0;$i<count($Tiendas['IMAGEN']);$i++){
			print '<img src="'.$Tiendas['IMAGEN'][$i].'" width="1px" height="1px"><br>';
		}
	?>
</div>
<div class="indexslideshow">
	<?php
		$c = 0;
		$s = 0;
		if(!empty($Slideshow[0])){
			$c++;
			$s = 0;
		}
		if(!empty($Slideshow[1])){
			$c++;
			$s = 1;
		}
		if(!empty($Slideshow[2])){
			$c++;
			$s = 2;
		}
		if(!empty($Slideshow[3])){
			$c++;
			$s = 3;
		}
		if(!empty($Slideshow[4])){
			$c++;
			$s = 4;
		}
		if($c==1)
			print '<img src="subidas/'.$Slideshow[$s].'" width="1000px" height="611px" alt="">';
		else{
			print '
			<div class="jcarousel-wrapper">
				<div class="jcarousel">
					<ul>';
							if(!empty($Slideshow[0]))
								print '<li><img src="subidas/'.$Slideshow[0].'" width="1000px" height="611px" alt=""></li>';
							if(!empty($Slideshow[1]))
								print '<li><img src="subidas/'.$Slideshow[1].'" width="1000px" height="611px" alt=""></li>';
							if(!empty($Slideshow[2]))
								print '<li><img src="subidas/'.$Slideshow[2].'" width="1000px" height="611px" alt=""></li>';
							if(!empty($Slideshow[3]))
								print '<li><img src="subidas/'.$Slideshow[3].'" width="1000px" height="611px" alt=""></li>';
							if(!empty($Slideshow[4]))
								print '<li><img src="subidas/'.$Slideshow[4].'" width="1000px" height="611px" alt=""></li>';
				print '
					</ul>
				</div>
				<a class="jcarousel-control-prev"><img src="imagenes/left.png"></a>
				<a class="jcarousel-control-next"><img src="imagenes/right.png"></a>
				<div class="raya1"></div>
				<p class="jcarousel-pagination"></p>
				<div class="raya2"></div>
			</div>';
		}
	?>
</div>
<div class="indexmarcas">
	<div class="jcarousel-wrapper2">
		<div class="jcarousel2">
			<ul>
				<?php
					$i = 0;
					for($i=0;$i<count($Logos['LOGO']);$i++){
						$cadena = "'http://".$_SERVER['HTTP_HOST']."/tienda/".$Logos['AMIGABLE'][$i]."/'";
						print '<li><img src="'.$Logos['LOGO'][$i].'" width="247px" height="120px" class="pointer" onclick="window.location='.$cadena.'"></li>';
					}
				?>
			</ul>
		</div>
		<a class="jcarousel-control-prev2"><img src="imagenes/left3.png"></a>
		<a class="jcarousel-control-next2"><img src="imagenes/right3.png"></a>
	</div>
</div>
<div class="indextitulo">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php print($Pagina[$_SESSION['idioma']][1]); ?></div>
<div class="indexcontenido">
	<div class="indexarticulo1">
		<div class="indexapcomun indexa1ancho indexa1a2alto1">
			<div class="im"><img src="<?php print($BenBetesh); ?>" width="338px" height="173px" onclick="window.location='<?php print('http://'.$_SERVER['HTTP_HOST']); ?>/benbetesh/'" class="pointer"></div>
			<div class="marco1tiendas"><span class="indexarticulo1titulo"><?php print($Pagina[$_SESSION['idioma']][2]); ?></span></div>
		</div>
		<div class="indexapcomun indexa1ancho indexa1a2alto3">
			<div class="bb1"><img src="<?php print($Nosotros['IMAGEN']); ?>" width="350px" height="200px"></div>
			<div class="bb2"><?php print($Nosotros['DESCRIPCION']); ?></div>
			<div <?php ($_SESSION['idioma']==1)? print 'class="bb3 indexvermasespa"':print 'class="bb3 indexvermasing"'; ?>><a href="<?php print('http://'.$_SERVER['HTTP_HOST']); ?>/nosotros/" class="indexenlace"><?php print($Pagina[$_SESSION['idioma']][3]); ?></a></div>
		</div>
		<div class="indexapcomun indexa1ancho indexa1a2alto4"><span class="indexarticulo1titulo"><?php print($Pagina[$_SESSION['idioma']][4]); ?></span></div>
	</div>
	<div class="indexarticulo2">
		<div class="indexapcomun indexa2ancho indexa1a2alto1">
			<div class="jcarousel-wrapper3">
				<div class="jcarousel3">
					<ul>
						<?php
						for($i=0;$i<count($Tiendas['IMAGEN']);$i++){
							$cadena = "'http://".$_SERVER['HTTP_HOST']."/tienda/".$Tiendas['AMIGABLE'][$i]."/'";
							print '<li><img src="'.$Tiendas['IMAGEN'][$i].'" width="187px" height="173px" onclick="window.location='.$cadena.'" class="pointer"></li>';
						}
						?>
					</ul>
				</div>
				<div class="marco2tiendas">
					<div <?php ($_SESSION['idioma']==1)? print 'class="tiendas"':print 'class="tiendasing"'; ?>><?php print($Pagina[$_SESSION['idioma']][5]); ?></div>
					<div <?php ($_SESSION['idioma']==1)? print 'class="raya3"':print 'class="raya5"'; ?>></div>
					<p class="jcarousel-pagination3"></p>
					<div class="raya4"></div>
				</div>
			</div>
		</div>
		<div class="indexapcomun indexa2ancho indexa1a2alto3">
			<?php 
			for($i=0;$i<count($Noticias['IMAGEN']);$i++){ 
			print '
				<div class="indexespacio2"></div>
				<div class="indexa2cont">
					<div class="indexa2img1"><img src="'.$Noticias['IMAGEN'][$i].'" width="190px" height="123px"></div>
					<div class="indexa2cont1">'.$Noticias['DESCRIPCION'][$i].'<br><div ';
					($_SESSION['idioma']==1)? print 'class="indexvermas indexvermasespa"':print 'class="indexvermas indexvermasing"';
				print '><a href="http://'.$_SERVER['HTTP_HOST'].'/noticia/'.$Noticias['AMIGABLE'][$i].'/" class="indexenlace">'.$Pagina[$_SESSION['idioma']][3].'</a></div></div>
				</div>';
			}
			?>
		</div>
		<div class="indexapcomun indexa2ancho indexa1a2alto4"><span class="indexarticulo2titulo"><?php print($Pagina[$_SESSION['idioma']][6]); ?></span></div>
	</div>
</div>