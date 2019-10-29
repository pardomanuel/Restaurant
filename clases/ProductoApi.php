<?php
include_once "Producto.php";

class ProductoApi{

    
    /*public function TraerProducto($request, $response, $args) {
        $nombre= $args['nombre'];
        $producto = new Producto();
       $producto= Producto::TraerProducto($nombre);
       $newresponse = $response->withJson($producto, 200);  
      return $newresponse;
    }*/
    public function TraerUnProducto($request, $response, $args) {
        $ArrayDeParametros = $request->getParsedBody();
        $id=$ArrayDeParametros['idProducto'];
        $Producto = new Producto();
        $Producto= Producto::TraerUnProducto($id);
        $newresponse = $response->withJson($Producto, 200);  
        return $newresponse;
    }

    public function TraerTodos($request, $response, $args) {
       $todosLosProductos=Producto::TraerTodosLosProductos();
       $newresponse = $response->withJson($todosLosProductos,200);  
      
      return $newresponse;
     
  }
  public function ModificarUnProducto($request, $response, $args) {
    $ArrayDeParametros = $request->getParsedBody();
    $miProducto = new Producto();
    $miProducto->id=$ArrayDeParametros['id'];
    $miProducto->descripcion=$ArrayDeParametros['descripcion'];
    $miProducto->precio=$ArrayDeParametros['precio'];
    $miProducto->sector=$ArrayDeParametros['sector'];
    $resultado =$miProducto->ModificarProductoParametros();
    $objDelaRespuesta= new stdclass();
    $objDelaRespuesta->resultado=$resultado;
    return $response->withJson($objDelaRespuesta, 200);		
    }

        public function BorrarProducto($request, $response, $args) {
        $objDelaRespuesta= new stdclass();
        $ArrayDeParametros = $request->getParsedBody();
        $idAborrar = $ArrayDeParametros['idABorrar'];
        $unProducto = new Producto();
        $unProducto->id = $idAborrar;
        $resultado = $unProducto->BorrarUnProducto();
        $objDelaRespuesta= new stdclass();
        $objDelaRespuesta->resultado=$resultado;
        return $response->withJson($objDelaRespuesta, 200);
    }
    public function CargarProducto($request, $response, $args) {
     	
        $objDelaRespuesta= new stdclass();
        
        $ArrayDeParametros = $request->getParsedBody();
        $descripcion= $ArrayDeParametros['descripcion'];
        $precio= $ArrayDeParametros['precio'];
        $sector= $ArrayDeParametros['sector'];
        $tiempoEstipulado= $ArrayDeParametros['tiempoEstipulado'];

        $miProducto= new Producto();
        $miProducto->descripcion=$descripcion;
        $miProducto->precio=$precio;
        $miProducto->sector=$sector;
        $miProducto->tiempoEstipulado=$tiempoEstipulado;
        $ultimoId=$miProducto->InsertarProducto();    
        
        $objDelaRespuesta->respuesta="Se guardo el Producto.";
        $objDelaRespuesta->ultimoIdGrabado=$ultimoId;   
        return $response->withJson($objDelaRespuesta, 200);
    }
}
?>