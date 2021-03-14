<?php
class formato_numero{
	function convertir_esp_mysql($numero){
		$a = array(".",",");
		$b = array("",".");
		$num_sc=str_replace($a, $b, $numero);	
		$num_formateado=number_format($num_sc,2,'.','');
		return $num_formateado;
	}
	function convertir_mysql_esp($numero){
		$num_formateado=number_format($numero,2,",",".");
		return $num_formateado;

	}	
	function pon_cero_izq($entero, $largo){
    // Limpiamos por si se encontraran errores de tipo en las variables
    $entero = (int)$entero;
    $largo = (int)$largo;
	
    $relleno = '';
	
    /**
     * Determinamos la cantidad de caracteres utilizados por $entero
     * Si este valor es mayor o igual que $largo, devolvemos el $entero
     * De lo contrario, rellenamos con ceros a la izquierda del número
     **/
    if (strlen($entero) < $largo) {
        $relleno = str_repeat('0', $largo - strlen($entero));
    }
    return $relleno . $entero;
	exit();
}
//------ FIN FUNCION CERO IZQUEIRDA 	
}
?>