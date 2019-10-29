<?php
include_once "AccesoDatos.php";
class PedidoDetalle{
public $id;
public $idPedido;
public $idProducto;
public $horaInicio;
public $horaPrometida;
public $horaEntrega;
public $estado;
public $sector;

	public function GuardarPedidoDetalle()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into pedidosdetalle (idPedido, idProducto,estado, sector)values(:idPedido, :idProducto, :estado, :sector)");
		$consulta->bindValue(':idProducto', $this->idProducto, PDO::PARAM_INT);
		$consulta->bindValue(':idPedido', $this->idPedido, PDO::PARAM_INT);
		$consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
		$consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
		$consulta->execute();
		return $objetoAccesoDato->RetornarUltimoIdInsertado();
	}
	public function AsignarHoraEntrega()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
			UPDATE `pedidosdetalle` SET `horaEntrega`='$this->horaEntrega' WHERE id='$this->id'");
			return $consulta->execute();	   
	}
	public static function TraerUnPedidoDetalle($id) 
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from pedidosdetalle WHERE id= :id ");  
		$consulta->bindValue(':id', $id, PDO::PARAM_INT);
		$consulta->execute();
		$producto= $consulta->fetchObject('PedidoDetalle');
				
		return $producto;
										
	}
	public static function TraerTodoLosPedidos()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT `pedidos`.`id`,`productos`.`descripcion`,`pedidos`.`estado`,`clientes`.`nombre`,`pedidos`.`idMesa` FROM `pedidos` 
			INNER JOIN `productos`
			ON `pedidos`.`idProducto` = `productos`.`id`
			INNER JOIN `clientes`
			ON `pedidos`.`idCliente` = `clientes`.`id`");
			$consulta->execute();			
			return $consulta->fetchAll(PDO::FETCH_CLASS, "Pedido");		
	}
	public static function ListadoHiHfImporte()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT `pedidosdetalle`.`idPedido`,`pedidosdetalle`.`horaInicio`,`pedidosdetalle`.`horaEntrega`,`facturas`.`montoTotal` from `pedidosdetalle` inner join `facturas` ON `facturas`.`idPedido` = `pedidosdetalle`.`idPedido` WHERE `pedidosdetalle`.`horaInicio` <> '' AND `pedidosdetalle`.`horaEntrega` <> '' GROUP BY `pedidosdetalle`.`idPedido`");
			$consulta->execute();			
			return $consulta->fetchAll(PDO::FETCH_CLASS, "PedidoDetalle");		
	}
	public static function TraerMasVendido($fechaMin,$fechaMax)
	{
		
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("SELECT `productos`.`descripcion`, 
		SUM(tablaDerivada.`idProducto`) AS TotalVentas 
		FROM (SELECT * FROM `pedidosdetalle` WHERE (`pedidosdetalle`.`fechaHora` > '$fechaMin' 
		AND `pedidosdetalle`.`fechaHora` < '$fechaMax')) as tablaDerivada INNER JOIN `productos` 
		ON `productos`.`id` = tablaDerivada.`idProducto` GROUP BY tablaDerivada.`idProducto` 
		ORDER BY SUM(tablaDerivada.`idProducto`) DESC LIMIT 0 , 3");
		$consulta->execute();
		return $consulta->fetchAll(PDO::FETCH_CLASS, "Pedido");	
	}
	public static function TraerMenosVendido($fechaMin,$fechaMax)
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT `productos`.`descripcion`, 
			SUM(tablaDerivada.`idProducto`) AS TotalVentas 
			FROM (SELECT * FROM `pedidosdetalle` WHERE (`pedidosdetalle`.`fechaHora` > '$fechaMin' 
			AND `pedidosdetalle`.`fechaHora` < '$fechaMax')) as tablaDerivada INNER JOIN `productos` 
			ON `productos`.`id` = tablaDerivada.`idProducto` GROUP BY tablaDerivada.`idProducto` 
			ORDER BY SUM(tablaDerivada.`idProducto`) ASC LIMIT 0 , 2");
			$consulta->execute();
			return $consulta->fetchAll(PDO::FETCH_CLASS, "Pedido");
	}
	public static function TraerCancelados($fechaMin,$fechaMax)
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * FROM `pedidos` WHERE `pedidos`.`cancelado` = 'si' AND `pedidos`.`fechaHora` > '$fechaMin' AND `pedidos`.`fechaHora` < '$fechaMax'");
			$consulta->execute();
			return $consulta->fetchAll(PDO::FETCH_CLASS, "Pedido");
	}

	public function CambiarEstadoPedido()
	{
			   if($this->estado == 'En Preparacion'){
			   $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			   $consulta =$objetoAccesoDato->RetornarConsulta("
			   UPDATE `pedidos` SET `estado`='En Preparacion' WHERE id='$this->id'");
				   
			   return $consulta->execute();
			   }
			   if($this->estado == 'Listo Para Servir'){
				$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
				$consulta =$objetoAccesoDato->RetornarConsulta("
				UPDATE `pedidos` SET `estado`='Listo Para Servir' WHERE id='$this->id'");
					
				return $consulta->execute();
				}
			   
	}
	public function FinalizarEstadoPedidoDetalle()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("UPDATE `pedidosdetalle` SET `estado`='$this->estado', `horaEntrega`='$this->horaEntrega' WHERE id='$this->id'");
		return $consulta->execute(); 
	}
	public function CambiarEstadoPedidoDetalle()
	{
			   $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			   $consulta =$objetoAccesoDato->RetornarConsulta("
			   UPDATE `pedidosdetalle` SET `horaInicio`='$this->horaInicio', `horaPrometida`='$this->horaPrometida', `estado`='$this->estado'  WHERE (idPedido='$this->idPedido' AND `pedidosdetalle`.`sector`='$this->sector')");
				   
			   return $consulta->execute(); 
	}
	public static function TraerTodosLosPedidoDetalle($id) 
	{
    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
    $consulta =$objetoAccesoDato->RetornarConsulta("SELECT  `pedidosdetalle`.`id`, `productos`.`descripcion` FROM `pedidosdetalle`
	INNER JOIN `productos`
	ON `productos`.`id` = `pedidosdetalle`.`idProducto` 
	WHERE `pedidosdetalle`.`idPedido`= :id ");  
    $consulta->bindValue(':id', $id, PDO::PARAM_INT);
	$consulta->execute();
	return $consulta->fetchAll(PDO::FETCH_CLASS, "PedidoDetalle");	
									
	}
	public static function TraerPedidosDetallePorPedido($idPedido)
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * FROM `pedidosdetalle` WHERE `pedidosdetalle`.`idPedido`=:idPedido");
			$consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
			$consulta->execute();			
			return $consulta->fetchAll(PDO::FETCH_CLASS, "PedidoDetalle");		
	}
	public static function TraerPedidosDetallePorSectorPorEstado($tipoEmpleado, $estado) 
	{
    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	$consulta =$objetoAccesoDato->RetornarConsulta("SELECT DISTINCT `pedidosdetalle`.`id`, `pedidosdetalle`.`idPedido`, `pedidos`.`estado`, `pedidos`.`idMesa` FROM `pedidosdetalle` 
	INNER JOIN `pedidos`
	ON `pedidosdetalle`.`idPedido` = `pedidos`.`id`
	INNER JOIN `productos`
	ON `productos`.`id` = `pedidosdetalle`.`idProducto`
	WHERE `pedidos`.`estado`= :estado && `productos`.`sector` = :tipoEmpleado");
	$consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
	$consulta->bindValue(':tipoEmpleado', $tipoEmpleado, PDO::PARAM_STR);
	$consulta->execute();
	return $consulta->fetchAll(PDO::FETCH_CLASS, "PedidoDetalle");	
									
	}
		public static function TraerPedidosDetallePorSector($tipoEmpleado, $estado) 
	{
    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	$consulta =$objetoAccesoDato->RetornarConsulta("SELECT `pedidosdetalle`.`idPedido`, `productos`.`sector`, `pedidosdetalle`.`id`, `pedidosdetalle`.`estado`, `pedidos`.`idMesa` FROM `pedidosdetalle` 
	INNER JOIN `pedidos`
	ON `pedidosdetalle`.`idPedido` = `pedidos`.`id`
	INNER JOIN `productos`
	ON `productos`.`id` = `pedidosdetalle`.`idProducto`
	WHERE `pedidos`.`termino` = 'no' AND `productos`.`sector`= :tipoEmpleado AND (`pedidosdetalle`.`estado`= :estado OR `pedidosdetalle`.`estado`= 'En Preparacion') group by `pedidosdetalle`.`idPedido`");
	$consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
	$consulta->bindValue(':tipoEmpleado', $tipoEmpleado, PDO::PARAM_STR);
	$consulta->execute();
	return $consulta->fetchAll(PDO::FETCH_CLASS, "PedidoDetalle");	
									
	}
	public static function TraerPedidoDetalleMozo($id) 
	{
    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	$consulta =$objetoAccesoDato->RetornarConsulta("SELECT `pedidosdetalle`.`id`,`pedidos`.`idMesa`,`pedidos`.`foto`,`pedidosdetalle`.`idPedido`, `productos`.`descripcion`,`pedidosdetalle`.`estado` 
	FROM `pedidosdetalle` INNER JOIN `productos` 
	ON `pedidosdetalle`.`idProducto` = `productos`.`id` INNER JOIN `pedidos` 
	ON `pedidosdetalle`.`idPedido` = `pedidos`.`id` WHERE `pedidosdetalle`.`idPedido`= :id");
	$consulta->bindValue(':id', $id, PDO::PARAM_INT);
	$consulta->execute();
	return $consulta->fetchAll(PDO::FETCH_CLASS, "PedidoDetalle");	
									
	}
	public static function TraerPendienteDetalladoParaFactura($id)
	{
    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	$consulta =$objetoAccesoDato->RetornarConsulta("SELECT `pedidosdetalle`.`id`,`productos`.`precio`,`pedidos`.`idMesa`,`pedidosdetalle`.`idPedido`, `productos`.`descripcion`,`pedidosdetalle`.`estado` 
	FROM `pedidosdetalle` INNER JOIN `productos` 
	ON `pedidosdetalle`.`idProducto` = `productos`.`id` INNER JOIN `pedidos` 
	ON `pedidosdetalle`.`idPedido` = `pedidos`.`id` WHERE `pedidosdetalle`.`idPedido`= :id");
	$consulta->bindValue(':id', $id, PDO::PARAM_INT);
	$consulta->execute();
	return $consulta->fetchAll(PDO::FETCH_CLASS, "PedidoDetalle");	
									
	}
	public static function TraerPedidosDetallePorSectorPorNPedido($tipoEmpleado, $idPedido) 
	{
    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	$consulta =$objetoAccesoDato->RetornarConsulta("SELECT `pedidosdetalle`.`idPedido`, `pedidos`.`idMesa` FROM `pedidosdetalle` 
	INNER JOIN `pedidos`
	ON `pedidosdetalle`.`idPedido` = `pedidos`.`id`
	INNER JOIN `productos`
	ON `productos`.`id` = `pedidosdetalle`.`idProducto`
	WHERE  `productos`.`sector` = :tipoEmpleado");
	$consulta =$objetoAccesoDato->RetornarConsulta("SELECT `productos`.`descripcion`,`pedidos`.`foto` FROM `pedidosdetalle` 
	INNER JOIN `productos`
	ON `pedidosdetalle`.`idProducto` = `productos`.`id`
	INNER JOIN `pedidos`
	ON `pedidos`.`id` = `pedidosdetalle`.`idPedido`
	WHERE `pedidosdetalle`.`idPedido` = :idPedido && `productos`.`sector` = :tipoEmpleado");
	$consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_STR);
	$consulta->bindValue(':tipoEmpleado', $tipoEmpleado, PDO::PARAM_STR);
	$consulta->execute();
	return $consulta->fetchAll(PDO::FETCH_CLASS, "PedidoDetalle");	
									
	}
	public function BorrarUnDetalle(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta = $objetoAccesoDato->RetornarConsulta("DELETE from `pedidosdetalle` WHERE id=:id");	
		$consulta->bindValue(':id', $this->id, PDO::PARAM_INT);		
		$consulta->execute();
		return $consulta->rowCount();
    }
	public static function TraerPDEntreFechas($fechaMin,$fechaMax)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("SELECT * from `pedidosdetalle` 
		WHERE `pedidosdetalle`.`fechaHora` > :fechaMin AND `pedidosdetalle`.`fechaHora` < :fechaMax");
		$consulta->bindValue(':fechaMin', $fechaMin, PDO::PARAM_STR);
		$consulta->bindValue(':fechaMax', $fechaMax, PDO::PARAM_STR);
		$consulta->execute();
		$Operaciones=$consulta->fetchAll(PDO::FETCH_CLASS, "PedidoDetalle");
		return $Operaciones;
	}
	public static function TraerUltimoPedidoDetalle()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from pedidosdetalle order by id desc LIMIT 0 , 1 ");
			$consulta->execute();
			return $consulta->fetchAll(PDO::FETCH_CLASS, "PedidoDetalle");
	}
	public function ModificarPedidoDetalleParametros()
		{
       $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
       $consulta =$objetoAccesoDato->RetornarConsulta("
           update pedidosdetalle 
           set idProducto=:idProducto
           WHERE id=:id");
       $consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
       $consulta->bindValue(':idProducto',$this->idProducto, PDO::PARAM_INT);
       return $consulta->execute();
		}
}

?>