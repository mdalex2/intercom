<?php
class bd_conexion{
	//Método de conexión PDO
	//Atributos ------
	//Métodos
	public function bd_conectarse(){
		$dbsystem='mysql';
		//server local
		$servidor=$_SERVER['SERVER_NAME'];
        if ($servidor=='localhost' || $servidor=='intercom'){
            $host='127.0.0.1';
            $dbname='id5985804_intercom';
            $username='root';
            $passwd='1234';
        } else {
            $host='localhost';
            $dbname='id5985804_intercom';
            $username='id5985804_intercom';
            $passwd='Si2018++';
        }
		//SERVER WEBHOSTSAPP:
		/*
		$host='fdb15.runhosting.com';
		$dbname='2526247_hogarisima';
		$username='2526247_hogarisima';
		$passwd='Sh2017++';
		*/
		// ---------------------------
		//SERVER 000WEBHOST:
		/*
		$host='localhost';
		$dbname='id3536949_hogarisima';
		$username='id3536949_hogarisima';
		$passwd='Sh2018++';
		*/
		
		$connection = null;
		$dsn=$dbsystem.':host='.$host.';dbname='.$dbname;
		try {    
			$connection = new PDO($dsn, $username, $passwd);
			$connection->exec("set names utf8"); //para charset y acentos			
			$connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); //para controlar errores en la app (muestra errores)
			return $connection;
		} catch (PDOException $pdoExcetion) {
			$connection = null;
			echo 'Error al establecer la conexión: '.$pdoExcetion;
		}
	}		
}
/*
$conex=new bd_conexion();
$conex->bd_conectarse();
*/
?>