<?php
include_once "Operaciones.php";

class OperacionesApi {

    public function SumarOperacion($request, $response, $args) {
     	
        $respuesta= new stdclass();
     	$ArrayDeParametros = $request->getParsedBody();
	    $idUsuario=$ArrayDeParametros['idUsuario'];
        $objetoUsuario=Operaciones::GuardarOperacion($idUsuario);
        $newResponse = $response->withJson($objetoUsuario,200);
        return $newResponse;
    }

    public function TraerTodos($request, $response, $args) {
    $todosLosPedidos=Pedido::TraerTodoLosPedidos();
    $newresponse = $response->withJson($todosLosPedidos,200);  
  
    return $newresponse;
 
    }
    public function CantidadOperacionesPorUsuario($request, $response, $args) {
        $respuesta= new stdclass();
     	$ArrayDeParametros = $request->getParsedBody();
        $fechaMin=$ArrayDeParametros['fechaMin'];
        $fechaMax=$ArrayDeParametros['fechaMax'];
        $todos=Operaciones::CantidadOperacionesPorUsuario($fechaMin,$fechaMax);
        $newresponse = $response->withJson($todos,200);  
      
        return $newresponse;
     
        }
    public function TraerOperacionesPorSectorPorEmpleado($request, $response, $args) {
        $respuesta= new stdclass();
     	$ArrayDeParametros = $request->getParsedBody();
        $fechaMin=$ArrayDeParametros['fechaMin'];
        $fechaMax=$ArrayDeParametros['fechaMax'];
        $sector= $ArrayDeParametros['sector'];
        $objetoUsuario=Operaciones::TraerOperacionesPorSectorPorEmpleado($fechaMin,$fechaMax,$sector);
        $newResponse = $response->withJson($objetoUsuario,200);
        return $newResponse;
    }

    public function TraerOperacionesPorSector($request, $response, $args) {
        $sector= $args['sector'];
        $Operaciones = new Operaciones();
        $Operaciones= Operaciones::TraerOperacionesPorSector($sector);
       $newresponse = $response->withJson($Operaciones, 200);  
      return $newresponse;
    }
    public function TraerOperacionesPorMes($request, $response, $args) {
       $mes= $args['mes'];
       $todasLasOperaciones=Operaciones::TraerOperacionesPorMes($mes);
       $newresponse = $response->withJson($todasLasOperaciones,200);  
      
      return $newresponse;
  }

      public function BorrarOperacion($request, $response, $args) 
      {
        $objDelaRespuesta= new stdclass();
        $ArrayDeParametros = $request->getParsedBody();
        $idAborrar = $ArrayDeParametros['idABorrar'];
        $unaOperacion = new Operaciones();
        $unaOperacion->id = $idAborrar;
        $resultado = $unaOperacion->BorrarunaOperacion();
        $objDelaRespuesta= new stdclass();
        $objDelaRespuesta->resultado=$resultado;
        return $response->withJson($objDelaRespuesta, 200);
    }

    public function ModificarOperacion($request, $response, $args){
        $ArrayDeParametros = $request->getParsedBody();
        $miOperacion = new Operaciones();
        $miOperacion->id=$ArrayDeParametros['id'];
        $miOperacion->idUsuario=$ArrayDeParametros['idUsuario'];
        $resultado =$miOperacion->ModificarOperacionesParametros();
        $objDelaRespuesta= new stdclass();
        $objDelaRespuesta->resultado=$resultado;
        return $response->withJson($objDelaRespuesta, 200);
    }
    
}

?>