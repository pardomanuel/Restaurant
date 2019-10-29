<?php
require_once 'Usuario.php';
/*require_once 'AutentificadorJWT.php';*/


class UsuarioApi 
{
 	public function TraerUno($request, $response, $args) {
     	$id=$args['id'];
        $Usuario=Usuario::TraerUnUsuario($id);
        if(!$Usuario)
        {
            $objDelaRespuesta= new stdclass();
            $objDelaRespuesta->error="No existe El usuario";
            $NuevaRespuesta = $response->withJson($objDelaRespuesta, 500); 
        }else
        {
            $NuevaRespuesta = $response->withJson($empleado, 200); 
        }     
        return $NuevaRespuesta;
    }

     public function TraerTodos($request, $response, $args) {
      	$todosLosUsuario=Usuario::TraerTodoLosUsuarios();
     	$newresponse = $response->withJson($todosLosUsuario,200);  
        
    	return $newresponse;
    }
    public function ListadoUsuarios($request, $response, $args) {
        $todosLosUsuario=Usuario::ListadoUsuarios();
       $newresponse = $response->withJson($todosLosUsuario,200);  
      
      return $newresponse;
  }
      public function AgregarUsuario($request, $response, $args) {
     	
        $objDelaRespuesta= new stdclass();
        
        $ArrayDeParametros = $request->getParsedBody();
        //var_dump($ArrayDeParametros);
         $usuario= $ArrayDeParametros['usuario'];
        $pass= $ArrayDeParametros['pass'];
        $tipo= $ArrayDeParametros['tipo'];
        $estado= "Activo";

        $miUsuario= new Usuario();
        $miUsuario->usuario=$usuario;
        $miUsuario->pass=$pass;
        $miUsuario->tipo=$tipo;
        $miUsuario->estado=$estado;

        $ultimoId=$miUsuario->InsertarUsuario();    
        //$response->getBody()->write("se guardo el empleado");
        $objDelaRespuesta->respuesta="Se guardo el Usuario.";
        $objDelaRespuesta->ultimoIdGrabado=$ultimoId;   
        return $response->withJson($objDelaRespuesta, 200);
    }




      public function BorrarUsuario($request, $response, $args) {
     	$ArrayDeParametros = $request->getParsedBody();
         
     	$id=$ArrayDeParametros['id'];
     	$Usuario= new Usuario();
     	$Usuario->id=$id;
         
     	$cantidadDeBorrados=$Usuario->BorrarUsuario();

     	$objDelaRespuesta= new stdclass();
	    $objDelaRespuesta->cantidad=$cantidadDeBorrados;
	    if($cantidadDeBorrados>0)
	    	{
	    		 $objDelaRespuesta->resultado="algo borro!!!";
	    	}
	    	else
	    	{
	    		$objDelaRespuesta->resultado="no Borro nada!!!";
	    	}
	    $newResponse = $response->withJson($ArrayDeParametros, 200);  
      	return $newResponse;
    }



     
     public function ModificarUno($request, $response, $args) 
     {
     	
     	$ArrayDeParametros = $request->getParsedBody(); 
        $objDelaRespuesta= new stdclass();

        $usuario= $ArrayDeParametros['usuario'];
        $sexo= $ArrayDeParametros['sexo'];
      $clave= $ArrayDeParametros['clave'];
      $perfil= $ArrayDeParametros['perfil'];
      $id= $ArrayDeParametros['id'];

      $miUsuario= new Usuario();
      $miUsuario->usuario=$usuario;
      $miUsuario->clave=$clave;
      $miUsuario->sexo=$sexo;
      $miUsuario->perfil=$perfil;
      $miUsuario->id=$id;

	   	$resultado =$miEmpleado->ModificarUsuario();
	   	$objDelaRespuesta= new stdclass();
		//var_dump($resultado);
		$objDelaRespuesta->resultado=$resultado;
        $objDelaRespuesta->tarea="modificar";
		return $response->withJson($objDelaRespuesta, 200);		
    }

 public function Login($request, $response, $args) 
 {
     	
     	$ArrayDeParametros = $request->getParsedBody();
	 
	    $usuario=$ArrayDeParametros['usuario'];
	    $clave=$ArrayDeParametros['clave'];
        $usuario=Usuario::ValidarUsuario($usuario,$clave);
        $datos = array('usuario' => $usuario->usuario,'perfil' => $usuario->perfil, 'id'=>$usuario->id, 'sector'=>$usuario->sector , 'estado'=>$usuario->estado);


       $token= AutentificadorJWT::CrearToken($datos);
        $respuesta= array('token'=>$token);
        


		return $response->withJson($respuesta, 200);		
}


