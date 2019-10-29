<?php
include_once "AccesoDatos.php";
class Producto
{
    public $id;
    public $descripcion;
    public $precio;
    public $sector;
    public $tiempoEstipulado;

public static function TraerTodosLosProductos() 
{
    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from productos ");  
	$consulta->execute();
	$producto= $consulta->fetchAll(PDO::FETCH_CLASS, "Producto");
            
    return $producto;
									
}
public static function TraerUnProducto($id) 
{
    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
    $consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from productos WHERE id= :id ");  
    $consulta->bindValue(':id', $id, PDO::PARAM_INT);
	$consulta->execute();
	$producto= $consulta->fetchObject('Producto');
            
    return $producto;
									
}
public function ModificarProductoParametros()
{
       $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
       $consulta =$objetoAccesoDato->RetornarConsulta("
           update productos 
           set descripcion=:descripcion,
           precio=:precio,
           sector=:sector
           WHERE id=:id");
       $consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
       $consulta->bindValue(':descripcion',$this->descripcion, PDO::PARAM_STR);
       $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
       $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
       return $consulta->execute();
}

public function BorrarUnProducto(){
	$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	$consulta = $objetoAccesoDato->RetornarConsulta("DELETE from `productos` WHERE id=:id");	
	$consulta->bindValue(':id', $this->id, PDO::PARAM_INT);		
	$consulta->execute();
	return $consulta->rowCount();
}
public function InsertarProducto()
	 {
		
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into clientes (descripcion,precio,tiempoEstipulado,sector) VALUES(:descripcion, :precio, :tiempoEstipulado, :sector)");

		$consulta->bindValue(':descripcion',$this->descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':precio',$this->precio, PDO::PARAM_INT);
        $consulta->bindValue(':tiempoEstipulado',$this->tiempoEstipulado, PDO::PARAM_INT);
        $consulta->bindValue(':sector',$this->sector, PDO::PARAM_STR);
		
		 $consulta->execute();

		 return $objetoAccesoDato->RetornarUltimoIdInsertado();		
		 	
	 }
/*
public static function TraerProducto($nombre) 
{
    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
    $consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from productos WHERE nombre= :nombre ");  
    $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
	$consulta->execute();
	$producto= $consulta->fetchObject('Producto');
            
    return $producto;
									
}*/
/*
public function BorrarProducto()
{
    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
    $consulta =$objetoAccesoDato->RetornarConsulta("
        delete 
        from productos 				
        WHERE nombre=:nombre");	
    $consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_STR);		
    $consulta->execute();
    return $consulta->rowCount();
}


public function ModificarProducto()
{

    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
    $consulta =$objetoAccesoDato->RetornarConsulta("
           update productos 
           set precio='$this->precio'
           WHERE nombre='$this->nombre'");
           
    return $consulta->execute();

}

*/
}

?>