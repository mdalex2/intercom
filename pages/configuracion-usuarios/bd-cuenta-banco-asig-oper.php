<?php
//OBTENGO LAS VARIABLES DEL POST
session_start();
require_once('../../funciones/func_formato_num.php');
$formato_num = new formato_numero();

try{
$faltan_datos=false;
foreach($_POST as $nombre_campo => $valor){ 
   $asignacion = "\$" . $nombre_campo . "='" . $valor . "';"; 
   eval($asignacion); 
   //echo "<br>".$asignacion;
   if ($valor=="" && $nombre_campo!="txt_nota"){ $faltan_datos=true;}
}
	var_dump($_POST);
//si todos los datos están llenos prosigo con el guardado y se ha iniciado sesion y el acceso a crear clientes y usarios es permitido
// ojo falta verificar que tenga acceso a crear usuario privilegios con variable de sesion OJO
if ($faltan_datos==false):
	require_once("../../clases/class.conexion.php");
	$conex= new bd_conexion();
	$conex=$conex->bd_conectarse();
	$conex->beginTransaction();
	$error_transac=false; //para saber si hago o no un roolback
	$fecha_act=date("Y-m-d H:i:s");
	//GUARDO EL OPERARIO PARA LA CUENTA
	$sql1="INSERT INTO clie_operarios  (id_clie,id_oper,id_cuenta,id_cate_tipo_usua,monto_max_tranf,fecha_reg) VALUES (:id_clie,:id_oper,:id_cuenta,:id_cate_tipo_usua,:monto_max_tranf,:fecha_reg)";
	$consulta1 = $conex->prepare($sql1); //preparo la conexion evitar sql injection
	//paso los parametros a la consulta para evitar sql injection
	$monto_max=$formato_num->convertir_esp_mysql($monto_max_modal);
	$data = array(
		'id_clie'=>$_SESSION['id_clie_abierto'], 
		'id_oper'=>$cmb_operador_cuenta_modal,
		'id_cuenta'=>$id_cuenta_ant_oper,
		'id_cate_tipo_usua'=>$cmb_tip_cliente_modal,
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
				text: 'Operario registrado correctamente.',
				type: 'success',
				 icon: false
			});
			cargar_cuentas_bancos();
			$('#form_asig_oper').trigger('reset');
			$('#cmb_tip_cliente_modal').val('').trigger('change');
			$('#cmb_operador_cuenta_modal').val('').trigger('change');
			$('#monto_max_modal').val('');
			$('#form_asig_oper').bootstrapValidator('revalidateField', 'monto_max_modal');
			$('#asignar_operador').modal('hide');
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