
<?php
include_once "Usuario.php";
include_once "AutentificadorJWT.php";

class SesionApi
{

    public function Login($request, $response, $args) {
     	
        $respuesta= new stdclass();
     	$ArrayDeParametros = $request->getParsedBody();
	    $usuario=$ArrayDeParametros['usuario'];
        $pass=$ArrayDeParametros['pass'];
        try{
            $objetoUsuario=Usuario::ValidarUsuario($usuario,$pass);
            if($objetoUsuario == false){
                $respuesta = false;
            }else{
                $datos = array('usuario' => $objetoUsuario->usuario,'tipo' => $objetoUsuario->tipo, 'idUsuario' => $objetoUsuario->id, 'estado' => $objetoUsuario->estado);
                $token= AutentificadorJWT::CrearToken($datos);
                $respuesta= array('token'=>$token,'datos'=> $datos);
            }
        }
        catch(Exception  $e)
        {
            $respuesta->error = $e->getMessage();
        }

		return $response->withJson($respuesta, 200);
    }
    public function GuardarLogueo($request, $response, $args) {
     	
        $respuesta= new stdclass();
     	$ArrayDeParametros = $request->getParsedBody();
	    $idUsuario=$ArrayDeParametros['id'];
        $objetoUsuario=Usuario::GuardarLogueo($idUsuario);
        $newResponse = $response->withJson($objetoUsuario,200);
        return $newResponse;
    }
    public function TraerLogueosPorFecha($request, $response, $args) {
     	
        $respuesta= new stdclass();
     	$ArrayDeParametros = $request->getParsedBody();
        $fechaMin=$ArrayDeParametros['fechaMin'];
        $fechaMax=$ArrayDeParametros['fechaMax'];
        $objetoUsuario=Usuario::TraerLogueosPorFecha($fechaMin,$fechaMax);
        $newResponse = $response->withJson($objetoUsuario,200);
        return $newResponse;
    }
}
?>
