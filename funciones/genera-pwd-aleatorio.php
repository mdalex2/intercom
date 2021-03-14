<?php
class generar_pwd_aleat{
	function claveAleatoria($longitud = 8, $opcLetra = TRUE, $opcNumero = TRUE, $opcMayus = TRUE, $opcEspecial = FALSE){
		$letras ="abcdefghijklmnopqrstuvwxyz";
		$numeros = "1234567890";
		$letrasMayus = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$especiales ="|@#~$%()=^*+[]{}-_";
		$listado = "";
		$password = ""; 
		if ($opcLetra == TRUE) $listado .= $letras;
		if ($opcNumero == TRUE) $listado .= $numeros;
		if($opcMayus == TRUE) $listado .= $letrasMayus;
		if($opcEspecial == TRUE) $listado .= $especiales;

		for( $i=1; $i<=$longitud; $i++) {
		$caracter = $listado[rand(0,strlen($listado)-1)];
		$password.=$caracter;
		$listado = str_shuffle($listado);
		}
		return $password;
		}
	function generar_password_complejo($largo){
	  $largo=($largo>0)? $largo : 8;
	  $cadena_base =  'ABCDEFGHIJKLMNOPQRSTUVWZabcdefghijklmnopqrstuvwxyz';
	  $cadena_base .= '0123456789' ;
	  $cadena_base .= '!@#%^&*()_,./<>?;:[]{}\|=+';

	  $password = '';
	  $limite = strlen($cadena_base) - 1;

	  for ($i=0; $i < $largo; $i++)
		$password .= $cadena_base[rand(0, $limite)];

	  return $password;
	}
}
?>