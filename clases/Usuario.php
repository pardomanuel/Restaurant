<?php
include_once "AccesoDatos.php";
class Usuario
{
	public $id;
	public $usuario;
  	public $pass;
	public $estado;
	public $foto;
	public $tipo;


  	public function BorrarUsuario()
	 {
	 		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				delete 
				from usuarios 				
				WHERE id=:id");	
				$consulta->bindValue(':id',$this->id, PDO::PARAM_INT);		
				$consulta->execute();
				return $consulta->rowCount();
	 }


	public function ModificarUsuario()
	 {

			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				update usuarios 
				set usuario='$this->usuario',
				clave='$this->clave',
				perfil='$this->perfil',
				sexo='$this->sexo',
				estado='$this->estado'
				WHERE id='$this->id'");
				
			return $consulta->execute();

	 }
	
  
	 public function InsertarUsuario()
	 {
		 
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into usuarios (usuario, pass, estado, tipo, foto) VALUES(:usuario, :pass, :estado, :tipo, :foto)");

		$consulta->bindValue(':usuario',$this->usuario, PDO::PARAM_STR);
		$consulta->bindValue(':pass', $this->pass, PDO::PARAM_STR);
		$consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
		$consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
		$consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
		
		 $consulta->execute();

		 return $objetoAccesoDato->RetornarUltimoIdInsertado();			

	 }

	  public function ModificarUsuarioParametros()
	 {
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				update usuarios 
				set usuario=:usuario,
				pass=:pass,
				tipo=:tipo,
				foto=:foto
				WHERE id=:id");
			$consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
			$consulta->bindValue(':usuario',$this->usuario, PDO::PARAM_STR);
			$consulta->bindValue(':pass', $this->pass, PDO::PARAM_STR);
			$consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
			$consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
			return $consulta->execute();
	 }
	 public function ModificarUsuarioParametrosSinFoto()
	 {
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				update usuarios 
				set usuario=:usuario,
				pass=:pass,
				tipo=:tipo
				WHERE id=:id");
			$consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
			$consulta->bindValue(':usuario',$this->usuario, PDO::PARAM_STR);
			$consulta->bindValue(':pass', $this->pass, PDO::PARAM_STR);
			$consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
			return $consulta->execute();
	 }


	 public function GuardarUsuario()
	 {

	 	if($this->id>0)
	 		{
	 			$this->ModificarUsuario();
	 		}else {
	 			$this->InsertarUsuario();
	 		}

	 }


  	public static function TraerTodoLosUsuarios()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * FROM usuarios");
			$consulta->execute();			
			return $consulta->fetchAll(PDO::FETCH_CLASS, "Usuario");		
	}
	public static function ListadoUsuarios()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from `usuarios`");
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






    public static function ValidarUsuario($usuario, $pass) 
	{
	
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT  * from usuarios WHERE usuario=:usuario and pass=:pass");
			$consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
			$consulta->bindValue(':pass', $pass, PDO::PARAM_STR);
			$consulta->execute();
			$usuarioBuscado= $consulta->fetchObject('Usuario');			

				return $usuarioBuscado;
			  
	}
	public static function GuardarLogueo($idUsuario) 
	{
			
			date_default_timezone_set('America/Argentina/Buenos_Aires');
			$fecha_actual = date("Y-m-d H:i:s");
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("INSERT INTO `logueos`(`idUsuario`, `fechaHoraLog`) VALUES ($idUsuario, '$fecha_actual')");
			$consulta->execute();		

			return $objetoAccesoDato->RetornarUltimoIdInsertado();
	}

		  public static function SuspenderUsuario($id)
	 {
		 
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("update usuarios set estado='suspendido' WHERE id=:id");
			$consulta->bindValue(':id',$id, PDO::PARAM_INT);

			 $consulta->execute();
			 return "Suspendido";

	 }
		  public static function ActivarUsuario($id)
	 {
		 
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("update usuarios set estado='Activo' WHERE id=:id");
			$consulta->bindValue(':id',$id, PDO::PARAM_INT);

			 $consulta->execute();
			 return "Activo";

	 }

public static function CantidadDeOperacionesUsuario($id)
{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * FROM operaciones WHERE idUsuario=:id");
			$consulta->bindValue(':id', $id, PDO::PARAM_INT);
			 $consulta->execute();
			 return $consulta->rowCount();

      			
}
public static function TraerLogueosPorFecha($fechaMin,$fechaMax)
{			
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT `idUsuario`,`usuarios`.`usuario`,`logueos`.`fechaHoraLog`  FROM `logueos` INNER JOIN `usuarios` ON `usuarios`.`id` = `logueos`.`idUsuario` WHERE `logueos`.`fechaHoraLog` >= :fechaMin AND `logueos`.`fechaHoraLog` <= :fechaMax");
			$consulta->bindValue(':fechaMin', $fechaMin, PDO::PARAM_STR);
			$consulta->bindValue(':fechaMax', $fechaMax, PDO::PARAM_STR);
			$consulta->execute();
			$operaciones= $consulta->fetchAll(PDO::FETCH_CLASS);
			return $operaciones;
			
}
public static function TraerUsuario($id)
{

	$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
	$consulta = $objetoAccesoDato->RetornarConsulta("SELECT * from usuarios WHERE id=:id");
	$consulta->bindValue(':id', $id, PDO::PARAM_INT);
	$consulta->execute();
	$Usuario=$consulta->fetchObject("Usuario");
	return $Usuario;

}

}