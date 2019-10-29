<?php
include_once "Pedido.php";

class PedidoApi {

    public static function IngresarPedido($request, $response, $args) 
{
     	
        $objDelaRespuesta= new stdclass();
        
        $ArrayDeParametros = $request->getParsedBody();
        
        
        $idCliente= $ArrayDeParametros['idCliente'];
        $idMesa= $ArrayDeParametros['idMesa'];
        $foto= $ArrayDeParametros['foto'];
    
            $nuevoPedido= new Pedido();
            $nuevoPedido->idCliente= $idCliente;
            $nuevoPedido->idMesa= $idMesa;
            $nuevoPedido->foto= $foto;
            $nuevoPedido->termino = "no";
            $idPedido=$nuevoPedido->GuardarPedido();

        $objDelaRespuesta->idPedido=$idPedido;
        return $response->withJson($objDelaRespuesta, 200);
        
    }

    public function TraerTodos($request, $response, $args) {
    $todosLosPedidos=Pedido::TraerTodoLosPedidos();
    $newresponse = $response->withJson($todosLosPedidos,200);  
  
    return $newresponse;
 
    }
    public function TraerTodosLosNoEntregados($request, $response, $args) {
    $todosLosPedidos=Pedido::TraerTodosLosNoEntregados();
    $newresponse = $response->withJson($todosLosPedidos,200);  
  
    return $newresponse;
 
    }
    public function TraerUnPedido($request, $response, $args){
        $ArrayDeParametros = $request->getParsedBody();
        $id=$ArrayDeParametros['id'];
        $Pedido = new Pedido();
        $Pedido= Pedido::TraerUnPedido($id);
        $newresponse = $response->withJson($Pedido, 200);  
        return $newresponse;
    }
    public function FinPedido($request, $response, $args) {

        $ArrayDeParametros = $request->getParsedBody();
        
        $id=$ArrayDeParametros['idPedido'];
        $idFactura=$ArrayDeParametros['idFactura'];
        $Pedido= new Pedido();
        $Pedido->id=$id;
        $Pedido->idFactura=$idFactura;
        $resultado =$Pedido->FinPedido();
        $objDelaRespuesta= new stdclass();
        $objDelaRespuesta->resultado=$resultado;
        $objDelaRespuesta->tarea="modificar";
        return $response->withJson($objDelaRespuesta, 200);		
   }
   public function TerminarPedido($request, $response, $args) {

    $ArrayDeParametros = $request->getParsedBody();
    
    $id=$ArrayDeParametros['id'];
    $Pedido= new Pedido();
    $Pedido->id=$id;
    $resultado =$Pedido->TerminarPedido();
    $objDelaRespuesta= new stdclass();
    $objDelaRespuesta->resultado=$resultado;
    $objDelaRespuesta->tarea="modificar";
    return $response->withJson($objDelaRespuesta, 200);		
}
    public function CambiarEstado($request, $response, $args) {

        $ArrayDeParametros = $request->getParsedBody();
        
        $id=$ArrayDeParametros['idPedido'];
        $estado=$ArrayDeParametros['estado'];
        $sector=$ArrayDeParametros['sector'];
        $Pedido= new Pedido();
        $Pedido->id=$id;
        $Pedido->estado=$estado;
        $resultado =$Pedido->CambiarEstadoPedido($sector);
        $objDelaRespuesta= new stdclass();
        $objDelaRespuesta->resultado=$resultado;
        $objDelaRespuesta->tarea="modificar";
        return $response->withJson($objDelaRespuesta, 200);		
   }
   public function TraerUsoDeMesas($request, $response, $args) {
       $mes= $args['mes'];
       $todasLasMesas=Pedido::TraerUsoDeMesas($mes);
       $newresponse = $response->withJson($todasLasMesas,200);  
      
      return $newresponse;
     
  }
  public function TraerUsoDeMesasGlobal($request, $response, $args) {
       $todasLasMesas=Pedido::TraerUsoDeMesasGlobal();
       $newresponse = $response->withJson($todasLasMesas,200);  
      
      return $newresponse;
     
  }
  public function TraerTodosLosPedidos($request, $response, $args) {
    $todosLosPedidos=Pedido::TraerTodosLosPedidos();
    $newresponse = $response->withJson($todosLosPedidos,200);  
   
   return $newresponse;
  
}
  public static function CancelarPedido($request, $response, $args){
    $ArrayDeParametros = $request->getParsedBody();
    $unPedido = new Pedido();
    $unPedido->id=$ArrayDeParametros['id'];
    $resultado =$unPedido->CancelarPedido();
    
    $objDelaRespuesta= new stdclass();
    $objDelaRespuesta->resultado=$resultado;
    return $response->withJson($objDelaRespuesta, 200);
    }

    public function CargarPedidoConFoto($request, $response, $args) {
     	$objDelaRespuesta= new stdclass();
        $resultado;
        $ArrayDeParametros = $request->getParsedBody();

        $archivos = $request->getUploadedFiles();
        $destino="./images/pedidos/";
        $logo="logo.png";

            $nombreAnterior=$archivos['foto']->getClientFilename();
            $idMesa = $ArrayDeParametros['mesa'];
            $idCliente = $ArrayDeParametros['idCliente'];
            $extension= explode(".", $nombreAnterior);
            $extension=array_reverse($extension);
            $nomFoto = $idCliente.".".$extension[0];
            
            
            $nuevoPedido= new Pedido();
            $nuevoPedido->idCliente= $idCliente;
            $nuevoPedido->idMesa= $idMesa;
            $nuevoPedido->foto= $nomFoto;
            $nuevoPedido->termino = "no";
            $ultimoDestinoFoto=$destino.$nomFoto;
            $idPedido=$nuevoPedido->GuardarPedido();

            if(file_exists($ultimoDestinoFoto))
            {
                copy($ultimoDestinoFoto,date("Ymd").".".$extension[0]);
            }

            $archivos['foto']->moveTo($ultimoDestinoFoto);
            $objDelaRespuesta->archivo= $archivos['foto'];
           
            return $response->withJson($objDelaRespuesta, 200);
    }
    public function BorrarPedido($request, $response, $args) {
        $objDelaRespuesta= new stdclass();
        $ArrayDeParametros = $request->getParsedBody();
        $idAborrar = $ArrayDeParametros['idABorrar'];
        $unPedido = new Pedido();
        $unPedido->id = $idAborrar;
        $resultado = $unPedido->BorrarUnPedido();
        $objDelaRespuesta= new stdclass();
        $objDelaRespuesta->resultado=$resultado;
        return $response->withJson($objDelaRespuesta, 200);
    }

}

?>