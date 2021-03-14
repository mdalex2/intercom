<?php
// asigna las configuraciones gloables a las paginas
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_cache_limiter('nocache,private'); //evita mensaje de sesion expirada al presionar atras en el navegador
}
if (!isset($_SESSION["time_zone"])){
	$_SESSION["time_zone"]="America/Caracas";
}
//ASIGNO LO CONFIGURACION DE MANIPULACION DE FECHAS A ESPAÑOL
date_default_timezone_set($_SESSION["time_zone"]);
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set("display_errors", 1); 

include_once("detectar_os.php");
if ($os=="Windows" || $os=="Macintosh" || $os=="OS/2" || $os=="BeOS"){
	setlocale(LC_ALL,'es_VE', 'es_VE.utf-8',"es_Es",'');
	date_default_timezone_set($_SESSION["time_zone"]); //pongo la traduccion de fecha a español
	//setlocale(LC_ALL,"spanish");
	}
else{
	//es linux pongo el locale de linux
   setlocale(LC_ALL,'es_VE', 'es_VE.utf-8',"es_Es");
   date_default_timezone_set($_SESSION["time_zone"]);
}
	//setlocale(LC_ALL,"es_VE.utf-8");	//-------------------------------------------------

	
?>
