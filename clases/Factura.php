<?php
include_once "AccesoDatos.php";
class Factura{
public $id;
public $idPedido;
public $montoTotal;
public $pago;

    public function GuardarFactura()
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into `facturas` (`idPedido`, `montoTotal`)values(:idPedido, :montoTotal)");
            $consulta->bindValue(':idPedido', $this->idPedido, PDO::PARAM_INT);
            $consulta->bindValue(':montoTotal', $this->montoTotal, PDO::PARAM_INT);
            $consulta->execute();
            return $objetoAccesoDato->RetornarUltimoIdInsertado();
        }
    public static function TraerFactura($id)
        {

            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * from facturas WHERE idPedido=:id");
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->execute();
            $factura=$consulta->fetchObject("Factura");
            return $factura;

        }
    public function Cobrar()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("UPDATE `facturas` SET `pago`= 'si' WHERE idPedido='$this->idPedido'");
        
        
		return $consulta->execute();  
	}
    public static function TraerFacturasEntreFechas($fechaMin,$fechaMax)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("SELECT * from `facturas` 
		WHERE `facturas`.`fechaHora` > :fechaMin AND `facturas`.`fechaHora` < :fechaMax");
		$consulta->bindValue(':fechaMin', $fechaMin, PDO::PARAM_STR);
		$consulta->bindValue(':fechaMax', $fechaMax, PDO::PARAM_STR);
		$consulta->execute();
		$Operaciones=$consulta->fetchAll(PDO::FETCH_CLASS, "Factura");
		return $Operaciones;
	}
    public static function TraerFacturasPorMes($mes)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("SELECT * from `facturas` 
		WHERE date_format(`fechaHora`, '%m') = $mes");
		$consulta->execute();
		$Operaciones=$consulta->fetchAll(PDO::FETCH_CLASS, "Factura");
		return $Operaciones;
	}
	public function BorrarUnaFactura(){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta = $objetoAccesoDato->RetornarConsulta("DELETE from `facturas` WHERE id=:id");	
		$consulta->bindValue(':id', $this->id, PDO::PARAM_INT);		
		$consulta->execute();
		return $consulta->rowCount();
	}
	public function ModificarFacturaParametros()
	{
       $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
       $consulta =$objetoAccesoDato->RetornarConsulta("
           update facturas 
		   set idPedido=:idPedido,
		   montoTotal=:montoTotal,
		   pago=:pago
           WHERE id=:id");
	   $consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
	   $consulta->bindValue(':montoTotal',$this->montoTotal, PDO::PARAM_INT);
       $consulta->bindValue(':pago',$this->pago, PDO::PARAM_STR);
       return $consulta->execute();
	}
}

?>