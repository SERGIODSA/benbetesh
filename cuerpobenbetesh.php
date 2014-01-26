<?php
	include_once('sqlqueries.php');
	$Queries = new Queries;
	$Tiendas = $Queries->Tiendas(($_SESSION['idioma']),'ben-betesh');
	$BenBetesh = $Queries->BenBetesh($_SESSION['idioma']);
	$Contacto = $Queries->Contacto($_SESSION['idioma']);
	$Conexion = $Queries->Desconectar();
	$Pagina['1'] = Array( 0 => 'UBICACI&Oacute;N DE NUESTRAS TIENDAS', 1 => 'Para mayor informaci&oacute;n sobre c&oacute;mo vender sus marcas en nuestras tiendas: ', 2 => 'Tel&eacute;fonos: ');
	$Pagina['2'] = Array( 0 => 'LOCATION OF OUR STORES', 1 => 'For more information about how to sell your brands in our stores: ', 2 => 'Phones: ');
?>
<link rel="stylesheet" href="estilos/cuerpobenbetesh.css" type="text/css">
<link rel="stylesheet" type="text/css" href="js/jcarousel-0.3.0/index/jcarousel.basic.css">
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript" src="js/jcarousel-0.3.0/dist/jquery.jcarousel.js"></script>
<script type="text/javascript" src="js/jcarousel-0.3.0/index/jcarousel.basic.js"></script>
<!-- styles needed by jScrollPane -->
<link type="text/css" href="js/jscrollpanel/style/jquery.jscrollpane.css" rel="stylesheet" media="all" />
<!-- the mousewheel plugin - optional to provide mousewheel support -->
<script type="text/javascript" src="js/jscrollpanel/script/jquery.mousewheel.js"></script>
<!-- the jScrollPane script -->
<script type="text/javascript" src="js/jscrollpanel/script/jquery.jscrollpane.min.js"></script>
<link type="text/css" href="js/jscrollpanel/themes/lozenge/style/jquery.jscrollpane.lozenge.css" rel="stylesheet">
<!-- styles needed by jScrollPane - include in your own sites -->
<link type="text/css" href="js/jscrollpanel/style/jquery.jscrollpane.css" rel="stylesheet" media="all" />
<!-- the styles for the lozenge theme -->
<style type="text/css" id="page-css">
	/* Styles specific to this particular page */
	.scroll-pane,
	.scroll-pane-arrows
	{
		width: 100%;
		height: 370px;
		overflow: auto;
	}
	.scroll-pane2,
	.scroll-pane-arrows2
	{
		width: 100%;
		height: 424px;
		overflow: auto;
	}
	.horizontal-only
	{
		height: auto;
		max-height: 200px;
	}
</style>
<!-- the mousewheel plugin -->
<script type="text/javascript" src="js/jscrollpanel/script/jquery.mousewheel.js"></script>
<!-- the jScrollPane script -->
<script type="text/javascript" src="js/jscrollpanel/script/jquery.jscrollpane.min.js"></script>
<script>
	$(document).ready(function(){
		// Create the "tabs"
		$('.tabs').each(function(){
			var currentTab, ul = $(this);
			$(this).find('a').each(function(i){
				var a = $(this).bind('click',function(){
					if (currentTab) {
						ul.find('a.active').removeClass('active');
						$(currentTab).hide();
					}
					currentTab = $(this).addClass('active').attr('href');
					$(currentTab).show().jScrollPane({
						autoReinitialise: true
					});
					return false;
				});
				$(a.attr('href')).hide();
			});
		});
		
		$('.scroll-pane2').jScrollPane({
			autoReinitialise: true
		});
		
		$('#perfil').bind('click',function(){
			$("#perfil").css("color",'#036bc2');
			$("#marcas").css("color",'#8c8e8d');
			$("#tiendas").css("color",'#8c8e8d');
			return false;
		});
		$('#marcas').bind('click',function(){
			$("#perfil").css("color",'#8c8e8d');
			$("#marcas").css("color",'#036bc2');
			$("#tiendas").css("color",'#8c8e8d');
			return false;
		});
		$('#tiendas').bind('click',function(){
			$("#perfil").css("color",'#8c8e8d');
			$("#marcas").css("color",'#8c8e8d');
			$("#tiendas").css("color",'#036bc2');
			return false;
		});
		$('#perfil').trigger('click');
	});
