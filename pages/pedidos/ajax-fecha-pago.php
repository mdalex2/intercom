<?php
	$fecha = date("d-m-Y");
	$dias= (int)$_REQUEST["txt_cant_dias"];
	//Incrementando 2 dias
	$fecha_pago = strtotime($fecha."+ $dias days");
	echo date("d-m-Y",$fecha_pago);
?>