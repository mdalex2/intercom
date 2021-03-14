<?php

error_reporting(E_ALL & ~E_DEPRECATED);
ini_set("display_errors", 0);
//esta funcion devuelve los 
function obtener_mes_num($mes_letra){
	$mes_mayuscula=strtoupper($mes_letra);
	$arr = array(1 => "ENERO", 2 => "FEBRERO", 3 => "MARZO",4 => "ABRIL",
	5=>"MAYO",6=>"JUNIO",7=>"JULIO",8=>"AGOSTO",9=>"SEPTIEMBRE",10=>"OCTUBRE",
	11=>"NOVIEMBRE", 12 => "DICIEMBRE");
	foreach($arr as $num=>$valor){
     //echo "<p>El vector con indice $num tiene el valor $valor </p>";
	 if ($valor==$mes_mayuscula){
	  	return $num;
		exit();}
	  }
	 }
	function obtener_mes_letras($mes_num){
	$arr = array(1 => "ENERO", 2 => "FEBRERO", 3 => "MARZO",4 => "ABRIL",
	5=>"MAYO",6=>"JUNIO",7=>"JULIO",8=>"AGOSTO",9=>"SEPTIEMBRE",10=>"OCTUBRE",
	11=>"NOVIEMBRE", 12 => "DICIEMBRE");
	foreach($arr as $num=>$valor){
    //echo "<p>El vector con indice $num tiene el valor $valor </p>";
	 if ($num==$mes_num){
	  	return $valor;
		}
	  } 
	  }
	 function obtener_primer_dia_mes($fecha,$formato){
		$fecha=date("Y-m-d",strtotime($fecha));
		$fecha = new DateTime($fecha);
		$fecha->modify('first day of this month');
		return $fecha->format($formato); // imprime por ejemplo: 01/12/2012
	 }
	 function obtener_ultimo_dia_mes($fecha,$formato){
		$fecha=date("Y-m-d",strtotime($fecha));
		$fecha = new DateTime($fecha);
		$fecha->modify('last day of this month');
		return $fecha->format($formato); // imprime por ejemplo: 31/12/2012
	 }
//---------------------------------------------------------
function calcular_edad($fechanacimiento){
list($d, $m, $y) = explode("-", $fechanacimiento);
    $y_dif = date("Y") - $y;
    $m_dif = date("m") - $m;
    $d_dif = date("d") - $d;
    if ((($d_dif < 0) && ($m_dif == 0)) || ($m_dif < 0))
        $y_dif--;
    return $y_dif;
	exit();
}
//------------FIN CALCULAR EDAD----------------
function fecha_actual($formato){
	include_once("aplica_config_global.php");
	switch ($formato){
		case "normal":
			$fecha_g=date("d-m-Y h:i:s A");
			break;
		case "mysql":
			$fecha_g=date("Y-m-d H:i:s");
			break;
	}
	return $fecha_g;
	exit();
}
function formato_fecha($formato,$fecha){
	include_once("aplica_config_global.php");
	$fecha_g=strtotime($fecha);
	//include_once("aplica_config_global.php");
	switch (strtoupper($formato)) {
	  case "H12":
		//21/12/2012 11:00 pm
			$fecha_g=strftime("%I:%M %p",strtotime($fecha)); 
			break;

		case "C":
		//21/12/2012
			$fecha_g=strftime("%d/%m/%Y", strtotime($fecha));
			break;
		case "CD":
		//lun, 21/12/2012
			$fecha_g=strftime("%a, %d/%m/%Y", strtotime($fecha));
			break;
			
		case "CH":
		//21/12/2012 11:00 pm
			$fecha_g=strftime("%d/%m/%Y-%I:%M %p",strtotime($fecha)); 
			break;
		case "CDH":
		//21/12/2012 11:00 pm
			$fecha_g=strftime("%a, %d/%m/%Y-%I:%M %p",strtotime($fecha)); 
			break;
			
		case "M":
			$fecha_g=strftime("%d/%b/%Y",strtotime($fecha));
			break;
		case "MH":
			$fecha_g=strftime("%d/%b/%Y-%I:%M %p",strtotime($fecha)); 
			break;
		case "MDH": //media agregando el dia ejm lun, 25-may-2016 10:00 am.
			$fecha_g=strftime("%a, %d/%b/%Y-%I:%M %p",strtotime($fecha)); 
			break;
		case "MD": //media agregando el dia ejm lun, 25-may-2016
			$fecha_g=strftime("%a, %d/%b/%Y",strtotime($fecha)); 
			break;			
						
		case "L":
			$fecha_g=strftime("%A, %d de %B de %Y",strtotime($fecha)); 
			break;
		case "LH":
		  //$fecha_g=date('l jS \of F Y h:i:s A',strtotime($fecha));
			$fecha_g=strftime("%A, %d de %B de %Y - %I:%M %p",strtotime($fecha)); 
			break;
		case "MA":
		  //mes y año letra
			$fecha_g=strftime("%B-%Y",strtotime($fecha)); 
			break;
		case "MCA":
		  //mes corto y año letra
			$fecha_g=strftime("%b-%Y",strtotime($fecha)); 
			break;
            
		case "MES":
		  //mes letra
			$fecha_g=strftime("%B",strtotime($fecha)); 
			break;
	} //fin switch
	
	return utf8_encode($fecha_g);
	exit();
} //fin de la funcion
//---------------------------------------
function diferenciaDias($inicio, $fin)
{
    $inicio = strtotime($inicio);
    $fin = strtotime($fin);
    $dif = $fin - $inicio;
    $diasFalt = (( ( $dif / 60 ) / 60 ) / 24);
    return ceil($diasFalt);
}
function calcula_tiempo_transcurrido($fech_hora_ini,$fech_hora_fin){
	//date_default_timezone_set($_SESSION['zona_hora']);
	$date1 = new DateTime($fech_hora_ini);
	$date2 = new DateTime($fech_hora_fin);
	$tiempo_transcurrido = $date1->diff($date2);
	//var_dump($diff);
	// will output 2 days
	//echo $diff->days . ' days ';
	//return $diff;
	if ($tiempo_transcurrido->y > 0){
		return ($tiempo_transcurrido->y == 1)? $tiempo_transcurrido->y . ' año' : $tiempo_transcurrido->m . ' años';
	} elseif ($tiempo_transcurrido->m > 0) {
		return ($tiempo_transcurrido->m == 1)? $tiempo_transcurrido->m . ' mes' : $tiempo_transcurrido->m . ' meses';
	} elseif ($tiempo_transcurrido->days > 0) {
		return ($tiempo_transcurrido->days == 1)? $tiempo_transcurrido->days . ' día' : $tiempo_transcurrido->days . ' días';
	} elseif ($tiempo_transcurrido->h > 0) {
		return ($tiempo_transcurrido->h == 1)? $tiempo_transcurrido->h . ' hora' : $tiempo_transcurrido->h . ' horas';
	} elseif ($tiempo_transcurrido->i > 0) {
		return $tiempo_transcurrido->i . ' min.';
	} elseif ($tiempo_transcurrido->s > 0) {
		return $tiempo_transcurrido->s . ' seg.';
	} else return(0);
	/*
	DateInterval Object
(
    [y] => 0 // year
    [m] => 0 // month
    [d] => 2 // days
    [h] => 0 // hours
    [i] => 0 // minutes
    [s] => 0 // seconds
    [invert] => 0 // positive or negative 
    [days] => 2 // total no of days

)
*/
}
?>