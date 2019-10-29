<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';
require 'clases/UsuarioApi.php';
require 'clases/SesionApi.php';
require 'clases/TokenApi.php';
require 'clases/PedidoApi.php';
require 'clases/ProductoApi.php';
require 'clases/MesaApi.php';
require 'clases/PedidoDetalleApi.php';
require 'clases/OperacionesApi.php';
require 'clases/FacturaApi.php';
require 'clases/ClienteApi.php';
require 'clases/EncuestaApi.php';
require 'clases/ExportarApi.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App(["settings" => $config]);
$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
    ->withHeader('Access-Control-Allow-Origin', '*')
    ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    });
$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hola $name");

    return $response;
});
/* SESION */
$app->group('/Sesion', function(){
    $this->post('/Log',\SesionApi::class . ':Login');
    $this->post('/GuardarLogueo', \SesionApi::class . ':GuardarLogueo');
    $this->post('/TraerLogueosPorFecha', \SesionApi::class . ':TraerLogueosPorFecha');
});
/* /SESION */
/* PRODUCTOS */
    $app->group('/Productos', function(){
        $this->get('/TraerTodos',\ProductoApi::class . ':TraerTodos'); 
        $this->post('/TraerUnProducto',\ProductoApi::class . ':TraerUnProducto');
        $this->put('/ModificarUnProducto',\ProductoApi::class . ':ModificarUnProducto');
        $this->delete('/BorrarProducto', \ProductoApi::class . ':BorrarProducto');
        $this->post('/CargarProducto',\ProductoApi::class . ':CargarProducto');
      });
/* /PRODUCTOS */
/* OPEARACIONES */
    $app->group('/Operaciones', function(){
      $this->post('/SumarOperacion',\OperacionesApi::class . ':SumarOperacion');
      $this->get('/TraerOperacionesPorSector/{sector}',\OperacionesApi::class . ':TraerOperacionesPorSector');
      $this->post('/TraerOperacionesPorSectorPorEmpleado',\OperacionesApi::class . ':TraerOperacionesPorSectorPorEmpleado');
      $this->post('/CantidadOperacionesPorUsuario',\OperacionesApi::class . ':CantidadOperacionesPorUsuario');
      $this->get('/TraerOperacionesPorMes/{mes}',\OperacionesApi::class . ':TraerOperacionesPorMes');
      $this->delete('/BorrarOperacion', \OperacionesApi::class . ':BorrarOperacion');
      $this->put('/ModificarOperacion',\OperacionesApi::class . ':ModificarOperacion');
    });
/* /OPEARACIONES */
/* MESAS */
    $app->group('/Mesas', function(){
        $this->get('/TraerTodas',\MesaApi::class . ':TraerTodos');
        $this->post('/CambiarEstado',\MesaApi::class . ':CambiarEstadoMesa');
        $this->post('/SumarFacturacion',\MesaApi::class . ':SumarFacturacion');
        $this->get('/TraerUnaMesa/{id}',\MesaApi::class . ':TraerUnaMesa');
        $this->post('/TraerMasUsada',\MesaApi::class . ':TraerMasUsada');
        $this->post('/TraerMenosUsada',\MesaApi::class . ':TraerMenosUsada');
        $this->get('/TraerMasFacturo',\MesaApi::class . ':TraerMasFacturo');
        $this->get('/TraerMenosFacturo',\MesaApi::class . ':TraerMenosFacturo');
        $this->get('/TraerLaQueTuvoFacturaMasImporte',\MesaApi::class . ':TraerLaQueTuvoFacturaMasImporte');
        $this->get('/TraerLaQueTuvoFacturaMenosImporte',\MesaApi::class . ':TraerLaQueTuvoFacturaMenosImporte');
        $this->post('/FacturacionDeMesasEntreFechas',\MesaApi::class . ':FacturacionDeMesasEntreFechas');
        $this->get('/MejoresComentarios',\MesaApi::class . ':MejoresComentarios');
        $this->get('/PeoresComentarios',\MesaApi::class . ':PeoresComentarios');
        $this->post('/AgregarMesa',\MesaApi::class . ':AgregarMesa');
        $this->delete('/BorrarMesa', \MesaApi::class . ':BorrarMesa');
        $this->put('/ModificarMesa',\MesaApi::class . ':ModificarMesa');
        
      });
/* /MESAS */
/* USUARIOS */
    $app->group('/Usuarios', function(){
      $this->get('/TraerTodosLosUsuarios',\UsuarioApi::class . ':TraerTodosLosUsuarios');
      $this->post('/AgregarUsuario',\UsuarioApi::class . ':AgregarUsuario');
      $this->post('/NuevoUsuario',\UsuarioApi::class . ':NuevoUsuario');
      $this->get('/ListadoUsuarios',\UsuarioApi::class . ':ListadoUsuarios');
      $this->put('/SuspenderActivarUsuario',\UsuarioApi::class . ':SuspenderActivarUsuario');
      $this->delete('/BorrarUsuario',\UsuarioApi::class . ':BorrarUsuario');
      $this->get('/TraerUnUsuario/{id}',\UsuarioApi::class . ':TraerUnUsuario');
      $this->post('/ModificarUsuario',\UsuarioApi::class . ':ModificarUsuario');
      $this->put('/ModificarUsuarioSinFoto',\UsuarioApi::class . ':ModificarUsuarioSinFoto');
    });
