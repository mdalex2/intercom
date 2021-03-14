<?php
//OBTENGO LAS VARIABLES DEL POST
session_start();
require_once('../../funciones/redireccionar.php');
require_once('../../clases/class.encriptar.php');
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
	$sql="UPDATE clie SET 
		id_clie=:id_clie,
		ciudad=:ciudad,
		desc_clie=:desc_clie,
		dire_fisc=:dire_fisc,
		telf=:telf,
		email=:email,
		fech_regi=:fech_regi,
		nota_obse=:nota_obse WHERE id_clie=:id_clie_ant";
	$consulta = $conex->prepare($sql); //preparo la conexion evitar sql injection
	//paso los parametros a la consulta para evitar sql injection
	$fecha_act=date('Y-m-d H:i:s');
	$visible=true;
	$data = array(
		'id_clie'=> $txt_cedula,
		'id_clie_ant'=>$txt_cedula_ant,
		'ciudad'=> $city,
		'desc_clie'=>$txt_nom_ape_raz_soc,
		'dire_fisc'=>$address,
		'telf'=>$phone,
		'email'=>$email,
		'fech_regi'=>$fecha_act,
		'nota_obse'=>$txt_nota
	);
	if(!$consulta->execute($data)):
		throw new Exception('No se pudo modificar el cliente');
		$error_transac=true;
	endif;
	$edit_bloqueo="";$edit_pwd="";
	$data = array(
		'login'=> $email,
		'id_clie_ant'=>$txt_cedula_ant
	);
	if (isset($chk_bloq_usu)){
		$edit_bloqueo= ",bloqueado=1 ";
	} else {
		$edit_bloqueo= ",bloqueado=0 ";
	}
	if (isset($chk_mod_pwd)){
		$edit_pwd= ",pass=aes_encrypt(:pwd_new,:pwd_new) ";
	}
	//EFECTUO EL GUARDADO DEL USUARIO
	$sql1="update usua_sist set 	
	login=:login 
	$edit_pwd $edit_bloqueo WHERE id_usua_sist=:id_clie_ant";
	$consulta1 = $conex->prepare($sql1); //preparo la conexion evitar sql injection
	//paso los parametros a la consulta para evitar sql injection
	$consulta1->bindParam(':login', $email, PDO::PARAM_STR);
	$consulta1->bindParam(':id_clie_ant',$txt_cedula_ant, PDO::PARAM_STR);
	if (isset($chk_mod_pwd)){
		$consulta1->bindParam(':pwd_new',$password, PDO::PARAM_LOB);
	}

	if(!$consulta1->execute()):
		throw new Exception('No se pudo modificar los datos del usuario');
		$error_transac=true;
	endif;
	
	//ACTUALIZO EL TIPO DE USUARIO
	$sql2="UPDATE usua_sist_tipo_usua SET 	
	id_cate_tipo_usua=:id_cate_tipo_usua WHERE id_usua_sist=:id_usua_sist";
	$consulta2 = $conex->prepare($sql2); //preparo la conexion evitar sql injection
	//paso los parametros a la consulta para evitar sql injection
	$data = array(
		'id_usua_sist'=> $txt_cedula, 
		'id_cate_tipo_usua'=> $cmb_tip_cuenta_usu
	);
	if(!$consulta2->execute($data)):
		throw new Exception('No se pudo asociar el tipo de usuario');
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
				text: 'Datos modificados correctamente.',
				type: 'success',
				 icon: false
			});

			});
			</script>"; 
		//echo "<h3><img src='../../images/sistema/succes_ico.png' width='24px'> Datos modificados correctamente</h3>";
		echo "<script type='text/javascript'>
			var cedula_ant=$('#txt_cedula').val();
			$('#txt_cedula_ant').val(cedula_ant);
			</script>";
		$_SESSION['id_clie_abierto']=$txt_cedula;
		/*sleep(3);
		$accion_encrypt=$encriptar->encriptar('mostrar','');
		$id_encrypt=$encriptar->encriptar($txt_cedula,'');
		$pag='usuarios.php?accion='.$accion_encrypt.'&id='.$id_encrypt;
		redireccionar_js($pag,$tiempoMS);
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
