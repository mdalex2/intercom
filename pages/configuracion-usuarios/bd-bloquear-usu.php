<?php 
require_once('../../funciones/redireccionar.php');
require_once('../../clases/class.encriptar.php');
require_once("../../clases/class.conexion.php");
$conex= new bd_conexion();
$conex=$conex->bd_conectarse();

$encriptar=new encriptado();
try{
	$id_usuario=$_POST['id_usu'];
//EFECTUO EL GUARDADO DEL USUARIO
	$sql1="update usua_sist set 	
	bloqueado=1 WHERE id_usua_sist=:id_usu";
	$consulta1 = $conex->prepare($sql1); //preparo la conexion evitar sql injection
	//paso los parametros a la consulta para evitar sql injection
	$consulta1->bindParam(':id_usu', $id_usuario, PDO::PARAM_STR);	

	if(!$consulta1->execute()){
		throw new Exception('No se pudo bloquear los datos del usuario');
	} else {
		echo "<script type='text/javascript'>
			$(function(){
			new PNotify({
				title: '<p style=\"color:#000000;\"><img src=\"../../images/icns_mensajeria/admiracion.png\" width=\"32\" height=\"32\" align=\"absmiddle\" style=\"margin-top:-10px;\">&nbsp Notificación</p>',
				text: 'El usuario se bloqueó.',
				type: 'success',
				icon: false
			});

			});
			$('#estatus_$id_usuario').text('Bloqueado');
			</script>";
	}
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