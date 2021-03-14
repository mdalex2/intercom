<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
class verificar_sesion{
	public function verifica_expira_sesion(){
		$logueado=(isset($_SESSION['logueado']) && $_SESSION['logueado']==true)? true : false;
		require_once("../../clases/class.conexion.php");
		require_once('../../funciones/redireccionar.php');	
		$conex= new bd_conexion();
		$conex=$conex->bd_conectarse();
		$sql="SELECT ultimo_acceso,on_line,tiem_expi FROM usua_sist where id_usua_sist=:id_usua_sist";
		$consulta = $conex->prepare($sql); //preparo la conexion evitar sql injection
		//paso los parametros a la consulta para evitar sql injection
		$fecha_act=date('Y-m-d H:i:s');
		$usuario_actual=$_SESSION['id_clie'];
		$data = array(
			'id_usua_sist'=> $usuario_actual
		);
		if(!$consulta->execute($data)):
			throw new Exception('No se pudo efectuar la consulta del último acceso');
			session_destroy();
			redireccionar_js('../login/login.php',0);
		else:
			$filas = $consulta->fetchAll(\PDO::FETCH_OBJ);
			$num_reg=$consulta->rowCount();	
			if ($num_reg>0):
				foreach ($filas as $fila){$fech_hora_ini=$fila->ultimo_acceso;$on_line=$fila->on_line;$tiem_expi=$fila->tiem_expi;}
				$date1 = new DateTime($fech_hora_ini);
				$date2 = new DateTime($fecha_act);
				$tiempo_transcurrido = $date1->diff($date2);
				//si tiempo transcurrido es mayor que 5 minutos cierro sesion
				if ($tiempo_transcurrido->i > $tiem_expi):
					//cierro la sessión
					$verificar_sesion = new verificar_sesion();
					$verificar_sesion->desconectar_sesion($id_usuario="");
					
					echo "<script>alert('La sesión a caducado porque pasaron más de $tiem_expi minutos de inactividad, debe ingresar nuevamente');</script>";
					session_destroy();
					redireccionar_js('../login/login.php',0);
					die();
				endif;
			endif;
		endif;
		//SI HAY ERROR DEVUELVO TODO A SU ESTADO ORIGINA
		
		/*
		echo "<script type='text/javascript'>
				$(function(){
				new PNotify({
					title: '<p style=\"color:#000000;\"><img src=\"../../images/icns_mensajeria/admiracion.png\" width=\"32\" height=\"32\" align=\"absmiddle\" style=\"margin-top:-10px;\">&nbsp Notificación</p>',
					text: 'La solicitud de registró exitosamente al operario: .',
					type: 'success',
					icon: false
				});
				});

				</script>";	
		*/
	}
	public function actualizar_ultimo_acceso(){
		require_once("../../clases/class.conexion.php");
		$conex= new bd_conexion();
		$conex=$conex->bd_conectarse();
		$sql="UPDATE usua_sist set on_line=:on_line,ultimo_acceso=:ultimo_acceso where id_usua_sist=:id_usua_sist";
		$consulta = $conex->prepare($sql); //preparo la conexion evitar sql injection
		//paso los parametros a la consulta para evitar sql injection
		$fecha_act=date('Y-m-d H:i:s');
		$online=1;
		$usuario_actual=$_SESSION['id_clie'];
		$data = array(
			'on_line' => $online,
			'ultimo_acceso'=> $fecha_act,
			'id_usua_sist'=> $usuario_actual
		);
		if(!$consulta->execute($data)):
			throw new Exception('No se pudo efectuar la consulta del último acceso');
		endif;
	}
	public function desconectar_sesion($id_usuario){
		require_once("../../clases/class.conexion.php");
		$conex= new bd_conexion();
		$conex=$conex->bd_conectarse();
		$sql="UPDATE usua_sist set on_line=:on_line where id_usua_sist=:id_usua_sist";
		$consulta = $conex->prepare($sql); //preparo la conexion evitar sql injection
		//paso los parametros a la consulta para evitar sql injection
		$fecha_act=date('Y-m-d H:i:s');
		$online=0;
		$usuario_actual=(isset($id_usuario) && $id_usuario!='') ? $id_usuario : $_SESSION['id_clie'];
		$data = array(
			'on_line' => $online,			
			'id_usua_sist'=> $usuario_actual
		);
		if(!$consulta->execute($data)):
			throw new Exception('No se pudo efectuar la consulta del último acceso');
		endif;
	}	
}
?>