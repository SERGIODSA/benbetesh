<?php
	$id = empty($_GET['id'])?'':$_GET['id'];
	include_once('sqlqueries.php');
	$Queries = new Queries;
	$Tiendas = $Queries->Tiendas(($_SESSION['idioma']),$id);
	$Conexion = $Queries->Desconectar();
	$Pagina['1'] = Array( 0 => 'PARA MAYOR INFORMACI&Oacute;N<br>SOBRE LA MARCA ', 1 => 'Contactar v&iacute;a formulario', 2 => 'Tel&eacute;fono: ', 3 => 'S&iacute;guenos en: ');
	$Pagina['2'] = Array( 0 => 'FOR MORE INFO ABOUT<br>THE BRAND', 1 => 'Contact via form', 2 => 'Phone: ', 3 => 'Follow us: ');
	$Formulario['1'] = Array(0 => 'Nombre: ', 1 => 'Empresa: ', 2 => 'E-mail: ', 3 => 'Tel&eacute;fono: ', 4 => 'Pa&iacute;s: ', 5 => 'Mensaje: ', 6 => 'ENVIAR');
	$Formulario['2'] = Array(0 => 'Name: ', 1 => 'Business: ', 2 => 'E-mail: ', 3 => 'Phone: ', 4 => 'Country: ', 5 => 'Message: ', 6 => 'SEND');
?>
<link rel="stylesheet" href="estilos/cuerpotienda.css" type="text/css">
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
<!-- the mousewheel plugin -->
<script type="text/javascript" src="js/jscrollpanel/script/jquery.mousewheel.js"></script>
<!-- the jScrollPane script -->
<script type="text/javascript" src="js/jscrollpanel/script/jquery.jscrollpane.min.js"></script>
<script src="js/jquery-validator/dist/jquery.validate.js" type="text/javascript"></script>
<style type="text/css" id="page-css">
	/* Styles specific to this particular page */
	.scroll-pane,
	.scroll-pane-arrows
	{
		width: 100%;
		height: 340px;
		overflow: auto;
	}
	.horizontal-only
	{
		height: auto;
		max-height: 200px;
	}
</style>
<script>
	var idioma = <?php print($_SESSION['idioma']); ?>;
	if(idioma === 1){
		var nombre1 = "<br>Campo obligatorio";
		var nombre2 = "<br>Maximo 45 caracteres";
		var empresa1 = "<br>Campo obligatorio";
		var empresa2 = "<br>Maximo 45 caracteres";
		var email1 = "<br>Campo obligatorio";
		var email2 = "<br>Email invalido";
		var email3 = "<br>Maximo 30 caracteres";
		var telefono1 = "<br>Campo obligatorio";
		var telefono2 = "<br>Maximo 20 caracteres";
		var pais1 = "<br>Campo obligatorio";
		var pais2 = "<br>Maximo 45 caracteres";
		var mensaje1 = "<br>Campo obligatorio";
		var mensaje2 = "<br>Maximo 65000 caracteres";
		var exito = "Formulario enviado con exito";
	}
	else{
		var nombre1 = "<br>Required field";
		var nombre2 = "<br>Max 45 characters";
		var empresa1 = "<br>Required field";
		var empresa2 = "<br>Max 45 characters";
		var email1 = "<br>Required field";
		var email2 = "<br>Invalid email";
		var email3 = "<br>Max 30 characters";
		var telefono1 = "<br>Required field";
		var telefono2 = "<br>Max 20 characters";
		var pais1 = "<br>Required field";
		var pais2 = "<br>Max 45 characters";
		var mensaje1 = "<br>Required field";
		var mensaje2 = "<br>Max 65000 characters";
		var exito = "form sent successfully";
	}
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
		
		$('#perfil').bind('click',function(){
			$("#perfil").css("color",'#036bc2');
			$("#historia").css("color",'#8c8e8d');
			$("#tiendas").css("color",'#8c8e8d');
			return false;
		});
		$('#historia').bind('click',function(){
			$("#perfil").css("color",'#8c8e8d');
			$("#historia").css("color",'#036bc2');
			$("#tiendas").css("color",'#8c8e8d');
			return false;
		});
		$('#tiendas').bind('click',function(){
			$("#perfil").css("color",'#8c8e8d');
			$("#historia").css("color",'#8c8e8d');
			$("#tiendas").css("color",'#036bc2');
			return false;
		});
		$('#perfil').trigger('click');
		
		$("#contacto").validate({
			rules: {
				nombre: { 
					required: true,
					maxlength: 45
				},
				empresa: { 
					required: true, 
					maxlength: 45,
				},
				email: { 
					required: true, 
					email: true,
					maxlength: 30
				},
				telefono: { 
					required: true, 
					maxlength: 20
				},
				pais: { 
					required: true,
					maxlength: 45
				},
				mensaje: { 
					required:true, 
					maxlength: 65000
				}
			},
			messages: {
				nombre: { 
					required: nombre1,
					maxlength: nombre2
				},
				empresa: {
					required: empresa1,
					maxlength: empresa2
				},
				email: {
					required: email1,
					email: email2,
					maxlength: email3
				},
				telefono: {
					required: telefono1,
					maxlength: telefono2
				},
				pais: {
					required: pais1,
					maxlength: pais2
				},
				mensaje: {
					required: mensaje1,
					maxlength: mensaje2
				}
			},
			submitHandler: function(form){
				var dataString = 'nombre='+$('#nombre').val()+'&empresa='+$('#empresa').val()+'&email='+$('#email').val()+'&telefono='+$('#telefono').val()+'&pais='+$('#pais').val()+'&mensaje='+$('#mensaje').val();
				$.ajax({
					type: "POST",
					url:"contacto.php",
					data: dataString,
					success: function(data){
						alert(exito);
						document.contacto.reset();
					}
				});
			}
		});	
	});
