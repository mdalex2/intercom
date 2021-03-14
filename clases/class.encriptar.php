<?php
class encriptado{
	//atributos o variables 
	var $clave='H0gar¡s¡m@2018$+';
	//Métodos
	public function encriptar($string, $key) {
	   $result = '';
	   $key=($key=='' || is_null($key))? $this->clave : $key;
	   for($i=0; $i<strlen($string); $i++) {
		  $char = substr($string, $i, 1);
		  $keychar = substr($key, ($i % strlen($key))-1, 1);
		  $char = chr(ord($char)+ord($keychar));
		  $result.=$char;
	   }
	   return base64_encode($result);
   }
	
	public function desencriptar($string, $key) {
	   $result = '';
	   $key=($key=='' || is_null($key))? $this->clave : $key;
	   $string = base64_decode($string);
	   for($i=0; $i<strlen($string); $i++) {
		  $char = substr($string, $i, 1);
		  $keychar = substr($key, ($i % strlen($key))-1, 1);
		  $char = chr(ord($char)-ord($keychar));
		  $result.=$char;
	   }
		//echo "<br>clave: $key";
	   return $result;
	}
}
/*
$obj=new encriptado();
$texto_encriptado=$obj->encriptar("1234","4321");
echo "encriptado: ".$texto_encriptado."<br>";
$texto_desencriptado=$obj->desencriptar($texto_encriptado,"4321");
echo "desencriptado: ".$texto_desencriptado;
*/
?>