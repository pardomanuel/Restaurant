<?php

include_once "Usuario.php";
include_once "AccesoDatos.php";
class Pedido{
public $id;
public $idProducto;
public $idCliente;
public $idMesa;
public $termino;
public $estado;
public $foto;
public $idFactura;

	public function GuardarPedido()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into pedidos (idCliente, idMesa, foto, termino)values(:idCliente, :idMesa, :foto, :termino)");
		$consulta->bindValue(':idCliente', $this->idCliente, PDO::PARAM_INT);
		$consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
		$consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
		$consulta->bindValue(':termino', $this->termino, PDO::PARAM_STR);
		$consulta->execute();
		return $objetoAccesoDato->RetornarUltimoIdInsertado();
	}
	public static function TraerTodoLosPedidos()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT `pedidos`.`id`,`clientes`.`nombre`,`pedidos`.`idMesa` FROM `pedidos` 
			INNER JOIN `clientes`
			ON `pedidos`.`idCliente` = `clientes`.`id`");
			$consulta->execute();
			return $consulta->fetchAll(PDO::FETCH_CLASS, "Pedido");		
	}
	public static function TraerTodosLosNoEntregados()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT `pedidos`.`id`,`pedidos`.`foto`,`clientes`.`nombre`,`pedidos`.`idMesa` FROM `pedidos` 
			INNER JOIN `clientes`
			ON `pedidos`.`idCliente` = `clientes`.`id` 
			WHERE `pedidos`.`termino` = 'no'");
			$consulta->execute();
			return $consulta->fetchAll(PDO::FETCH_CLASS, "Pedido");		
	}
	public function CambiarEstadoPedido($sector)
	{
			   /*if($this->estado == 'En Preparacion'){
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
				}*/
			   $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			   $consulta =$objetoAccesoDato->RetornarConsulta("
			   UPDATE `pedidosdetalle` SET `estado`='$this->estado' WHERE (idPedido='$this->id' AND `pedidosdetalle`.`sector` = '$sector')");
				   
			   return $consulta->execute();
			   
	}
	public function FinPedido()
	{
			   $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			   $consulta =$objetoAccesoDato->RetornarConsulta("
			   UPDATE `pedidos` SET `idFactura`='$this->idFactura' WHERE id='$this->id'");
				   
			   return $consulta->execute();
			   
	}
	public function TerminarPedido()
	{
			   $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			   $consulta =$objetoAccesoDato->RetornarConsulta("
			   UPDATE `pedidos` SET `termino`='si' WHERE id='$this->id'");
				   
			   return $consulta->execute();
			   
	}
	public static function TraerUnPedido($id) 
{
    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
    $consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from pedidos WHERE id= :id ");  
    $consulta->bindValue(':id', $id, PDO::PARAM_INT);
	$consulta->execute();
	$producto= $consulta->fetchObject('Pedido');
            
    return $producto;
									
}
	public static function TraerUsoDeMesas($mes)
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT `pedidos`.`idMesa`,count(*) as cantidad from `pedidos` WHERE date_format(`fechaHora`, '%m') = $mes group by idmesa;");
			$consulta->execute();
			return $consulta->fetchAll(PDO::FETCH_CLASS, "Pedido");		
	}
	public static function TraerUsoDeMesasGlobal()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT `pedidos`.`idMesa`,count(*) as cantidad from `pedidos` group by idmesa;");
			$consulta->execute();
			return $consulta->fetchAll(PDO::FETCH_CLASS, "Pedido");		
	}
	public static function TraerTodosLosPedidos()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from `pedidos`;");
			$consulta->execute();
			return $consulta->fetchAll(PDO::FETCH_CLASS, "Pedido");		
	}
	public function CancelarPedido()
	 {
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				update pedidos 
				set termino= 'si',
				cancelado='si'
				WHERE id=:id");
			$consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
			return $consulta->execute();
	 }
	 public function BorrarUnPedido(){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta = $objetoAccesoDato->RetornarConsulta("DELETE from `pedidos` WHERE id=:id");	
		$consulta->bindValue(':id', $this->id, PDO::PARAM_INT);		
		$consulta->execute();
		return $consulta->rowCount();
		}

}

?>