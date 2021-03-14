<?php
//OBTENGO LAS VARIABLES DEL POST
session_start();
require_once('../../funciones/redireccionar.php');
require_once('../../clases/class.encriptar.php');
require_once('../../funciones/func_formato_num.php');
$formato_num = new formato_numero();

$encriptar=new encriptado();
try{
$faltan_datos=false;
foreach($_POST as $nombre_campo => $valor){ 
   $asignacion = "\$" . $nombre_campo . "='" . $valor . "';"; 
   eval($asignacion); 
   //echo "<br>".$asignacion;
   if ($valor=="" && $nombre_campo!="txt_nota"){ $faltan_datos=true;}
}
//si todos los datos están llenos prosigo con el guardado y se ha iniciado sesion y el acceso a crear clientes y usarios es permitido
// ojo falta verificar que tenga acceso a crear usuario privilegios con variable de sesion OJO
if ($faltan_datos==false):
	require_once("../../clases/class.conexion.php");
	$conex= new bd_conexion();
	$conex=$conex->bd_conectarse();
	$conex->beginTransaction();
	$error_transac=false; //para saber si hago o no un roolback
	$sql="INSERT INTO clie_cuentas_bancos  (id_cuenta,id_clie,id_banco,id_cate_tipo_usua,id_cate_tipo_cuenta,id_cate_tipo_divisa,num_cuenta,monto_max_tranf) VALUES (:id_cuenta,:id_clie,:id_banco,:id_cate_tipo_usua,:id_cate_tipo_cuenta,:id_cate_tipo_divisa,:num_cuenta,:monto_max_tranf)";
	$consulta = $conex->prepare($sql); //preparo la conexion evitar sql injection
	//paso los parametros a la consulta para evitar sql injection
	$fecha_act=date('Y-m-d H:i:s');
	$visible=true;
	$id_cuenta_gen=$_SESSION['id_clie_abierto'].$cmb_tip_divisa.$cmb_banco.$num_cuenta;
	
	$monto_max=$formato_num->convertir_esp_mysql($monto_max);
	$data = array(
		'id_cuenta'=> $id_cuenta_gen, 
		'id_clie'=>$_SESSION['id_clie_abierto'],
		'id_banco'=>$cmb_banco,
		'id_cate_tipo_usua'=>$cmb_tip_cliente,
		'id_cate_tipo_cuenta'=>$cmb_tip_cuenta,
		'id_cate_tipo_divisa'=>$cmb_tip_divisa,
		'num_cuenta'=>$num_cuenta,
		'monto_max_tranf'=>$monto_max
	);
	if(!$consulta->execute($data)):
		throw new Exception('No se pudo registrar la cuenta del cliente / usuario');
		$error_transac=true;
	endif;
	
	//GUARDO EL OPERARIO PARA LA CUENTA
	$sql1="INSERT INTO clie_operarios  (id_clie,id_oper,id_cuenta,id_cate_tipo_usua,monto_max_tranf,fecha_reg) VALUES (:id_clie,:id_oper,:id_cuenta,:id_cate_tipo_usua,:monto_max_tranf,:fecha_reg)";
	$consulta1 = $conex->prepare($sql1); //preparo la conexion evitar sql injection
	//paso los parametros a la consulta para evitar sql injection
	$data = array(
		'id_clie'=>$_SESSION['id_clie_abierto'], 
		'id_oper'=>$cmb_operador_cuenta_add,
		'id_cuenta'=>$id_cuenta_gen,
		'id_cate_tipo_usua'=>$cmb_tip_cliente,
		'monto_max_tranf'=>$monto_max,
		'fecha_reg'=>$fecha_act
	);
	if(!$consulta1->execute($data)):
		throw new Exception('No se pudo registrar el operario de la cuenta');
		$error_transac=true;
	endif;	
	//SI HAY ERROR DEVUELVO TODO A SU ESTADO ORIGINA
	if ($error_transac==true):
		$conex->rollBack();
	else:
		$conex->commit();
		echo "<script type='text/javascript'>
			$(function(){
			new PNotify({
				title: '<p style=\"color:#000000;\"><img src=\"../../images/icns_mensajeria/admiracion.png\" width=\"32\" height=\"32\" align=\"absmiddle\" style=\"margin-top:-10px;\">&nbsp Notificación</p>',
				text: 'Cuenta y operario registrados correctamente.',
				type: 'success',
				 icon: false
			});
			cargar_cuentas_bancos();
			$('#exampleModal').modal('hide');
			});
			
			</script>";
		/*
		$accion_encrypt=$encriptar->encriptar('mostrar','');
		$_SESSION['id_clie_abierto']=$txt_cedula;
		$pag='usuarios.php?accion='.$accion_encrypt.'&id='.$txt_cedula;
		redireccionar_js($pag,1000);
		*/
	endif;
	
else:
?>
<div class="box-header with-border">
	<h3 class="box-title">Faltan algunos datos, por lo tanto, no se puede guardar la información...</h3>
</div>
<?php
endif;
}	catch (PDOException $pdoException) {
		switch ($pdoException->errorInfo[1]){
			case 1062:
				echo "<script type='text/javascript'>
			$(function(){
			new PNotify({
				title: '<p style=\"color:#000000;\"><img src=\"../../images/icns_mensajeria/admiracion.png\" width=\"32\" height=\"32\" align=\"absmiddle\" style=\"margin-top:-10px;\">&nbsp Notificación</p>',
				text: 'El registro ya existe no se permiten valores duplicados.',
				type: 'warning',
				 icon: false
			});

			});
			</script>";
				break;
			default:
				echo "<script type='text/javascript'>
			$(function(){
			new PNotify({
				title: '<p style=\"color:#000000;\"><img src=\"../../images/icns_mensajeria/admiracion.png\" width=\"32\" height=\"32\" align=\"absmiddle\" style=\"margin-top:-10px;\">&nbsp Notificación</p>',
				text: 'No se pudo registrar la cuenta.',
				type: 'warning',
				 icon: false
			});

			});
			</script>";
				echo $error= 'Error hacer la consulta: '.$pdoException->getMessage() ;
				break;
		}
		
		//return false;
	} catch (Exception $e) {
		echo $error='Excepción capturada: '.  $e->getMessage(). '\n';
		//return false;		
	}
?>