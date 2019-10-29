<?php
include_once "Factura.php";

class FacturaApi {

    public static function GenerarFactura($request, $response, $args) 
        {
            
            $objDelaRespuesta= new stdclass();
            
            $ArrayDeParametros = $request->getParsedBody();
            
            $idPedido= $ArrayDeParametros['id'];
            $montoTotal= $ArrayDeParametros['montoTotal'];

            $nuevaFactura= new Factura();
            $nuevaFactura->idPedido=$idPedido;
            $nuevaFactura->montoTotal=$montoTotal;
            $id=$nuevaFactura->GuardarFactura();

            $objDelaRespuesta->id=$id;
            return $response->withJson($objDelaRespuesta, 200);
            
        }
        public function TraerUnaFactura($request, $response, $args) {
            $id= $args['id'];
            $Factura = new Factura();
            $Factura= Factura::TraerFactura($id);
           $newresponse = $response->withJson($Factura, 200);  
          return $newresponse;
        }
        public function TraerFacturasPorMes($request, $response, $args) {
            $mes= $args['mes'];
            $todos=Factura::TraerFacturasPorMes($mes);
            $newresponse = $response->withJson($todos,200);  
        
            return $newresponse;
        }
        public function Cobrar($request, $response, $args) {

            $ArrayDeParametros = $request->getParsedBody();
            
            $idPedido=$ArrayDeParametros['idPedido'];
            $Factura= new Factura();
            $Factura->idPedido=$idPedido;
            $resultado =$Factura->Cobrar();
            $objDelaRespuesta= new stdclass();
            $objDelaRespuesta->resultado=$resultado;
            $objDelaRespuesta->tarea="modificar";
            return $response->withJson($objDelaRespuesta, 200);		
        }
        public function TraerEntreFechas($request, $response, $args) {
            $respuesta= new stdclass();
            $ArrayDeParametros = $request->getParsedBody();
            $fechaMin=$ArrayDeParametros['fechaMin'];
            $fechaMax=$ArrayDeParametros['fechaMax'];
            $todos=Factura::TraerFacturasEntreFechas($fechaMin,$fechaMax);
            $newresponse = $response->withJson($todos,200);  
        
            return $newresponse;
     
        }
        public function BorrarFactura($request, $response, $args) {
            $objDelaRespuesta= new stdclass();
            $ArrayDeParametros = $request->getParsedBody();
            $idAborrar = $ArrayDeParametros['idABorrar'];
            $unaFactura = new Factura();
            $unaFactura->id = $idAborrar;
            $resultado = $unaFactura->BorrarUnaFactura();
            $objDelaRespuesta= new stdclass();
            $objDelaRespuesta->resultado=$resultado;
            return $response->withJson($objDelaRespuesta, 200);
        }
        public function ModificarFactura($request, $response, $args) {
            $ArrayDeParametros = $request->getParsedBody();
            $miFactura = new Factura();
            $miFactura->id=$ArrayDeParametros['id'];
            $miFactura->idPedido=$ArrayDeParametros['idPedido'];
            $miFactura->montoTotal=$ArrayDeParametros['montoTotal'];
            $miFactura->pago=$ArrayDeParametros['pago'];
            $resultado =$miFactura->ModificarFacturaParametros();
            $objDelaRespuesta= new stdclass();
            $objDelaRespuesta->resultado=$resultado;
            return $response->withJson($objDelaRespuesta, 200);		
        }
        
}

?>