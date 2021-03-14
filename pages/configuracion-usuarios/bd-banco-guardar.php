<?php
//OBTENGO LAS VARIABLES DEL POST
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
	$sql="INSERT INTO clie (id_clie,ciudad,desc_clie,dire_fisc,telf,email,fech_regi,nota_obse) VALUES (:id_clie,:ciudad,:desc_clie,:dire_fisc,:telf,:email,:fech_regi,:nota_obse)";
	$consulta = $conex->prepare($sql); //preparo la conexion evitar sql injection
	//paso los parametros a la consulta para evitar sql injection
	$fecha_act=date('Y-m-d H:i:s');
	$visible=true;
	$data = array(
		'id_clie'=> $txt_cedula, 
		'ciudad'=> $city,
		'desc_clie'=>$txt_nom_ape_raz_soc,
		'dire_fisc'=>$address,
		'telf'=>$phone,
		'email'=>$email,
		'fech_regi'=>$fecha_act,
		'nota_obse'=>$txt_nota
	);
	if(!$consulta->execute($data)):
		throw new Exception('No se pudo registrar el cliente');
		$error_transac=true;
	endif;
	
	//EFECTUO EL GUARDADO DEL USUARIO
	$sql1="INSERT INTO usua_sist (id_usua_sist,login,pass,fech_regi) VALUES (:id_usua_sist,:login,aes_encrypt(:pass,:pass),:fech_regi)";
	$consulta1 = $conex->prepare($sql1); //preparo la conexion evitar sql injection
	//paso los parametros a la consulta para evitar sql injection
	$data = array(
		'id_usua_sist'=> $txt_cedula, 
		'login'=> $email,
		'pass'=>$password,
		'fech_regi'=>$fecha_act
	);
	if(!$consulta1->execute($data)):
		throw new Exception('No se pudo registrar los datos del usuario');
		$error_transac=true;
	endif;
	
	//INSERTO EL TIPO DE USUARIO
	$sql2="INSERT INTO usua_sist_tipo_usua (id_usua_sist,id_cate_tipo_usua) VALUES (:id_usua_sist,:id_cate_tipo_usua)";
	$consulta2 = $conex->prepare($sql2); //preparo la conexion evitar sql injection
	//paso los parametros a la consulta para evitar sql injection
	$data = array(
		'id_usua_sist'=> $txt_cedula, 
		'id_cate_tipo_usua'=> $cmb_tip_cuenta
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
		echo "<img src='../../images/sistema/succes_ico.png' width='16px'> Datos registrados, ahora puede agregar cuentas y operadores";
		$accion_encrypt=$encriptar->encriptar('mostrar','');
		$_SESSION['id_clie_abierto']=$txt_cedula;
		$pag='usuarios.php?accion='.$accion_encrypt.'&id='.$txt_cedula;
		redireccionar_js($pag,1000);
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