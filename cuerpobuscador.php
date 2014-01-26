<?php
	$Consulta = $_POST['consulta'];
	include_once('sqlqueries.php');
	$Queries = new Queries;
	$Busqueda = $Queries->Buscador($_SESSION['idioma'],$Consulta);
	$Conexion = $Queries->Desconectar();
	$Pagina['1'] = Array(0 => 'RESULTADOS DE LA B&Uacute;SQUEDA', 1 => 'Marcas', 2 => 'Noticias', 3 => '>> M&aacute;s', 4 => 'NO SE HAN ENCONTRADO RESULTADOS PARA TU B&Uacute;SQUEDA');
	$Pagina['2'] = Array(0 => 'SEARCH RESULTS', 1 => 'Brands', 2 => 'News', 3 => '>> More', 4 => 'NO RESULTS FOUND FOR YOUR SEARCH');
?>
<link rel="stylesheet" href="estilos/cuerpobuscador.css" type="text/css">
<link type="text/css" href="js/jscrollpanel/themes/lozenge/style/jquery.jscrollpane.lozenge.css" rel="stylesheet">
<!-- styles needed by jScrollPane - include in your own sites -->
<link type="text/css" href="js/jscrollpanel/style/jquery.jscrollpane.css" rel="stylesheet" media="all" />
<style type="text/css" id="page-css">
	/* Styles specific to this particular page */
	.scroll-pane,
	.scroll-pane-arrows
	{
		width: 100%;
		height: 1000px;
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
<!-- the mousewheel plugin - optional to provide mousewheel support -->
<script type="text/javascript" src="js/jscrollpanel/script/jquery.mousewheel.js"></script>
<!-- the jScrollPane script -->
<script type="text/javascript" src="js/jscrollpanel/script/jquery.jscrollpane.min.js"></script>

<script type="text/javascript" src="js/jscrollpanel/script/jquery.mousewheel.js"></script>
<!-- the jScrollPane script -->
<script type="text/javascript" src="js/jscrollpanel/script/jquery.jscrollpane.min.js"></script>
<script type="text/javascript">
	$(function(){
		$('.scroll-pane').jScrollPane({
			autoReinitialise: true
		});
	});
</script>
<div class="resultado scroll-pane">
	<div class="titulo">
	<?php 
		if((!empty($Busqueda['MARCAS']['AMIGABLE'][0]))||(!empty($Busqueda['NOTICIAS']['AMIGABLE'][0])))
			print $Pagina[$_SESSION['idioma']][0]; 
		else
			print $Pagina[$_SESSION['idioma']][4]; 
	?>
	</div>
	<?php if(!empty($Busqueda['MARCAS']['AMIGABLE'][0])){ ?>
	<div class="marcas">
		<div class="titulomarcas"><?php print $Pagina[$_SESSION['idioma']][1]; ?></div>
		<div class="contenidomarcas">
		<?php
			for($i=0;$i<count($Busqueda['MARCAS']['AMIGABLE']);$i++){
				print '<div class="marca">';
					print '<div class="imagen"><img src="/subidas/'.$Busqueda['MARCAS']['IMAGEN'][$i].'" width="165px" height="115px"></div>';
					print '<div class="resumen">';
						print '<div class="ti">'.$Busqueda['MARCAS']['TITULO'][$i].'</div>';
						print '<div class="co">'.$Busqueda['MARCAS']['DESCRIPCION'][$i].'</div>';
						print '<div class="ma"><a class="mas" href="/tienda/'.$Busqueda['MARCAS']['AMIGABLE'][$i].'/">'.$Pagina[$_SESSION['idioma']][3].'</a></div>';
					print '</div>';
				print '</div>';
			}
		?>
		</div>
	</div>
	<?php
	}
	if(!empty($Busqueda['NOTICIAS']['AMIGABLE'][0])){
	?>
	<div class="noticias">
		<div class="titulonoticias"><?php print $Pagina[$_SESSION['idioma']][2]; ?></div>
		<div class="contenidonoticias">
		<?php
			for($i=0;$i<count($Busqueda['NOTICIAS']['AMIGABLE']);$i++){
				print '<div class="marca">';
					print '<div class="imagen"><img src="/subidas/'.$Busqueda['NOTICIAS']['IMAGEN'][$i].'" width="165px" height="115px"></div>';
					print '<div class="resumen">';
						print '<div class="ti">'.$Busqueda['NOTICIAS']['TITULO'][$i].'</div>';
						print '<div class="co">'.$Busqueda['NOTICIAS']['DESCRIPCION'][$i].'</div>';
						print '<div class="ma"><a class="mas" href="/noticia/'.$Busqueda['NOTICIAS']['AMIGABLE'][$i].'/">'.$Pagina[$_SESSION['idioma']][3].'</a></div>';
					print '</div>';
				print '</div>';
			}
		?>
		</div>
		<div class="espacio"></div>
	</div>
	<?php }
	else
		print '<br>';
	?>
</div>