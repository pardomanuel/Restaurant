<?php
include_once "AccesoDatos.php";
class Cliente
{
	public $id;
	public $nombre;
  	public $idEncuesta;

      
	public function InsertarCliente()
	 {
		
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into clientes (nombre) VALUES(:nombre)");

		$consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_STR);
		
		 $consulta->execute();

		 return $objetoAccesoDato->RetornarUltimoIdInsertado();		
		 	
	 }


  	public static function TraerTodoLosUsuarios()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * FROM usuarios");
			$consulta->execute();			
			return $consulta->fetchAll(PDO::FETCH_CLASS, "Usuario");		
	}

	public static function TraerUnUsuario($id) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select * from usuarios where id = $id");
			$consulta->execute();
			$usuario= $consulta->fetchObject('Usuario');
			return $usuario;				

			
	}
	public static function TraerEstadoDePedido($codigoMesa,$codigoPedido) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT `clientes`.`nombre`,`pedidos`.`id`,`mesas`.`codigo`, `productos`.`descripcion`,`pedidosdetalle`.`estado`,`pedidosdetalle`.`horaInicio`,`pedidosdetalle`.`horaPrometida`,`pedidosdetalle`.`horaEntrega`,`pedidos`.`termino`,`pedidos`.`foto` 
			FROM `pedidosdetalle` 
			INNER JOIN `pedidos` ON `pedidos`.`id` = `pedidosdetalle`.`idPedido` 
			INNER JOIN `clientes` ON `clientes`.`id` = `pedidos`.`idCliente` 
			INNER JOIN `productos` ON `productos`.`id` = `pedidosdetalle`.`idProducto` 
			INNER JOIN `mesas` ON `mesas`.`id` = `pedidos`.`idMesa` 
			WHERE `pedidosdetalle`.`idPedido` = :codigoPedido");
			$consulta->bindValue(':codigoPedido', $codigoPedido, PDO::PARAM_INT);
			$consulta->execute();
			return $consulta->fetchAll(PDO::FETCH_CLASS, "Cliente");				
	}

	public static function FechasDeLogueo()
	{
		$objetoAccesoDato= AccesoDatos::DameUnObjetoAcceso();
		$consulta=$objetoAccesoDato->RetornarConsulta("SELECT u.usuario, s.horaInicio from usuarios as u, sesiones as s where s.idEmpleado=u.id ORDER by u.usuario");
		$consulta->execute();
		$fechas= $consulta->fetchAll(PDO::FETCH_CLASS);
		return $fechas;
	}

	public static function OperacionesTodosLosUsuarios()
	{
		$objetoAccesoDato= AccesoDatos::DameUnObjetoAcceso();
		$consulta=$objetoAccesoDato->RetornarConsulta("SELECT u.usuario as usuario, COUNT(*) as operaciones FROM usuarios as u, pedidodetalle as pd WHERE pd.idEmpleado= u.id GROUP by u.usuario");
		$consulta->execute();
		$operaciones= $consulta->fetchAll(PDO::FETCH_CLASS);
		return $operaciones;
		
	}

	public static function CantidadOperacionesTodosSectores()
	{
		$objetoAccesoDato= AccesoDatos::DameUnObjetoAcceso();
		$consulta=$objetoAccesoDato->RetornarConsulta("SELECT perfil as perfil, COUNT(*) as operaciones from pedidodetalle GROUP by perfil");
		$consulta->execute();
		$operaciones= $consulta->fetchAll(PDO::FETCH_CLASS);
		return $operaciones;
		
	}

	public static function CantidadOperacionesUsuariosSeparado($idUsuario)
	{
		$objetoAccesoDato= AccesoDatos::DameUnObjetoAcceso();
		$consulta=$objetoAccesoDato->RetornarConsulta("SELECT e.usuario, COUNT(*) as operaciones from usuarios as e, pedidodetalle as pd where pd.idUsuario in (SELECT e.id from usuarios WHERE e.id= :idUsuario)");
		$consulta->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
		
		$consulta->execute();
		$operaciones= $consulta->fetchAll(PDO::FETCH_CLASS);
		return $operaciones;
		
	}


	public static function CantidadOperacionesEmpleadoPorSector($perfil)
	{
		
		$objetoAccesoDato= AccesoDatos::DameUnObjetoAcceso();
		$consulta=$objetoAccesoDato->RetornarConsulta("SELECT e.usuario, COUNT(*) as operaciones FROM usuarios as e, pedidodetalle as pd WHERE pd.idUsuario= e.id and pd.perfil=:perfil GROUP by e.usuario");
		$consulta->bindValue(':perfil', $perfil, PDO::PARAM_STR);
		
		$consulta->execute();
		$operaciones= $consulta->fetchAll(PDO::FETCH_CLASS);
		return $operaciones;
		
	}






    public static function ValidarUsuario($usuario, $clave) 
	{
		
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT  * from usuarios WHERE usuario=:usuario and clave=:clave");
			$consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
			$consulta->bindValue(':clave', $clave, PDO::PARAM_STR);
			$consulta->execute();
			$usuarioBuscado= $consulta->fetchObject('Usuario');			

				return $usuarioBuscado;
			  
	}


		  public static function SuspenderUsuario($id, $estado)
	 {
		 
		 if($estado=="activo")
		 {
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("update usuarios set estado='suspendido' WHERE id=:id");
			$consulta->bindValue(':id',$id, PDO::PARAM_INT);

			 $consulta->execute();
			 return "Suspendido";

		 }
		 else
		 {
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("update usuarios set estado='activo' WHERE id=:id");
			$consulta->bindValue(':id',$id, PDO::PARAM_INT);

			 $consulta->execute();
			 return "activado";
		 }

	 }

public static function CantidadDeOperacionesUsuario($id)
{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * FROM operaciones WHERE idUsuario=:id");
			$consulta->bindValue(':id', $id, PDO::PARAM_INT);
			 $consulta->execute();
			 return $consulta->rowCount();

      			
}

public function BorrarUnCliente(){
	$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	$consulta = $objetoAccesoDato->RetornarConsulta("DELETE from `clientes` WHERE id=:id");	
	$consulta->bindValue(':id', $this->id, PDO::PARAM_INT);		
	$consulta->execute();
	return $consulta->rowCount();
}

public function ModificarClienteParametros()
{
       $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
       $consulta =$objetoAccesoDato->RetornarConsulta("
           update clientes 
           set nombre=:nombre
           WHERE id=:id");
       $consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
       $consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_STR);
       return $consulta->execute();
}



}