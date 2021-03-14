<?php 
require_once($_SERVER['DOCUMENT_ROOT']."/clases/class.conexion.php");
require_once($_SERVER['DOCUMENT_ROOT']."/funciones/func_formato_num.php");

//$encriptar=new encriptado();
class saldos_cuenta{
	public function ver_saldo_cuenta($id_cuenta){
	try{
		$conex= new bd_conexion();
		$conex=$conex->bd_conectarse();
		$formato_num = new formato_numero();
		
		//EFECTUO LA CONSULTA DE LOS ABONOS
		$sql1="select sum(monto) as saldo,cate_tipo_divisa.tipo_divisa_cort from (cuentas_bancos_movimientos 
		INNER JOIN clie_cuentas_bancos ON cuentas_bancos_movimientos.id_cuenta = clie_cuentas_bancos.id_cuenta
		INNER JOIN cate_tipo_divisa ON clie_cuentas_bancos.id_cate_tipo_divisa = cate_tipo_divisa.id_cate_tipo_divisa 
		) WHERE cuentas_bancos_movimientos.id_cuenta=:id_cuenta";
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
				$tipo_divisa=$fila->tipo_divisa_cort;
				//$saldo_nega=$fila->debe;
			}
			}
		}
		return $formato_num->convertir_mysql_esp($saldo). " ".$tipo_divisa;
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
	}
	
	public function obtener_valor_saldo_cuenta($id_cuenta){
	try{
		$conex= new bd_conexion();
		$conex=$conex->bd_conectarse();
		$formato_num = new formato_numero();
		
		//EFECTUO LA CONSULTA DE LOS ABONOS
		$sql1="select sum(monto) as saldo,cate_tipo_divisa.tipo_divisa_cort from (cuentas_bancos_movimientos 
		INNER JOIN clie_cuentas_bancos ON cuentas_bancos_movimientos.id_cuenta = clie_cuentas_bancos.id_cuenta
		INNER JOIN cate_tipo_divisa ON clie_cuentas_bancos.id_cate_tipo_divisa = cate_tipo_divisa.id_cate_tipo_divisa 
		) WHERE cuentas_bancos_movimientos.id_cuenta=:id_cuenta";
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
				$tipo_divisa=$fila->tipo_divisa_cort;
				//$saldo_nega=$fila->debe;
			}
			}
		}
		return $saldo;
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
	}	
	public function obtener_total_solicitudes($id_operador){
	try{
		$conex= new bd_conexion();
		$conex=$conex->bd_conectarse();
		
		//EFECTUO LA CONSULTA DE LOS ABONOS
		$sql1="select count(*) as total_solic from solicitudes where id_oper=:id_oper and item_proc=0";
		$consulta1 = $conex->prepare($sql1); //preparo la conexion evitar sql injection
		//paso los parametros a la consulta para evitar sql injection
		$consulta1->bindParam(':id_oper', $id_operador, PDO::PARAM_STR);	
		$total_solic=0;
		if(!$consulta1->execute()){
			throw new Exception('No se pudo consultar el total de solicitudes');
		} else {
			if ($consulta1->rowCount() > 0){
			$filas   = $consulta1->fetchAll(\PDO::FETCH_OBJ);
			//var_dump($filas)."<br>";
			foreach ($filas as $fila){
				$total_solic=$fila->total_solic;
				//$saldo_nega=$fila->debe;
			}
			}
		}
		return $total_solic;
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
	}
}
?>