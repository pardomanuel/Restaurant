<?php
include_once "Encuesta.php";

class EncuestaApi{
    public static function IngresarEncuesta($request, $response, $args) 
    {
             
            $objDelaRespuesta= new stdclass();
            
            $ArrayDeParametros = $request->getParsedBody();
            $idPedido= $ArrayDeParametros['idPedido'];
            $puntosMesa= $ArrayDeParametros['puntosMesa'];
            $puntosRestaurante= $ArrayDeParametros['puntosRestaurante'];
            $puntosMozo= $ArrayDeParametros['puntosMozo'];
            $puntosCocinero= $ArrayDeParametros['puntosCocinero'];
            $experiencia= $ArrayDeParametros['experiencia'];
        
            $nuevaEncuesta= new Encuesta();
            $nuevaEncuesta->idPedido= $idPedido;
            $nuevaEncuesta->puntosMesa= $puntosMesa;
            $nuevaEncuesta->puntosRestaurante= $puntosRestaurante;
            $nuevaEncuesta->puntosMozo = $puntosMozo;
            $nuevaEncuesta->puntosCocinero = $puntosCocinero;
            $nuevaEncuesta->experiencia = $experiencia;
            $idEncuesta=$nuevaEncuesta->GuardarEncuesta();
    
            $objDelaRespuesta->idEncuesta=$idEncuesta;
            return $response->withJson($objDelaRespuesta, 200);
            
        }
        public function ComentariosEntreFechas($request, $response, $args) {
            $respuesta= new stdclass();
            $ArrayDeParametros = $request->getParsedBody();
            $fechaMin=$ArrayDeParametros['fechaMin'];
            $fechaMax=$ArrayDeParametros['fechaMax'];
            $todos=Encuesta::TraerComentariosEntreFechas($fechaMin,$fechaMax);
            $newresponse = $response->withJson($todos,200);  
        
            return $newresponse;
     
        }
        public function BorrarEncuesta($request, $response, $args) {
            $objDelaRespuesta= new stdclass();
            $ArrayDeParametros = $request->getParsedBody();
            $idAborrar = $ArrayDeParametros['idABorrar'];
            $unaEncuesta = new Encuesta();
            $unaEncuesta->id = $idAborrar;
            $resultado = $unaEncuesta->BorrarUnaEncuesta();
            $objDelaRespuesta= new stdclass();
            $objDelaRespuesta->resultado=$resultado;
            return $response->withJson($objDelaRespuesta, 200);
        }
        public function ModificarEncuesta($request, $response, $args) {
            $ArrayDeParametros = $request->getParsedBody();
            $miEncuesta = new Encuesta();
            $miEncuesta->id=$ArrayDeParametros['id'];
            $miEncuesta->idPedido=$ArrayDeParametros['idPedido'];
            $miEncuesta->puntosMesa=$ArrayDeParametros['puntosMesa'];
            $miEncuesta->puntosRestaurante=$ArrayDeParametros['puntosRestaurante'];
            $miEncuesta->puntosMozo=$ArrayDeParametros['puntosMozo'];
            $miEncuesta->puntosCocinero=$ArrayDeParametros['puntosCocinero'];
            $miEncuesta->experiencia=$ArrayDeParametros['experiencia'];
            $resultado =$miEncuesta->ModificarEncuestaParametros();
            $objDelaRespuesta= new stdclass();
            $objDelaRespuesta->resultado=$resultado;
            return $response->withJson($objDelaRespuesta, 200);		
        }

}
?>