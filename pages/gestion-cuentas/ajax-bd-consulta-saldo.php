<?php 
require_once('../../funciones/redireccionar.php');
//require_once('../../clases/class.encriptar.php');
require_once("../../clases/class.conexion.php");
$conex= new bd_conexion();
$conex=$conex->bd_conectarse();

require_once('../../funciones/func_formato_num.php');
$formato_num = new formato_numero();
//$encriptar=new encriptado();
try{
	$id_cuenta=$_POST['id_cuenta'];
	//EFECTUO LA CONSULTA DE LOS ABONOS
	$sql1="select sum(monto) as saldo from cuentas_bancos_movimientos WHERE id_cuenta=:id_cuenta";
	$consulta1 = $conex->prepare($sql1); //preparo la conexion evitar sql injection
	//paso los parametros a la consulta para evitar sql injection
	$consulta1->bindParam(':id_cuenta', $id_cuenta, PDO::PARAM_STR);	
	$saldo=0.0;
	if(!$consulta1->execute()){
		throw new Exception('No se pudo consultar el saldo de la cuenta');
	} else {
		if ($consulta1->rowCount() > 0){
		$filas   = $consulta1->fetchAll(\PDO::FETCH_OBJ);
		//var_dump($filas)."<br>";
		foreach ($filas as $fila){
			$saldo=$fila->saldo;
			//$saldo_nega=$fila->debe;
		}
		}
		
	}
	echo $formato_num->convertir_mysql_esp($saldo);
	//echo "<br>negativo: ".$saldo_nega;
	
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