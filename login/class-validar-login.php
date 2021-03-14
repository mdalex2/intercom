<?php

include_once($_SERVER['DOCUMENT_ROOT']."/clases/class.conexion.php");
class verificar_login{
	public function valida_login($login,$pwd){
		try{
			$conex= new bd_conexion();
			$conex=$conex->bd_conectarse();
			//$login='mdalex2@gmail.co';
			//$pwd='Jm12345++';
			//echo $pwd=$pwd;
			//echo $login=$login;
			$sql="Select usua_sist.id_usua_sist,usua_sist.login,usua_sist.fech_regi,usua_sist.tiem_expi,clie.dire_fisc,clie.desc_clie,clie.telf from (usua_sist 
			inner join clie on usua_sist.id_usua_sist=clie.id_clie 
			) where login=:login AND AES_DECRYPT(pass,:pwd)=:pwd GROUP BY usua_sist.id_usua_sist";
			$consulta = $conex->prepare($sql); //evita sql injection
			$consulta->bindParam(':login', $login, PDO::PARAM_STR);
			$consulta->bindParam(':pwd', $pwd, PDO::PARAM_LOB);
			$consulta->execute(); //se puede o no guardar en variable por si se usa luego
			$filas = $consulta->fetchAll(\PDO::FETCH_OBJ);
			$num_reg=$consulta->rowCount();
			$existe=false;
			//echo $sql;
			//echo "<br>total filas: ".count($filas);
			if ($num_reg>0){
				session_start();
				//agrego los valores a las sesiones y redirecciono al home pero sesionado para poder 
				//habiliar las comprar y el menu personal del usuario
				//echo "<br>Estatus: ".session_status();
				foreach ($filas as $fila){	
					if (strtolower($fila->login)==strtolower($login)){
						$existe=true; 
					
						$_SESSION['logueado']=1; //1=true
						$_SESSION['login']=$login; //email del usuario
						$_SESSION['id_clie']=$fila->id_usua_sist;
						$_SESSION['tiem_expi']=$fila->tiem_expi;
						$_SESSION['desc_clie']=$fila->desc_clie;
						$_SESSION['dire_fisc']=$fila->dire_fisc; //direccion del usuario-cliente
						$_SESSION['telf']=$fila->telf;
                        $_SESSION['fech_regi']=$fila->fech_regi;
                        $_SESSION['zona_hora']= 'America/Caracas';
						 //extraigo el nombre
						$parte = explode(" ",$fila->desc_clie); 
						$_SESSION['prim_nomb']= $parte[0]; // esto muestra la primera palabra del cliente
					}
				}
				//echo "<br>array: ".print_r($filas);
				//echo "<br>existe: ".$existe;
			}
			return $existe;
		} catch (PDOException $pdoExcetion) {
			echo $error= 'Error hacer la consulta: '.$pdoExcetion->getMessage();
			return $error;
		} catch (Exception $e) {
			echo $error='Excepción capturada: '.  $e->getMessage(). '\n';
			return $error;		} 
	} // FIN DE LA FUNCIÓN VALIDA LOGIN
}
?>