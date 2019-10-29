<?php
include_once "AccesoDatos.php";
class Encuesta
{
    public $id;
    public $idPedido;
    public $puntosMesa;
    public $puntosRestaurante;
    public $puntosMozo;
    public $puntosCocinero;
    public $experiencia;

    public function GuardarEncuesta()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into encuestas (idPedido, puntosMesa, puntosRestaurante, puntosMozo, puntosCocinero, experiencia)values(:idPedido, :puntosMesa, :puntosRestaurante, :puntosMozo, :puntosCocinero, :experiencia)");
		$consulta->bindValue(':idPedido', $this->idPedido, PDO::PARAM_INT);
		$consulta->bindValue(':puntosMesa', $this->puntosMesa, PDO::PARAM_INT);
		$consulta->bindValue(':puntosRestaurante', $this->puntosRestaurante, PDO::PARAM_INT);
        $consulta->bindValue(':puntosMozo', $this->puntosMozo, PDO::PARAM_INT);
        $consulta->bindValue(':puntosCocinero', $this->puntosCocinero, PDO::PARAM_INT);
        $consulta->bindValue(':experiencia', $this->experiencia, PDO::PARAM_STR);
		$consulta->execute();
		return $objetoAccesoDato->RetornarUltimoIdInsertado();
	}
    public static function TraerComentariosEntreFechas($fechaMin,$fechaMax)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("SELECT * from `encuestas` 
		WHERE `encuestas`.`fechaHora` > :fechaMin AND `encuestas`.`fechaHora` < :fechaMax");
		$consulta->bindValue(':fechaMin', $fechaMin, PDO::PARAM_STR);
		$consulta->bindValue(':fechaMax', $fechaMax, PDO::PARAM_STR);
		$consulta->execute();
		$Operaciones=$consulta->fetchAll(PDO::FETCH_CLASS, "Encuesta");
		return $Operaciones;
	}

	public function BorrarUnaEncuesta(){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta = $objetoAccesoDato->RetornarConsulta("DELETE from `encuestas` WHERE id=:id");	
		$consulta->bindValue(':id', $this->id, PDO::PARAM_INT);		
		$consulta->execute();
		return $consulta->rowCount();
	}

	public function ModificarEncuestaParametros()
{
       $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
       $consulta =$objetoAccesoDato->RetornarConsulta("
           update encuestas 
		   set idPedido=:idPedido,
		   puntosMesa=:puntosMesa,
		   puntosRestaurante=:puntosRestaurante,
		   puntosMozo=:puntosMozo,
		   puntosCocinero=:puntosCocinero,
		   experiencia=:experiencia
           WHERE id=:id");
	   $consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
	   $consulta->bindValue(':idPedido',$this->idPedido, PDO::PARAM_INT);
	   $consulta->bindValue(':puntosMesa',$this->puntosMesa, PDO::PARAM_INT);
	   $consulta->bindValue(':puntosRestaurante',$this->puntosRestaurante, PDO::PARAM_INT);
	   $consulta->bindValue(':puntosMozo',$this->puntosMozo, PDO::PARAM_INT);
	   $consulta->bindValue(':puntosCocinero',$this->puntosCocinero, PDO::PARAM_INT);
       $consulta->bindValue(':experiencia',$this->experiencia, PDO::PARAM_STR);
       return $consulta->execute();
}
    
}
?>