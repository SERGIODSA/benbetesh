<?php
	include_once('sqlqueries.php');
	$Queries = new Queries;
	$id = empty($_GET['id'])?'':$_GET['id'];
	$Noticia = $Queries->Noticia(($_SESSION['idioma']),$id);
	$Conexion = $Queries->Desconectar();
	$Pagina = Array(1 => 'NOTICIAS' , 2 => 'NEWS');
?>
<link rel="stylesheet" href="estilos/cuerponoticias.css" type="text/css">
<div class="noticias2">
	<div class="notititulo"><?php print('<a class="vinculo" href="/noticias/"><< </a>'.$Pagina[$_SESSION['idioma']]); ?></div>
	<div class="notifotogrande"><img src="subidas/<?php print($Noticia['IMAGEN']); ?>" width="882px" height="544px"></div>
	<div class="notititulo2"><?php print($Noticia['TITULO']); ?></div>
	<div class="notidescripcion"><?php print($Noticia['DESCRIPCION']); ?></div>
</div>