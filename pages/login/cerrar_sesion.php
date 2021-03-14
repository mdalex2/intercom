<?php
	require_once($_SERVER['DOCUMENT_ROOT']."/clases/verifica_expira_sesion.php");
	//ACTUALIZO LA ULTIMA VEZ QUE SE INGRESO LOGUEADO
	require_once($_SERVER['DOCUMENT_ROOT']."/funciones/aplica_config_global.php");
	$verificar_sesion= new verificar_sesion();
	$verificar_sesion->desconectar_sesion($id_usuario="");

	session_destroy();
	require_once('../../funciones/redireccionar.php');
	redireccionar_js('login.php',0);
?>
