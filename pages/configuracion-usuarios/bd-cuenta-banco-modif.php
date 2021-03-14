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
	$sql="UPDATE clie_cuentas_bancos SET 
	id_cuenta=:id_cuenta,
	id_clie=:id_clie,
	id_banco=:id_banco,
	id_cate_tipo_usua=:id_cate_tipo_usua,
	id_cate_tipo_cuenta=:id_cate_tipo_cuenta,
	id_cate_tipo_divisa=:id_cate_tipo_divisa,
	num_cuenta=:num_cuenta,
	monto_max_tranf=:monto_max_tranf,
	item_visi=:item_visi 
	where id_cuenta=:id_cuenta_ant";
	$consulta = $conex->prepare($sql); //preparo la conexion evitar sql injection
	//paso los parametros a la consulta para evitar sql injection
	$fecha_act=date('Y-m-d H:i:s');
	if (isset($chk_bloq_cuenta_banc)){
		$visible=0;
	} else {
		$visible=1;
	}
	$monto_max_edit=$formato_num->convertir_esp_mysql($monto_max_edit);
	$id_cuenta_gen=$_SESSION['id_clie_abierto'].$cmb_tip_divisa_edit.$cmb_banco_edit.$num_cuenta_edit;
	$data = array(
		'id_cuenta'=> $id_cuenta_gen, 
		'id_cuenta_ant'=>$id_cuenta_ant,
		'id_clie'=>$_SESSION['id_clie_abierto'],
		'id_banco'=>$cmb_banco_edit,
		'id_cate_tipo_usua'=>$cmb_tip_cliente_edit,
		'id_cate_tipo_cuenta'=>$cmb_tip_cuenta_edit,
		'id_cate_tipo_divisa'=>$cmb_tip_divisa_edit,
		'num_cuenta'=>$num_cuenta_edit,
		'monto_max_tranf'=>$monto_max_edit,
		'item_visi'=>$visible,
	);
	if(!$consulta->execute($data)):
		throw new Exception('No se pudo modificar la cuenta del cliente / usuario');
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
				text: 'Cuenta modificada correctamente.',
				type: 'success',
				 icon: false
			});
			cargar_cuentas_bancos();
			$('#modificar_cuenta').modal('hide');
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
				text: 'No se pudo modificar la cuenta.',
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