</script>
<div class="benbeteshslideshow">
	<?php
		$c = 0;
		$s = 0;
		if(!empty($Tiendas['SLIDE1'])){
			$c++;
			$s = 1;
		}
		if(!empty($Tiendas['SLIDE2'])){
			$c++;
			$s = 2;
		}
		if(!empty($Tiendas['SLIDE3'])){
			$c++;
			$s = 3;
		}
		if(!empty($Tiendas['SLIDE4'])){
			$c++;
			$s = 4;
		}
		if(!empty($Tiendas['SLIDE5'])){
			$c++;
			$s = 5;
		}
		if($c==1)
			print '<img src="subidas/'.$Tiendas['SLIDE'.$s].'" width="1000px" height="611px" alt="">';
		else{
			print '
			<div class="jcarousel-wrapper">
				<div class="jcarousel">
					<ul>';
							if(!empty($Tiendas['SLIDE1']))
								print '<li><img src="subidas/'.$Tiendas['SLIDE1'].'" width="1000px" height="611px" alt=""></li>';
							if(!empty($Tiendas['SLIDE2']))
								print '<li><img src="subidas/'.$Tiendas['SLIDE2'].'" width="1000px" height="611px" alt=""></li>';
							if(!empty($Tiendas['SLIDE3']))
								print '<li><img src="subidas/'.$Tiendas['SLIDE3'].'" width="1000px" height="611px" alt=""></li>';
							if(!empty($Tiendas['SLIDE4']))
								print '<li><img src="subidas/'.$Tiendas['SLIDE4'].'" width="1000px" height="611px" alt=""></li>';
							if(!empty($Tiendas['SLIDE5']))
								print '<li><img src="subidas/'.$Tiendas['SLIDE5'].'" width="1000px" height="611px" alt=""></li>';
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
<div class="benbeteshmarcas">
	<img src="subidas/<?php print $Tiendas['LOGO']; ?>" width="373px" height="175px" class="smallimg">
	<img src="subidas/<?php print $Tiendas['TIENDA1']; ?>" width="209px" height="175px" class="smallimg">
	<img src="subidas/<?php print $Tiendas['TIENDA2']; ?>" width="209px" height="175px" class="smallimg">
	<img src="subidas/<?php print $Tiendas['TIENDA3']; ?>" width="209px" height="175px" class="smallimg">
</div>
<div class="benbeteshcontenido">
	<div class="benbeteshinfo">
		<div class="benbeteshinfotit">
			<ul class="tabs">
				<li><a href="#desc1" id="perfil"><?php print $Tiendas['TITULO1']; ?></a>&nbsp;&nbsp;/&nbsp;&nbsp;</li>
				<li><a href="#desc2" id="marcas"><?php print $Tiendas['TITULO2']; ?></a>&nbsp;&nbsp;/&nbsp;&nbsp;</li>
				<li><a href="#desc3" id="tiendas"><?php print $Tiendas['TITULO3']; ?></a></li>
			</ul>
		</div>
		<div class="benbeteshinfocuerpo">
			<div id="container">
				<div class="scroll-pane" id="desc1">
					<?php print '<p class="descripcion">'.$Tiendas['DESCRIPCION1'].'</p>'; ?>
				</div>
				<div class="scroll-pane" id="desc2">
					<?php print '<p class="descripcion">'.$Tiendas['DESCRIPCION2'].'</p>'; ?>
				</div>
				<div class="scroll-pane" id="desc3">
					<?php print '<p class="descripcion">'.$Tiendas['DESCRIPCION3'].'</p>'; ?>
				</div>
			</div>
		</div>
	</div>
	<div class="benbeteshubicacion">
		<div class="iub">
			<div class="scroll-pane2">
				<div class="iubcontenido iubcelda1">
				<?php print $Pagina[$_SESSION['idioma']]['0']; ?>
				</div>
				<?php
					for($i=0;$i<count($BenBetesh['TITULO']);$i++){
						print '
						<div class="iubcontenido iubcelda2">
							<div class="iubfotico"><img src="subidas/'.$BenBetesh['IMAGEN'][$i].'" width="114px" height="74px"></div>
							<div class="iubinfo">
								<div class="titulo">'.$BenBetesh['TITULO'][$i].'</div>
								<div class="texto">';
								(!empty($BenBetesh['DIAS'][$i])) ? print $BenBetesh['DIAS'][$i].'<br>':'';
								print $BenBetesh['HORARIO'][$i].'<br>'.$Pagina[$_SESSION['idioma']]['2'].' '.$BenBetesh['TELEFONO'][$i];
								print '
								</div>';
							print '
							</div>';
						print '
						</div>';
					}
				?>
				<div class="iubcontenido iubcelda2">
					<div class="iubmi"><?php print $Pagina[$_SESSION['idioma']]['1'].' '.$Contacto['CORREO'].'<br>'.$Pagina[$_SESSION['idioma']]['2'].' '.$Contacto['TELEFONO']; ?><br><br></div>
				</div>
			</div>
		</div>
	</div>
</div>