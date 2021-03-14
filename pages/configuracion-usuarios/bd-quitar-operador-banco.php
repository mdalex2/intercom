<?php 
session_start();
require_once('../../funciones/redireccionar.php');
//require_once('../../clases/class.encriptar.php');
require_once("../../clases/class.conexion.php");
$conex= new bd_conexion();
$conex=$conex->bd_conectarse();

//$encriptar=new encriptado();
try{
	$array_id_cuenta_oper=explode("|",$_POST['id_cuenta_oper']);
	$id_cuenta=$array_id_cuenta_oper[0]; // 0= primer delimitado de | pasado por el id mostrado en la tabla
	$id_oper=$array_id_cuenta_oper[1];   // 1 = al anterior segunda porcion
	$id_clie=$_SESSION['id_clie_abierto'];
//EFECTUO EL GUARDADO DEL USUARIO
	$sql1="delete from clie_operarios WHERE id_cuenta=:id_cuenta and id_oper=:id_oper";
	$consulta1 = $conex->prepare($sql1); //preparo la conexion evitar sql injection
	//paso los parametros a la consulta para evitar sql injection
	$consulta1->bindParam(':id_cuenta', $id_cuenta, PDO::PARAM_STR);
	$consulta1->bindParam(':id_oper', $id_oper, PDO::PARAM_STR);

	if(!$consulta1->execute()){
		throw new Exception('No se pudo quitar el operador');
	} else {
		echo "<script type='text/javascript'>
			$(function(){
			new PNotify({
				title: '<p style=\"color:#000000;\"><img src=\"../../images/icns_mensajeria/admiracion.png\" width=\"32\" height=\"32\" align=\"absmiddle\" style=\"margin-top:-10px;\">&nbsp Notificación</p>',
				text: 'El operador se eliminó, no podrá realizar transacciones hasta que sea asociado nuevamente.',
				type: 'success',
				icon: false
			});

			});
			cargar_cuentas_bancos();
			
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