</script>
<div class="tiendaslideshow">
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
<div class="tiendamarcas">
	<img src="subidas/<?php print $Tiendas['LOGO']; ?>" width="373px" height="175px" class="smallimg">
	<img src="subidas/<?php print $Tiendas['TIENDA1']; ?>" width="209px" height="175px" class="smallimg">
	<img src="subidas/<?php print $Tiendas['TIENDA2']; ?>" width="209px" height="175px" class="smallimg">
	<img src="subidas/<?php print $Tiendas['TIENDA3']; ?>" width="209px" height="175px" class="smallimg">
</div>
<div class="tiendacontenido">
	<div class="tiendainfo">
		<div class="tiendainfotit">
			<ul class="tabs">
			<li><a href="#desc1" id="perfil"><?php print $Tiendas['TITULO1']; ?></a>&nbsp;&nbsp;/&nbsp;&nbsp;</li>
			<li><a href="#desc2" id="historia"><?php print $Tiendas['TITULO2']; ?></a>&nbsp;&nbsp;/&nbsp;&nbsp;</li>
			<li><a href="#desc3" id="tiendas"><?php print $Tiendas['TITULO3']; ?></a></li>
			</ul>
		</div>
		<div class="tiendainfocuerpo">
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
		<div class="tiendasiguenos">
			<div class="ts1">
			<?php 
				((!empty($Tiendas['FACEBOOK']))||(!empty($Tiendas['TWITTER']))||(!empty($Tiendas['YOUTUBE']))) ? print($Pagina[$_SESSION['idioma']][3]).'&nbsp;&nbsp;':''; 
			?>
			</div>
			<div class="ts2">
			<?php
				(!empty($Tiendas['FACEBOOK'])) ? print '<a href="'.$Tiendas['FACEBOOK'].'"><img src="imagenes/facebook_32.png" class="logos"></a>&nbsp;':'';
				(!empty($Tiendas['TWITTER'])) ? print '<a href="'.$Tiendas['TWITTER'].'"><img src="imagenes/twitter_32.png" class="logos"></a>&nbsp;':'';
				(!empty($Tiendas['YOUTUBE'])) ? print '<a href="'.$Tiendas['YOUTUBE'].'"><img src="imagenes/youtube_32.png" class="logos"></a>':'';
			?>
			</div>
		</div>
	</div>
	<div class="tiendacontacto">
		<div class="tub">
			<div class="tubcelda1">
			<?php print($Pagina[$_SESSION['idioma']][0]); ?>
			</div>
			<div class="tubcelda2">
				<?php print $Tiendas['FORM']; ?><br>
				<?php print($Pagina[$_SESSION['idioma']][2]);  print ' '.$Tiendas['TELEFONO']; ?><br><br>
				<?php print($Pagina[$_SESSION['idioma']][1]); ?><br>
			</div>
			<div class="formcontacto">
				<form name="contacto" id="contacto" method="post">
					<div class="contactanos">
						<div>
							<div class="formizq"><?php print($Formulario[$_SESSION['idioma']][0]); ?></div>
							<div class="formder"><input type="text" class="sizeinput" name="nombre" id="nombre"></div>
						</div>
						<div>
							<div class="formizq"><?php print($Formulario[$_SESSION['idioma']][1]); ?></div>
							<div class="formder"><input type="text" class="sizeinput" name="empresa" id="empresa"></div>
						</div>
						<div>
							<div class="formizq"><?php print($Formulario[$_SESSION['idioma']][2]); ?></div>
							<div class="formder"><input type="text" class="sizeinput" name="email" id="email"></div>
						</div>
						<div>
							<div class="formizq"><?php print($Formulario[$_SESSION['idioma']][3]); ?></div>
							<div class="formder"><input type="text" class="sizeinput" name="telefono" id="telefono"></div>
						</div>
						<div>
							<div class="formizq"><?php print($Formulario[$_SESSION['idioma']][4]); ?></div>
							<div class="formder"><input type="text" class="sizeinput" name="pais" id="pais"></div>
						</div>
						<div>
							<div class="formizq"><?php print($Formulario[$_SESSION['idioma']][5]); ?></div>
							<div class="formder"><input type="text" class="sizeinput" name="mensaje" id="mensaje"></div>
						</div>
					</div>
					<div class="boton"><button type="submit" class="btn"><?php print($Formulario[$_SESSION['idioma']][6]); ?></button></div>	
				</form>
			</div>
		</div>
	</div>
</div>