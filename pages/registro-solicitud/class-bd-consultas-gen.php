<?php
require_once($_SERVER['DOCUMENT_ROOT']."/clases/class.conexion.php");
class consultas_bd{
	public function obtener_datos_clie($id_clie){
		try{
			$conex= new bd_conexion();
			$conex=$conex->bd_conectarse();
			$sql="SELECT * FROM clie where id_clie=:id_clie";
			$data=array('id_clie'=>$id_clie);
			$consulta = $conex->prepare($sql); //evita sql injection
			$resultado = $consulta->execute($data); //se puede o no guardar en variable por si se usa luego
			return($consulta);

		} catch (PDOException $PDOException) {
			echo $error= 'Error hacer la consulta: '.$PDOException->getMessage();
			return $error;
		} catch (Exception $e) {
			echo $error='Excepción capturada: '.  $e->getMessage(). '\n';
			return $error;		} 
	}
	
	public function obtener_tipo_usua($id_clie){
		try{
			$conex= new bd_conexion();
			$conex=$conex->bd_conectarse();
			$sql="SELECT * FROM usua_sist_tipo_usua where id_usua_sist=:id_clie";
			$data=array('id_clie'=>$id_clie);
			$consulta = $conex->prepare($sql); //evita sql injection
			$resultado = $consulta->execute($data); //se puede o no guardar en variable por si se usa luego
			return($consulta);

		} catch (PDOException $PDOException) {
			echo $error= 'Error hacer la consulta: '.$PDOException->getMessage();
			return $error;
		} catch (Exception $e) {
			echo $error='Excepción capturada: '.  $e->getMessage(). '\n';
			return $error;		} 
	}
	public function obtener_bd_bancos(){
		try{
			$conex= new bd_conexion();
			$conex=$conex->bd_conectarse();
			$sql="SELECT id_banco,deno_banco FROM cate_banco where item_visi=1";
			//$data=array('id_clie'=>$id_clie);
			$consulta = $conex->prepare($sql); //evita sql injection
			$resultado = $consulta->execute(); //se puede o no guardar en variable por si se usa luego
			return($consulta);

		} catch (PDOException $PDOException) {
			echo $error= 'Error hacer la consulta: '.$PDOException->getMessage();
			return $error;
		} catch (Exception $e) {
			echo $error='Excepción capturada: '.  $e->getMessage(). '\n';
			return $error;		} 
	}
	
	public function obtener_tipo_cuenta_bancos(){
		try{
			$conex= new bd_conexion();
			$conex=$conex->bd_conectarse();
			$sql="SELECT id_cate_tipo_cuenta,tipo_cuenta FROM cate_tipo_cuenta where item_visi=1";
			//$data=array('id_clie'=>$id_clie);
			$consulta = $conex->prepare($sql); //evita sql injection
			$resultado = $consulta->execute(); //se puede o no guardar en variable por si se usa luego
			return($consulta);

		} catch (PDOException $PDOException) {
			echo $error= 'Error hacer la consulta: '.$PDOException->getMessage();
			return $error;
		} catch (Exception $e) {
			echo $error='Excepción capturada: '.  $e->getMessage(). '\n';
			return $error;		} 
	}
	public function obtener_tipo_divisa(){
		try{
			$conex= new bd_conexion();
			$conex=$conex->bd_conectarse();
			$sql="SELECT id_cate_tipo_divisa,tipo_divisa,tipo_divisa_cort FROM cate_tipo_divisa where item_visi=1";
			//$data=array('id_clie'=>$id_clie);
			$consulta = $conex->prepare($sql); //evita sql injection
			$resultado = $consulta->execute(); //se puede o no guardar en variable por si se usa luego
			return($consulta);

		} catch (PDOException $PDOException) {
			echo $error= 'Error hacer la consulta: '.$PDOException->getMessage();
			return $error;
		} catch (Exception $e) {
			echo $error='Excepción capturada: '.  $e->getMessage(). '\n';
			return $error;		} 
	}	
	public function obtener_categ_tipo_usuarios($conex){
		try{
			//$conex= new bd_conexion();
			//$conex=$conex->bd_conectarse();
			$sql="SELECT id_cate_tipo_usua,cate_tipo_usua FROM cate_tipo_usua where item_visi=true ORDER BY cate_tipo_usua";
			$consulta = $conex->prepare($sql); //evita sql injection
			$resultado = $consulta->execute(); //se puede o no guardar en variable por si se usa luego
			//$filas = $consulta->fetchAll(\PDO::FETCH_OBJ);				
			return $consulta;

		} catch (PDOException $PDOException) {
			echo $error= 'Error hacer la consulta: '.$PDOException->getMessage();
			//return $error;
		} catch (Exception $e) {
			echo $error='Excepción capturada: '.  $e->getMessage(). '\n';
			//return $error;		
		} 
	}
	//OBTIENE LOS OPERADORES PARA CADA CUENTA DEPENDIENDO DEL ID
	public function obtener_operadores_cuentas_banco($id_clie,$id_cuenta){
		try{
			$conex= new bd_conexion();
			$conex=$conex->bd_conectarse();
			$sql="SELECT clie.id_clie,clie.desc_clie,clie_operarios.id_oper,clie_operarios.id_cuenta FROM  
			(clie_operarios INNER JOIN clie ON clie.id_clie=clie_operarios.id_oper) 
			where clie_operarios.id_clie=:id_clie and id_cuenta=:id_cuenta";
			$data=array('id_clie'=>$id_clie,
						'id-cuenta'=>$id_cuenta
					   );
			$consulta = $conex->prepare($sql); //evita sql injection
			$resultado = $consulta->execute($data); //se puede o no guardar en variable por si se usa luego
			return($consulta);
		} catch (PDOException $PDOException) {
			echo $error= 'Error hacer la consulta: '.$PDOException->getMessage();
			return $error;
		} catch (Exception $e) {
			echo $error='Excepción capturada: '.  $e->getMessage(). '\n';
			return $error;		} 
	}
	public function obtener_listado_cuentas_asignadas($id_usuario){
		try{
			$conex= new bd_conexion();
			$conex=$conex->bd_conectarse();
			$sql="SELECT clie_operarios.id_cuenta,clie_cuentas_bancos.id_banco,cate_banco.deno_banco,clie_cuentas_bancos.num_cuenta,cate_tipo_cuenta.tipo_cuenta,clie.desc_clie FROM (clie_operarios 
			INNER JOIN clie ON clie.id_clie=clie_operarios.id_clie 
			INNER JOIN clie_cuentas_bancos ON clie_cuentas_bancos.id_cuenta=clie_operarios.id_cuenta 
			INNER JOIN cate_banco on cate_banco.id_banco=clie_cuentas_bancos.id_banco 
			INNER JOIN cate_tipo_cuenta ON cate_tipo_cuenta.id_cate_tipo_cuenta = clie_cuentas_bancos.id_cate_tipo_cuenta
			) 
			where clie_operarios.id_oper=:id_oper ORDER BY id_banco asc";
			$data=array('id_oper'=>$id_usuario);
			$consulta = $conex->prepare($sql); //evita sql injection
			$resultado = $consulta->execute($data); //se puede o no guardar en variable por si se usa luego
			//$filas = $consulta->fetchAll(\PDO::FETCH_OBJ);				
			return $consulta;

		} catch (PDOException $PDOException) {
			echo $error= 'Error hacer la consulta: '.$PDOException->getMessage();
			//return $error;
		} catch (Exception $e) {
			echo $error='Excepción capturada: '.  $e->getMessage(). '\n';
			//return $error;		
		} 
	}
}
?>