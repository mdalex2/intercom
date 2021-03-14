<?php
//OBTENGO LAS VARIABLES DEL POST
session_start();
require_once('../../funciones/aplica_config_global.php');
require_once('../../funciones/redireccionar.php');
require_once('../../funciones/func_formato_num.php');
require_once('../../clases/class.encriptar.php');
require_once('../../clases/class-bd-consulta-saldo.php');
$encriptar=new encriptado();
try{
$faltan_datos=false;
foreach($_POST as $nombre_campo => $valor){ 
   $asignacion = "\$" . $nombre_campo . "='" . $valor . "';"; 
   eval($asignacion); 
   //echo "<br>".$asignacion;
   if ($valor=="" && $nombre_campo!="notas"){ $faltan_datos=true;}
}
$faltan_datos=(!isset($id_cuenta_orig))? true : false;
//si todos los datos están llenos prosigo con el guardado y se ha iniciado sesion y el acceso a crear clientes y usarios es permitido
// ojo falta verificar que tenga acceso a crear usuario privilegios con variable de sesion OJO
//BUSCO CUANTO TENGO EN LA CUENTA PARA SABER SI PROSIGO O NO CON EL GUARDADO
	
if ($faltan_datos==false):
	$formato_num= new formato_numero();
	$monto_soli_mysql=$formato_num->convertir_esp_mysql($monto_soli);
	$saldos_cuenta= new saldos_cuenta();
	$saldo_disponible=$saldos_cuenta->obtener_valor_saldo_cuenta($id_cuenta_orig);
	if ($saldo_disponible>=$monto_soli_mysql):
		require_once("../../clases/class.conexion.php");
		$conex= new bd_conexion();
		$conex=$conex->bd_conectarse();
		$conex->beginTransaction();
		$error_transac=false; //para saber si hago o no un roolback
		
		//VERIFICO A QUE OPERADOR CORRESPONDERÁ EL CASO O SOLICITUD
		$sql1="SELECT clie_operarios.id_oper,
		clie.desc_clie,
		usua_sist.on_line,
		usua_sist.tiem_expi,
		usua_sist.ultimo_acceso,
		COUNT(solicitudes.id_sol) as total_sol 
		FROM (clie_operarios 
			LEFT JOIN solicitudes on solicitudes.id_oper=clie_operarios.id_oper 
			INNER JOIN clie ON clie.id_clie=clie_operarios.id_oper 
			INNER JOIN usua_sist on usua_sist.id_usua_sist = clie_operarios.id_oper ) 
		WHERE clie_operarios.id_cate_tipo_usua=4 and usua_sist.on_line=1 and id_cuenta=:id_cuenta 
		GROUP BY clie_operarios.id_oper 
		ORDER BY total_sol ASC LIMIT 1";
		$consulta1 = $conex->prepare($sql1); //preparo la conexion evitar sql injection
		//paso los parametros a la consulta para evitar sql injection
		$data = array(
			'id_cuenta'=> $id_cuenta_orig
		);
		$id_operario='SA'; //sin asignación de operario predefinido cuando no hay nadie on line
		$nombre_operario=" Sin asignación (No hay operadores en línea)";
		if(!$consulta1->execute($data)):
			throw new Exception('No se pudo obtener un operario para la solicitud');
			$error_transac=true;
		else:

			$filas   = $consulta1->fetchAll(\PDO::FETCH_OBJ);
			$tot_reg = $consulta1->rowCount();
			foreach ($filas as $fila){
				$id_operario=$fila->id_oper; //ASIGNO EL OPERARIO QUE TIENE MENOS ASIGNACIONES
				$nombre_operario=$fila->desc_clie;
			}
		endif;

		

		$sql="INSERT INTO solicitudes  (id_cuenta_orig,guard_por,id_oper,id_banco,id_cate_tipo_cuenta,num_ide,desc_clie,telf,email,notas,num_cuenta,monto_soli,fecha_soli,fecha_proc,fecha_canc,fecha_trans) VALUES (:id_cuenta_orig,:guard_por,:id_oper,:id_banco,:id_cate_tipo_cuenta,:num_ide,:desc_clie,:telf,:email,:notas,:num_cuenta,:monto_soli,:fecha_soli,:fecha_proc,:fecha_canc,:fecha_trans)";
		$consulta = $conex->prepare($sql); //preparo la conexion evitar sql injection
		//paso los parametros a la consulta para evitar sql injection
		$fecha_act=date('Y-m-d H:i:s');
		$usuario_actual=$_SESSION['id_clie'];
		
		$data = array(
			'id_cuenta_orig' => $id_cuenta_orig,
			'guard_por'=> $usuario_actual, 
			'id_oper'=> $id_operario,
			'id_banco'=> $cmb_banco,
			'id_cate_tipo_cuenta'=>$cmb_tip_cuenta,
			'num_ide'=>$num_ide,
			'desc_clie'=>$desc_clie,
			'telf'=>$telf,
			'email'=>$email,
			'notas'=>$notas,
			'num_cuenta'=>$num_cuenta,
			'monto_soli'=>$monto_soli_mysql,
			'fecha_soli'=>$fecha_act,
			'fecha_proc'=>$fecha_act,
			'fecha_canc'=>$fecha_act,
			'fecha_trans'=>$fecha_act
		);
		if(!$consulta->execute($data)):
			throw new Exception('No se pudo registrar la solicitud del cliente');
			$error_transac=true;
		endif;
		$id_insertado=$conex->lastInsertId();
		//DESCUENTO EL MONTO EN LA CUENTA
		$sql="INSERT INTO cuentas_bancos_movimientos  (id_cate_tipo_movi,id_cuenta,id_cuenta_orig,fecha_mov,num_ref,monto,notas,id_oper,fecha_reg) VALUES (:id_cate_tipo_movi,:id_cuenta,:id_cuenta_orig,:fecha_mov,:num_ref,:monto,:notas,:id_oper,:fecha_reg)";
		$consulta = $conex->prepare($sql); //preparo la conexion evitar sql injection
		//paso los parametros a la consulta para evitar sql injection
		$fecha_hora_act=date('Y-m-d H:i:s');
		$fecha_act=date('Y-m-d');
		$usuario_actual=$_SESSION['id_clie'];
		$id_cate_tipo_movi=2; //debito
		$num_ref='Déb. Aut. Solic. #'.$id_insertado;
		$notas="Débito automático por solicitud de divisas Nº: ".$id_insertado;
		$monto_soli_mysql_neg=$monto_soli_mysql*(-1);
		$data = array(
			'id_cate_tipo_movi' => $id_cate_tipo_movi,
			'id_cuenta'=> $id_cuenta_orig, 
			'id_cuenta_orig'=> $id_cuenta_orig, 
			'fecha_mov'=> $fecha_act,
			'num_ref'=> $num_ref,
			'monto'=>$monto_soli_mysql_neg,
			'notas'=>$notas,
			'id_oper'=>$usuario_actual,
			'fecha_reg'=>$fecha_hora_act
		);
		if(!$consulta->execute($data)):
			throw new Exception('No se pudo registrar la solicitud del cliente');
			$error_transac=true;
		endif;

		//SI HAY ERROR DEVUELVO TODO A SU ESTADO ORIGINA
		if ($error_transac==true):
			$conex->rollBack();
		else:
			$conex->commit();
			echo "<script type='text/javascript'>
				//VACÍO EL DIV DEL FORM NUEVO
				$('#div_nuevo').empty();
				$('#div_nuevo').append('<h3>La solícitud se guardó correctamente y fue asignada al operario: $nombre_operario</h3>');

				$(function(){
				new PNotify({
					title: '<p style=\"color:#000000;\"><img src=\"../../images/icns_mensajeria/admiracion.png\" width=\"32\" height=\"32\" align=\"absmiddle\" style=\"margin-top:-10px;\">&nbsp Notificación</p>',
					text: 'La solicitud de registró exitosamente al operario: $nombre_operario.',
					type: 'success',
					icon: false
				});
				});
				</script>";

			//echo "<img src='../../images/sistema/succes_ico.png' width='16px'> Solicitud registrada correctamente";
			//$accion_encrypt=$encriptar->encriptar('mostrar','');
			//$_SESSION['id_solic']=$txt_cedula;
			//$pag='registro-solicitud.php?accion='.$accion_encrypt.'&id='.$txt_cedula;
			//redireccionar_js($pag,1000);
		endif;
else:
		echo "<script type='text/javascript'>

				$(function(){
				new PNotify({
					title: '<p style=\"color:#000000;\"><img src=\"../../images/icns_mensajeria/admiracion.png\" width=\"32\" height=\"32\" align=\"absmiddle\" style=\"margin-top:-10px;\">&nbsp Error!!!</p>',
					text: 'El monto solicitado excede el saldo disponible en la cuenta.',
					type: 'warning',
					icon: false
				});
				});
				</script>";
	endif;
	else:	
		$msg_falta_cuenta= (!isset($id_cuenta_orig))? "Se debe configurar una caja de divisas, contacte su proveedor. " : "";
	?>
	
	<div class="box-header with-border">
		<h3 class="box-title"><?php echo $msg_falta_cuenta;?> Faltan algunos datos, por lo tanto, no se puede registrar la solicitud...</h3>
	</div>
	<?php
	
	endif;
	
}	catch (PDOException $pdoException) {
		switch ($pdoException->errorInfo[1]){
			case 1062:
				echo "<h3>El registro ya existe, no se permiten valores duplicados</h3>";
				break;
			default:
				echo $error= 'Error hacer la consulta: '.$pdoException->getMessage() . " Número: " ;
				break;
		}
		
		//return false;
	} catch (Exception $e) {
		echo $error='Excepción capturada: '.  $e->getMessage(). '\n';
		//return false;		
	}
?>