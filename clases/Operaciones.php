<?php
include_once "AccesoDatos.php";
class Operaciones{
public $id;
public $idUsuario;
public $fechaHora;

public static function GuardarOperacion($idUsuario) 
	{
			
			date_default_timezone_set('America/Argentina/Buenos_Aires');
			$fecha_actual = date("Y-m-d H:i:s");
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("INSERT INTO `operaciones`(`idUsuario`, `fechaHora`) VALUES ($idUsuario, '$fecha_actual')");
			$consulta->execute();		

			return $objetoAccesoDato->RetornarUltimoIdInsertado();
	}

	public static function TraerOperacionesPorSector($sector)
{

	$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
	$consulta = $objetoAccesoDato->RetornarConsulta("SELECT `usuarios`.`tipo`,COUNT(*) FROM `operaciones` 
	INNER JOIN `usuarios` 
	ON `operaciones`.`idUsuario` = `usuarios`.`id` 
	WHERE `usuarios`.`tipo` = :tipo");
	$consulta->bindValue(':tipo', $sector, PDO::PARAM_STR);
	$consulta->execute();
	$Operaciones=$consulta->fetchAll(PDO::FETCH_CLASS, "Operaciones");
	return $Operaciones;

}
public static function CantidadOperacionesPorUsuario($fechaMin,$fechaMax)
{

	$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
	$consulta = $objetoAccesoDato->RetornarConsulta("SELECT `usuarios`.`usuario`, COUNT(`operaciones`.`idUsuario`) as cantidad from `operaciones` INNER JOIN `usuarios` ON `usuarios`.`id` = `operaciones`.`idUsuario` 
	WHERE `operaciones`.`fechaHora` > :fechaMin AND `operaciones`.`fechaHora` < :fechaMax
	group by `operaciones`.`idUsuario` ");
	$consulta->bindValue(':fechaMin', $fechaMin, PDO::PARAM_STR);
	$consulta->bindValue(':fechaMax', $fechaMax, PDO::PARAM_STR);
	$consulta->execute();
	$Operaciones=$consulta->fetchAll(PDO::FETCH_CLASS, "Operaciones");
	return $Operaciones;

}

public static function TraerOperacionesPorSectorPorEmpleado($fechaMin,$fechaMax,$sector)
{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT `usuarios`.`usuario`, COUNT(`operaciones`.`idUsuario`) as cantidad
			FROM `operaciones` 
			INNER JOIN `usuarios`
			ON `usuarios`.`id` = `operaciones`.`idUsuario` 
			WHERE `usuarios`.`tipo` = :sector AND `operaciones`.`fechaHora` > :fechaMin AND `operaciones`.`fechaHora` < :fechaMax group by `operaciones`.`idUsuario`");
			$consulta->bindValue(':fechaMin', $fechaMin, PDO::PARAM_STR);
			$consulta->bindValue(':fechaMax', $fechaMax, PDO::PARAM_STR);
			$consulta->bindValue(':sector', $sector, PDO::PARAM_STR);
			$consulta->execute();
			$operaciones= $consulta->fetchAll(PDO::FETCH_CLASS);
			return $operaciones;
}
	public static function TraerOperacionesPorMes($mes)
		{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT `usuarios`.`usuario`, COUNT(`operaciones`.`idUsuario`) as cantidad from `operaciones` INNER JOIN `usuarios` ON `usuarios`.`id` = `operaciones`.`idUsuario` 
			WHERE date_format(`fechaHora`, '%m') = $mes group by `usuarios`.`usuario`;");
			$consulta->execute();
			return $consulta->fetchAll(PDO::FETCH_CLASS, "Mesa");		
		}
		public function BorrarunaOperacion()
		{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
			$consulta = $objetoAccesoDato->RetornarConsulta("DELETE from `operaciones` WHERE id=:id");
			$consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
			$consulta->execute();
			return $consulta->rowCount();
		}

		public function ModificarOperacionesParametros()
		{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				update operaciones 
				set idUsuario=:idUsuario
				WHERE id=:id");
			$consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
			$consulta->bindValue(':idUsuario',$this->idUsuario, PDO::PARAM_INT);
			return $consulta->execute();
		}
}

?>