         public static function SuspenderActivarUsuario($request, $response, $args) 
         {
     	 $ArrayDeParametros = $request->getParsedBody(); 
         $id=$ArrayDeParametros['id']; 	
         $estado=$ArrayDeParametros['estado']; 	
         if($estado == 'Activo'){
            $resultado= Usuario::ActivarUsuario($id);
            $objDelaRespuesta= new stdclass();
            $objDelaRespuesta->resultado=$resultado;
            $objDelaRespuesta->tarea="activar";
         }else if($estado == 'suspendido'){
            $resultado= Usuario::SuspenderUsuario($id);
            $objDelaRespuesta= new stdclass();
            $objDelaRespuesta->resultado=$resultado;
            $objDelaRespuesta->tarea="Suspender";
         }
		 return $response->withJson($objDelaRespuesta, 200);		
         }

    public static function CantidadDeOperaciones($request, $response, $args)
    {
        $id=$args['id'];
        $operaciones=Usuario::CantidadDeOperacionesUsuario($id);
        return $response->withJson($operaciones, 200);
    }

    public static function IngresosAlSistema($request, $response, $args)
    {
        $objDelaRespuesta= new stdclass();
        $objDelaRespuesta=Usuario::FechasDeLogueo();

        return $response->withJson($objDelaRespuesta, 200);


    }

    public static function OperacionesTodosUsuarios($request, $response, $args)
    {
        $objDelaRespuesta= new stdclass();
        $objDelaRespuesta=Usuario::OperacionesTodosLosUsuarios();
        return $response->withJson($objDelaRespuesta, 200);

    }

    public static function OperacionestodosSectores($request, $response, $args)
    {
        $ArrayDeParametros = $request->getParsedBody();
	    $perfil=$ArrayDeParametros['perfil'];
        $objDelaRespuesta= new stdclass();
        $objDelaRespuesta=Usuario::CantidadOperacionesTodosSectores($perfil);
        return $response->withJson($objDelaRespuesta, 200);

    }

    public static function OperacionesUsuarioSeparado($request, $response, $args)
    {
        
        $idUsuario=$args['idUsuario'];
        $objDelaRespuesta= new stdclass();
        $objDelaRespuesta=Usuario::CantidadOperacionesUsuariosSeparado($idUsuario);
        return $response->withJson($objDelaRespuesta, 200);

    }
    
    public static function OperacionesUsuariossSector($request, $response, $args)
    {
        
        $perfil=$args['perfil'];
        
        $objDelaRespuesta= new stdclass();
        $objDelaRespuesta=Usuario::CantidadOperacionesUsuariosPorSector($perfil);
        return $response->withJson($objDelaRespuesta, 200);

    }
    public function TraerUnUsuario($request, $response, $args) {
        $id= $args['id'];
        $Usuario = new Usuario();
        $Usuario= Usuario::TraerUsuario($id);
        $newresponse = $response->withJson($Usuario, 200);  
        return $newresponse;
    }
    
