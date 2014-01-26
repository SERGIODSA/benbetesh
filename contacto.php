<?php
	session_start();
	$Idioma = $_SESSION['idioma'];
	$Nombre = $_POST['nombre'];
	$Empresa = $_POST['empresa'];
	$Email = $_POST['email'];
	$Telefono = $_POST['telefono'];
	$Pais = $_POST['pais'];
	$Mensaje = $_POST['mensaje'];
	
	include_once('sqlqueries.php');
	$Queries = new Queries;
	$Queries->Formulario($Idioma,$Nombre,$Empresa,$Email,$Telefono,$Pais,$Mensaje);
	$Conexion = $Queries->Desconectar();
?>