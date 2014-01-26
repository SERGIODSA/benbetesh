<?php
	$Formulario['1'] = Array(0 => 'ENV&Iacute;ANOS TU HOJA DE VIDA AQUI', 1 => 'Nombre: ', 2 => 'Tel&eacute;fono: ', 3 => 'E-mail: ', 4 => 'Ingresa tu hoja de vida en formato PDF o WORD' , 5 => 'SUBIR', 6 => 'MENSAJE:', 7 => 'ENVIAR');
	$Formulario['2'] = Array(0 => 'SEND YOUR RESUME HERE', 1 => 'Name: ', 2 => 'Phone: ', 3 => 'E-mail: ', 4 => 'Enter your resume in PDF or WORD format' , 5 => 'UPLOAD', 6 => 'MESSAGE:', 7 => 'SEND');
	include_once('sqlqueries.php');
	$Queries = new Queries;
	$Contacto = $Queries->Contacto($_SESSION['idioma']);
	$Empleo = $Queries->Empleo($_SESSION['idioma']);
	$Conexion = $Queries->Desconectar();
?>
<link rel="stylesheet" href="estilos/cuerpotrabajo.css" type="text/css">
<link rel="stylesheet" href="estilos/fileinput/bootstrap-combined.min.css" type="text/css">
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript" src="estilos/fileinput/bootstrap.file-input.js"></script>
<script type="text/javascript" src="estilos/fileinput/fileinput.js"></script>
<script src="js/jquery-validator/dist/jquery.validate.js" type="text/javascript"></script>
<script src="js/jquery-validator/dist/additional-methods.js" type="text/javascript"></script>
<script>
	var idioma = <?php print($_SESSION['idioma']); ?>;
	if(idioma === 1){
		var nombre1 = "<br>Campo obligatorio";
		var nombre2 = "<br>Maximo 45 caracteres";
		var email1 = "<br>Campo obligatorio";
		var email2 = "<br>Email invalido";
		var email3 = "<br>Maximo 30 caracteres";
		var archivo1= "<br>Campo obligatorio";
		var telefono1 = "<br>Campo obligatorio";
		var telefono2 = "<br>Maximo 20 caracteres";
		var mensaje1 = "<br>Campo obligatorio";
		var mensaje2 = "<br>Maximo 65000 caracteres";
	}
	else{
		var nombre1 = "<br>Required field";
		var nombre2 = "<br>Max 45 characters";
		var email1 = "<br>Required field";
		var email2 = "<br>Invalid email";
		var email3 = "<br>Max 30 characters";
		var archivo1= "<br>Required field";
		var telefono1 = "<br>Required field";
		var telefono2 = "<br>Max 20 characters";
		var mensaje1 = "<br>Required field";
		var mensaje2 = "<br>Max 65000 characters";
	}
	$(document).ready(function(){
		$('.archivo').bootstrapFileInput();
		$("#contacto").validate({
			rules: {
				nombre: { 
					required: true,
					maxlength: 45
				},
				email: { 
					required: true, 
					email: true,
					maxlength: 30
				},
				archivo: {
					required: true
				},
				telefono: { 
					required: true, 
					maxlength: 20
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
				email: {
					required: email1,
					email: email2,
					maxlength: email3
				},
				archivo: {
					required: archivo1
				},
				telefono: {
					required: telefono1,
					maxlength: telefono2
				},
				mensaje: {
					required: mensaje1,
					maxlength: mensaje2
				}
			},
		});
	});
</script>
<div class="contenido">
	<div>
		<div class="descripcion">
			<div class="notisubtitulo"><?php print $Empleo['TITULO1']; ?></div>
			<div class="notitexto"><?php print $Empleo['DESCRIPCION1']; ?></div>
			<div class="notisubtitulo"><?php print $Empleo['TITULO2']; ?></div>
			<div class="notitexto"><?php print $Empleo['DESCRIPCION2']; ?></div>
		</div>
		<div class="formulario">
			<div class="formempleo">
				<form name="contacto" id="contacto" action="empleo.php" enctype="multipart/form-data" method="post">
					<div class="empleo">
						<div class="tituloform">
							<?php 
								print '<span class="sizetit">'.$Formulario[$_SESSION['idioma']][0].'</span>';
								print '<br>';
								print $Formulario[$_SESSION['idioma']][2];
								print $Contacto['TELEFONO'];
								print '<br>';
								print $Contacto['CORREO'];
							?>
						</div>
						<div>
							<div class="formizq"><?php print($Formulario[$_SESSION['idioma']][1]); ?></div>
							<div class="formder"><input type="text" class="sizeinput" name="nombre" id="nombre"></div>
						</div>
						<div>
							<div class="formizq"><?php print($Formulario[$_SESSION['idioma']][2]); ?></div>
							<div class="formder"><input type="text" class="sizeinput" name="telefono" id="telefono"></div>
						</div>
						<div>
							<div class="formizq"><?php print($Formulario[$_SESSION['idioma']][3]); ?></div>
							<div class="formder"><input type="text" class="sizeinput" name="email" id="email"></div>
						</div>
						<div class="fileup"><?php print($Formulario[$_SESSION['idioma']][4]); ?></div>
						<div class="filedown"><input type="file" class="archivo" name="archivo" id="archivo" title="<?php print($Formulario[$_SESSION['idioma']][5]); ?>"></div>
						<div class="formup"><?php print $Formulario[$_SESSION['idioma']][6]; ?></div>
						<div class="formdown"><textarea id="mensaje" name="mensaje"></textarea></div>
					</div>
					<div>
						<div class="info"></div>
						<div class="boton"><input type="submit" class="btn envio" value="<?php print($Formulario[$_SESSION['idioma']][7]); ?>"></input></div>
					</div>					
				</form>
			</div>
		</div>
	</div>
	<div class="submit"></div>
	<div class="divautoajustable"></div>
</div>
<?php
	if(empty($_SESSION['MENSAJE']))
		$_SESSION['MENSAJE'] = 0;
	else{
		echo '<script language="JavaScript">alert("'.$_SESSION['MENSAJE'].'");</script>';
		$_SESSION['MENSAJE'] = 0;
	}
?>