    public function NuevoUsuario($request, $response, $args) {
     	$objDelaRespuesta= new stdclass();
        $resultado;
        $ArrayDeParametros = $request->getParsedBody();

        $archivos = $request->getUploadedFiles();
        $destino="./images/usuarios/";
        $logo="logo.png";

            $nombreAnterior=$archivos['foto']->getClientFilename();
            $nombre = $ArrayDeParametros['nuevoUsuario'];
            $pass = $ArrayDeParametros['nuevoPass'];
            $sector = $ArrayDeParametros['sector'];
            $extension= explode(".", $nombreAnterior);
            $estado = "Activo";
            $extension=array_reverse($extension);
            $nomFoto = $nombre . $sector.".".$extension[0];
            
            $miUsuario= new Usuario();
            $miUsuario->usuario=$nombre;
            $miUsuario->pass=$pass;
            $miUsuario->tipo=$sector;
            $miUsuario->estado=$estado;
            $miUsuario->foto=$nomFoto;
            $ultimoId=$miUsuario->InsertarUsuario(); 
            $ultimoDestinoFoto=$destino.$nomFoto;

            if(file_exists($ultimoDestinoFoto))
            {
                copy($ultimoDestinoFoto,date("Ymd").".".$extension[0]);
            }

            $archivos['foto']->moveTo($ultimoDestinoFoto);
        
        $objDelaRespuesta->archivo= $archivos['foto'];
           
        return $response->withJson($objDelaRespuesta, 200);
    }
    public static function ModificarUsuario($request, $response, $args) 
         {

     	$ArrayDeParametros = $request->getParsedBody(); 
        $archivos = $request->getUploadedFiles();
        $destino="./images/usuarios/";
        $destinobk = "./images/backup/";
        $nombre = $ArrayDeParametros['usuario'];
        $nombreAnterior=$archivos['foto']->getClientFilename();
        $extension= explode(".", $nombreAnterior);
        $extension=array_reverse($extension);
        $fotoAnterior = $ArrayDeParametros['fotoAnterior'];
        $extensionAnterior = explode(".", $fotoAnterior);
        $extensionAnterior = array_reverse($extensionAnterior);
        $nomFoto = $nombre . $ArrayDeParametros['tipo'].".".$extension[0];
        $ultimoDestinoFoto=$destino.$nomFoto;
        $destinoAnterior = $destino.$fotoAnterior;
        if(file_exists($destinoAnterior)){
            copy($destinoAnterior,$destinobk.date("Ymd").'-'.$ArrayDeParametros['id'].".jpeg");
            unlink($destinoAnterior);

            /**/
            // Cargar la estampa y la foto para aplicarle la marca de agua
            $im = imagecreatefromjpeg($destinobk.date("Ymd").'-'.$ArrayDeParametros['id'].".jpeg");

            // Primero crearemos nuestra imagen de la estampa manualmente desde GD
            $estampa = imagecreatetruecolor(100, 70);
            imagefilledrectangle($estampa, 0, 0, 99, 69, 0x0000FF);
            imagefilledrectangle($estampa, 9, 9, 90, 60, 0xFFFFFF);
            $im = imagecreatefromjpeg($destinobk.date("Ymd").'-'.$ArrayDeParametros['id'].".jpeg");
            imagestring($estampa, 5, 20, 20, 'IMG BK', 0x0000FF);
            imagestring($estampa, 3, 20, 40, $ArrayDeParametros['usuario'], 0x0000FF);

            // Establecer los márgenes para la estampa y obtener el alto/ancho de la imagen de la estampa
            $margen_dcho = 10;
            $margen_inf = 10;
            $sx = imagesx($estampa);
            $sy = imagesy($estampa);

            // Fusionar la estampa con nuestra foto con una opacidad del 50%
            imagecopymerge($im, $estampa, imagesx($im) - $sx - $margen_dcho, imagesy($im) - $sy - $margen_inf, 0, 0, imagesx($estampa), imagesy($estampa), 50);

            // Guardar la imagen en un archivo y liberar memoria
            imagepng($im, 'images/backup/'.date("Ymd").'-'.$ArrayDeParametros['id'].".png");
            imagedestroy($im);
            /**/
            

            $miUsuario = new Usuario();
            $miUsuario->id=$ArrayDeParametros['id'];
            $miUsuario->usuario=$ArrayDeParametros['usuario'];
            $miUsuario->pass=$ArrayDeParametros['pass'];
            $miUsuario->tipo=$ArrayDeParametros['tipo'];
            $miUsuario->foto=$nomFoto;
            $resultado =$miUsuario->ModificarUsuarioParametros();
        }
        $archivos['foto']->moveTo($ultimoDestinoFoto);
        unlink($destinobk.date("Ymd").'-'.$ArrayDeParametros['id'].".jpeg");
         $objDelaRespuesta= new stdclass();
         $objDelaRespuesta->resultado=$resultado;
         return $response->withJson($objDelaRespuesta, 200);
    }
         
         public static function ModificarUsuarioSinFoto($request, $response, $args) 
         {

     	    $ArrayDeParametros = $request->getParsedBody();
            $miUsuario = new Usuario();
            $miUsuario->id=$ArrayDeParametros['id'];
            $miUsuario->usuario=$ArrayDeParametros['usuario'];
            $miUsuario->pass=$ArrayDeParametros['pass'];
            $miUsuario->tipo=$ArrayDeParametros['tipo'];
            $resultado =$miUsuario->ModificarUsuarioParametrosSinFoto();
        
         $objDelaRespuesta= new stdclass();
         $objDelaRespuesta->resultado=$resultado;
         return $response->withJson($objDelaRespuesta, 200);
         }

        
}
?>