<?php
	include_once('sqlqueries.php');
	$Queries = new Queries;
	$Pie = $Queries->Pie($_SESSION['idioma']);
	$Pagina[1] = Array( 1 => 'Marcas', 2 => 'Tiendas', 3 => 'Corporativo', 4 => 'Cont&aacute;ctenos');
	$Pagina[2] = Array( 1 => 'Brands', 2 => 'Stores', 3 => 'Corporative', 4 => 'Contact Us');
	$Conexion = $Queries->Desconectar();
?>
<link rel="stylesheet" href="estilos/pie.css" type="text/css">
<div class="footer">
	<div class="piecontenido">
		<div class="piemarcas">
			<div class="margen">
				<span class="pietitulos"><?php print $Pagina[$_SESSION['idioma']][1]; ?></span><br>
				<?php
					for($i=0;$i<count($Pie['MARCAS']['NOMBRE']);$i++){
						print '<a href="http://'.$_SERVER['HTTP_HOST'].'/tienda/'.$Pie['MARCAS']['AMIGABLE'][$i].'/" class="language">'.ucwords(strtolower($Pie['MARCAS']['NOMBRE'][$i])).'</a>';
						if($i<(count($Pie['MARCAS']['NOMBRE'])-1))
							print '<br>';
					}
				?>
			</div>
		</div>
		<div class="pietiendas">
			<div class="margen">
				<span class="pietitulos"><?php print $Pagina[$_SESSION['idioma']][2]; ?></span><br>
				<?php
					for($i=0;$i<count($Pie['TIENDAS']['NOMBRE']);$i++){
						print '<a href="http://'.$_SERVER['HTTP_HOST'].'/tienda/'.$Pie['TIENDAS']['AMIGABLE'][$i].'/" class="language">'.ucwords(strtolower($Pie['TIENDAS']['NOMBRE'][$i])).'</a>';
						if($i<(count($Pie['TIENDAS']['NOMBRE'])-1))
							print '<br>';
					}
				?>
			</div>
		</div>
		<div class="piecorporativo">
			<div class="margen">
				<span class="pietitulos"><?php print $Pagina[$_SESSION['idioma']][3]; ?></span><br>
				<?php
					for($i=0;$i<count($Pie['CORPORATIVO']);$i++){
						print '<a href="http://'.$_SERVER['HTTP_HOST'].'/nosotros/" class="pievinculos">'.ucwords(strtolower($Pie['CORPORATIVO'][$i])).'</a>';
						if($i<(count($Pie['CORPORATIVO'])-1))
							print '<br>';
					}
				?>
			</div>
		</div>
		<div class="piecontactenos">
			<div class="margen2">
				<div>
					<span class="pietitulos"><?php print $Pagina[$_SESSION['idioma']][4]; ?></span><br>
					<span class="lettersize">Tel: <?php print $Pie['TELEFONO']; ?><br><?php print $Pie['CORREO']; ?><br></span>
				</div>
				<br>
				<div>
				<?php
					(!empty($Pie['FACEBOOK'])) ? print '<a href="'.$Pie['FACEBOOK'].'"><img src="imagenes/facebook_32.png" class="logos"></a>&nbsp;':'';
					(!empty($Pie['TWITTER'])) ? print '<a href="'.$Pie['TWITTER'].'"><img src="imagenes/twitter_32.png" class="logos"></a>&nbsp;':'';
					(!empty($Pie['YOUTUBE'])) ? print '<a href="'.$Pie['YOUTUBE'].'"><img src="imagenes/youtube_32.png" class="logos"></a>':'';
				?>
				</div>
			</div>
		</div>
	</div>
</div>