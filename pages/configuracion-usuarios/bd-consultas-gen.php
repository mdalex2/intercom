<?php
require_once($_SERVER['DOCUMENT_ROOT']."/clases/class.conexion.php");
class consultas{
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
	
}
?>