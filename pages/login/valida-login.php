<?php
	require_once('../../funciones/redireccionar.php');
	require_once('class-validar-login.php');
	$verificar_login= new verificar_login();
	$email=$_POST['email'];
	$password=$_POST['password'];
	$login=$verificar_login->valida_login($email,$password);
	if ($login==true){
		$link_menu="../home/";
		redireccionar_js($link_menu,0);
	} else {
		echo "<div class='callout callout-danger'>
                <h4>Error!</h4>

                <p>Login o clave incorrectos.</p>
              </div>";
	}
?>