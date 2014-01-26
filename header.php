<?php
	include('sqlqueries.php');
	session_start();
	if(empty($_SESSION['idioma']))
		$_SESSION['idioma'] = 1;
	$Queries = new Queries;
	$Menu = $Queries->MenuPrincipal($_SESSION['idioma']);
	$Conexion = $Queries->Desconectar();
?>
<link rel="stylesheet" href="estilos/cabecera.css" type="text/css">
<link rel="icon" type="image/ico" href="imagenes/favicon.ico"/>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script>
	$(document).ready( function($){
		$('.language').click(function(){
			$.ajax({
				type: "POST",
				url: 'callback.php',
				data: {
					idioma: $(this).attr('id')
				},
				success: function(data){
					window.location.reload();
				}
			});
		});
		 /* Obtener el alto del contendor  del objeto a centrar. */
		var alto_canvas = $(".cabm").height();
		/* Obtener el valor del margen a aplicar.  */
		for(i=1;i<=7;i++){
			if(i==7)
				var alto_canvas = $(".cabm2").height();
			var margen_top = (alto_canvas - $('.cont'+i).height())/2;
			/* Aplicar el Margen */
			$('.cont'+i).css("top",margen_top);
		}
	});	
	function Busqueda(){
		var parametro = document.getElementById('consulta').value;
		if(parametro!=='')
			document.buscador.submit();
		else{
			document.buscador.consulta.focus() 
			return false;
		}
	}
</script>
<div class="header">
	<div class="cab1">
		<div class="cablogo"><a href="<?php echo 'http://'.$_SERVER['HTTP_HOST']; ?>/"><img class="logo" src="imagenes/logo.png"></a></div>
		<div class="sociales">
			<div class="cabsocial">
				<?php
					(!empty($Menu['FACEBOOK'])) ? print '<a href="'.$Menu['FACEBOOK'].'"><img src="imagenes/facebook_32.png" class="logos"></a>&nbsp;':'';
					(!empty($Menu['TWITTER'])) ? print '<a href="'.$Menu['TWITTER'].'"><img src="imagenes/twitter_32.png" class="logos"></a>&nbsp;':'';
					(!empty($Menu['YOUTUBE'])) ? print '<a href="'.$Menu['YOUTUBE'].'"><img src="imagenes/youtube_32.png" class="logos"></a>':'';
				?>
			</div>
			<div class="cabidiomas">
				<a href="javascript:void(0)" id="2" title="English" class="language">ENGLISH</a> / <a href="javascript:void(0)" id="1" title="Espa&ntilde;ol" class="language">ESPA&Ntilde;OL</a> 
			</div>
			<div class="cabbanderas">
				<img src="imagenes/USA.png"/>&nbsp;<img src="imagenes/Spain.png"/>
			</div>
		</div>
		<div class="buscar">
			<form name="buscador" method="post" action="/buscador/">
				<div class="cabbuscador"><input type="text" id="consulta" name="consulta"></div>
				<div class="cabboton"><img src="imagenes/busqueda.png" onclick="Busqueda();"></div>
			</form>
		</div>
	</div>
	<div class="cab3"></div>
	<div class="cab2">
		<div class="cabm"><div class="cont1"><a class="cablinks" href="<?php echo 'http://'.$_SERVER['HTTP_HOST']; ?>/"><?php print($Menu['DESCRIPCION'][0]); ?></a></div></div>
		<div class="cabm"><div class="cont2"><a class="cablinks" href="<?php echo 'http://'.$_SERVER['HTTP_HOST']; ?>/marcas/"><?php print($Menu['DESCRIPCION'][1]); ?></a></div></div>
		<div class="cabm"><div class="cont3"><a class="cablinks" href="<?php echo 'http://'.$_SERVER['HTTP_HOST']; ?>/benbetesh/"><?php print($Menu['DESCRIPCION'][2]); ?></a></div></div>
		<div class="cabm"><div class="cont4"><a class="cablinks" href="<?php echo 'http://'.$_SERVER['HTTP_HOST']; ?>/nosotros/"><?php print($Menu['DESCRIPCION'][3]); ?></a></div></div>
		<div class="cabm"><div class="cont5"><a class="cablinks" href="<?php echo 'http://'.$_SERVER['HTTP_HOST']; ?>/trabajo/"><?php print($Menu['DESCRIPCION'][4]); ?></a></div></div>
		<div class="cabm"><div class="cont6"><a class="cablinks" href="<?php echo 'http://'.$_SERVER['HTTP_HOST']; ?>/noticias/"><?php print($Menu['DESCRIPCION'][5]); ?></a></div></div>
		<div class="cabm2"><div class="cont7"><a class="cablinks" href="<?php echo 'http://'.$_SERVER['HTTP_HOST']; ?>/contactenos/"><?php print($Menu['DESCRIPCION'][6]); ?></a></div></div>
	</div>
</div>