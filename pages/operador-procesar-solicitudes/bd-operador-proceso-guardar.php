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
$falta_bauche=($_FILES['arch_soporte']['name']=='')? 1 : 0; 		
//si todos los datos están llenos prosigo con el guardado y se ha iniciado sesion y el acceso a crear clientes y usarios es permitido
// ojo falta verificar que tenga acceso a crear usuario privilegios con variable de sesion OJO
if ($faltan_datos==false && $falta_bauche==false):
	require_once("../../clases/class.conexion.php");
	
	$conex= new bd_conexion();
	$conex=$conex->bd_conectarse();
	$conex->beginTransaction();
	$error_transac=false; //para saber si hago o no un roolback
	
	//INSERTO EL ABONO EN LA CUENTA DESTIINO (+)
	$sql="update solicitudes SET 
	item_proc=:item_proc,
	fecha_proc=:fecha_proc,
	fecha_trans=:fecha_trans,
	nume_refe=:nume_refe,
	exte_arch=:exte_arch WHERE id_sol=:id_sol";
	$consulta = $conex->prepare($sql); //preparo la conexion evitar sql injection
	//paso los parametros a la consulta para evitar sql injection
	$fecha_act=date('Y-m-d H:i:s');
	$fecha_trans=date('Y-m-d',strtotime($_POST['txt_fecha']));
	$item_proc = 1; //1 porque es true
	$temporal = explode(".", $_FILES["arch_soporte"]["name"]);
    $extension_archivo_cargado = end($temporal);
	//$notas='Transferencias a mayoristas';	
	$data = array(
		'item_proc'=> $item_proc, 
		'fecha_proc'=>$fecha_act,
		'fecha_trans'=>$fecha_trans,
		'nume_refe'=>$txt_num_ref,
		'id_sol'=>$txt_id_sol,
		'exte_arch'=>$extension_archivo_cargado
	);
	if(!$consulta->execute($data)):
		throw new Exception('No se pudo procesar la solicitud');
		$error_transac=true;
	endif;
	
	//PONGO EL ADJUNTO DEL BAUCHER BANCARIO DONDE DEBE IR
	$id_bauche_orig=$txt_id_sol; //para el nombre del archivo o soporte
	$archivo_destino="";
	$extension_archivo_cargado=""; //para guardar en la BD si se procesa
	if(!empty($_FILES["arch_soporte"]["type"])){
		
//INICIO LA TRANSACCIÓN GUARDO LOS DATOS EN LA BD
    $uploadedFile = '';
	//prosigo con el guardado en la base de datos
	
	//asigno las variables
	$nombre_archivo = $id_bauche_orig;
    $extensiones_validas = array("jpeg", "jpg", "png","pdf");
    $temporal = explode(".", $_FILES["arch_soporte"]["name"]);
    $extension_archivo_cargado = end($temporal);
    if((($_FILES["arch_soporte"]["type"] == "application/pdf") || ($_FILES["arch_soporte"]["type"] == "image/png") || ($_FILES["arch_soporte"]["type"] == "image/jpg") || ($_FILES["arch_soporte"]["type"] == "image/jpeg")) && in_array($extension_archivo_cargado, $extensiones_validas)){
    	$origen_temp = $_FILES["arch_soporte"]['tmp_name'];
		$folder_fecha=date('Y-m',strtotime($_POST['txt_fecha']));
        $ruta_destino = '../../images/archiv_proc_solic/'.$folder_fecha.'/';
			//si no existe la carpeta de recibos la creo
			if (!file_exists($ruta_destino)){
				
				if (!mkdir($ruta_destino,0777,true)){
					echo "No se pudo crear el directorio de archivos adjuntos.";
				}
			}
			$archivo_destino=$ruta_destino.$nombre_archivo.'.'.$extension_archivo_cargado;
            if (move_uploaded_file($origen_temp,$archivo_destino)) {
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
	//SI HAY ERROR DEVUELVO TODO A SU ESTADO ORIGINA
	if ($error_transac==true):
		$conex->rollBack();
	else:
		$conex->commit();
		echo "<script type='text/javascript'>
			$(function(){
			new PNotify({
				title: '<p style=\"color:#000000;\"><img src=\"../../images/icns_mensajeria/admiracion.png\" width=\"32\" height=\"32\" align=\"absmiddle\" style=\"margin-top:-10px;\">&nbsp Notificación</p>',
				text: 'La solicitud se procesó con éxito.',
				type: 'success',
				icon: false
			});
			});
			</script>";	
	?>
		<div class="alert bg-gray alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Información!</h4>
                La transferencia de fondos al mayorista se efectuó correctamente...
                 <br><br>
                <?php
                $link='procesar-solicitud.php';
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
?>
<div class="box-header with-border">
	<script>
			$('#frm_procesar').bootstrapValidator('revalidateField', 'arch_soporte');
			$('#frm_procesar').bootstrapValidator('revalidateField', 'txt_num_ref');
			$('#frm_procesar').bootstrapValidator('revalidateField', 'txt_fecha');
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
				text: 'No se pudo procesar la solicitud.',
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