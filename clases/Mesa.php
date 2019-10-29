<?php
include_once "AccesoDatos.php";
class Mesa
{
    public $id;
    public $estado;
	public $codigo;

public static function TraerTodasLasMesas() 
{
    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from mesas");  
	$consulta->execute();
	$producto= $consulta->fetchAll(PDO::FETCH_CLASS, "Mesa");
            
    return $producto;
									
}
public static function TraerLaQueMasFacturo() 
{
    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	$consulta =$objetoAccesoDato->RetornarConsulta("SELECT `mesas`.`id`,`mesas`.`codigo`,`mesas`.`facturacion` from `mesas` ORDER BY `mesas`.`facturacion` DESC LIMIT 0 , 1");  
	$consulta->execute();
	$Mesa= $consulta->fetchObject('Mesa');
            
    return $Mesa;
									
}
public static function TraerLaQueMenosFacturo() 
{
    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	$consulta =$objetoAccesoDato->RetornarConsulta("SELECT `mesas`.`id`,`mesas`.`codigo`,`mesas`.`facturacion` from `mesas` ORDER BY `mesas`.`facturacion` ASC LIMIT 0 , 1");  
	$consulta->execute();
	$Mesa= $consulta->fetchObject('Mesa');
            
    return $Mesa;
									
}
public static function TraerLaQueTuvoFacturaMasImporte() 
{
    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	$consulta =$objetoAccesoDato->RetornarConsulta("SELECT `pedidos`.`idMesa` FROM `facturas` INNER JOIN `pedidos` ON `pedidos`.`id` = `facturas`.`idPedido` ORDER BY `facturas`.`montoTotal` DESC LIMIT 0,2 ");  
	$consulta->execute();
	$Mesa= $consulta->fetchAll(PDO::FETCH_CLASS, "Mesa");
            
    return $Mesa;
									
}
public static function TraerLaQueTuvoFacturaMenosImporte() 
{
    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	$consulta =$objetoAccesoDato->RetornarConsulta("SELECT `pedidos`.`idMesa` FROM `facturas` INNER JOIN `pedidos` ON `pedidos`.`id` = `facturas`.`idPedido` ORDER BY `facturas`.`montoTotal` ASC LIMIT 0,2");  
	$consulta->execute();
	$Mesa= $consulta->fetchAll(PDO::FETCH_CLASS, "Mesa");
            
    return $Mesa;
									
}

public static function TraerProducto($nombre) 
{
    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
    $consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from productos WHERE nombre= :nombre ");  
    $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
	$consulta->execute();
	$producto= $consulta->fetchObject('Producto');
            
    return $producto;
									
}
public static function TraerMesa($id)
{

	$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
	$consulta = $objetoAccesoDato->RetornarConsulta("SELECT * from mesas WHERE id=:id");
	$consulta->bindValue(':id', $id, PDO::PARAM_INT);
	$consulta->execute();
	$Mesa=$consulta->fetchObject("Mesa");
	return $Mesa;

}
public function SumarFacturacion($total,$id)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("UPDATE `mesas` SET facturacion= $total WHERE id= $id");
        

		return $consulta->execute();   
	}
public function CambiarEstadoMesa($estado)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("UPDATE `mesas` SET estado= :estado WHERE id='$this->id'");
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);

		return $consulta->execute();   
	}
    public static function TraerMenosUsada($fechaMin,$fechaMax)
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT `pedidos`.`idMesa`,count(`pedidos`.`idMesa`) as cantidad from `pedidos` WHERE (`pedidos`.`fechaHora` > '$fechaMin' 
            AND `pedidos`.`fechaHora` < '$fechaMax') group by idmesa  DESC LIMIT 0 , 1");
			$consulta->execute();
			return $consulta->fetchAll(PDO::FETCH_CLASS, "Mesa");
	}
    public static function TraerMasUsada($fechaMin,$fechaMax)
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
			$consulta = $objetoAccesoDato->RetornarConsulta("SELECT `pedidos`.`idMesa`,count(`pedidos`.`idMesa`) as cantidad from `pedidos` WHERE (`pedidos`.`fechaHora` > '$fechaMin' 
            AND `pedidos`.`fechaHora` < '$fechaMax') group by idmesa  ASC LIMIT 0 , 1");
			$consulta->execute();
			return $consulta->fetchAll(PDO::FETCH_CLASS, "Mesa");
	}