/* /USUARIOS */
/* FACTURAS */
    $app->group('/Facturas', function(){
        $this->post('/GenerarFactura',\FacturaApi::class . ':GenerarFactura');
        $this->get('/TraerUnaFactura/{id}',\FacturaApi::class . ':TraerUnaFactura'); 
        $this->post('/Cobrar',\FacturaApi::class . ':Cobrar');
        $this->post('/TraerEntreFechas',\FacturaApi::class . ':TraerEntreFechas');
        $this->get('/TraerFacturasPorMes/{mes}',\FacturaApi::class . ':TraerFacturasPorMes');
        $this->delete('/BorrarFactura', \FacturaApi::class . ':BorrarFactura');
        $this->put('/ModificarFactura', \FacturaApi::class . ':ModificarFactura');
      });
/* /FACTURAS */
/* PEDIDOS */
    $app->group('/Pedidos', function(){
        $this->post('/CargarPedido',\PedidoApi::class . ':IngresarPedido');
        $this->get('/TraerTodos',\PedidoApi::class . ':TraerTodos');
        $this->get('/TraerTodosLosNoEntregados',\PedidoApi::class . ':TraerTodosLosNoEntregados');
        $this->post('/CambiarEstado',\PedidoApi::class . ':CambiarEstado');
        $this->post('/TraerUnPedido',\PedidoApi::class . ':TraerUnPedido');
        $this->post('/FinPedido',\PedidoApi::class . ':FinPedido');
        $this->post('/TerminarPedido',\PedidoApi::class . ':TerminarPedido');
        $this->get('/TraerUsoDeMesas/{mes}',\PedidoApi::class . ':TraerUsoDeMesas');
        $this->get('/TraerUsoDeMesasGlobal',\PedidoApi::class . ':TraerUsoDeMesasGlobal');
        $this->put('/CancelarPedido',\PedidoApi::class . ':CancelarPedido');
        $this->post('/CargarPedidoConFoto',\PedidoApi::class . ':CargarPedidoConFoto');
        $this->get('/TraerTodosLosPedidos',\PedidoApi::class . ':TraerTodosLosPedidos');
        $this->delete('/BorrarPedido', \PedidoApi::class . ':BorrarPedido');
    });
/* /PEDIDOS */
/* TOKEN */
$app->group('/Token', function(){
    $this->post('/Crear',\TokenApi::class . ':Crear');
    $this->post('/Obtener',\TokenApi::class . ':Obtener');
});
/* /TOKEN */

/* PEDIDOS DETALLE */
    $app->group('/PedidoDetalle', function(){
        $this->post('/Cargar',\PedidoDetalleApi::class . ':Cargar'); 
        $this->post('/MostrarPedidoDetalle',\PedidoDetalleApi::class . ':MostrarPedidoDetalle');
        $this->post('/TraerPendientesPorSector',\PedidoDetalleApi::class . ':TraerPendientesPorSector');
        $this->post('/TraerPendienteDetallado',\PedidoDetalleApi::class . ':TraerPendienteDetallado');
        $this->post('/TraerPendienteDetalladoMozo',\PedidoDetalleApi::class . ':TraerPendienteDetalladoMozo');
        $this->post('/TraerUnPedidoDetalle',\PedidoDetalleApi::class . ':TraerUnPedidoDetalle');
        $this->post('/ChequeoFinPEdido',\PedidoDetalleApi::class . ':ChequeoFinPEdido');
        $this->delete('/BorrarProductoDetalle', \PedidoDetalleApi::class . ':BorrarProductoDetalle');
        $this->post('/FinalizarEstado',\PedidoDetalleApi::class . ':FinalizarEstado');
        $this->post('/AsignarHoraEntrega',\PedidoDetalleApi::class . ':AsignarHoraEntrega');
        $this->post('/GuardarHoraInicioYPrometida',\PedidoDetalleApi::class . ':GuardarHoraInicioYPrometida');
        $this->get('/TraerPendienteDetalladoParaFactura/{id}',\PedidoDetalleApi::class . ':TraerPendienteDetalladoParaFactura');
        $this->post('/MasVendido',\PedidoDetalleApi::class . ':MasVendido');
        $this->post('/MenosVendido',\PedidoDetalleApi::class . ':MenosVendido');
        $this->post('/TraerCancelados',\PedidoDetalleApi::class . ':TraerCancelados');
        $this->post('/TraerEntreFechas',\PedidoDetalleApi::class . ':TraerEntreFechas');
        $this->get('/ListadoHiHfImporte',\PedidoDetalleApi::class . ':ListadoHiHfImporte');
        $this->get('/TraerUltimoPedidoDetalle',\PedidoDetalleApi::class . ':TraerUltimoPedidoDetalle');
        $this->put('/ModificarPedidoDetalle', \PedidoDetalleApi::class . ':ModificarPedidoDetalle');
      });
/* /PEDIDOS */
/* CLIENTES */
    $app->group('/Clientes', function(){
        $this->post('/CargarCliente',\ClienteApi::class . ':CargarCliente');
        $this->post('/EstadoPedido',\ClienteApi::class . ':EstadoPedido');
        $this->delete('/BorrarCliente', \ClienteApi::class . ':BorrarCliente');
        $this->put('/ModificarCliente',\ClienteApi::class . ':ModificarCliente');
      });
/* /CLIENTES */
/* ENCUESTA */
    $app->group('/Encuestas', function(){
      $this->post('/IngresarEncuesta',\EncuestaApi::class . ':IngresarEncuesta');
      $this->post('/ComentariosEntreFechas',\EncuestaApi::class . ':ComentariosEntreFechas');
      $this->delete('/BorrarEncuesta', \EncuestaApi::class . ':BorrarEncuesta');
      $this->put('/ModificarEncuesta',\EncuestaApi::class . ':ModificarEncuesta');
    });
/* /ENCUESTA */
/* EXPORTAR */
    $app->group('/Exportar', function(){
      $this->post('/Excel',\ExportarApi::class . ':ExportarExcel');
    });
/* /EXPORTAR */
$app->run();
?>