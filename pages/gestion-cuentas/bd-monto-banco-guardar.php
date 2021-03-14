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
//si todos los datos están llenos prosigo con el guardado y se ha iniciado sesion y el acceso a crear clientes y usarios es permitido
// ojo falta verificar que tenga acceso a crear usuario privilegios con variable de sesion OJO
if ($faltan_datos==false):
	require_once("../../clases/class.conexion.php");
	$conex= new bd_conexion();
	$conex=$conex->bd_conectarse();
	$conex->beginTransaction();
	$error_transac=false; //para saber si hago o no un roolback
	$sql="INSERT INTO cuentas_bancos_movimientos (id_cate_tipo_movi,id_cuenta,id_cuenta_orig,num_ref,monto,notas,id_oper,fecha_mov,fecha_reg) VALUES (:id_cate_tipo_movi,:id_cuenta,:id_cuenta_orig,:num_ref,:monto,:notas,:id_oper,:fecha_mov,:fecha_reg)";
	$consulta = $conex->prepare($sql); //preparo la conexion evitar sql injection
	//paso los parametros a la consulta para evitar sql injection
	$fecha_act=date('Y-m-d H:i:s');
	$fecha_mov=date('Y-m-d',strtotime($_POST['txt_fecha']));
	$id_cate_tipo_movi = 1; //1 porque es abono
	$notas='Depósito directo gestor cuentas';
	$txt_monto=$formato_num->convertir_esp_mysql($txt_monto);
	$data = array(
		'id_cate_tipo_movi'=> $id_cate_tipo_movi, 
		'id_cuenta'=>$cmb_cuenta,
		'id_cuenta_orig'=>$cmb_cuenta,
		'num_ref'=>$txt_num_ref,
		'monto'=>$txt_monto,
		'notas'=>$notas,
		'id_oper'=>$_SESSION['id_clie'],
		'fecha_mov'=>$fecha_mov,
		'fecha_reg'=>$fecha_act
	);
	if(!$consulta->execute($data)):
		throw new Exception('No se pudo registrar la cuenta del cliente / usuario');
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
				text: 'Monto registrado a la cuenta.',
				type: 'success',
				 icon: false
			});
			});
			$('#cmb_cuenta').change();	
			$('#txt_monto').val('');
			$('#txt_num_ref').val('');
			$('#txt_fecha').val('');
			/*
			
			*/
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
	<script>
			$('#frm_gestion_cuenta').bootstrapValidator('revalidateField', 'txt_monto');
			$('#frm_gestion_cuenta').bootstrapValidator('revalidateField', 'txt_num_ref');
			$('#frm_gestion_cuenta').bootstrapValidator('revalidateField', 'txt_fecha');
	</script>
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
				text: 'No se pudo registrar el monto a la cuenta.',
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