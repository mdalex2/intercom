<?php
require_once($_SERVER['DOCUMENT_ROOT']."/clases/class.conexion.php");
class bd_usuarios{
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
	public function obtener_operadores_cuentas_banco($id_clie){
		try{
			$conex= new bd_conexion();
			$conex=$conex->bd_conectarse();
			$sql="SELECT clie.id_clie,clie.desc_clie,clie_operarios.id_oper,clie_operarios.id_cuenta FROM  
			(clie_operarios INNER JOIN clie ON clie.id_clie=clie_operarios.id_oper) 
			where clie_operarios.id_clie=:id_clie";
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
}
?>