<?php
	session_start();
	$Idioma = $_SESSION['idioma'];
	$Nombre = $_POST['nombre'];
	$Telefono = $_POST['telefono'];
	$Email = $_POST['email'];
	$Mensaje = $_POST['mensaje'];
	$Error["SIZE"] = Array( 1 => 'El archivo debe tener un tama&#241;o inferior a 2MB', 2 => 'The file must be no larger than 2MB');
	$Error["LOAD"] = Array( 1 => 'Error: El archivo no se pudo cargar', 2 => 'Error: The file could not be loaded');
	$Error["TYPE"] = Array( 1 => 'Ingresa tu hoja de vida en formato PDF o WORD', 2 => 'Enter your resume in PDF or WORD format');
	$Error["SUCCESS"] = Array( 1 => 'Formulario enviado con exito', 2 => 'Form sent successfully');
	
	if(is_uploaded_file($_FILES['archivo']['tmp_name'])){
		$rand = rand(1000,999999);
		$Archivo = 'http://'.$_SERVER['HTTP_HOST'].'/curriculum/'.$rand.$_FILES['archivo']['name'];
		if( $_FILES['archivo']['size']<(2048*1024) ){
			if(($_FILES['archivo']['type']=='application/pdf')||($_FILES['archivo']['type']=='application/msword')||($_FILES['archivo']['type']=='application/vnd.openxmlformats-officedocument.wordprocessingml.document')){
				$origen = $_FILES['archivo']['tmp_name'];
				$destino = 'curriculum/'.$rand.$_FILES['archivo']['name'];
				move_uploaded_file($origen, $destino);
				include_once('sqlqueries.php');
				$Queries = new Queries;
				$Queries->Solicitud($Idioma,$Nombre,$Telefono,$Email,$Archivo,$Mensaje);
				$Conexion = $Queries->Desconectar();
				$_SESSION["MENSAJE"] = $Error["SUCCESS"][$_SESSION['idioma']];
				header('location: ../trabajo/');
			}
			else{
				$_SESSION["MENSAJE"] = $Error["TYPE"][$_SESSION['idioma']];
				header('location: ../trabajo/');
			}
		}
		else{
			$_SESSION["MENSAJE"] = $Error["SIZE"][$_SESSION['idioma']];
			header('location: ../trabajo/');
		}
	}
	else{
		$_SESSION["MENSAJE"] = $Error["LOAD"][$_SESSION['idioma']];
		header('location: ../trabajo/');
	}
?>