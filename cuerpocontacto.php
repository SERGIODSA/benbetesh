<?php
	$Formulario['1'] = Array(0 => 'Nombre: ', 1 => 'Empresa: ', 2 => 'E-mail: ', 3 => 'Tel&eacute;fono: ', 4 => 'Pa&iacute;s: ', 5 => '&iquest;En qu&eacute; podemos ayudarle?', 6 => 'enviar');
	$Formulario['2'] = Array(0 => 'Name: ', 1 => 'Business: ', 2 => 'E-mail: ', 3 => 'Phone: ', 4 => 'Country: ', 5 => 'What can we help you?', 6 => 'send');
	$Pagina['1'] = Array(0 => 'OFICINAS REGIONALES', 1 => 'DIRECCI&Oacute;N: ', 2 => 'TEL&Eacute;FONOS: ', 3 => 'CONT&Aacute;CTENOS', 4 => 'PA&Iacute;SES CON DISTRIBUCI&Oacute;N:', 5 => 'PANAM&Aacute;, CENTROAM&Eacute;RICA, CARIBE,', 6 => 'M&Eacute;XICO, SURAM&Eacute;RICA', 7 => 'FAX: ');
	$Pagina['2'] = Array(0 => 'REGIONAL OFFICES', 1 => 'ADDRESS: ', 2 => 'PHONES: ', 3 => 'CONTACT US', 4 => 'COUNTRIES WITH DISTRIBUTION:', 5 => 'PANAMA, CENTRAL AMERICA, CARIBBEAN,', 6 => 'MEXICO, SOUTH AMERICA', 7 => 'FAX: ');
	include_once('sqlqueries.php');
	$Queries = new Queries;
	$Oficinas = $Queries->Contactenos($_SESSION['idioma']);
	$Conexion = $Queries->Desconectar();
?>
<link rel="stylesheet" href="estilos/cuerpocontacto.css" type="text/css">
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script src="js/jquery-validator/dist/jquery.validate.js" type="text/javascript"></script>
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
<div class="contacto">
	<div class="oficinas">
		<div class="contitulo1"><span class="titulo"><?php print $Pagina[$_SESSION['idioma']][0]; ?></span></div>
		<div class="cond">
			<?php
				for($i=0;$i<count($Oficinas['DIRECCION']);$i++){
					if($i%2==0)
						print '<div class="izquierda">';
					else
						print '<div class="derecha">';
					print '<b>'.$Oficinas['TITULO'][$i].'</b><br>';
					if(!empty($Oficinas['TIENDA'][$i]))
						print $Oficinas['TIENDA'][$i].'<br>';
					print $Pagina[$_SESSION['idioma']][1].$Oficinas['DIRECCION'][$i].'<br>';
					print $Pagina[$_SESSION['idioma']][2].$Oficinas['TELEFONO'][$i].'<br>';
					if(!empty($Oficinas['FAX'][$i]))
						print $Pagina[$_SESSION['idioma']][7].$Oficinas['FAX'][$i].'<br>';
					if(!empty($Oficinas['CORREO'][$i]))
						print $Oficinas['CORREO'][$i];
					print '</div>';
				}
			?>
		</div>
	</div>
	<div class="contactenos">
		<div class="subtitulo">
			<?php print $Pagina[$_SESSION['idioma']][3]; ?>
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
					<div class="formup"><?php print $Formulario[$_SESSION['idioma']][5]; ?></div>
					<div class="formdown"><textarea class="ayuda" id="mensaje" name="mensaje"></textarea></div>
				</div>
				<div class="boton"><button type="submit" class="btn"><?php print($Formulario[$_SESSION['idioma']][6]); ?></button></div>	
			</form>
		</div>
	</div>
	<div class="paises">
		<?php print $Pagina[$_SESSION['idioma']][4]; ?><br>
		<?php print $Pagina[$_SESSION['idioma']][5]; ?><br>
		<?php print $Pagina[$_SESSION['idioma']][6]; ?>
	</div>
</div>