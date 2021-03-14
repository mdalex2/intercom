<?php
//OBTENGO LAS VARIABLES DEL POST
session_start();
require_once('../../funciones/aplica_config_global.php');
require_once('../../funciones/func_formato_num.php');
require_once('../../clases/class-bd-consulta-saldo.php');

$formato_num = new formato_numero();

try{
$faltan_datos=false;
foreach($_POST as $nombre_campo => $valor){ 
   $asignacion = "\$" . $nombre_campo . "='" . $valor . "';"; 
   eval($asignacion); 
   //echo "<br>".$asignacion;
   if ($valor=="" && $nombre_campo!="txt_nota"){ $faltan_datos=true;}
}
//$falta_bauche=($_FILES['arch_soporte']['name']=='')? 1 : 0; 		
//si todos los datos están llenos prosigo con el guardado y se ha iniciado sesion y el acceso a crear clientes y usarios es permitido
// ojo falta verificar que tenga acceso a crear usuario privilegios con variable de sesion OJO
if ($faltan_datos==false):
	require_once("../../clases/class.conexion.php");
	//VERIFICO QUE EXISTA DISPONIBILIDAD EN LA CUENTA
	$saldos_cuenta= new saldos_cuenta();
	$saldo_disponible=$saldos_cuenta->obtener_valor_saldo_cuenta($cmb_cuenta);
	$txt_monto=$formato_num->convertir_esp_mysql($txt_monto); //MONTO SOLICITADO EN FORMATO NORMAL DE MYSQL
if ($saldo_disponible>=$txt_monto):
	
	$conex= new bd_conexion();
	$conex=$conex->bd_conectarse();
	$conex->beginTransaction();
	$error_transac=false; //para saber si hago o no un roolback
	
	//INSERTO EL ABONO EN LA CUENTA DESTIINO (+)
	$sql="INSERT INTO cuentas_bancos_movimientos (id_cate_tipo_movi,id_cuenta,id_cuenta_orig,num_ref,monto,notas,id_oper,fecha_mov,fecha_reg) VALUES (:id_cate_tipo_movi,:id_cuenta,:id_cuenta_orig,:num_ref,:monto,:notas,:id_oper,:fecha_mov,:fecha_reg)";
	$consulta = $conex->prepare($sql); //preparo la conexion evitar sql injection
	//paso los parametros a la consulta para evitar sql injection
	$fecha_act=date('Y-m-d H:i:s');
	$fecha_mov=date('Y-m-d',strtotime($_POST['txt_fecha']));
	$id_cate_tipo_movi = 1; //1 porque es abono
	$notas='Transferencias a usuarios finales';	
	$data = array(
		'id_cate_tipo_movi'=> $id_cate_tipo_movi, 
		'id_cuenta'=>$cmb_cuenta_dest,
		'id_cuenta_orig'=>$cmb_cuenta, //la cuenta para devolver si es cancelada la operacion
		'num_ref'=>'Abono tranf. direct.',
		'monto'=>$txt_monto,
		'notas'=>$notas,
		'id_oper'=>$_SESSION['id_clie'],
		'fecha_mov'=>$fecha_mov,
		'fecha_reg'=>$fecha_act
	);
	if(!$consulta->execute($data)):
		throw new Exception('No se pudo efectuar la transferencia');
		$error_transac=true;
	endif;
	$id_bauche_orig=$conex->lastInsertId();
	//INSERTO EL DÉBITO EN LA CUENTA DESTINO (-)
	$sql="INSERT INTO cuentas_bancos_movimientos (id_cate_tipo_movi,id_cuenta,id_cuenta_orig,num_ref,monto,notas,id_oper,fecha_mov,fecha_reg) VALUES (:id_cate_tipo_movi,:id_cuenta,:id_cuenta_orig,:num_ref,:monto,:notas,:id_oper,:fecha_mov,:fecha_reg)";
	$consulta = $conex->prepare($sql); //preparo la conexion evitar sql injection
	//paso los parametros a la consulta para evitar sql injection
	$id_cate_tipo_movi = 2; //2 porque es débito
	$notas='Débito por transferencias a usuarios';
	$txt_monto=$txt_monto*(-1);
	$data = array(
		'id_cate_tipo_movi'=> $id_cate_tipo_movi, 
		'id_cuenta'=>$cmb_cuenta,
		'id_cuenta_orig'=>$cmb_cuenta_dest, //la cuenta para devolver si es cancelada la operacion
		'num_ref'=>'Débito tranf. direct.',
		'monto'=>$txt_monto,
		'notas'=>$notas,
		'id_oper'=>$_SESSION['id_clie'],
		'fecha_mov'=>$fecha_mov,
		'fecha_reg'=>$fecha_act
	);
	if(!$consulta->execute($data)):
		throw new Exception('No se pudo efectuar la transferencia');
		$error_transac=true;
	endif;
	/*
	$id_bauche_dest=$conex->lastInsertId();
	//PONGO EL ADJUNTO DEL BAUCHER BANCARIO DONDE DEBE IR
	//var_dump($_POST);
	//var_dump($_FILES["arch_soporte"]);
 	if(!empty($_FILES["arch_soporte"]["type"])){
		
//INICIO LA TRANSACCIÓN GUARDO LOS DATOS EN LA BD
    $uploadedFile = '';
	//prosigo con el guardado en la base de datos
	
	//asigno las variables
	$nombre_archivo = $id_bauche_orig;
	$nombre_archivo1 = $id_bauche_dest;
    $extensiones_validas = array("jpeg", "jpg", "png","pdf");
    $temporal = explode(".", $_FILES["arch_soporte"]["name"]);
    $extension_archivo_cargado = end($temporal);
    if((($_FILES["arch_soporte"]["type"] == "application/pdf") || ($_FILES["arch_soporte"]["type"] == "image/png") || ($_FILES["arch_soporte"]["type"] == "image/jpg") || ($_FILES["arch_soporte"]["type"] == "image/jpeg")) && in_array($extension_archivo_cargado, $extensiones_validas)){
    	$origen_temp = $_FILES["arch_soporte"]['tmp_name'];
        $ruta_destino = '../../images/archiv_transf/';
			//si no existe la carpeta de recibos la creo
			if (!file_exists($ruta_destino)){
				
				if (!mkdir($ruta_destino,0777,true)){
					echo "No se pudo crear el directorio de archivos adjuntos.";
				}
			}
			$archivo_destino=$ruta_destino.$nombre_archivo.'.'.$extension_archivo_cargado;
			$archivo_destino1=$ruta_destino.$nombre_archivo1.'.'.$extension_archivo_cargado;
            if (move_uploaded_file($origen_temp,$archivo_destino) && copy($archivo_destino,$archivo_destino1)) {
				$archivo_cargado=true;
                $uploadedFile = $nombre_archivo;
            } else {
				$error_transac=true; // para devolver la transacción
				$archivo_cargado=false;
				echo "No se pudo guardar el archivo del baucher";
			}
        } else {
			echo 'Tipo de archivo no válido, no se puede efectuar la transferencia';
			$error_transac=true; // para devolver la transacción
		}
    }	
	*/
	//SI HAY ERROR DEVUELVO TODO A SU ESTADO ORIGINA
	if ($error_transac==true):
		$conex->rollBack();
	else:
		$conex->commit();
		echo "<script type='text/javascript'>
			$(function(){
			new PNotify({
				title: '<p style=\"color:#000000;\"><img src=\"../../images/icns_mensajeria/admiracion.png\" width=\"32\" height=\"32\" align=\"absmiddle\" style=\"margin-top:-10px;\">&nbsp Notificación</p>',
				text: 'Transferencia efectuada.',
				type: 'success',
				icon: false
			});
			});
			$('#div_limpiar').empty();
			</script>";	
	?>
		<div class="alert bg-gray alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Información!</h4>
                La transferencia de fondos se efectuó correctamente...
                 <br><br>
                <?php
                $link='transferencias-usuario-final.php';
                ?>
                 <button type="button" class="btn btn-danger" onclick="window.location.href='<?php echo $link;?>'">
                    Continuar&nbsp;
                    <span class="glyphicon glyphicon-circle-arrow-right"></span>
                </button>
                 <br><br>                
       	</div>
		<?php
	endif;
else:
		echo "<script type='text/javascript'>

				$(function(){
				new PNotify({
					title: '<p style=\"color:#000000;\"><img src=\"../../images/icns_mensajeria/admiracion.png\" width=\"32\" height=\"32\" align=\"absmiddle\" style=\"margin-top:-10px;\">&nbsp Error!!!</p>',
					text: 'No se puede realizar la transferencia, el monto solicitado excede el saldo disponible en la cuenta.',
					type: 'warning',
					icon: false
				});
				});
				</script>";
	endif;	
else:
?>
<div class="box-header with-border">
	<script>
			$('#frm_gestion_cuenta').bootstrapValidator('revalidateField', 'txt_monto');
			$('#frm_gestion_cuenta').bootstrapValidator('revalidateField', 'txt_num_ref');
			$('#frm_gestion_cuenta').bootstrapValidator('revalidateField', 'txt_fecha');
	</script>
	<h3 class="box-title">Faltan algunos datos, por lo tanto, no se puede efectuar la transferencia...</h3>
</div>
<?php
	
endif;	
}	
catch (PDOException $pdoException) {
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
				text: 'No se pudo efecutar la transferencia.',
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