public static function FacturacionDeMesasEntreFechas($fechaMin,$fechaMax,$id)
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT `pedidos`.`idMesa`,SUM(`facturas`.`montoTotal`) as facturo from `facturas` INNER JOIN `pedidos` ON `pedidos`.`idFactura` = `facturas`.`id` 
            WHERE `pedidos`.`fechaHora` > '$fechaMin' AND `pedidos`.`fechaHora` < '$fechaMax' AND `pedidos`.`idMesa` = $id
            GROUP BY `pedidos`.`idMesa` ORDER BY SUM(facturas.montoTotal)");
			$consulta->execute();
			return $consulta->fetchAll(PDO::FETCH_CLASS, "Mesa");
	}
    
public static function MejoresComentarios()
{
	$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
	$consulta = $objetoAccesoDato->RetornarConsulta("SELECT `encuestas`.`id`,`encuestas`.`idPedido`,`encuestas`.`puntosMesa`,`encuestas`.`puntosRestaurante`,`encuestas`.`puntosMozo`,`encuestas`.`puntosCocinero`,`encuestas`.`experiencia` FROM `encuestas` 
    INNER JOIN `pedidos`
    ON `pedidos`.`id` = `encuestas`.`idPedido`
    ORDER BY `encuestas`.`puntosMesa` DESC LIMIT 0,2");
	$consulta->execute();
	return $consulta->fetchAll(PDO::FETCH_CLASS, "Mesa");
}
public static function PeoresComentarios()
{
	$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
	$consulta = $objetoAccesoDato->RetornarConsulta("SELECT `encuestas`.`id`,`encuestas`.`idPedido`,`encuestas`.`puntosMesa`,`encuestas`.`puntosRestaurante`,`encuestas`.`puntosMozo`,`encuestas`.`puntosCocinero`,`encuestas`.`experiencia` FROM `encuestas` 
    INNER JOIN `pedidos`
    ON `pedidos`.`id` = `encuestas`.`idPedido`
    ORDER BY `encuestas`.`puntosMesa` ASC LIMIT 0,2");
	$consulta->execute();
	return $consulta->fetchAll(PDO::FETCH_CLASS, "Mesa");
}


	public function InsertarMesa()
	 {
		
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into mesas (estado,codigo,facturacion) VALUES(:estado, :codigo, :facturacion)");

		$consulta->bindValue(':estado',$this->estado, PDO::PARAM_STR);
		$consulta->bindValue(':codigo',$this->codigo, PDO::PARAM_INT);
		$consulta->bindValue(':facturacion',$this->facturacion, PDO::PARAM_INT);
		
		 $consulta->execute();

		 return $objetoAccesoDato->RetornarUltimoIdInsertado();		
		 	
	 }
	public function BorrarunaMesa()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta = $objetoAccesoDato->RetornarConsulta("DELETE from `mesas` WHERE id=:id");	
		$consulta->bindValue(':id', $this->id, PDO::PARAM_INT);		
		$consulta->execute();
		return $consulta->rowCount();
	}

	public function ModificarMesaParametros()
	{
       $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
       $consulta =$objetoAccesoDato->RetornarConsulta("
           update mesas 
           set estado=:estado,
		   codigo=:codigo
           WHERE id=:id");
       $consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
       $consulta->bindValue(':estado',$this->estado, PDO::PARAM_STR);
	   $consulta->bindValue(':codigo',$this->codigo, PDO::PARAM_STR);
       return $consulta->execute();
	}
}

?>