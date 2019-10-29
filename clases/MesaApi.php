<?php
include_once "Mesa.php";

class MesaApi{

    public function TraerTodos($request, $response, $args) {
       $todasLasMesas=Mesa::TraerTodasLasMesas();
       $newresponse = $response->withJson($todasLasMesas,200);  
      
      return $newresponse;
     
  }
  public function CambiarEstadoMesa($request, $response, $args) {

    $ArrayDeParametros = $request->getParsedBody();
    
    $id=$ArrayDeParametros['id'];
    $estado = $ArrayDeParametros['estado'];
    $Mesa= new Mesa();
    $Mesa->id=$id;
    $resultado =$Mesa->CambiarEstadoMesa($estado);
    $objDelaRespuesta= new stdclass();
    $objDelaRespuesta->resultado=$resultado;
    $objDelaRespuesta->tarea="modificar";
    return $response->withJson($objDelaRespuesta, 200);		
}
public static function SumarFacturacion($request, $response, $args) {
        $ArrayDeParametros = $request->getParsedBody();
        $id = $ArrayDeParametros['id'];
        $total = $ArrayDeParametros['total'];
        $Mesa = new Mesa();
        $Mesa= Mesa::SumarFacturacion($total,$id);
        $newresponse = $response->withJson($Mesa, 200);  
        return $newresponse;
}
public function TraerUnaMesa($request, $response, $args) {
    $id= $args['id'];
    $mesa = new Mesa();
    $mesa= Mesa::TraerMesa($id);
   $newresponse = $response->withJson($mesa, 200);  
  return $newresponse;
}
public static function TraerMenosUsada($request, $response, $args) {
        $ArrayDeParametros = $request->getParsedBody();
        $fechaMin = $ArrayDeParametros['fechaMin'];
        $fechaMax = $ArrayDeParametros['fechaMax'];
        $Mesa = new Mesa();
        $Mesa= Mesa::TraerMenosUsada($fechaMin,$fechaMax);
        $newresponse = $response->withJson($Mesa, 200);  
        return $newresponse;
}
public static function TraerMasUsada($request, $response, $args) {
        $ArrayDeParametros = $request->getParsedBody();
        $fechaMin = $ArrayDeParametros['fechaMin'];
        $fechaMax = $ArrayDeParametros['fechaMax'];
        $Mesa = new Mesa();
        $Mesa= Mesa::TraerMasUsada($fechaMin,$fechaMax);
        $newresponse = $response->withJson($Mesa, 200);  
        return $newresponse;
}
public static function FacturacionDeMesasEntreFechas($request, $response, $args) {
        $ArrayDeParametros = $request->getParsedBody();
        $fechaMin = $ArrayDeParametros['fechaMin'];
        $fechaMax = $ArrayDeParametros['fechaMax'];
        $id = $ArrayDeParametros['id'];
        $Mesa = new Mesa();
        $Mesa= Mesa::FacturacionDeMesasEntreFechas($fechaMin,$fechaMax,$id);
        $newresponse = $response->withJson($Mesa, 200);  
        return $newresponse;
}
public static function TraerMasFacturo($request, $response, $args) {
        $MasFacturo=Mesa::TraerLaQueMasFacturo();
        $newresponse = $response->withJson($MasFacturo,200);  
      
        return $newresponse;
}
public static function TraerMenosFacturo($request, $response, $args) {
        $MasFacturo=Mesa::TraerLaQueMenosFacturo();
        $newresponse = $response->withJson($MasFacturo,200);  
      
        return $newresponse;
}
public static function TraerLaQueTuvoFacturaMasImporte($request, $response, $args) {
        $MasFacturo=Mesa::TraerLaQueTuvoFacturaMasImporte();
        $newresponse = $response->withJson($MasFacturo,200);  
      
        return $newresponse;
}
public static function TraerLaQueTuvoFacturaMenosImporte($request, $response, $args) {
        $MasFacturo=Mesa::TraerLaQueTuvoFacturaMasImporte();
        $newresponse = $response->withJson($MasFacturo,200);  
      
        return $newresponse;
}
public function MejoresComentarios($request, $response, $args) {
    $mesa = new Mesa();
    $mesa= Mesa::MejoresComentarios();
    $newresponse = $response->withJson($mesa, 200);  
    return $newresponse;
}
public function PeoresComentarios($request, $response, $args) {
    $mesa = new Mesa();
    $mesa= Mesa::PeoresComentarios();
    $newresponse = $response->withJson($mesa, 200);  
    return $newresponse;
}
public function AgregarMesa($request, $response, $args) {
     	
        $objDelaRespuesta= new stdclass();
        
        $ArrayDeParametros = $request->getParsedBody();
        $estado= $ArrayDeParametros['estado'];
        $codigo= $ArrayDeParametros['codigo'];
        $facturacion= $ArrayDeParametros['facturacion'];

        $miMesa= new Mesa();
        $miMesa->estado=$estado;
        $miMesa->codigo=$codigo;
        $miMesa->facturacion=$facturacion;
        $ultimoId=$miMesa->InsertarMesa();    
        
        $objDelaRespuesta->respuesta="Se guardo el Usuario.";
        $objDelaRespuesta->ultimoIdGrabado=$ultimoId;   
        return $response->withJson($objDelaRespuesta, 200);
    }
    public function BorrarMesa($request, $response, $args) {
        $objDelaRespuesta= new stdclass();
        $ArrayDeParametros = $request->getParsedBody();
        $idAborrar = $ArrayDeParametros['idABorrar'];
        $unaMesa = new Mesa();
        $unaMesa->id = $idAborrar;
        $resultado = $unaMesa->BorrarunaMesa();
        $objDelaRespuesta= new stdclass();
        $objDelaRespuesta->resultado=$resultado;
        return $response->withJson($objDelaRespuesta, 200);
    }
    public function ModificarMesa($request, $response, $args) {
        $ArrayDeParametros = $request->getParsedBody();
        $miMesa = new Mesa();
        $miMesa->id=$ArrayDeParametros['id'];
        $miMesa->estado=$ArrayDeParametros['estado'];
        $miMesa->codigo=$ArrayDeParametros['codigo'];
        $resultado =$miMesa->ModificarMesaParametros();
        $objDelaRespuesta= new stdclass();
        $objDelaRespuesta->resultado=$resultado;
        return $response->withJson($objDelaRespuesta, 200);		
    }

}
?>