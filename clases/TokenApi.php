
<?php
include_once "AutentificadorJWT.php";

class TokenApi
{

    public function Crear($request, $response, $args) {
     	
        $respuesta= new stdclass();
     	$ArrayDeParametros = $request->getParsedBody();
	    $usuario=$ArrayDeParametros['usuario'];
        $pass=$ArrayDeParametros['pass'];
        try{
            $objetoUsuario=Usuario::ValidarUsuario($usuario,$pass);
            $datos = array('usuario' => $objetoUsuario->usuario,'tipo' => $objetoUsuario->tipo, 'idUsuario' => $objetoUsuario->id, 'estado' => $objetoUsuario->estado);
            $token= AutentificadorJWT::CrearToken($datos);
            $respuesta= array('token'=>$token,'datos'=> $datos);
        }
        catch(Exception  $e)
        {
            $respuesta->error = $e->getMessage();
        }

		return $response->withJson($respuesta, 200);
    }
        public function Obtener($request, $response, $args) {
     	
        $respuesta= new stdclass();
     	$ArrayDeParametros = $request->getParsedBody();
	    $token=$ArrayDeParametros['token'];
        try{
            $datos = AutentificadorJWT::ObtenerData($token);
            $respuesta= array('datos'=>$datos);
        }
        catch(Exception  $e)
        {
            $respuesta->error = $e->getMessage();
        }

		return $response->withJson($respuesta, 200);
    }
}
?>
