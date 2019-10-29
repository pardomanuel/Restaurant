<?php
include_once "Encuesta.php";

class ExportarApi{
    public static function ExportarExcel($request, $response, $args) 
    {
            /*$objDelaRespuesta= new stdclass();
            
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
            return $response->withJson($objDelaRespuesta, 200);*/
            $objDelaRespuesta= new stdclass();
            $ArrayDeParametros = $request->getParsedBody();
            $usuarios= $ArrayDeParametros['array'];
            $filename="libros.xls";
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=".$filename);
            $mostrar_columnas=false;
            foreach($usuarios as $usuario){
            if(!$mostrar_columnas){
            echoimplode("\t",array_keys($usuario))."\n";
            $mostrar_columnas=true;
            }
            echo implode("\t",array_values($usuario))."\n";
            }
        }
}
?>