<?php
require_once 'Cliente.php';


class ClienteApi extends Cliente
{


      public function CargarCliente($request, $response, $args) {
     	
        $objDelaRespuesta= new stdclass();
        
        $ArrayDeParametros = $request->getParsedBody();
        $nombre= $ArrayDeParametros['nombre'];       

        $miCliente= new Cliente();
        $miCliente->nombre=$nombre;
        $ultimoId=$miCliente->InsertarCliente();    
        
        $objDelaRespuesta->respuesta="Se guardo el Usuario.";
        $objDelaRespuesta->ultimoIdGrabado=$ultimoId;   
        return $response->withJson($objDelaRespuesta, 200);
    }

    public static function EstadoPedido($request, $response, $args) {
        $ArrayDeParametros = $request->getParsedBody();
        $codigoMesa= $ArrayDeParametros['codigoMesa'];
        $codigoPedido= $ArrayDeParametros['codigoPedido'];
        $estadoPedido = new Cliente();
        $estadoPedido = CLiente::TraerEstadoDePedido($codigoMesa,$codigoPedido);
        $newresponse = $response->withJson($estadoPedido, 200);
        return $newresponse;
    }

    public function BorrarCliente($request, $response, $args) {
        $objDelaRespuesta= new stdclass();
        $ArrayDeParametros = $request->getParsedBody();
        $idAborrar = $ArrayDeParametros['idABorrar'];
        $unCliente = new Cliente();
        $unCliente->id = $idAborrar;
        $resultado = $unCliente->BorrarUnCliente();
        $objDelaRespuesta= new stdclass();
        $objDelaRespuesta->resultado=$resultado;
        return $response->withJson($objDelaRespuesta, 200);
    }

    public function ModificarCliente($request, $response, $args) {
        $ArrayDeParametros = $request->getParsedBody();
        $miCliente = new Cliente();
        $miCliente->id=$ArrayDeParametros['id'];
        $miCliente->nombre=$ArrayDeParametros['nombre'];
        $resultado =$miCliente->ModificarClienteParametros();
        $objDelaRespuesta= new stdclass();
        $objDelaRespuesta->resultado=$resultado;
        return $response->withJson($objDelaRespuesta, 200);		
    }
}