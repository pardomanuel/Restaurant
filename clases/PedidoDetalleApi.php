<?php
include_once "PedidoDetalle.php";

class PedidoDetalleApi {

    public static function Cargar($request, $response, $args) 
{
     	
        $objDelaRespuesta= new stdclass();
        
        $ArrayDeParametros = $request->getParsedBody();
        
        $idProducto= $ArrayDeParametros['idProducto'];
        $idPedido= $ArrayDeParametros['idPedido'];
        $estado= $ArrayDeParametros['estado'];
        $sector = $ArrayDeParametros['sector'];
    
            $nuevoPedido= new PedidoDetalle();
            $nuevoPedido->idProducto=$idProducto;
            $nuevoPedido->idPedido= $idPedido;
            $nuevoPedido->estado= $estado;
            $nuevoPedido->sector = $sector;
            $idPedido=$nuevoPedido->GuardarPedidoDetalle();

        $objDelaRespuesta->idPedido=$idPedido;
        return $response->withJson($objDelaRespuesta, 200);
        
    }

    public function TraerTodos($request, $response, $args) {
    $todosLosPedidos=Pedido::TraerTodoLosPedidos();
    $newresponse = $response->withJson($todosLosPedidos,200);  
  
    return $newresponse;
 
    }
    public function ListadoHiHfImporte($request, $response, $args) {
    $todosLosPedidos=PedidoDetalle::ListadoHiHfImporte();
    $newresponse = $response->withJson($todosLosPedidos,200);  
  
    return $newresponse;
 
    }
    public static function MostrarPedidoDetalle($request, $response, $args) {
        $ArrayDeParametros = $request->getParsedBody();
        $id=$ArrayDeParametros['idPedidoDetalle'];
        $PedidoDetalle = new PedidoDetalle();
        $PedidoDetalle= PedidoDetalle::TraerTodosLosPedidoDetalle($id);
        $newresponse = $response->withJson($PedidoDetalle, 200);  
        return $newresponse;
    }
    
    public static function TraerPendientesPorSector($request, $response, $args) {
        $ArrayDeParametros = $request->getParsedBody();
        $tipoEmpleado=$ArrayDeParametros['tipoEmpleado'];
        $estado = $ArrayDeParametros['estado'];
        $PedidoDetalle = new PedidoDetalle();
        $PedidoDetalle= PedidoDetalle::TraerPedidosDetallePorSector($tipoEmpleado,$estado);
        $newresponse = $response->withJson($PedidoDetalle, 200);
        return $newresponse;
    }
    public function TraerUnPedidoDetalle($request, $response, $args) {
        $ArrayDeParametros = $request->getParsedBody();
        $id=$ArrayDeParametros['id'];
        $PedidoDetalle = new PedidoDetalle();
        $PedidoDetalle= PedidoDetalle::TraerUnPedidoDetalle($id);
        $newresponse = $response->withJson($PedidoDetalle, 200);  
        return $newresponse;
    }
    public function ChequeoFinPEdido($request, $response, $args) {
        $ArrayDeParametros = $request->getParsedBody();
        $idPedido=$ArrayDeParametros['idPedido'];
        $PedidoDetalle = new PedidoDetalle();
        $PedidoDetalle= PedidoDetalle::TraerPedidosDetallePorPedido($idPedido);
        $newresponse = $response->withJson($PedidoDetalle, 200);  
        return $newresponse;
    }
    public static function TraerPendienteDetallado($request, $response, $args) {
        $ArrayDeParametros = $request->getParsedBody();
        $tipoEmpleado=$ArrayDeParametros['tipoEmpleado'];
        $idPedido = $ArrayDeParametros['idPedido'];
        $PedidoDetalle = new PedidoDetalle();
        $PedidoDetalle= PedidoDetalle::TraerPedidosDetallePorSectorPorNPedido($tipoEmpleado,$idPedido);
        $newresponse = $response->withJson($PedidoDetalle, 200);  
        return $newresponse;
    }
    public static function TraerPendienteDetalladoMozo($request, $response, $args) {
        $ArrayDeParametros = $request->getParsedBody();
        $id=$ArrayDeParametros['id'];
        $PedidoDetalle = new PedidoDetalle();
        $PedidoDetalle= PedidoDetalle::TraerPedidoDetalleMozo($id);
        $newresponse = $response->withJson($PedidoDetalle, 200);  
        return $newresponse;
    }
    public function TraerPendienteDetalladoParaFactura($request, $response, $args) {
        $id= $args['id'];
        $PedidoDetalle = new PedidoDetalle();
        $PedidoDetalle= PedidoDetalle::TraerPendienteDetalladoParaFactura($id);
        $newresponse = $response->withJson($PedidoDetalle, 200);
        return $newresponse;
    }
    public function AsignarHoraEntrega($request, $response, $args) {

        $ArrayDeParametros = $request->getParsedBody();
        
        $id=$ArrayDeParametros['id'];
        $horaEntrega=$ArrayDeParametros['horaEntrega'];
        $PedidoDetalle= new PedidoDetalle();
        $PedidoDetalle->id=$id;
        $PedidoDetalle->horaEntrega=$horaEntrega;
        $resultado =$PedidoDetalle->AsignarHoraEntrega();
        $objDelaRespuesta= new stdclass();
        $objDelaRespuesta->resultado=$resultado;
        $objDelaRespuesta->tarea="modificar";
        return $response->withJson($objDelaRespuesta, 200);		
   }
    public function CambiarEstado($request, $response, $args) {

        $ArrayDeParametros = $request->getParsedBody();
        
        $id=$ArrayDeParametros['id'];
        $estado=$ArrayDeParametros['estado'];
        $Pedido= new Pedido();
        $Pedido->id=$id;
        $Pedido->estado=$estado;
        $resultado =$Pedido->CambiarEstadoPedido();
        $objDelaRespuesta= new stdclass();
        $objDelaRespuesta->resultado=$resultado;
        $objDelaRespuesta->tarea="modificar";
        return $response->withJson($objDelaRespuesta, 200);		
   }
   public function FinalizarEstado($request, $response, $args) {

        $ArrayDeParametros = $request->getParsedBody();
        
        $id=$ArrayDeParametros['id'];
        $estado=$ArrayDeParametros['estado'];
        $horaEntrega = $ArrayDeParametros['horaEntrega'];
        $Pedido= new PedidoDetalle();
        $Pedido->id=$id;
        $Pedido->estado=$estado;
        $Pedido->horaEntrega = $horaEntrega;
        $resultado =$Pedido->FinalizarEstadoPedidoDetalle();
        $objDelaRespuesta= new stdclass();
        $objDelaRespuesta->resultado=$resultado;
        $objDelaRespuesta->tarea="modificar";
        return $response->withJson($objDelaRespuesta, 200);		
   }
    public function GuardarHoraInicioYPrometida($request, $response, $args) {

        $ArrayDeParametros = $request->getParsedBody();
        
        $id=$ArrayDeParametros['idPedido'];
        $horaInicio=$ArrayDeParametros['horaInicio'];
        $horaPrometida=$ArrayDeParametros['horaPrometida'];
        $estado=$ArrayDeParametros['estado'];
        $sector =$ArrayDeParametros['sector'];
        $PedidoDetalle= new PedidoDetalle();
        $PedidoDetalle->idPedido=$id;
        $PedidoDetalle->horaInicio=$horaInicio;
        $PedidoDetalle->horaPrometida=$horaPrometida;
        $PedidoDetalle->estado=$estado;
        $PedidoDetalle->sector=$sector;
        $resultado =$PedidoDetalle->CambiarEstadoPedidoDetalle();
        $objDelaRespuesta= new stdclass();
        $objDelaRespuesta->resultado=$resultado;
        $objDelaRespuesta->tarea="modificar";
        return $response->withJson($objDelaRespuesta, 200);		
   }

