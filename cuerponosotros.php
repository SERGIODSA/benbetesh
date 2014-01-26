<?php
	include_once('sqlqueries.php');
	$Queries = new Queries;
	$Nosotros = $Queries->Nosotros($_SESSION['idioma']);
	$Slide = $Queries->SS_Nosotros($_SESSION['idioma']);
	$Conexion = $Queries->Desconectar();
?>
<link rel="stylesheet" href="estilos/cuerponosotros.css" type="text/css">
<link rel="stylesheet" href="estilos/slideshownosotros.css" type="text/css" media="screen">
<link type="text/css" href="js/jscrollpanel/themes/lozenge/style/jquery.jscrollpane.lozenge.css" rel="stylesheet">
<!-- styles needed by jScrollPane - include in your own sites -->
<link type="text/css" href="js/jscrollpanel/style/jquery.jscrollpane.css" rel="stylesheet" media="all" />
<style type="text/css" id="page-css">
	/* Styles specific to this particular page */
	.scroll-pane,
	.scroll-pane-arrows
	{
		width: 100%;
		height: 458px;
		overflow: auto;
	}
	.horizontal-only
	{
		height: auto;
		max-height: 100px;
	}
</style>
<!-- the mousewheel plugin -->
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script src="js/slidesjs/slides.jquery.js"></script>
<!-- the mousewheel plugin - optional to provide mousewheel support -->
<script type="text/javascript" src="js/jscrollpanel/script/jquery.mousewheel.js"></script>
<!-- the jScrollPane script -->
<script type="text/javascript" src="js/jscrollpanel/script/jquery.jscrollpane.min.js"></script>
<script type="text/javascript">
	$(function(){
		$('.scroll-pane').jScrollPane({
			autoReinitialise: true
		});
		// Set starting slide to 1
		var startSlide = 1;
		// Get slide number if it exists
		if (window.location.hash) {
			startSlide = window.location.hash.replace('#','');
		}
		// Initialize Slides
		$('#slides').slides({
			preload: true,
			generatePagination: true,
			play: 5000,
			pause: 2500,
			hoverPause: true,
			effect: 'fade',
			// Get the starting slide
			start: startSlide,
			animationComplete: function(current){
				// Set the slide number as a hash
				//window.location.hash = '#' + current;
			}
		});
	});
</script>
<div class="nosotros">
	<div class="somos">
		<div class="quienessomos">
			<div class="nosotrostitulo"><?php print($Nosotros['TITULO1']); ?></div>
			<div class="nosotroscontenido">
				<div class="scroll-pane"><?php print($Nosotros['DESCRIPCION1']); ?></div>
			</div>
			
		</div>
		<div class="fotossomos">
			<img src="subidas/<?php print($Nosotros['IMAGEN1']); ?>" width="314px" height="210px"><br><br><br>
			<img src="subidas/<?php print($Nosotros['IMAGEN2']); ?>" width="314px" height="210px">
		</div>
	</div>
	<div class="misvis">
		<div class="mision">
			<h2><?php print($Nosotros['TITULO2']); ?></h2>
			<p><?php print($Nosotros['DESCRIPCION2']); ?></p>
		</div>
		<div class="vision">
			<h2><?php print($Nosotros['TITULO3']); ?></h2>
			<p><?php print($Nosotros['DESCRIPCION3']); ?></p>
		</div>
	</div>
	<div id="example">
		<div id="slides">
			<div class="slides_container">
			<?php 
			for($i=0;$i<count($Slide['TITULO']);$i++){
				print '
				<div class="slide">
					<div class="valores">
						<div class="valorimagen">
							<img src="subidas/'.$Slide['IMAGEN'][$i].'" width="314px" height="250px">
						</div>
						<div class="valortexto">
							<div class="tituloslide">'.$Slide['TITULO'][$i].'</div>
							<div class="contslide">'.$Slide['DESCRIPCION'][$i].'</div>
						</div>
					</div>
				</div>';
			}
			?>
			</div>
			<img class="prev" src="imagenes/left2.png" width="24" height="43" alt="Arrow Prev">
			<img class="next" src="imagenes/right2.png" width="24" height="43" alt="Arrow Next">
		</div>
	</div>
</div>