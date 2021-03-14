<?php
echo "<option value=''>SELECCIONE...</option>";
	try{
			require_once($_SERVER['DOCUMENT_ROOT']."/clases/class.conexion.php");

			$conex= new bd_conexion();
			$conex=$conex->bd_conectarse();
			echo $id_cate_tipo_usua=$_POST['tipo_oper'];
			// creo que debo poner distinct o group by id_clie
			$sql="SELECT clie.desc_clie,usua_sist_tipo_usua.id_usua_sist,cate_tipo_usua.cate_tipo_usua,usua_sist.bloqueado FROM 
			(usua_sist_tipo_usua 
				INNER JOIN clie ON clie.id_clie=usua_sist_tipo_usua.id_usua_sist 
				INNER JOIN cate_tipo_usua ON cate_tipo_usua.id_cate_tipo_usua=usua_sist_tipo_usua.id_cate_tipo_usua 
				INNER JOIN usua_sist ON usua_sist.id_usua_sist=usua_sist_tipo_usua.id_usua_sist 
			)
			where usua_sist_tipo_usua.id_cate_tipo_usua=:id_cate_tipo_usua AND bloqueado=false";
			$data=array('id_cate_tipo_usua'=>$id_cate_tipo_usua);
			$query1 = $conex->prepare($sql); //evita sql injection
			if(!$query1->execute($data)):
				throw new Exception('No se pudo realizar la consulta con la base de datos');
			elseif ($query1->rowCount()>0):
			//OBTENGO LOS REGISTROS Y LOS MUESTRO
				$filas   = $query1->fetchAll(\PDO::FETCH_OBJ);
				$tot_reg = $query1->rowCount();
				foreach ($filas as $fila):
			?>
				<option value="<?php echo $fila->id_usua_sist;?>"><?php echo $fila->desc_clie;?></option>
		<?php
				endforeach;
			endif;
		} catch (PDOException $PDOException) {
			echo $error= 'Error hacer la consulta: '.$PDOException->getMessage();
			return $error;
		} catch (Exception $e) {
			echo $error='Excepción capturada: '.  $e->getMessage(). '\n';
			return $error;		}
?>