    public function BorrarProductoDetalle($request, $response, $args) {
        $objDelaRespuesta= new stdclass();
        $ArrayDeParametros = $request->getParsedBody();
        $idAborrar = $ArrayDeParametros['idABorrar'];
        $unProductoDetalle = new PedidoDetalle();
        $unProductoDetalle->id = $idAborrar;
        $resultado = $unProductoDetalle->BorrarUnDetalle();
        $objDelaRespuesta= new stdclass();
        $objDelaRespuesta->resultado=$resultado;
        return $response->withJson($objDelaRespuesta, 200);
    }
    public static function MasVendido($request, $response, $args){
        $ArrayDeParametros = $request->getParsedBody();
        $fechaMin = $ArrayDeParametros['fechaMin'];
        $fechaMax = $ArrayDeParametros['fechaMax'];
        $PedidoDetalle = new PedidoDetalle();
        $PedidoDetalle= PedidoDetalle::TraerMasVendido($fechaMin,$fechaMax);
        $newresponse = $response->withJson($PedidoDetalle, 200);  
        return $newresponse;
    }
    public static function MenosVendido($request, $response, $args) {
        $ArrayDeParametros = $request->getParsedBody();
        $fechaMin = $ArrayDeParametros['fechaMin'];
        $fechaMax = $ArrayDeParametros['fechaMax'];
        $PedidoDetalle = new PedidoDetalle();
        $PedidoDetalle= PedidoDetalle::TraerMenosVendido($fechaMin,$fechaMax);
        $newresponse = $response->withJson($PedidoDetalle, 200);  
        return $newresponse;
    }
    public static function TraerCancelados($request, $response, $args) {
        $ArrayDeParametros = $request->getParsedBody();
        $fechaMin = $ArrayDeParametros['fechaMin'];
        $fechaMax = $ArrayDeParametros['fechaMax'];
        $PedidoDetalle = new PedidoDetalle();
        $PedidoDetalle= PedidoDetalle::TraerCancelados($fechaMin,$fechaMax);
        $newresponse = $response->withJson($PedidoDetalle, 200);  
        return $newresponse;
    }
    public function TraerEntreFechas($request, $response, $args) {
        $respuesta= new stdclass();
     	$ArrayDeParametros = $request->getParsedBody();
        $fechaMin=$ArrayDeParametros['fechaMin'];
        $fechaMax=$ArrayDeParametros['fechaMax'];
        $todos=PedidoDetalle::TraerPDEntreFechas($fechaMin,$fechaMax);
        $newresponse = $response->withJson($todos,200);  
      
        return $newresponse;
     
    }
    public static function TraerUltimoPedidoDetalle($request, $response, $args) {
        $ArrayDeParametros = $request->getParsedBody();
        $PedidoDetalle = new PedidoDetalle();
        $PedidoDetalle= PedidoDetalle::TraerUltimoPedidoDetalle();
        $newresponse = $response->withJson($PedidoDetalle, 200);  
        return $newresponse;
    }
    public function ModificarPedidoDetalle($request, $response, $args) {
        $ArrayDeParametros = $request->getParsedBody();
        $miPedidoDetalle = new PedidoDetalle();
        $miPedidoDetalle->id=$ArrayDeParametros['id'];
        $miPedidoDetalle->idProducto=$ArrayDeParametros['idProducto'];
        $resultado =$miPedidoDetalle->ModificarPedidoDetalleParametros();
        $objDelaRespuesta= new stdclass();
        $objDelaRespuesta->resultado=$resultado;
        return $response->withJson($objDelaRespuesta, 200);		
    }
}

?>