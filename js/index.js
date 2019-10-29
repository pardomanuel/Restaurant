onload=function(){
    var miToken = localStorage.getItem("mitoken");

    objJson=
    {
        "token": miToken
    }
     var funcionAjax=$.ajax({
            url:"http://www.pardomanuel.xyz/restaurant/Token/Obtener",
            type:"post",
            data: objJson
        }).then(function(respuesta){
            document.getElementById('moduloMuestroGraficoTorta').style.display='none';
            if(window.location == "http://www.pardomanuel.xyz/restaurant/panel.html" && respuesta['datos'] == undefined){
                window.location = "http://www.pardomanuel.xyz/restaurant/error.html"
            }
            document.getElementById('moduloTomarPedidos').style.display="none";
            document.getElementById('modulPedidosPendientes').style.display="none";
            document.getElementById('moduloCambioEstadoMesas').style.display="none";
            document.getElementById('moduloMostrar').style.display = 'none';
            if(respuesta['datos']['tipo'] == 'bartender' || respuesta['datos']['tipo'] == 'cervecero' || respuesta['datos']['tipo'] == 'cocinero'){
                document.getElementById('btnListarMesas').style.display = 'none';
                document.getElementById('btnTomarPedido').style.display = 'none';
            }
            if(respuesta['datos']['tipo'] == 'bartender' || respuesta['datos']['tipo'] == 'cervecero' || respuesta['datos']['tipo'] == 'cocinero' || respuesta['datos']['tipo'] == 'mozo'){
                document.getElementById('btnAbmEmpleados').style.display = 'none';
                document.getElementById('btnMostrar').style.display = 'none';
            }
        },function(error){
                console.info("error", error);
    });
}
function MostrarUsuarios(){
    var funcionAjax=$.ajax({
        url:"http://www.pardomanuel.xyz/restaurant/Usuarios/TraerTodosLosUsuarios",
		type:"get"
    }).then(function(respuesta){
            console.log(respuesta);
	},function(error){
		console.log(error);
    });
}
function AgregarUsuario(){
    var usuario = document.getElementById('txtUsuario').value;
    var pass = document.getElementById('txtPass').value;
    data=
    {
        "usuario": usuario,
        "pass": pass
    }
    var funcionAjax=$.ajax({
        url:"http://www.pardomanuel.xyz/restaurant/Usuarios/AgregarUsuario",
		type:"post",
        data: data
    }).then(function(respuesta){
            console.log(respuesta);
            debugger;
	},function(error){
		console.log(error);
    });
}
function ModificarUsuario(){
    var id = document.getElementById('txtId').value;
    var usuario = document.getElementById('txtUsuario').value;
    var pass = document.getElementById('txtPass').value;
    var estado = document.getElementById('txtEstado').value;
    data=
    {
        "usuario": usuario,
        "pass": pass,
        "id": id,
        "estado": estado
    }
    var funcionAjax=$.ajax({
        url:"http://www.pardomanuel.xyz/restaurant/Usuarios/ModificarUnUsuario",
		type:"put",
        data: data
    }).then(function(respuesta){
            console.log(respuesta);
	},function(error){
		console.log(error);
    });
}
function BorrarUsuario(){
    var id = document.getElementById('txtId').value;
    data=
    {
        "idABorrar": id
    }
    var funcionAjax=$.ajax({
        url:"http://www.pardomanuel.xyz/restaurant/Usuarios/BorrarUsuario",
		type:"delete",
        data: data
    }).then(function(respuesta){
            console.log(respuesta);
	},function(error){
		console.log(error);
    });
}
function loguear(){
    if(document.getElementById('usuario').value == '' || document.getElementById('pass').value == ''){
        alert("Error, debe completar todos los campos");
    }else{
    var seccionLogin=document.getElementById('login');
    var usuario=document.getElementById('usuario').value;
    var pass= document.getElementById('pass').value;

    objJson=
    {
        "usuario": usuario,
        "pass": pass
    }

    var funcionAjax=$.ajax({
        url:"http://www.pardomanuel.xyz/restaurant/Sesion/Log",
		type:"post",
		data:objJson,
    }).then(function(respuesta){
        console.log(respuesta);
        if(respuesta == false){
               console.log("ERROR");
               alert("Usuario no encontrado");
           }else if(respuesta['datos']['estado'] == 'suspendido'){
               alert("Error, Usuario suspendido");
           }
           else{
                usuario=
                {
                    'id': respuesta['datos']['idUsuario']
                }
                localStorage.setItem("mitoken",respuesta['token']);
               var funcionAjax=$.ajax({
                url:"http://www.pardomanuel.xyz/restaurant/Sesion/GuardarLogueo",
                type:"post",
                data:usuario
            }).then(function(respuesta){
                    window.location="panel.html";
                },function(error){
                        console.info("error", error);
                });
           }
	},function(error){
			console.info("error", error);
    });
}
}
function CerrarSesion(){
    localStorage.removeItem('mitoken');
    localStorage.removeItem('tipoEmpleado');
    localStorage.removeItem('idEmpleado');
    window.location="http://www.pardomanuel.xyz/restaurant/index.html";
}
function botonTomarPedido(){
    document.getElementById('moduloModificoEmpleado').style.display = 'none';
    document.getElementById('moduloMuestroGraficoTorta').style.display='none';
    var modulo = document.getElementById('moduloTomarPedidos');
    document.getElementById('agregoTiempoPedido').style.display = 'none';
    document.getElementById('moduloMuestroEnDetalle').style.display = "none";
    document.getElementById('moduloMostrar').style.display = "none";
    document.getElementById('nombreCliente').value = "";
    document.getElementById('imagenPedido').value = null;
    document.getElementById('moduloCambioEstadoMesas').style.display="none";
    document.getElementById('btnCambioEstadoMesas').style.display="none";
    var moduloDetallesPedidos = document.getElementById('detallePedido');
    moduloDetallesPedidos.innerHTML = "";
    var moduloMuestroMesas = document.getElementById('moduloMuestroMesas');
    var textBoxNroPedido = document.getElementById('numeroPedido');
    moduloMuestroMesas.style.display = "none";
    document.getElementById('productos').options.length = 0;
    document.getElementById('mesa').options.length = 0;
    var moduloPedidosPendientes = document.getElementById('modulPedidosPendientes');
    moduloPedidosPendientes.style.display = "none";
        /*CARGO TEXTBOX NUMERO PEDIDO*/
        var funcionAjax=$.ajax({
            url:"http://www.pardomanuel.xyz/restaurant/Pedidos/TraerTodos",
            type:"get"
        }).then(function(respuesta){
            textBoxNroPedido.value=(respuesta[respuesta.length-1]['id'] + 1);
        },function(error){
                console.info("error", error);
        });
        /*CARGO SELECT MESAS*/
        /*CARGO SELECT PRODUCTOS*/
        var funcionAjax=$.ajax({
            url:"http://www.pardomanuel.xyz/restaurant/Productos/TraerTodos",
            type:"get"
        }).then(function(respuesta){
            cargarSelectProductos(respuesta);
        },function(error){
                console.info("error", error);
        });
        /*CARGO SELECT MESAS*/
        var funcionAjax=$.ajax({
            url:"http://www.pardomanuel.xyz/restaurant/Mesas/TraerTodas",
            type:"get"
        }).then(function(respuesta){
            cargarSelectMesas(respuesta);
            modulo.style.display = "block";
            $('html, body').animate({
                scrollTop: $(document).height()
            }, 'slow');
        },function(error){
                console.info("error", error);
        });
        
}
function cargarSelectProductos(array){
    var select = document.getElementById("productos");
    for(var i=0; i < array.length; i++){ 
        var option = document.createElement("option"); //Creamos la opcion
        option.innerHTML = array[i]['id'] + "- " + array[i]['descripcion']; //Metemos el texto en la opción
        select.appendChild(option); //Metemos la opción en el select
    }
}
function cargarSelectMesas(array){
    var select = document.getElementById("mesa");
    for(var i=0; i < array.length; i++){
        var option = document.createElement("option"); //Creamos la opcion
        option.innerHTML = array[i]['id']; //Metemos el texto en la opción
        select.appendChild(option); //Metemos la opción en el select
    }
}
function cargarSelectEstadoMesa(){
    var miToken = localStorage.getItem("mitoken");

    objJson=
    {
        "token": miToken
    }
     var funcionAjax=$.ajax({
            url:"http://www.pardomanuel.xyz/restaurant/Token/Obtener",
            type:"post",
            data: objJson
        }).then(function(respuesta){
            console.log(respuesta);
            console.log(respuesta['datos']['tipo']);
            var select = document.getElementById("estadosMesas");
            var array = ["con cliente esperando pedido", "con cliente comiendo", "con cliente pagando"];
            if(respuesta['datos']['tipo'] == 'socio'){
                array.push("cerrada");
            }
            for(var i=0; i < array.length; i++){ 
                var option = document.createElement("option"); //Creamos la opcion
                option.innerHTML = array[i];//Metemos el texto en la opción
                select.appendChild(option); //Metemos la opción en el select
            }
        },function(error){
                console.info("error", error);
    });
}
function AgregarAlPedido(){
    var mesa=document.getElementById('mesa').value;
    var mesa = parseInt(mesa);
    var idPedido = parseInt(document.getElementById("numeroPedido").value);
    var productos=document.getElementById('productos').value;
    var primerCaracterDeProducto =productos.substr(0,1);
    var primerCaracterDeProducto = parseInt(primerCaracterDeProducto);
    var nombreCliente=document.getElementById('nombreCliente').value;
    
    if(nombreCliente == ''){
        alert("Error, complete el nombre del cliente.");
    }else{
    var funcionAjax=$.ajax({
        url:"http://www.pardomanuel.xyz/restaurant/Mesas/TraerUnaMesa/"+mesa,
		type:"get"
    }).then(function(respuesta){
        if(respuesta['estado'] == 'cerrada'){
            document.getElementById("detallePedido").innerHTML = '<p align="left"><img src="images/gif/loading_spinner.gif" width="5%"></p>';
            datos=
    {
        "idProducto":primerCaracterDeProducto
    }
    
    var funcionAjax=$.ajax({
        url:"http://www.pardomanuel.xyz/restaurant/Productos/TraerUnProducto",
		type:"post",
		data:datos
    }).then(function(respuesta){
        if(respuesta!=null){
           var sector = respuesta['sector'];
           objJson=
            {
                "idPedido": idPedido,
                "idProducto":primerCaracterDeProducto,
                "estado": "Pendiente",
                "sector": sector
            }
    
        var funcionAjax=$.ajax({
            url:"http://www.pardomanuel.xyz/restaurant/PedidoDetalle/Cargar",
            type:"post",
            data:objJson
        }).then(function(respuesta){
            if(respuesta!=null){
                MostrarDetallePedido(idPedido,respuesta['idPedido']);
            }
        },function(error){
                console.log(error);
        });
            }
        },function(error){
                console.log(error);
        });
        }else {
            alert("Error, la mesa no está cerrada.");
        }
	},function(error){
			console.log(error);
    });
    }
}
function MostrarDetallePedido(id,idPedido){
    var modulo = document.getElementById('detallePedido'); 
    var idPedidoDetalle = id;
    var idPedidoOk = idPedido;
    objJson=
    {
        "idPedidoDetalle": idPedidoDetalle
    }
    
    var funcionAjax=$.ajax({
        url:"http://www.pardomanuel.xyz/restaurant/PedidoDetalle/MostrarPedidoDetalle",
		type:"post",
		data:objJson
    }).then(function(respuesta){
            var i;
            var tabla ='<div class="table-responsive">';
            tabla+='<table class="table w3-table-all" border=1>';
            if(respuesta.length>=1){
                tabla+='<tr class="w3-black"><td><strong>Detalle</strong></td></tr>';
                for (i = 0; i < respuesta.length; i++){
                    var descripcion = respuesta[i]['descripcion'];
                    var id = respuesta[i]['id'];
                    tabla+='<tr><td>' + descripcion + '</td>'+
                    '<td><input type="button" value="Borrar" onclick="BorrarProducto('+id+','+idPedidoDetalle+','+idPedidoOk+')"></td></tr>';
                }
            }
            tabla+='</table>';
            modulo.innerHTML = tabla;

	},function(error){
		console.log(error);
    });
}
function BorrarProducto(id, idPedidoDetalle,idPedidoOk){
    var idABorrar = id;
    objJson=
    {
        "idABorrar": idABorrar
    }
    
    var funcionAjax=$.ajax({
        url:"http://www.pardomanuel.xyz/restaurant/PedidoDetalle/BorrarProductoDetalle",
		type:"delete",
		data:objJson
    }).then(function(respuesta){
        if(respuesta['resultado']==1){
            MostrarDetallePedido(idPedidoDetalle,idPedidoOk);
        }
	},function(error){
		console.log(error);
    });
}
function cargarPedido(){
    var funcionAjax=$.ajax({
        url:"http://www.pardomanuel.xyz/restaurant/PedidoDetalle/TraerUltimoPedidoDetalle",
		type:"get"
    }).then(function(ultimoPedidoDetalle){
        if(ultimoPedidoDetalle[0]['idPedido']!=parseInt(document.getElementById("numeroPedido").value)){
            alert("Error, primero tiene que cargar algun producto al pedido");
        }else{
            var mesa=document.getElementById('mesa').value;
            var mesa = parseInt(mesa);
            var nombreCliente=document.getElementById('nombreCliente').value;
            var inputFileImage = document.getElementById("imagenPedido");
            var foto = inputFileImage.files[0];

            
            if(nombreCliente == ''){
                alert("Error, complete el nombre del cliente.");
            }else{
            var idCliente;
            var funcionAjax=$.ajax({
                url:"http://www.pardomanuel.xyz/restaurant/Mesas/TraerUnaMesa/"+mesa,
                type:"get"
            }).then(function(respuesta){
                if(respuesta['estado'] == 'cerrada' && foto == null){
                    /*CLIENTE*/
                    objJsonCliente=
                    {
                        "nombre": nombreCliente
                    }
                    
                    var funcionAjax=$.ajax({
                        url:"http://www.pardomanuel.xyz/restaurant/Clientes/CargarCliente",
                        type:"post",
                        data:objJsonCliente,
                    }).then(function(respuesta){
                        idCliente = respuesta['ultimoIdGrabado'];
                        idCliente = parseInt(idCliente);
                        CargarFinPedido(mesa,idCliente);
                        cambiarEstadoMesa(mesa,"con cliente esperando pedido");
                        botonTomarPedido();
                    },function(error){
                            console.log(error);
                    });
                    /* /CLIENTE */
                }else if(respuesta['estado'] != 'cerrada'){
                    alert("Error, la mesa no está cerrada.");
                }else if(foto != null){
                    /**/
                    var extension = (foto['name'].substring(foto['name'].lastIndexOf("."))).toLowerCase();
                    var permitido = false;
                    var peso = inputFileImage.files[0].size;
                    if(peso > 500000){
                        alert("Error, la imagen no puede pesar mas de 5MB");
                    }else if(extension == '.jpg' || extension == '.png' || extension == '.jpeg'){
                    /*CLIENTE*/
                    objJsonCliente=
                    {
                        "nombre": nombreCliente
                    }
                    
                    var funcionAjax=$.ajax({
                        url:"http://www.pardomanuel.xyz/restaurant/Clientes/CargarCliente",
                        type:"post",
                        data:objJsonCliente,
                    }).then(function(respuesta){
                        idCliente = respuesta['ultimoIdGrabado'];
                        idCliente = parseInt(idCliente);
                        var datosDelForm=new FormData();
                        datosDelForm.append("foto",foto);
                        datosDelForm.append("mesa",mesa);
                        datosDelForm.append("idCliente",idCliente);
                        var funcionAjax=$.ajax({
                        url:'http://www.pardomanuel.xyz/restaurant/Pedidos/CargarPedidoConFoto',
                        type:'POST',
                        contentType:false,
                        data:datosDelForm,
                        processData:false,
                        cache:false
                        }).then(function(respuesta){
                            if(respuesta != null){
                                cambiarEstadoMesa(mesa,"con cliente esperando pedido");
                                botonTomarPedido();
                            }
                        },function(error){
                                console.info("error", error);
                        });
                    },function(error){
                            console.log(error);
                    });
                    }else{
                        alert("Error, extensión de imagen inválida.");
                    }
                    }
                    },function(error){
                        console.log(error);
                });
            
            }
        }
	},function(error){
		console.log(error);
    });
}
function CargarNuevoEmpleado(){
    
    var nuevoUsuario = document.getElementById("nuevoUsuario").value;
    var nuevoPass = document.getElementById("nuevoPass").value;
    var sector = document.getElementById("nuevoTipoEmpleado").value;
    var inputFileImage = document.getElementById("archivoImage");
    var foto = inputFileImage.files[0];

    if(foto!=null){
        var extension = (foto['name'].substring(foto['name'].lastIndexOf("."))).toLowerCase();
        var peso = inputFileImage.files[0].size;
    }
    var permitido = false;
    
    if(nuevoUsuario == '' || nuevoPass == ''){
        alert("Error, debe completar todos los campos.");
    }else if(foto == null){
        alert("Seleccione una imagen");
    }else if(peso > 500000){
        alert("Error, la imagen no puede pesar mas de 5 MB");
    }else if(extension == '.jpg' || extension == '.png' || extension == '.jpeg'){
        permitido = true;
    }else {alert("Error, extensión de imagen inválida.");}
    if(permitido){
        var datosDelForm=new FormData();
        datosDelForm.append("foto",foto);
        datosDelForm.append("nuevoUsuario",nuevoUsuario);
        datosDelForm.append("nuevoPass",nuevoPass);
        datosDelForm.append("sector",sector);

        var funcionAjax=$.ajax({
        url:'http://www.pardomanuel.xyz/restaurant/Usuarios/NuevoUsuario',
        type:'POST',
        contentType:false,
        data:datosDelForm,
        processData:false,
        cache:false
        }).then(function(respuesta){
            if(respuesta != null){
                alert("Empleado cargado con éxito.");
                $(".close").click();
                Empleados();
            }
        },function(error){
                console.info("error", error);
        });
    }
}
function cambiarEstadoMesa(mesa,estado,valor){

    objJson=
    {
        "id": mesa,
        "estado": estado
    }
    var funcionAjax=$.ajax({
        url:"http://www.pardomanuel.xyz/restaurant/Mesas/CambiarEstado",
		type:"post",
		data:objJson
    }).then(function(respuesta){
        if(valor == 1){
            listarMesas();
            document.getElementById('moduloCambioEstadoMesas').style.display="none";
            document.getElementById('btnCambioEstadoMesas').style.display="none";
        }
        if(respuesta['resultado'] == false){
            alert("Error, algo ocurrio.");
        }
	},function(error){
			console.log(error);
    });
}
function CargarFinPedido(mesa,idCliente){
    var idMesa = mesa;
    var idClienteOk = idCliente;
    var pathFoto = 'sinFoto.png';

    objJson=
    {
        "idMesa": idMesa,
        "idCliente": idClienteOk,
        "foto": pathFoto
    }

    
    var funcionAjax=$.ajax({
        url:"http://www.pardomanuel.xyz/restaurant/Pedidos/CargarPedido",
		type:"post",
		data:objJson,
    }).then(function(respuesta){
        sumarOperacion();
	},function(error){
			console.info("error", error);
    });
}
function botonPedidosPendientes(){
    document.getElementById('moduloModificoEmpleado').style.display = 'none';
    document.getElementById('moduloMuestroGraficoTorta').style.display='none';
    var moduloMuestroMesas = document.getElementById('moduloMuestroMesas');
    moduloMuestroMesas.style.display = "none";
    var moduloPedidosPendientes = document.getElementById('modulPedidosPendientes');
    moduloPedidosPendientes.style.display = "block";
    moduloPedidosPendientes.innerHTML = '<p align="left"><img src="images/gif/loading_spinner.gif" width="5%"></p>';
    document.getElementById('moduloMuestroEnDetalle').style.display = 'none';
    document.getElementById('agregoTiempoPedido').style.display = 'block';
    document.getElementById('moduloMostrar').style.display = 'none';
    var agregoTiempoPedido = document.getElementById('agregoTiempoPedido');
    var moduloTomarPedidos = document.getElementById('moduloTomarPedidos');
    document.getElementById('moduloCambioEstadoMesas').style.display="none";
    document.getElementById('btnCambioEstadoMesas').style.display="none";
    moduloTomarPedidos.style.display = "none";
    agregoTiempoPedido.innerHTML = "";
    var miToken = localStorage.getItem("mitoken");

    objJson=
    {
        "token": miToken
    }
     var funcionAjax=$.ajax({
            url:"http://www.pardomanuel.xyz/restaurant/Token/Obtener",
            type:"post",
            data: objJson
        }).then(function(respuestaToken){
            if(respuestaToken['datos'] == undefined){
                window.location = "http://www.pardomanuel.xyz/restaurant/";
            }else{
                var tipoEmpleado = respuestaToken['datos']['tipo'];
                if(tipoEmpleado == 'socio'){
                    var funcionAjax=$.ajax({
                    url:"http://www.pardomanuel.xyz/restaurant/Pedidos/TraerTodosLosNoEntregados",
                    type:"get"
                }).then(function(respuesta){
                    if(respuesta!=null){
                        if(respuesta.length>0){
                            var i;
                            var tabla = '<h2>PEDIDOS PENDIENTES</h2>';
                            tabla+='<div class="table-responsive">';
                            tabla+='<table class="table w3-table-all" border=1>';
                            if(respuesta.length>=1){
                            tabla+='<tr class="w3-black"><td><strong>Id</strong></td>'+
                            '<td><strong>Mesa</strong></td> <td><strong>Cliente</strong></td> <td><strong>Ver Detalles</strong></td><td><strong>Facturar</strong></td><td><strong>Cancelar Pedido</strong></td></tr>';
                            for (i = 0; i < respuesta.length; i++) {
                                var id = respuesta[i]['id'];
                                var estado = respuesta[i]['estado'];
                                tabla+='<tr><td>' + respuesta[i]['id'] + '</td>'+
                                '<td>' + respuesta[i]['idMesa'] + '</td>' + 
                                '<td>' + respuesta[i]['nombre'] + '</td>' +
                                '<td><input type="button" value="Ver Detalles" onclick="mozoSocioVerDetalles('+respuesta[i]['id']+')"></div></td>' +
                                '<td><input type="button" value="Detalles para Factura" onclick="VerDetallesFactura('+respuesta[i]['id']+')"></div></td>' +
                                '<td><input type="button" value="Cancelar Pedido" onclick="CancelarPedido('+respuesta[i]['id']+')"></div></td></tr>';
                            }
                            tabla+='</table></div>';
                            moduloPedidosPendientes.innerHTML = tabla;
                            $('html, body').animate({
                                scrollTop: $(document).height()
                            }, 'slow');
                        }
                    }else{
                        moduloPedidosPendientes.innerHTML = "<h2>NO HAY PEDIDOS PENDIENTES.</h2>";          
                    }
                    }
                },function(error){
                        console.info("error", error);
                });
                }
                if(tipoEmpleado == 'mozo'){
                var funcionAjax=$.ajax({
                    url:"http://www.pardomanuel.xyz/restaurant/Pedidos/TraerTodosLosNoEntregados",
                    type:"get"
                }).then(function(respuesta){
                    if(respuesta!=null){
                        if(respuesta.length>0){
                            var i;
                            var tabla = '<h2>PEDIDOS PENDIENTES</h2>';
                            tabla+='<div class="table-responsive">';
                            tabla+='<table class="table w3-table-all" border=1>';
                            if(respuesta.length>=1){
                            tabla+='<tr class="w3-black"><td><strong>Id</strong></td>'+
                            '<td><strong>Mesa</strong></td> <td><strong>Cliente</strong></td> <td><strong>Ver Detalles</strong></td>';
                            for (i = 0; i < respuesta.length; i++) {
                                var id = respuesta[i]['id'];
                                var estado = respuesta[i]['estado'];
                                tabla+='<tr><td>' + respuesta[i]['id'] + '</td>'+
                                '<td>' + respuesta[i]['idMesa'] + '</td>' + 
                                '<td>' + respuesta[i]['nombre'] + '</td>' +
                                '<td><input type="button" value="Ver Detalles" onclick="mozoSocioVerDetalles('+respuesta[i]['id']+')"></div></td></tr>';
                            }
                            tabla+='</table></div>';
                            moduloPedidosPendientes.innerHTML = tabla;
                            $('html, body').animate({
                                scrollTop: $(document).height()
                            }, 'slow');
                        }
                    }else{
                        moduloPedidosPendientes.innerHTML = "<h2>NO HAY PEDIDOS PENDIENTES.</h2>";          
                    }
                    }
                },function(error){
                        console.info("error", error);
                });
                }
                if(tipoEmpleado == 'bartender'){
                    objJson=
                    {
                        "tipoEmpleado": 'bartender',
                        "estado": 'Pendiente'
                    }
                    var funcionAjax=$.ajax({
                        url:"http://www.pardomanuel.xyz/restaurant/PedidoDetalle/TraerPendientesPorSector",
                        type:"post",
                        data: objJson
                    }).then(function(respuesta){
                        if(respuesta!=null){
                            if(respuesta.length == 0){
                            moduloPedidosPendientes.innerHTML = "<h2> NO HAY PEDIDOS PENDIENTES </h2>"
                        }else{
                            
                                var arrayPedidosDetalle = [];
                                arrayPedidosDetalle = respuesta;
                                sector = respuesta[0]['sector'];
                                var i;
                                var tabla = '<h2>BARTENDER</h2>';
                                tabla+='<div class="table-responsive">';
                                tabla+='<table class="table w3-table-all" border=1>';
                                if(respuesta.length>=1){
                                tabla+='<tr class="w3-black"><td><strong>Pedido Nro</strong></td>'+
                                '<td><strong>Estado</strong></td> <td><strong>Mesa</strong></td>'+
                                '<td><strong>Ver Detalles</strong></td><td align="center"><strong>Tiempo de Preparacion (min)</strong></td>';
                
                                for (i = 0; i < respuesta.length; i++){
                                    var idPedidoDetalle = respuesta[i]['id'];
                                    var idPedido = respuesta[i]['idPedido'];
                                    var idInput = "tiempoAsignado" + idPedido;
                                    var estado = respuesta[i]['estado'];
                                    var idMesa = respuesta[i]['idMesa'];
                                    var text = '';
                                        
                                    if(respuesta[i]['estado'] == 'Pendiente'){
                                        text = '<td><div id='+idPedido+'><input type="text" onKeyPress="return soloNumeros(event)" id='+idInput+'><input type="button" value="Asignar Tiempo"  onclick="AsignarTiempo('+idInput+','+idPedidoDetalle+','+respuesta[i]['idPedido']+','+"sector"+')"></div></td></tr>';
                                    }else if(respuesta[i]['estado'] == 'En Preparacion'){
                                        text = '<td><input type="button" value="Listo Para Servir" onclick="CambiarEstadoPedido('+idPedido+','+"sector"+')"></div></td></tr>';
                                    }
                                
                                    tabla+='<tr><td>' + idPedido + '</td>'+
                                    '<td>' + estado + '</td>' + 
                                    '<td>' + idMesa + '</td>' + 
                                    '<td><input type="button" value="Ver Detalles" onclick="VerDetalle('+respuesta[i]['idPedido']+','+"sector"+')"></td>' +
                                    text;
                                    
                                }
                                
                                
                                tabla+='</table></div>';
                                moduloPedidosPendientes.innerHTML = tabla;
                                $('html, body').animate({
                                    scrollTop: $(document).height()
                                }, 'slow');
                                }
                        }
                        }
                    },function(error){
                            console.info("error", error);
                    });
                }
                if(tipoEmpleado == 'cervecero'){
                    objJsonCerveza=
                    {
                        "tipoEmpleado": 'cerveza',
                        "estado": 'Pendiente'
                    }
                    var funcionAjax=$.ajax({
                        url:"http://www.pardomanuel.xyz/restaurant/PedidoDetalle/TraerPendientesPorSector",
                        type:"post",
                        data: objJsonCerveza
                    }).then(function(respuesta){
                        if(respuesta!=null){
                            if(respuesta.length == 0){
                            moduloPedidosPendientes.innerHTML = "<h2> NO HAY PEDIDOS PENDIENTES </h2>"
                        }else{
                            
                                var arrayPedidosDetalle = [];
                                arrayPedidosDetalle = respuesta;
                                sector = respuesta[0]['sector'];
                                var i;
                                var tabla = '<h2>CERVEZA</h2>';
                                tabla+='<div class="table-responsive">';
                                tabla+='<table class="table w3-table-all" border=1>';
                                if(respuesta.length>=1){
                                tabla+='<tr class="w3-black"><td><strong>Pedido Nro</strong></td>'+
                                '<td><strong>Estado</strong></td> <td><strong>Mesa</strong></td>'+
                                '<td><strong>Ver Detalles</strong></td><td align="center"><strong>Tiempo de Preparacion (min)</strong></td>';
                
                                for (i = 0; i < respuesta.length; i++){
                                    var idPedidoDetalle = respuesta[i]['id'];
                                    var idPedido = respuesta[i]['idPedido'];
                                    var idInput = "tiempoAsignado" + idPedido;
                                    var estado = respuesta[i]['estado'];
                                    var idMesa = respuesta[i]['idMesa'];
                                    var text = '';
                                        
                                    if(respuesta[i]['estado'] == 'Pendiente'){
                                        text = '<td><div id='+idPedido+'><input type="text" onKeyPress="return soloNumeros(event)" id='+idInput+'><input type="button" value="Asignar Tiempo"  onclick="AsignarTiempo('+idInput+','+idPedidoDetalle+','+respuesta[i]['idPedido']+','+"sector"+')"></div></td></tr>';
                                    }else if(respuesta[i]['estado'] == 'En Preparacion'){
                                        text = '<td><input type="button" value="Listo Para Servir" onclick="CambiarEstadoPedido('+idPedido+','+"sector"+')"></div></td></tr>';
                                    }
                                
                                    tabla+='<tr><td>' + idPedido + '</td>'+
                                    '<td>' + estado + '</td>' + 
                                    '<td>' + idMesa + '</td>' + 
                                    '<td><input type="button" value="Ver Detalles" onclick="VerDetalle('+respuesta[i]['idPedido']+','+"sector"+')"></td>' +
                                    text;
                                    
                                }
                                
                                
                                tabla+='</table></div>';
                                moduloPedidosPendientes.innerHTML = tabla;
                                $('html, body').animate({
                                    scrollTop: $(document).height()
                                }, 'slow');
                                }
                        }
                        }
                    },function(error){
                            console.info("error", error);
                    });
                }
                if(tipoEmpleado == 'cocinero'){
                    objJsonCocina=
                    {
                        "tipoEmpleado": 'cocina',
                        "estado": 'Pendiente'
                    }
                    var funcionAjax=$.ajax({
                        url:"http://www.pardomanuel.xyz/restaurant/PedidoDetalle/TraerPendientesPorSector",
                        type:"post",
                        data: objJsonCocina
                    }).then(function(respuesta){
                        if(respuesta!=null){
                            if(respuesta.length == 0){
                            moduloPedidosPendientes.innerHTML = "<h2> NO HAY PEDIDOS PENDIENTES </h2>"
                        }else{
                            
                                var arrayPedidosDetalle = [];
                                arrayPedidosDetalle = respuesta;
                                sector = respuesta[0]['sector'];
                                var i;
                                var tabla = '<h2>Cocina</h2>';
                                tabla+='<div class="table-responsive">';
                                tabla+='<table class="table w3-table-all" border=1>';
                                if(respuesta.length>=1){
                                tabla+='<tr class="w3-black"><td><strong>Pedido Nro</strong></td>'+
                                '<td><strong>Estado</strong></td> <td><strong>Mesa</strong></td>'+
                                '<td><strong>Ver Detalles</strong></td><td align="center"><strong>Tiempo de Preparacion (min)</strong></td>';
                
                                for (i = 0; i < respuesta.length; i++){
                                    var idPedidoDetalle = respuesta[i]['id'];
                                    var idPedido = respuesta[i]['idPedido'];
                                    var idInput = "tiempoAsignado" + idPedido;
                                    var estado = respuesta[i]['estado'];
                                    var idMesa = respuesta[i]['idMesa'];
                                    var text = '';
                                        
                                    if(respuesta[i]['estado'] == 'Pendiente'){
                                        text = '<td><div id='+idPedido+'><input type="text" onKeyPress="return soloNumeros(event)" id='+idInput+'><input type="button" value="Asignar Tiempo" onclick="AsignarTiempo('+idInput+','+idPedidoDetalle+','+respuesta[i]['idPedido']+','+"sector"+')"></div></td></tr>';
                                    }else if(respuesta[i]['estado'] == 'En Preparacion'){
                                        text = '<td><input type="button" value="Listo Para Servir" onclick="CambiarEstadoPedido('+idPedido+','+"sector"+')"></div></td></tr>';
                                    }
                                
                                    tabla+='<tr><td>' + idPedido + '</td>'+
                                    '<td>' + estado + '</td>' + 
                                    '<td>' + idMesa + '</td>' + 
                                    '<td><input type="button" value="Ver Detalles" onclick="VerDetalle('+respuesta[i]['idPedido']+','+"sector"+')"></td>' +
                                    text;
                                    
                                }
                                
                                
                                tabla+='</table></div>';
                                moduloPedidosPendientes.innerHTML = tabla;
                                $('html, body').animate({
                                    scrollTop: $(document).height()
                                }, 'slow');
                                }
                        }
                        }
                    },function(error){
                            console.info("error", error);
                    });
                }
            }
        },function(error){
                console.info("error", error);
    });
}
function soloNumeros(e){
        var key = window.Event ? e.which : e.keyCode
        return ((key >= 48 && key <= 57) || (key==8))
    }
function VerDetallesFactura(id){
    var idOk = id;
    
    
    var funcionAjax=$.ajax({
        url:"http://www.pardomanuel.xyz/restaurant/PedidoDetalle/TraerPendienteDetalladoParaFactura/" + id,
        type:"get"
    }).then(function(respuesta){
        idPedido = respuesta[0]['idPedido'];
        var agregoTiempoPedido = document.getElementById('agregoTiempoPedido');
        montoTotal = 0;
        var i;
        var idInput;
        var tabla = '<h2>DETALLES</h2>';
        tabla+='<div class="table-responsive">';
        tabla+='<table class="table w3-table-all" border=1>';
        if(respuesta.length>=1){
        tabla+='<tr class="w3-grey"><td><strong>Descripción</strong></td>'+
        '<td><strong>estado</strong></td> <td><strong>Precio</strong></td>';
        for (i = 0; i < respuesta.length; i++) {
            montoTotal += parseFloat(respuesta[i]['precio']);
            estado = respuesta[i]['estado'];
            var pedido = respuesta[i]['idPedido'];
            var id = respuesta[i]['id'];
            var descripcion = respuesta[i]['descripcion'];
            tabla+='<tr><td>' + descripcion + '</td>'+
            '<td>' + estado + '</td>';
            tabla+='<td>' + respuesta[i]['precio'] + '</td></tr>';
        }
        tabla+='</table></div>';
        tabla+='<br><input type="button" value="Generar Factura" onclick="Facturar('+pedido+','+"montoTotal"+')">';
        tabla+='<br><input type="button" value="Cobrar" onclick="Cobrar('+pedido+')">';
        agregoTiempoPedido.innerHTML = tabla;
        $('html, body').animate({
            scrollTop: $(document).height()
         }, 'slow');
        }
    },function(error){
            console.info("error", error);
    });
}
function Cobrar(idPedido){
    var funcionAjax=$.ajax({
        url:"http://www.pardomanuel.xyz/restaurant/Facturas/TraerUnaFactura/"+idPedido,
		type:"get",
    }).then(function(respuesta){
           if(respuesta){
            objJson=
        {
            "idPedido": idPedido
        }
            var funcionAjax=$.ajax({
            url:"http://www.pardomanuel.xyz/restaurant/Facturas/Cobrar",
            type:"post",
            data: objJson
            }).then(function(respuesta){
                if(respuesta['resultado']){
                    var confirma = confirm("Se Cobró con éxito, quiere cerrar la mesa?");
                    if(confirma){
                        objJsonPedido=
                        {
                            "id": idPedido
                        }
                        var funcionAjax=$.ajax({
                            url:"http://www.pardomanuel.xyz/restaurant/Pedidos/TraerUnPedido",
                            type:"post",
                            data: objJsonPedido
                            }).then(function(respuesta){
                                cambiarEstadoMesa(respuesta['idMesa'],"cerrada");
                                botonPedidosPendientes();
                                var funcionAjax=$.ajax({
                                    url:"http://www.pardomanuel.xyz/restaurant/Pedidos/TerminarPedido",
                                    type:"post",
                                    data: objJsonPedido
                                    }).then(function(respuesta){
                                        botonPedidosPendientes();
                                    },function(error){
        
                                        console.info("error", error);
                                });
                            },function(error){

                                console.info("error", error);
                        });
                    }
                }
            },function(error){
                    console.info("error", error);
            });
            
           }else{
            alert("Error, primero tiene que generar la factura para cobrar.");
        }
	},function(error){

			console.info("error", error);
    });
}
function Facturar(id, montoTotal){
    var idOk = id;
    var funcionAjax=$.ajax({
        url:"http://www.pardomanuel.xyz/restaurant/Facturas/TraerUnaFactura/"+idOk,
		type:"get",
    }).then(function(respuesta){
           if(respuesta){
                alert("Error, esta facutra ya está generada.");
           }else{
            objJson=
            {
                "id": idOk,
                "montoTotal":montoTotal
            }
            var funcionAjax=$.ajax({
                url:"http://www.pardomanuel.xyz/restaurant/Facturas/GenerarFactura",
                type:"post",
                data: objJson
            }).then(function(respuesta){
                alert("Factura generada con exito");
                SumarFacturacionAMesa(idOk,montoTotal);
                var idFactura = respuesta['id'];
                if(respuesta!=null){
                    objJsonDos=
                    {
                        "idPedido": idOk,
                        "idFactura": idFactura
                    }
                    var funcionAjax=$.ajax({
                        url:"http://www.pardomanuel.xyz/restaurant/Pedidos/FinPedido",
                        type:"post",
                        data: objJsonDos
                    }).then(function(respuesta){
                        console.log(respuesta);
                    },function(error){
                        console.info("error", error);
                    });
                }
                },function(error){
                        console.info("error", error);
                });
           }
	},function(error){

			console.info("error", error);
    });
}
function mozoSocioVerDetalles(id){
    var idOk = id;
    objJson=
    {
        "id": idOk
    }
    var funcionAjax=$.ajax({
        url:"http://www.pardomanuel.xyz/restaurant/PedidoDetalle/TraerPendienteDetalladoMozo",
        type:"post",
        data: objJson
    }).then(function(respuesta){
        idPedido = respuesta[0]['idPedido'];
        var agregoTiempoPedido = document.getElementById('agregoTiempoPedido');
        var i;
        var idInput;
        var tabla = '<h2>DETALLES</h2><br>';
        if(respuesta[0]['foto']!='sinFoto.png'){
            tabla+='<div align="left"><img id="imgListaEmpleados" src="images/pedidos/'+respuesta[0]['foto']+'"></div><br>';
        }
        tabla+='<div class="table-responsive">';
        tabla+='<table class="table w3-table-all" border=1>';
        if(respuesta.length>=1){
        tabla+='<tr class="w3-grey"><td><strong>Descripción</strong></td>'+
        '<td><strong>estado</strong></td> <td><strong>Entregar</strong></td>';
        for (i = 0; i < respuesta.length; i++) {
            estado = respuesta[i]['estado'];
            var id = respuesta[i]['id'];
            var descripcion = respuesta[i]['descripcion'];
            tabla+='<tr><td>' + descripcion + '</td>'+
            '<td>' + estado + '</td>';
            tabla+='<td><input type="button" value="Entregar" onclick="Entregar('+id+','+respuesta[0]['idMesa']+')"></td></tr>';
        }
        tabla+='</table></div>';
        agregoTiempoPedido.innerHTML = tabla;
        $('html, body').animate({
            scrollTop: $(document).height()
         }, 'slow');
        }
    },function(error){
            console.info("error", error);
    });
}
function Entregar(id,idMesa){

    unObjJson=
    {
        "id": id
    }
    var funcionAjax=$.ajax({
        url:"http://www.pardomanuel.xyz/restaurant/PedidoDetalle/TraerUnPedidoDetalle",
        type:"post",
        data: unObjJson
    }).then(function(respuestaUno){ 
        if(respuestaUno!=null){
            if(respuestaUno['estado'] != 'Listo Para Servir' && respuestaUno['estado'] != 'Entregado'){
                alert("Error, el pedido no esta listo para servir.");
            }else if(respuestaUno['estado'] == 'Entregado'){
                alert("No se puede entregar un pedido ya entregado.");
            }else if(respuestaUno['estado'] == 'Listo Para Servir'){
            var fecha = new Date();
            var horas = fecha.getHours();
            horas = horas.toString();
            var minutos = fecha.getMinutes();
            minutos = minutos.toString();
            if(horas.length == 1){
                horas = '0'+horas;
            }
            if(minutos.length == 1){
                minutos='0'+minutos;
            }
            var horaEntrega = horas + ":" + minutos;
            
            objJson=
            {
                "id": id,
                "estado":"Entregado",
                "horaEntrega": horaEntrega
            }
            var funcionAjax=$.ajax({
                url:"http://www.pardomanuel.xyz/restaurant/PedidoDetalle/FinalizarEstado",
                type:"post",
                data: objJson
            }).then(function(respuesta){
                botonPedidosPendientes();
                mozoSocioVerDetalles(respuestaUno['idPedido']);
                ChequeoFinPEdido(respuestaUno['idPedido'],idMesa);
            },function(error){
                    console.log(error);
            });
        }
        }
    },function(error){
        console.log(error);
    });

}
function ChequeoFinPEdido(idPedido,idMesa){
    var retorno = false;
    objJson=
    {
        "idPedido": idPedido
    }
    var funcionAjax=$.ajax({
        url:"http://www.pardomanuel.xyz/restaurant/PedidoDetalle/ChequeoFinPEdido",
        type:"post",
        data: objJson
    }).then(function(respuesta){
        if(respuesta!=null){
            for (i = 0; i < respuesta.length; i++){
                if(respuesta[i]['estado'] == 'Entregado'){
                    retorno = true;
                }else{
                    retorno = false;
                    break;
                }
                
            }
        
        /*if(retorno){
            var funcionAjax=$.ajax({
            url:"http://www.pardomanuel.xyz/restaurant/Pedidos/FinPedido",
            type:"post",
            data: objJson
            }).then(function(respuestaDos){
                if(respuestaDos['resultado']){
                    botonPedidosPendientes();
                    cambiarEstadoMesa(idMesa,"Con Clientes Pagando");
                    alert("Pedido Finalizado, solo el socio podrá facturar");
                }else{
                    console.log("algo ocurrio");
                }
                
            },function(error){
                    console.info("error", error);
            });*/
        }   
    },function(error){
            console.info("error", error);
    });
    
}
function VerDetalle(unIdPedido,sector){
   if(sector == 'cocina' || sector == 'bartender' || sector == 'cerveza'){
    objJson=
    {
        "tipoEmpleado": sector,
        "idPedido": unIdPedido
    }
    var funcionAjax=$.ajax({
        url:"http://www.pardomanuel.xyz/restaurant/PedidoDetalle/TraerPendienteDetallado",
        type:"post",
        data: objJson
    }).then(function(respuesta){
        if(respuesta!=null){
            var agregoTiempoPedido = document.getElementById('agregoTiempoPedido');
            sector = "cocina";
            var i;
            var tabla = '<h2>DETALLE</h2><br>';
            if(respuesta[0]['foto']!='sinFoto.png'){
            tabla+='<div align="left"><img id="imgListaEmpleados" src="images/pedidos/'+respuesta[0]['foto']+'"></div><br>';
            }
            tabla+='<div class="table-responsive">';
            tabla+='<table class="table w3-table-all" border=1>';
            if(respuesta.length>=1){
                tabla+='<tr><td><strong>Pedido Nro</strong></td>'+
                '<td><strong>Detalle</strong></td>';
                for (i = 0; i < respuesta.length; i++) {
                    tabla+='<tr><td>' + unIdPedido + '</td>'+
                    '<td>' + respuesta[i]['descripcion'] + '</td></tr>';
                }
            tabla+='</table></div>';
            agregoTiempoPedido.innerHTML = tabla;
            $('html, body').animate({
                scrollTop: $(document).height()
             }, 'slow');
            }
        }
    },function(error){
            console.info("error", error);
    });
   }

}

function AsignarTiempo(tiempo,id,idPedido,sector){
    var fecha = new Date();
    var unaHoraInicio = fecha.getHours();
    unaHoraInicio = unaHoraInicio.toString();
    var unMinutoInicio = fecha.getMinutes();
    unMinutoInicio = unMinutoInicio.toString();
    if(unaHoraInicio.length == 1){
        unaHoraInicio = '0'+unaHoraInicio;
    }
    if(unMinutoInicio.length == 1){
        unMinutoInicio='0'+unMinutoInicio;
    }
    var horaInicio = unaHoraInicio + ":" + unMinutoInicio;
    fecha.setMinutes(fecha.getMinutes() + parseFloat(tiempo.value));
    var unaHoraPrometida = fecha.getHours();
    unaHoraPrometida = unaHoraPrometida.toString();
    var unMinutoPrometido = fecha.getMinutes();
    unMinutoPrometido = unMinutoPrometido.toString();
    if(unaHoraPrometida.length == 1){
        unaHoraPrometida = '0'+unaHoraPrometida;
    }
    if(unMinutoPrometido.length == 1){
        unMinutoPrometido='0'+unMinutoPrometido;
    }
    var horaPrometida =unaHoraPrometida + ":" + unMinutoPrometido;
    
    objJson=
    {
        "horaInicio": horaInicio,
        "horaPrometida": horaPrometida,
        "idPedido": idPedido,
        "estado": "En Preparacion",
        "sector": sector
    }
    var funcionAjax=$.ajax({
        url:"http://www.pardomanuel.xyz/restaurant/PedidoDetalle/GuardarHoraInicioYPrometida",
        type:"post",
        data: objJson
    }).then(function(respuesta){
        console.log(respuesta);
        sumarOperacion();
        botonPedidosPendientes();
    },function(error){
            console.info("error", error);
    });
}


function CambiarEstadoPedido(id,sector){
    var confirma = confirm("Seguro termino todo el pedido?");
    var fecha = new Date();
    var horaEntrega = fecha.getHours() + ":" + fecha.getMinutes() + ":" + fecha.getSeconds();
    if(confirma){
        datos=
        {
            "idPedido": id,
            "estado": "Listo Para Servir",
            "sector":sector
        }
        var funcionAjax=$.ajax({
                    url:"http://www.pardomanuel.xyz/restaurant/Pedidos/CambiarEstado",
                    type:"post",
                    data: datos
                }).then(function(respuesta){
                    console.log(respuesta);
                    botonPedidosPendientes();
                },function(error){
                        console.log(error);
                });
    }
    /*
    var confirma = confirm("Seguro quieres cambiar el estado?");
    if(confirma){
        objJson=
        {
            "id": id
        }
        var funcionAjax=$.ajax({
            url:"http://www.pardomanuel.xyz/restaurant/Pedidos/TraerUnPedido",
            type:"post",
            data: objJson
        }).then(function(respuesta){
            if(respuesta['estado'] == "Pendiente"){
                datos=
                {
                    "id": id,
                    "estado": "En Preparacion"
                }
                var funcionAjax=$.ajax({
                    url:"http://www.pardomanuel.xyz/restaurant/Pedidos/CambiarEstado",
                    type:"post",
                    data: datos
                }).then(function(respuesta){
                    if(respuesta['resultado']){
                        botonPedidosPendientes();
                    }else{
                        alert("ERROR");
                        console.log(resultado);
                    }
                },function(error){
                        console.log(error);
                });
            }
            if(respuesta['estado'] == "En Preparacion"){
                datos=
                {
                    "id": id,
                    "estado": "Listo Para Servir"
                }
                var funcionAjax=$.ajax({
                    url:"http://www.pardomanuel.xyz/restaurant/Pedidos/CambiarEstado",
                    type:"post",
                    data: datos
                }).then(function(respuesta){
                    if(respuesta['resultado']){
                        botonPedidosPendientes();
                    }else{
                        alert("ERROR");
                        console.log(resultado);
                    }
                },function(error){
                        console.log(error);
                });
            }
        },function(error){
                console.log(error);
        });
    }*/
}
function listarMesas(){
    document.getElementById('moduloModificoEmpleado').style.display = 'none';
    document.getElementById('moduloMuestroGraficoTorta').style.display='none';
    document.getElementById('moduloCambioEstadoMesas').style.display="none";
    document.getElementById('btnCambioEstadoMesas').style.display="none";
    document.getElementById('moduloMuestroEnDetalle').style.display = "none";
    document.getElementById('moduloMostrar').style.display = "none";
    var agregoTiempoPedido = document.getElementById('agregoTiempoPedido');
    var moduloTomarPedidos = document.getElementById('moduloTomarPedidos');
    var moduloPedidosPendientes = document.getElementById('modulPedidosPendientes');
    var moduloMuestroMesas = document.getElementById('moduloMuestroMesas');
    moduloTomarPedidos.style.display = "none";
    moduloPedidosPendientes.style.display = "none";
    moduloMuestroMesas.style.display = "block";
    agregoTiempoPedido.innerHTML = "";
    
    var funcionAjax=$.ajax({
        url:"http://www.pardomanuel.xyz/restaurant/Mesas/TraerTodas",
        type:"get"
    }).then(function(respuesta){
        
            if(respuesta!=null){
                var i;
                var tabla = '<h2>LISTADO MESAS</h2>';
                tabla+='<div class="table-responsive">';
                tabla+='<table class="table w3-table-all" border=1>';
                if(respuesta.length>=1){
                    tabla+='<tr class="w3-black"><td><strong>Id Mesa</strong></td><td><strong>Codigo</strong></td><td><strong>Estado Actual</strong></td><td><strong>Cambiar Estado</strong></td></tr>';
                    for (i = 0; i < respuesta.length; i++) {
                        var estado = respuesta[i]['estado'];
                        var id = respuesta[i]['id'];
                        tabla+='<tr><td>' + respuesta[i]['id'] + '</td>'+
                        '<td>' + respuesta[i]['codigo'] + '</td>' +
                        '<td>' + respuesta[i]['estado'] + '</td>' +
                        '<td><input type="button" value="Cambiar Estado" onclick="botonMuestroFormulario('+id+')"></td></tr>';
                    }
                }
                tabla+='</table></div>';
                moduloMuestroMesas.innerHTML = tabla;
                $('html, body').animate({
                    scrollTop: $(document).height()
                 }, 'slow');
                
            }
	},function(error){
			console.info("error", error);
    });
}
function botonMuestroFormulario(id){
    cargarSelectEstadoMesa();
    valor = 1;
    document.getElementById("estadosMesas").length = 0;
    document.getElementById('btnCambioEstadoMesas').style.display="block";
    document.getElementById('moduloCambioEstadoMesas').style.display="block";
    document.getElementById('btnCambioEstadoMesas').innerHTML  = '<input type="button" value="Cambiar Estado" onclick="cambiarEstadoMesa('+id+','+"document.getElementById('estadosMesas').value"+','+"valor"+')"><br><h3>AFECTA MESA N°: '+id+ '</h3>';
    $('html, body').animate({
        scrollTop: $(document).height()
    }, 'slow');
}

function sumarOperacion(){
    var miToken = localStorage.getItem("mitoken");

    objJson=
    {
        "token": miToken
    }
     var funcionAjax=$.ajax({
            url:"http://www.pardomanuel.xyz/restaurant/Token/Obtener",
            type:"post",
            data: objJson
        }).then(function(respuestaToken){
            var id = respuestaToken['datos']['idUsuario']; 
            id = parseInt(id);
            data = 
            {
                "idUsuario": id
            }
            var funcionAjax=$.ajax({
                url:"http://www.pardomanuel.xyz/restaurant/Operaciones/SumarOperacion",
                type:"post",
                data: data
            }).then(function(respuesta){
            },function(error){
                    console.info("error", error);
            });
        },function(error){
                console.info("error", error);
    });
}
function SumarFacturacionAMesa(id,montoTotal){
    ObjJson =
    {
        "id": id
    }
    var funcionAjax=$.ajax({
        url:"http://www.pardomanuel.xyz/restaurant/Pedidos/TraerUnPedido ",
        type:"post",
        data: ObjJson
    }).then(function(respuestaPedido){
        var funcionAjax=$.ajax({
        url:"http://www.pardomanuel.xyz/restaurant/Mesas/TraerUnaMesa/"+respuestaPedido['idMesa'],
        type:"get"
    }).then(function(respuestaMesa){
        var idMesa = respuestaMesa['id'];
        var facturacion = respuestaMesa['facturacion'];
        
        facturacion = parseFloat(facturacion);
        total = facturacion + montoTotal;
        ObjJsonMesa =
        {
            "id": idMesa,
            "total":total
        }
            var funcionAjax=$.ajax({
            url:"http://www.pardomanuel.xyz/restaurant/Mesas/SumarFacturacion",
            type:"post",
            data: ObjJsonMesa
            }).then(function(respuesta){
            
            },function(error){
                console.info("error", error);
            });
	},function(error){
			console.info("error", error);
    });
	},function(error){
			console.info("error", error);
    });

}

function ModuloMostrar(){
    document.getElementById('moduloModificoEmpleado').style.display = 'none';
    document.getElementById('moduloMuestroGraficoTorta').style.display='none';
    var miToken = localStorage.getItem("mitoken");

    objJson=
    {
        "token": miToken
    }
     var funcionAjax=$.ajax({
            url:"http://www.pardomanuel.xyz/restaurant/Token/Obtener",
            type:"post",
            data: objJson
        }).then(function(respuestaToken){
            if(respuestaToken['datos']['tipo'] != "socio"){
            alert("Error, usted no tiene permiso");
            }else{
                document.getElementById('agregoTiempoPedido').style.display = 'none';
                document.getElementById('modulPedidosPendientes').style.display="none";
                document.getElementById('moduloCambioEstadoMesas').style.display="none";
                document.getElementById('btnCambioEstadoMesas').style.display="none";
                var modulo = document.getElementById('moduloMostrar');
                modulo.style.display = 'block';
                document.getElementById('moduloMuestroEnDetalle').style.display = 'none';
                document.getElementById('moduloTomarPedidos').style.display = 'none';
                document.getElementById('moduloMuestroMesas').style.display = 'none';
            
            }
        },function(error){
                console.info("error", error);
    });
}

function Mostrar(){
    var queMostrar = document.getElementById('selectMostrar').value;
    var modulo = document.getElementById('moduloMuestroEnDetalle');
    modulo.style.display = 'block';
    modulo.innerHTML='';
    if(queMostrar == "ingresos"){  
        if(document.getElementById('fechaMin').value == '' || document.getElementById('fechaMax').value == ''){
            alert("ERROR, debe seleccionar fechas");
        }else{
            data = {
                "fechaMin": document.getElementById('fechaMin').value,
                "fechaMax": document.getElementById('fechaMax').value
            }
            var funcionAjax=$.ajax({
                url:"http://www.pardomanuel.xyz/restaurant/Sesion/TraerLogueosPorFecha",
                type:"post",
                data: data
            }).then(function(respuesta){
                 if(respuesta!= null){
                     if(respuesta.length == 0){
                        var codigo = "<h4>No se encontraron logueos</h4>";
                        modulo.innerHTML = codigo;
                     }else{
                        var tabla = '';
                        tabla+='<div class="table-responsive">';
                        tabla+='<table class="table w3-table-all" border=1>';
                        if(respuesta.length>=1){
                        tabla+='<tr class="w3-black"><td><strong>Id Usuario</strong></td><td><strong>Nombre</strong></td>'+
                        '<td><strong>Fecha Hora Logueo</strong></td></tr>';
                        for (i = 0; i < respuesta.length; i++) {
                            tabla+='<tr><td>' + respuesta[i]['idUsuario'] + '</td>'+
                            '<td>' + respuesta[i]['usuario'] + '</td>'+
                            '<td>' + respuesta[i]['fechaHoraLog'] + '</td></tr>';
                        }
                        tabla+='</table></div>';
                        tabla+='<input type="button" id="btnExport" onclick="ExportarExcel()" value="Exportar a Excel">';
                        tabla+='<input type="button" id="btnExportPDF" value="Exportar a PDF" onclick="ExportarPDF()">';
                        modulo.innerHTML = tabla;
                        $('html, body').animate({
                            scrollTop: $(document).height()
                         }, 'slow');
                        }
                     }
                 }
            },function(error){
                    console.info("error", error);
            });
        }
    }else if(queMostrar == "operacionesPorSector"){
        var sector = document.getElementById('sector').value;
        if(sector == ''){
            alert("Error, tiene que ingresar un sector");
        }else if(sector != 'bartender' && sector != 'mozo' && sector != 'cocinero' && sector != 'cervecero'){  
            alert("Error, ingrese un sector valido");
        }else{
            
            var funcionAjax=$.ajax({
                url:"http://www.pardomanuel.xyz/restaurant/Operaciones/TraerOperacionesPorSector/"+sector,
                type:"get"
            }).then(function(respuesta){
                var codigo = "";
                codigo+= "Cantidad total de operaciones para el sector "+sector+": " + respuesta[0]["COUNT(*)"];
                modulo.innerHTML = codigo;
            },function(error){
                    console.info("error", error);
            });
        }
    }else if(queMostrar == "operacionesPorSectorPorEmpleado"){
        var sector = document.getElementById('sector').value;
        if(sector == '' || document.getElementById('fechaMin').value == '' || document.getElementById('fechaMax').value == ''){
            alert("Error, tiene que ingresar todos los datos (no olvidar fechas)");
        }else if(sector != 'bartender' && sector != 'mozo' && sector != 'cocinero' && sector != 'cervecero'){  
            alert("Error, ingrese un sector valido");
        }else{
            data = {
                "fechaMin": document.getElementById('fechaMin').value,
                "fechaMax": document.getElementById('fechaMax').value,
                "sector":sector
            }
            var funcionAjax=$.ajax({
                url:"http://www.pardomanuel.xyz/restaurant/Operaciones/TraerOperacionesPorSectorPorEmpleado",
                type:"post",
                data: data
            }).then(function(respuesta){
                var tabla = '';
                tabla+='<div class="table-responsive">';
                tabla+='<table class="table w3-table-all" border=1>';
                if(respuesta.length>=1){
                    tabla+='<tr class="w3-black"><td><strong>Usuario</strong></td>'+
                    '<td><strong>Cantidad Operaciones</strong></td></tr>';
                    for (i = 0; i < respuesta.length; i++) {
                        tabla+='<tr><td>' + respuesta[i]['usuario'] + '</td>'+
                        '<td>' + respuesta[i]["cantidad"] + '</td></tr>';
                    }
                    tabla+='</table></div>';
                    tabla+='<input type="button" id="btnExport" onclick="ExportarExcel()" value="Exportar a Excel">';
                    tabla+='<input type="button" id="btnExportPDF" value="Exportar a PDF" onclick="ExportarPDF()">';
                    modulo.innerHTML = tabla;
                    $('html, body').animate({
                        scrollTop: $(document).height()
                     }, 'slow');
                }
            },function(error){
                    console.info("error", error);
            });
        }
    }else if(queMostrar == "operacionesPorEmpleadoPorSeparado"){
        if(document.getElementById('fechaMin').value == '' || document.getElementById('fechaMax').value == ''){
            alert("Error, tiene que ingresar ambas fechas");
        }else{
        data = {
                "fechaMin": document.getElementById('fechaMin').value,
                "fechaMax": document.getElementById('fechaMax').value
            }
        var funcionAjax=$.ajax({
            url:"http://www.pardomanuel.xyz/restaurant/Operaciones/CantidadOperacionesPorUsuario",
            type:"post",
            data: data
        }).then(function(respuesta){
            if(respuesta!=null){
                var tabla = '';
                tabla+='<div class="table-responsive">';
                tabla+='<table class="table w3-table-all" border=1>';
                if(respuesta.length>=1){
                    tabla+='<tr class="w3-black"><td><strong>Usuario</strong></td>'+
                    '<td><strong>Cantidad Operaciones</strong></td></tr>';
                    for (i = 0; i < respuesta.length; i++) {
                        tabla+='<tr><td>' + respuesta[i]['usuario'] + '</td>'+
                        '<td>' + respuesta[i]['cantidad'] + '</td></tr>';
                    }
                    tabla+='</table></div>';
                    tabla+='<input type="button" id="btnExport" onclick="ExportarExcel()" value="Exportar a Excel">';
                    tabla+='<input type="button" id="btnExportPDF" value="Exportar a PDF" onclick="ExportarPDF()">';
                    modulo.innerHTML = tabla;
                    $('html, body').animate({
                        scrollTop: $(document).height()
                     }, 'slow');
                }
            }
            
        },function(error){
                console.info("error", error);
        });
        }
    }else if(queMostrar == "productosMasVendidos"){
        if(document.getElementById('fechaMin').value == '' || document.getElementById('fechaMax').value == ''){
            alert("Error, ingrese ambas fechas");
        }else{
            data = 
            {
                "fechaMin": document.getElementById('fechaMin').value,
                "fechaMax": document.getElementById('fechaMax').value
            }
            var funcionAjax=$.ajax({
                url:"http://www.pardomanuel.xyz/restaurant/PedidoDetalle/MasVendido",
                type:"post",
                data:data
            }).then(function(respuesta){
                if(respuesta!=null){
                    var tabla = '';
                    tabla+='<div class="table-responsive">';
                    tabla+='<table class="table w3-table-all" border=1>';
                    if(respuesta.length>=1){
                        tabla+='<tr class="w3-black"><td><strong>Producto</strong></td>'+
                        '<td><strong>Veces Vendido</strong></td></tr>';
                        for (i = 0; i < respuesta.length; i++) {
                            tabla+='<tr><td>' + respuesta[i]['descripcion'] + '</td>'+
                            '<td>' + respuesta[i]['TotalVentas'] + '</td></tr>';
                        }
                        tabla+='</table></div>';
                        tabla+='<input type="button" id="btnExport" onclick="ExportarExcel()" value="Exportar a Excel">';
                        tabla+='<input type="button" id="btnExportPDF" value="Exportar a PDF" onclick="ExportarPDF()">';
                        modulo.innerHTML = tabla;
                        $('html, body').animate({
                            scrollTop: $(document).height()
                         }, 'slow');
                    }
                }
                
            },function(error){
                    console.info("error", error);
            });
        }
        
    }else if(queMostrar == "productosMenosVendidos"){
        if(document.getElementById('fechaMin').value == '' || document.getElementById('fechaMax').value == ''){
            alert("Error, ingrese ambas fechas");
        }else{
            data = 
            {
                "fechaMin": document.getElementById('fechaMin').value,
                "fechaMax": document.getElementById('fechaMax').value
            }
            var funcionAjax=$.ajax({
                url:"http://www.pardomanuel.xyz/restaurant/PedidoDetalle/MenosVendido",
                type:"post",
                data:data
            }).then(function(respuesta){
                if(respuesta!=null){
                    var tabla = '';
                    tabla+='<div class="table-responsive">';
                    tabla+='<table class="table w3-table-all" border=1>';
                    if(respuesta.length>=1){
                        tabla+='<tr class="w3-black"><td><strong>Producto</strong></td>'+
                        '<td><strong>Veces Vendido</strong></td></tr>';
                        for (i = 0; i < respuesta.length; i++) {
                            tabla+='<tr><td>' + respuesta[i]['descripcion'] + '</td>'+
                            '<td>' + respuesta[i]['TotalVentas'] + '</td></tr>';
                        }
                        tabla+='</table></div>';
                        tabla+='<input type="button" id="btnExport" onclick="ExportarExcel()" value="Exportar a Excel">';
                        tabla+='<input type="button" id="btnExportPDF" value="Exportar a PDF" onclick="ExportarPDF()">';
                        modulo.innerHTML = tabla;
                        $('html, body').animate({
                            scrollTop: $(document).height()
                         }, 'slow');
                    }
                }
                
            },function(error){
                    console.info("error", error);
            });
        }
        
    }else if(queMostrar == "pedidosCancelados"){
        if(document.getElementById('fechaMin').value == '' || document.getElementById('fechaMax').value == ''){
            alert("Error, ingrese ambas fechas");
        }else{
            data = 
            {
                "fechaMin": document.getElementById('fechaMin').value,
                "fechaMax": document.getElementById('fechaMax').value
            }
            var funcionAjax=$.ajax({
                url:"http://www.pardomanuel.xyz/restaurant/PedidoDetalle/TraerCancelados",
                type:"post",
                data:data
            }).then(function(respuesta){
                if(respuesta!=null){
                    var tabla = '';
                    tabla+='<div class="table-responsive">';
                    tabla+='<table class="table w3-table-all" border=1>';
                    if(respuesta.length>=1){
                        tabla+='<tr class="w3-black"><td><strong>Pedido N°</strong></td></tr>';
                        for (i = 0; i < respuesta.length; i++) {
                            tabla+='<tr><td>' + respuesta[i]['id'] + '</td></tr>';
                        }
                        tabla+='</table></div>';
                        tabla+='<input type="button" id="btnExport" onclick="ExportarExcel()" value="Exportar a Excel">';
                        tabla+='<input type="button" id="btnExportPDF" value="Exportar a PDF" onclick="ExportarPDF()">';
                        modulo.innerHTML = tabla;
                        $('html, body').animate({
                            scrollTop: $(document).height()
                         }, 'slow');
                    }
                }
                
            },function(error){
                    console.info("error", error);
            });
        }
    }else if(queMostrar == "mesaMasUsada"){
        if(document.getElementById('fechaMin').value == '' || document.getElementById('fechaMax').value == ''){
            alert("Error, ingrese ambas fechas");
        }else{
            data = 
            {
                "fechaMin": document.getElementById('fechaMin').value,
                "fechaMax": document.getElementById('fechaMax').value
            }
            var funcionAjax=$.ajax({
                url:"http://www.pardomanuel.xyz/restaurant/Mesas/TraerMasUsada",
                type:"post",
                data:data
            }).then(function(respuesta){
                if(respuesta!=null){
                    var tabla = '';
                    tabla+='<div class="table-responsive">';
                    tabla+='<table class="table w3-table-all" border=1>';
                    if(respuesta.length>=1){
                        tabla+='<tr class="w3-black"><td><strong>Mesa N°</strong></td><td><strong>Veces usada</strong></td></tr>';
                        for (i = 0; i < respuesta.length; i++) {
                            tabla+='<tr><td>' + respuesta[i]['idMesa'] + '</td>' + 
                            '<td>' + respuesta[i]['cantidad'] + '</td></tr>';
                        }
                        tabla+='</table></div>';
                        tabla+='<input type="button" id="btnExport" onclick="ExportarExcel()" value="Exportar a Excel">';
                        tabla+='<input type="button" id="btnExportPDF" value="Exportar a PDF" onclick="ExportarPDF()">';
                        modulo.innerHTML = tabla;
                        $('html, body').animate({
                            scrollTop: $(document).height()
                         }, 'slow');
                    }
                }
                
            },function(error){
                    console.info("error", error);
            });
        }
        
    }else if(queMostrar == "mesaMenosUsada"){
        if(document.getElementById('fechaMin').value == '' || document.getElementById('fechaMax').value == ''){
            alert("Error, ingrese ambas fechas");
        }else{
            data = 
            {
                "fechaMin": document.getElementById('fechaMin').value,
                "fechaMax": document.getElementById('fechaMax').value
            }
            var funcionAjax=$.ajax({
                url:"http://www.pardomanuel.xyz/restaurant/Mesas/TraerMenosUsada",
                type:"post",
                data:data
            }).then(function(respuesta){
                if(respuesta!=null){
                    var tabla = '';
                    tabla+='<div class="table-responsive">';
                    tabla+='<table class="table w3-table-all" border=1>';
                    if(respuesta.length>=1){
                        tabla+='<tr class="w3-black"><td><strong>Mesa N°</strong></td><td><strong>Veces usada</strong></td></tr>';
                        for (i = 0; i < respuesta.length; i++) {
                            tabla+='<tr><td>' + respuesta[i]['idMesa'] + '</td>' + 
                            '<td>' + respuesta[i]['cantidad'] + '</td></tr>';
                        }
                        tabla+='</table></div>';
                        tabla+='<input type="button" id="btnExport" onclick="ExportarExcel()" value="Exportar a Excel">';
                        tabla+='<input type="button" id="btnExportPDF" value="Exportar a PDF" onclick="ExportarPDF()">';
                        modulo.innerHTML = tabla;
                        $('html, body').animate({
                            scrollTop: $(document).height()
                         }, 'slow');
                    }
                }
                
            },function(error){
                    console.info("error", error);
            });
        }
        
    }else if(queMostrar == "mesaMasFacturo"){
            var funcionAjax=$.ajax({
                url:"http://www.pardomanuel.xyz/restaurant/Mesas/TraerMasFacturo",
                type:"get"
            }).then(function(respuesta){
                if(respuesta!=null){
                    var tabla = '';
                    tabla+='<div class="table-responsive">';
                    tabla+='<table class="table w3-table-all" border=1>';
                    tabla+='<tr class="w3-black"><td><strong>ID</strong></td><td><strong>Mesa N°</strong></td><td><strong>Facturacion</strong></td></tr>';
                    tabla+='<tr><td>' + respuesta['id'] + '</td>' + 
                    '<td>' + respuesta['codigo'] + '</td>' +
                    '<td>' + respuesta['facturacion'] + '</td></tr>';
                    tabla+='</table></div>';
                    tabla+='<input type="button" id="btnExport" onclick="ExportarExcel()" value="Exportar a Excel">';
                    tabla+='<input type="button" id="btnExportPDF" value="Exportar a PDF" onclick="ExportarPDF()">';
                    modulo.innerHTML = tabla;
                    $('html, body').animate({
                        scrollTop: $(document).height()
                     }, 'slow');
                    
                }
                
            },function(error){
                    console.info("error", error);
            });
        
        
    }else if(queMostrar == "mesaMenosFacturo"){
        var funcionAjax=$.ajax({
            url:"http://www.pardomanuel.xyz/restaurant/Mesas/TraerMenosFacturo",
            type:"get"
        }).then(function(respuesta){
            if(respuesta!=null){
                var tabla = '';
                tabla+='<div class="table-responsive">';
                tabla+='<table class="table w3-table-all" border=1>';
                tabla+='<tr class="w3-black"><td><strong>ID</strong></td><td><strong>Mesa N°</strong></td><td><strong>Facturacion</strong></td></tr>';
                tabla+='<tr><td>' + respuesta['id'] + '</td>' + 
                '<td>' + respuesta['codigo'] + '</td>' +
                '<td>' + respuesta['facturacion'] + '</td></tr>';
                tabla+='</table></div>';
                tabla+='<input type="button" id="btnExport" onclick="ExportarExcel()" value="Exportar a Excel">';
                tabla+='<input type="button" id="btnExportPDF" value="Exportar a PDF" onclick="ExportarPDF()">';
                modulo.innerHTML = tabla;
                $('html, body').animate({
                    scrollTop: $(document).height()
                 }, 'slow');
            }
            
        },function(error){
                console.info("error", error);
        });
    }else if(queMostrar == "mesaFacturaMayorImporte"){
        var funcionAjax=$.ajax({
            url:"http://www.pardomanuel.xyz/restaurant/Mesas/TraerLaQueTuvoFacturaMasImporte",
            type:"get"
        }).then(function(respuesta){
            if(respuesta!=null){
                var tabla = '';
                tabla+='<div class="table-responsive">';
                tabla+='<table class="table w3-table-all" border=1>';
                if(respuesta.length>=1){
                    tabla+='<tr class="w3-black"><td><strong>Mesa N°</strong></td></tr>';
                    for (i = 0; i < respuesta.length; i++) {
                        tabla+='<tr><td>' + respuesta[i]['idMesa'] + '</td></tr>';
                    }
                    tabla+='</table></div>';
                    tabla+='<input type="button" id="btnExport" onclick="ExportarExcel()" value="Exportar a Excel">';
                    tabla+='<input type="button" id="btnExportPDF" value="Exportar a PDF" onclick="ExportarPDF()">';
                    modulo.innerHTML = tabla;
                    $('html, body').animate({
                        scrollTop: $(document).height()
                     }, 'slow');
                }
            }
            
        },function(error){
                console.info("error", error);
        });
    }else if(queMostrar == "mesaFacturaMenorImporte"){
        var funcionAjax=$.ajax({
            url:"http://www.pardomanuel.xyz/restaurant/Mesas/TraerLaQueTuvoFacturaMenosImporte",
            type:"get"
        }).then(function(respuesta){
            if(respuesta!=null){
                var tabla = '';
                tabla+='<div class="table-responsive">';
                tabla+='<table class="table w3-table-all" border=1>';
                if(respuesta.length>=1){
                    tabla+='<tr class="w3-black"><td><strong>Mesa N°</strong></td></tr>';
                    for (i = 0; i < respuesta.length; i++) {
                        tabla+='<tr><td>' + respuesta[i]['idMesa'] + '</td></tr>';
                    }
                    tabla+='</table></div>';
                    tabla+='<input type="button" id="btnExport" onclick="ExportarExcel()" value="Exportar a Excel">';
                    tabla+='<input type="button" id="btnExportPDF" value="Exportar a PDF" onclick="ExportarPDF()">';
                    modulo.innerHTML = tabla;
                    $('html, body').animate({
                        scrollTop: $(document).height()
                     }, 'slow');
                }
            }
            
        },function(error){
                console.info("error", error);
        });
    }else if(queMostrar == "facturacionMesaEntreFechas"){
        if(document.getElementById('fechaMin').value == '' || document.getElementById('fechaMax').value == '' || document.getElementById('sector').value == ''){
            alert("Error, ingrese ambas fechas y complete el campo disponible con el id de la mesa");
        }else{
            
            data = 
            {
                "fechaMin": document.getElementById('fechaMin').value,
                "fechaMax": document.getElementById('fechaMax').value,
                "id": document.getElementById('sector').value
            }
            var funcionAjax=$.ajax({
                url:"http://www.pardomanuel.xyz/restaurant/Mesas/FacturacionDeMesasEntreFechas",
                type:"post",
                data:data
            }).then(function(respuesta){
                if(respuesta!=null){
                    if(respuesta.length == 0){
                        codigo = "<h3>No se facturo con esta mesa entre estas fechas.</h3>";
                        modulo.innerHTML = codigo;
                    }else{
                    var tabla = '';
                    tabla+='<div class="table-responsive">';
                    tabla+='<table class="table w3-table-all" border=1>';
                    if(respuesta.length>=1){
                        tabla+='<tr class="w3-black"><td><strong>Mesa N°</strong></td><td><strong>Facturo</strong></td></tr>';
                        for (i = 0; i < respuesta.length; i++) {
                            tabla+='<tr><td>' + respuesta[i]['idMesa'] + '</td>' + 
                            '<td>' + respuesta[i]['facturo'] + '</td></tr>';
                        }
                        tabla+='</table></div>';
                        tabla+='<input type="button" id="btnExport" onclick="ExportarExcel()" value="Exportar a Excel">';
                        tabla+='<input type="button" id="btnExportPDF" value="Exportar a PDF" onclick="ExportarPDF()">';
                        modulo.innerHTML = tabla;
                        $('html, body').animate({
                            scrollTop: $(document).height()
                         }, 'slow');
                    }
                    }
                }
                
            },function(error){
                    console.info("error", error);
            });
        }
    }else if(queMostrar == "mejoresComentariosMesa"){
        var funcionAjax=$.ajax({
            url:"http://www.pardomanuel.xyz/restaurant/Mesas/MejoresComentarios",
            type:"get"
        }).then(function(respuesta){
            if(respuesta!=null){
                var tabla = '';
                tabla+='<div class="table-responsive">';
                tabla+='<table class="table w3-table-all" border=1>';
                if(respuesta.length>=1){
                    tabla+='<tr class="w3-black"><td><strong>Id</strong></td><td><strong>Pedido N°</strong></td><td><strong>Puntos Cocinero</strong></td><td><strong>Puntos Mesa</strong></td><td><strong>Puntos Mozo</strong></td><td><strong>Puntos Restaurante</strong></td><td><strong>Experiencia</strong></td></tr>';
                    for (i = 0; i < respuesta.length; i++) {
                        tabla+='<tr><td>' + respuesta[i]['id'] + '</td>' +
                        '<td>' + respuesta[i]['idPedido'] + '</td>' +
                        '<td>' + respuesta[i]['puntosCocinero'] + '</td>' +
                        '<td>' + respuesta[i]['puntosMesa'] + '</td>' +
                        '<td>' + respuesta[i]['puntosMozo'] + '</td>' +
                        '<td>' + respuesta[i]['puntosRestaurante'] + '</td>' +
                        '<td>' + respuesta[i]['experiencia'] + '</td></tr>';
                    }
                    tabla+='</table></div>';
                    tabla+='<input type="button" id="btnExport" onclick="ExportarExcel()" value="Exportar a Excel">';
                    tabla+='<input type="button" id="btnExportPDF" value="Exportar a PDF" onclick="ExportarPDF()">';
                    modulo.innerHTML = tabla;
                    $('html, body').animate({
                        scrollTop: $(document).height()
                     }, 'slow');
                }
            }
            
        },function(error){
                console.info("error", error);
        });
    }else if(queMostrar == "peoresComentariosMesa"){
        var funcionAjax=$.ajax({
            url:"http://www.pardomanuel.xyz/restaurant/Mesas/PeoresComentarios",
            type:"get"
        }).then(function(respuesta){
            if(respuesta!=null){
                tabla = '';
                tabla+='<head><script src="http://code.jquery.com/jquery-1.10.2.min.js"></script></head>';
                tabla+='<div class="table-responsive">';
                tabla += '<table class="table w3-table-all" border=1 id="tblReporte">';
                if(respuesta.length>=1){
                    tabla+='<tr class="w3-black"><td><strong>Id</strong></td><td><strong>Pedido N°</strong></td><td><strong>Puntos Cocinero</strong></td><td><strong>Puntos Mesa</strong></td><td><strong>Puntos Mozo</strong></td><td><strong>Puntos Restaurante</strong></td><td><strong>Experiencia</strong></td></tr>';
                    for (i = 0; i < respuesta.length; i++) {
                        tabla+='<tr><td>' + respuesta[i]['id'] + '</td>' +
                        '<td>' + respuesta[i]['idPedido'] + '</td>' +
                        '<td>' + respuesta[i]['puntosCocinero'] + '</td>' +
                        '<td>' + respuesta[i]['puntosMesa'] + '</td>' +
                        '<td>' + respuesta[i]['puntosMozo'] + '</td>' +
                        '<td>' + respuesta[i]['puntosRestaurante'] + '</td>' +
                        '<td>' + respuesta[i]['experiencia'] + '</td></tr>';
                    }
                    urlOk = "Mesas/PeoresComentarios";
                    tabla+='</table></div>';

                    tabla+='<input type="button" id="btnExport" onclick="ExportarExcel()" value="Exportar a Excel">';
                    tabla+='<input type="button" id="btnExportPDF" value="Exportar a PDF" onclick="ExportarPDF()">';
                    modulo.innerHTML = tabla;
                    $('html, body').animate({
                        scrollTop: $(document).height()
                     }, 'slow');
                }
            }
            
        },function(error){
                console.info("error", error);
        });
    }else if(queMostrar == "todosLosPedidos"){
        var funcionAjax=$.ajax({
            url:"http://www.pardomanuel.xyz/restaurant/Pedidos/TraerTodosLosPedidos",
            type:"get"
        }).then(function(respuesta){
            if(respuesta!=null){
                var tabla = '<br>';
                tabla+='<div class="table-responsive">';
                tabla += '<table class="table w3-table-all" border=1 id="tblReporte">';
                if(respuesta.length>=1){
                    tabla+='<tr class="w3-black"><td><strong>Id Pedido</strong></td><td><strong>Mesa Nro</strong></td><td><strong>Terminó?</strong></td></tr>';
                    for (i = 0; i < respuesta.length; i++) {
                        tabla+='<tr><td>' + respuesta[i]['id'] + '</td>' +
                        '<td>' + respuesta[i]['idMesa'] + '</td>' +
                        '<td>' + respuesta[i]['termino'] + '</td></tr>';
                    }
                    tabla+='</table></div><br>';
                    modulo.innerHTML = tabla;
                    $('html, body').animate({
                        scrollTop: $(document).height()
                     }, 'slow');
                }
            }
        },function(error){
                console.info("error", error);
        });
    }else if(queMostrar == "listadoEmpleados"){
        var funcionAjax=$.ajax({
            url:"http://www.pardomanuel.xyz/restaurant/Usuarios/ListadoUsuarios",
            type:"get"
        }).then(function(respuesta){
            if(respuesta!=null){
                var tabla = '<br>';
                tabla+='<div class="table-responsive">';
                tabla += '<table class="table w3-table-all" border=1 id="tblReporte">';
                if(respuesta.length>=1){
                    tabla+='<tr class="w3-black"><td><strong>Id</strong></td><td><strong>Usuario</strong></td><td><strong>Estado</strong></td><td><strong>Cargo</strong></td><td><strong>Foto</strong></td></tr>';
                    for (i = 0; i < respuesta.length; i++) {
                        var path = "http://www.pardomanuel.xyz/restaurant/images/usuarios/"+respuesta[i]['foto'];
                        var linea = "";
                        if(respuesta[i]['estado'] == 'suspendido'){
                            linea = '<td class="w3-red">' + respuesta[i]['estado'] + '</td>';
                        }else{
                            linea = '<td>' + respuesta[i]['estado'] + '</td>';
                        }
                        tabla+='<tr><td>' + respuesta[i]['id'] + '</td>' +
                        '<td>' + respuesta[i]['usuario'] + '</td>' +
                        linea +
                        '<td>' + respuesta[i]['tipo'] + '</td>' +
                        '<td><img src='+path+' alt="img" id="imgListaEmpleados"></td></tr>';
                    }
                    tabla+='</table></div><br>';
                    tabla+='<input type="button" id="btnExport" onclick="ExportarExcel()" value="Exportar a Excel">';
                    modulo.innerHTML = tabla;
                    $('html, body').animate({
                        scrollTop: $(document).height()
                     }, 'slow');
                }
            }
            
        },function(error){
                console.info("error", error);
        });
    }else if(queMostrar == "pedidosNoEntregadosTiempoEstipulado"){
        if(document.getElementById('fechaMin').value == '' || document.getElementById('fechaMax').value == ''){
            alert("Error, ingrese ambas fechas.");
        }else{
            modulo.innerHTML = '<p align="center"><img src="images/gif/loading_spinner.gif" width="5%"></p>';
            data = 
            {
                "fechaMin": document.getElementById('fechaMin').value,
                "fechaMax": document.getElementById('fechaMax').value
            }
            var funcionAjax=$.ajax({
                url:"http://www.pardomanuel.xyz/restaurant/PedidoDetalle/TraerEntreFechas",
                type:"post",
                data:data
            }).then(function(respuesta){
                if(respuesta!=null){
                    miArray = [];
                    arrayfinal = [];
                    
                    for (var i = 0; i < respuesta.length; i++) {
                        if(respuesta[i]['horaEntrega'] != '' && respuesta[i]['horaPrometida'] != ''){
                            miArray.push(respuesta[i]);
                        }
                    }
                    for (var i = 0; i < miArray.length; i++) {
            
                        var horaPrometida = miArray[i]['horaPrometida'].substring(0,2);
                        var horaEntrega = miArray[i]['horaEntrega'].substring(0,2);
                        var minutoPrometido = miArray[i]['horaPrometida'].substring(3, 5);
                        var minutoEntrega = miArray[i]['horaEntrega'].substring(3,5);
                        horaPrometida = parseInt(horaPrometida);
                        horaEntrega = parseInt(horaEntrega);
                        minutoPrometido = parseInt(minutoPrometido);
                        minutoEntrega = parseInt(minutoEntrega);
                        if(horaEntrega>=horaPrometida){
                            if(horaEntrega == horaPrometida){
                                if(minutoEntrega > minutoPrometido){
                                    arrayfinal.push(miArray[i]);
                                }
                            }else{
                                arrayfinal.push(miArray[i]);
                            }
                        }
                    }
                        
                        var tabla = '<br>';
                        tabla+='<div class="table-responsive">';
                        tabla += '<table class="table w3-table-all" border=1 id="tblReporte">';
                        tabla+='<tr class="w3-black"><td><strong>Id Pedido</strong></td><td><strong>Sector</strong></td><td><strong>Hora Entregado</strong></td><td><strong>Hora Prometida</strong></td></tr>';
                        for (var i = 0; i < arrayfinal.length; i++) {
                        tabla+='<tr><td>' + respuesta[i]['idPedido'] + '</td>' +
                        '<td>' + respuesta[i]['sector'] + '</td>' +
                        '<td>' + respuesta[i]['horaEntrega'] + '</td>' +
                        '<td>' + respuesta[i]['horaPrometida'] + '</td></tr>';
                    }
                    tabla+='</table></div><br>';
                    tabla+='<input type="button" id="btnExport" onclick="ExportarExcel()" value="Exportar a Excel">';
                    modulo.innerHTML = tabla;
                    $('html, body').animate({
                        scrollTop: $(document).height()
                     }, 'slow');
                    
                }
            },function(error){
                    console.info("error", error);
            });
        }
    }else if(queMostrar == "listadoHIHFImporte"){
        modulo.innerHTML = '<p align="center"><img src="images/gif/loading_spinner.gif" width="5%"></p>';
        var funcionAjax=$.ajax({
                url:"http://www.pardomanuel.xyz/restaurant/PedidoDetalle/ListadoHiHfImporte",
                type:"get"
            }).then(function(respuesta){
                if(respuesta!=null){
                    var tabla = '<br>';
                    tabla+='<div class="table-responsive">';
                    tabla += '<table class="table w3-table-all" border=1 id="tblReporte">';
                    if(respuesta.length>=1){
                        tabla+='<tr class="w3-black"><td><strong>N° Pedido</strong></td><td><strong>Hora Inicial</strong></td><td><strong>Hora Final</strong></td><td><strong>Importe</strong></td></tr>';
                        for (i = 0; i < respuesta.length; i++) {
                            tabla+='<tr><td>' + respuesta[i]['idPedido'] + '</td>' +
                            '<td>' + respuesta[i]['horaInicio'] + '</td>' +
                            '<td>' + respuesta[i]['horaEntrega'] + '</td>' +
                            '<td>' + respuesta[i]['montoTotal'] + '</td></tr>';
                        }
                        tabla+='</table></div><br>';
                        tabla+='<input type="button" id="btnExport" onclick="ExportarExcel()" value="Exportar a Excel">';
                        modulo.innerHTML = tabla;
                        $('html, body').animate({
                        scrollTop: $(document).height()
                        }, 'slow');
                    }
                }
                
            },function(error){
                    console.info("error", error);
            });
    }else if(queMostrar == "ComPunEntreFechas"){
        if(document.getElementById('fechaMin').value == '' || document.getElementById('fechaMax').value == ''){
            alert("Error, ingrese ambas fechas.");
        }else{
            modulo.innerHTML = '<p align="center"><img src="images/gif/loading_spinner.gif" width="5%"></p>';
            data = 
            {
                "fechaMin": document.getElementById('fechaMin').value,
                "fechaMax": document.getElementById('fechaMax').value
            }
            var funcionAjax=$.ajax({
                url:"http://www.pardomanuel.xyz/restaurant/Encuestas/ComentariosEntreFechas",
                type:"post",
                data:data
            }).then(function(respuesta){
                if(respuesta!=null){
                    tabla = '';
                tabla+='<head><script src="http://code.jquery.com/jquery-1.10.2.min.js"></script></head>';
                tabla+='<div class="table-responsive">';
                tabla += '<table class="table w3-table-all" border=1 id="tblReporte">';
                if(respuesta.length>=1){
                    tabla+='<tr class="w3-black"><td><strong>Id</strong></td><td><strong>Pedido N°</strong></td><td><strong>Puntos Cocinero</strong></td><td><strong>Puntos Mesa</strong></td><td><strong>Puntos Mozo</strong></td><td><strong>Puntos Restaurante</strong></td><td><strong>Experiencia</strong></td></tr>';
                    for (i = 0; i < respuesta.length; i++) {
                        tabla+='<tr><td>' + respuesta[i]['id'] + '</td>' +
                        '<td>' + respuesta[i]['idPedido'] + '</td>' +
                        '<td>' + respuesta[i]['puntosCocinero'] + '</td>' +
                        '<td>' + respuesta[i]['puntosMesa'] + '</td>' +
                        '<td>' + respuesta[i]['puntosMozo'] + '</td>' +
                        '<td>' + respuesta[i]['puntosRestaurante'] + '</td>' +
                        '<td>' + respuesta[i]['experiencia'] + '</td></tr>';
                    }
                    urlOk = "Mesas/PeoresComentarios";
                    tabla+='</table></div>';

                    tabla+='<input type="button" id="btnExport" onclick="ExportarExcel()" value="Exportar a Excel">';
                    tabla+='<input type="button" id="btnExportPDF" value="Exportar a PDF" onclick="ExportarPDF()">';
                    modulo.innerHTML = tabla;
                    $('html, body').animate({
                        scrollTop: $(document).height()
                     }, 'slow');
                    }else{
                        tabla='<h3>NO HAY COMENTARIOS EN ÉSTAS FECHAS</H3>';
                        modulo.innerHTML = tabla;
                    }
                }
            },function(error){
                    console.info("error", error);
            });
        }
    }else if(queMostrar == "promedioEmpleados"){
        document.getElementById('moduloMuestroGraficoTorta').innerHTML = '';
        document.getElementById('moduloMuestroGraficoTorta').innerHTML = '<canvas id="oilChart" width="50" height=""></canvas>';
        if(document.getElementById('fechaMin').value == '' || document.getElementById('fechaMax').value == ''){
            alert("Error, tiene que ingresar ambas fechas");
        }else{
        data = {
                "fechaMin": document.getElementById('fechaMin').value,
                "fechaMax": document.getElementById('fechaMax').value
            }
        var funcionAjax=$.ajax({
            url:"http://www.pardomanuel.xyz/restaurant/Operaciones/CantidadOperacionesPorUsuario",
            type:"post",
            data: data
        }).then(function(respuesta){
            var arrayEstadistica = [];
            var arrayEmpleados = [];
            var fechaMin = document.getElementById('fechaMin').value;
            var fechaMax = document.getElementById('fechaMax').value;

            var fecha1 = moment(fechaMin);
            var fecha2 = moment(fechaMax);

            var diferencia = fecha2.diff(fecha1, 'days');
            diferencia = parseInt(diferencia);
            if(respuesta!=null){
                if(diferencia <=0){
                    alert("Error, la primer fecha tiene que ser menor que la segunda");
                }else{
                    for(var i = 0; i < respuesta.length; i++) {
                    var cantidad = parseInt(respuesta[i]['cantidad']);
                    arrayEstadistica.push(cantidad/diferencia);
                    arrayEmpleados.push(respuesta[i]['usuario']);
                }

                var oilCanvas = document.getElementById("oilChart");

                    Chart.defaults.global.defaultFontFamily = "Lato";
                    Chart.defaults.global.defaultFontSize = 18;

                    var oilData = {
                        labels: arrayEmpleados,
                        datasets: [
                            {
                                data: arrayEstadistica,
                                backgroundColor: [
                                    "#FF6384",
                                    "#63FF84",
                                    "#84FF63",
                                    "#8463FF",
                                    "#6384FF",
                                    "#d562ff",
                                    "#ff6161",
                                    "#fff461",
                                    "#c2ff61",
                                    "#61fcff"
                                ]
                            }]
                    };

                    var pieChart = new Chart(oilCanvas, {
                    type: 'pie',
                    data: oilData
                    });
                    document.getElementById('moduloMuestroGraficoTorta').style.display='block';
                    $('html, body').animate({
                        scrollTop: $(document).height()
                     }, 'slow');
                }
            }
            
        },function(error){
                console.info("error", error);
        });
        }
        
        
    }else if(queMostrar == "facturacionEntreFechas"){
        if(document.getElementById('fechaMin').value == '' || document.getElementById('fechaMax').value == ''){
            alert("Error, ingrese ambas fechas.");
        }else{
            modulo.innerHTML = '<p align="center"><img src="images/gif/loading_spinner.gif" width="5%"></p>';
            data = 
            {
                "fechaMin": document.getElementById('fechaMin').value,
                "fechaMax": document.getElementById('fechaMax').value
            }
            var funcionAjax=$.ajax({
                url:"http://www.pardomanuel.xyz/restaurant/Facturas/TraerEntreFechas",
                type:"post",
                data:data
            }).then(function(respuesta){
                if(respuesta!=null){
                    var tabla = '<br>';
                    tabla+='<div class="table-responsive">';
                    tabla += '<table class="table w3-table-all" border=1 id="tblReporte">';
                    if(respuesta.length>=1){
                        tabla+='<tr class="w3-black"><td><strong>N° Pedido</strong></td><td><strong>Monto Total</strong></td><td><strong>Fecha y Hora</strong></td></tr>';
                        for (i = 0; i < respuesta.length; i++) {
                            tabla+='<tr><td>' + respuesta[i]['idPedido'] + '</td>' +
                            '<td>' + respuesta[i]['montoTotal'] + '</td>' +
                            '<td>' + respuesta[i]['fechaHora'] + '</td></tr>';
                        }
                        tabla+='</table></div><br>';
                        tabla+='<input type="button" id="btnExport" onclick="ExportarExcel()" value="Exportar a Excel">';
                        modulo.innerHTML = tabla;
                    $('html, body').animate({
                        scrollTop: $(document).height()
                     }, 'slow');
                    }
                }
            },function(error){
                    console.info("error", error);
            });
        }
    }else if(queMostrar == "estadisticaEmpleados"){
        document.getElementById('moduloMuestroGraficoTorta').innerHTML = '';
        document.getElementById('moduloMuestroGraficoTorta').innerHTML = '<canvas id="oilChart" width="50" height=""></canvas>';
        if(document.getElementById('fechaMin').value == '' || document.getElementById('fechaMax').value == ''){
            alert("Error, tiene que ingresar ambas fechas");
        }else{
        data = {
                "fechaMin": document.getElementById('fechaMin').value,
                "fechaMax": document.getElementById('fechaMax').value
            }
        var funcionAjax=$.ajax({
            url:"http://www.pardomanuel.xyz/restaurant/Operaciones/CantidadOperacionesPorUsuario",
            type:"post",
            data: data
        }).then(function(respuesta){
            var arrayEstadistica = [];
            var arrayEmpleados = [];
            var fechaMin = document.getElementById('fechaMin').value;
            var fechaMax = document.getElementById('fechaMax').value;

            var fecha1 = moment(fechaMin);
            var fecha2 = moment(fechaMax);

            var diferencia = fecha2.diff(fecha1, 'days');
            diferencia = parseInt(diferencia);
            if(respuesta!=null){
                if(diferencia <=0){
                    alert("Error, la primer fecha tiene que ser menor que la segunda");
                }else{
                    for(var i = 0; i < respuesta.length; i++) {
                    var cantidad = parseInt(respuesta[i]['cantidad']);
                    arrayEstadistica.push(cantidad);
                    arrayEmpleados.push(respuesta[i]['usuario']);
                }

                var oilCanvas = document.getElementById("oilChart");

                    Chart.defaults.global.defaultFontFamily = "Lato";
                    Chart.defaults.global.defaultFontSize = 18;

                    var densityData = {
                    label: 'Operaciones',
                    data: arrayEstadistica,
                    backgroundColor: 'rgba(0, 99, 132, 0.6)',
                    borderWidth: 0,
                    yAxisID: "y-axis-density"
                    };

                    

                    var planetData = {
                    labels: arrayEmpleados,
                    datasets: [densityData]
                    };

                    var chartOptions = {
                    scales: {
                        xAxes: [{
                        barPercentage: 1,
                        categoryPercentage: 0.6
                        }],
                        yAxes: [{
                        id: "y-axis-density"
                        }, {
                        id: "y-axis-gravity"
                        }]
                    }
                    };

                    var barChart = new Chart(oilCanvas, {
                    type: 'bar',
                    data: planetData,
                    options: chartOptions
                });
                document.getElementById('moduloMuestroGraficoTorta').style.display='block';
                $('html, body').animate({
                        scrollTop: $(document).height()
                     }, 'slow');
                }
            }
            
        },function(error){
                console.info("error", error);
        });
        }
        
    }else if(queMostrar == "promedioMesas"){
        document.getElementById('moduloMuestroGraficoTorta').innerHTML = '';
        document.getElementById('moduloMuestroGraficoTorta').innerHTML = '<canvas id="oilChart" width="50" height=""></canvas>';
        var fecha = document.getElementById('fechaMin').value;
        if(fecha != ''){
            var mes = fecha.substr(5,2);
            parseInt(mes);
            var funcionAjax=$.ajax({
                url:"http://www.pardomanuel.xyz/restaurant/Pedidos/TraerUsoDeMesas/"+mes,
                type:"get"
            }).then(function(respuesta){
                if(respuesta!=null){
                    if(respuesta.length>1){
                        var promedioDeMesas = [];
                    var cantidadDeUsosTotal = 0;
                    var arrayNombreMesas = [];
                    for (var i = 0; i < respuesta.length; i++) {
                        var cantidad = respuesta[i]['cantidad'];
                        var nombre = "Mesa " + respuesta[i]['idMesa']+".";
                        arrayNombreMesas.push(nombre);
                        cantidad = parseInt(cantidad);
                        cantidadDeUsosTotal+=cantidad;
                    }
                    for (var i = 0; i < respuesta.length; i++) {
                        var cantidad = respuesta[i]['cantidad'];
                        cantidad = parseInt(cantidad);
                        promedioDeMesas.push(cantidad/cantidadDeUsosTotal);
                    }
                    
                    var oilCanvas = document.getElementById("oilChart");

                    Chart.defaults.global.defaultFontFamily = "Lato";
                    Chart.defaults.global.defaultFontSize = 18;

                    var oilData = {
                        labels: arrayNombreMesas,
                        datasets: [
                            {
                                data: promedioDeMesas,
                                backgroundColor: [
                                    "#FF6384",
                                    "#63FF84",
                                    "#84FF63",
                                    "#8463FF",
                                    "#6384FF",
                                    "#d562ff",
                                    "#ff6161",
                                    "#fff461",
                                    "#c2ff61",
                                    "#61fcff"
                                ]
                            }]
                    };

                    var pieChart = new Chart(oilCanvas, {
                    type: 'pie',
                    data: oilData
                    });
                    document.getElementById('moduloMuestroGraficoTorta').style.display='block';
                    $('html, body').animate({
                        scrollTop: $(document).height()
                     }, 'slow');

                    }else{
                        alert("Error, no se usaron mesas el mes seleccionado.");
                    }
                }
                    

            },function(error){
                    console.info("error", error);
            });
        }else{
            alert("Error, debe seleccionar una fecha en el campo Desde");
        }
    }else if(queMostrar == "promedioImportes"){
        var fecha = document.getElementById('fechaMin').value;
        if(fecha != ''){
            var mes = fecha.substr(5,2);
            parseInt(mes);
            var funcionAjax=$.ajax({
                url:"http://www.pardomanuel.xyz/restaurant/Facturas/TraerFacturasPorMes/"+mes,
                type:"get"
            }).then(function(respuesta){
                if(respuesta.length>=1){
                    var sumaTotal = 0;
                for (var i = 0; i < respuesta.length; i++) {
                    var valor = respuesta[i]['montoTotal'];
                    valor = parseInt(valor);
                    sumaTotal+=valor;
                }
                sumaTotal = sumaTotal/respuesta.length;
                var tabla = '<br>';
                tabla+='<div class="table-responsive">';
                tabla += '<table class="table w3-table-all" border=1 id="tblReporte">';
                tabla+='<tr class="w3-black"><td><strong>Cantidad de Facturas</strong></td><td><strong>Promedio Mensual</strong></td></tr>';
                tabla+='<tr><td>' + respuesta.length + '</td>' +
                '<td>' + sumaTotal + '</td></tr>';
                tabla+='</table></div><br>';
                tabla+='<input type="button" id="btnExport" onclick="ExportarExcel()" value="Exportar a Excel">';
                modulo.innerHTML = tabla;
                $('html, body').animate({
                    scrollTop: $(document).height()
                }, 'slow');
                }else{
                    alert("Error, no se facturo en el mes "+mes+".");
                }

            },function(error){
                    console.info("error", error);
            });
        }else{
            alert("Error, debe seleccionar una fecha en el campo Desde");
        }
    }else if(queMostrar == "estadisticaMesas"){
        document.getElementById('moduloMuestroGraficoTorta').innerHTML = '';
        document.getElementById('moduloMuestroGraficoTorta').innerHTML = '<canvas id="oilChart" width="50" height=""></canvas>';
        var fecha = document.getElementById('fechaMin').value;
        
        if(fecha != ''){
            var mes = fecha.substr(5,2);
            parseInt(mes);
            var funcionAjax=$.ajax({
                url:"http://www.pardomanuel.xyz/restaurant/Pedidos/TraerUsoDeMesas/"+mes,
                type:"get"
            }).then(function(respuesta){
                if(respuesta!=''){
                    var arrayMesasCantidad = [];
                    var arrayNombreMesas = [];
                    for(var i = 0; i < respuesta.length; i++){
                        arrayMesasCantidad.push(respuesta[i]['cantidad']);
                        var numMesa = i+1;
                        numMesa = parseInt(numMesa);
                        arrayNombreMesas.push('Mesa ' + numMesa);
                    }
                    var oilCanvas = document.getElementById("oilChart");

                    Chart.defaults.global.defaultFontFamily = "Lato";
                    Chart.defaults.global.defaultFontSize = 18;

                    var oilData = {
                        labels: arrayNombreMesas,
                        datasets: [
                            {
                                data: arrayMesasCantidad,
                                backgroundColor: [
                                    "#FF6384",
                                    "#63FF84",
                                    "#84FF63",
                                    "#8463FF",
                                    "#6384FF",
                                    "#d562ff",
                                    "#ff6161",
                                    "#fff461",
                                    "#c2ff61",
                                    "#61fcff"
                                ]
                            }]
                    };

                    var pieChart = new Chart(oilCanvas, {
                    type: 'pie',
                    data: oilData
                    });
                    document.getElementById('moduloMuestroGraficoTorta').style.display='block';
                    $('html, body').animate({
                        scrollTop: $(document).height()
                     }, 'slow');
                }else{
                    alert("No hay mesas usadas éste mes.");
                }
            },function(error){
                    console.info("error", error);
            });
            }else if(fecha == ''){
                var funcionAjax=$.ajax({
                    url:"http://www.pardomanuel.xyz/restaurant/Pedidos/TraerUsoDeMesasGlobal",
                    type:"get"
                }).then(function(respuesta){
                    if(respuesta!=''){
                    var arrayMesasCantidad = [];
                    var arrayNombreMesas = [];
                    for(var i = 0; i < respuesta.length; i++){
                        arrayMesasCantidad.push(respuesta[i]['cantidad']);
                        var numMesa = i+1;
                        numMesa = parseInt(numMesa);
                        arrayNombreMesas.push('Mesa ' + numMesa);
                    }
                    var oilCanvas = document.getElementById("oilChart");

                    Chart.defaults.global.defaultFontFamily = "Lato";
                    Chart.defaults.global.defaultFontSize = 18;

                    var oilData = {
                        labels: arrayNombreMesas,
                        datasets: [
                            {
                                data: arrayMesasCantidad,
                                backgroundColor: [
                                    "#FF6384",
                                    "#63FF84",
                                    "#84FF63",
                                    "#8463FF",
                                    "#6384FF",
                                    "#d562ff",
                                    "#ff6161",
                                    "#fff461",
                                    "#c2ff61",
                                    "#61fcff"
                                ]
                            }]
                    };

                    var pieChart = new Chart(oilCanvas, {
                    type: 'pie',
                    data: oilData
                    });
                    document.getElementById('moduloMuestroGraficoTorta').style.display='block';
                    $('html, body').animate({
                        scrollTop: $(document).height()
                     }, 'slow');
                }else{
                    alert("No hay mesas usadas globalmente aún.");
                }
                },function(error){
                        console.info("error", error);
                });
            }
        
    }
}
function opcionSelect(){
    var queMostrar = document.getElementById('selectMostrar').value;
    if(queMostrar == "estadisticaMesas"){
        //alert("ATENCIÓN: Para filtrarlo por mes, seleccione una fecha en el campo 'Desde'. Si no selecciona ninguna, mostrara el promedio global.");
        
        document.getElementById("leyendaAclaratoria").innerHTML = "<p><font style='background-color:#FF0000;' color='white'>* Para filtrarlo por mes, seleccione una fecha en el campo 'Desde'. Si no selecciona ninguna, mostrara el promedio global.</font></p>";
    }else if(queMostrar == "promedioImportes" || queMostrar == "promedioMesas"){
        document.getElementById("leyendaAclaratoria").innerHTML = "<p><font style='background-color:#FF0000;' color='white'>* Para filtrarlo por mes, seleccione una fecha en el campo 'Desde'.</font></p>";
    }else{
        document.getElementById('moduloMuestroGraficoTorta').style.display='none';
        document.getElementById("leyendaAclaratoria").innerHTML = "";
        document.getElementById("divFechaMin").style.backgroundColor = "white";
    }
}
function SuspenderActivar(id){
    var funcionAjax=$.ajax({
        url:"http://www.pardomanuel.xyz/restaurant/Usuarios/TraerUnUsuario/"+id,
        type:"get"
    }).then(function(respuesta){
        if(respuesta!=null){
            if(respuesta['tipo'] == 'socio'){
                alert("Error, no se puede suspender a un socio.");
            }else{
                var estadoOk = "";
            if(respuesta['estado'] == 'Activo'){
                estadoOk = 'suspendido';
            }else if(respuesta['estado'] == 'suspendido'){
                estadoOk = 'Activo';
            }
            data = 
            {
                "id": id,
                "estado": estadoOk
            }
            var funcionAjax=$.ajax({
                url:"http://www.pardomanuel.xyz/restaurant/Usuarios/SuspenderActivarUsuario",
                type:"put",
                data: data
            }).then(function(res){
                Mostrar();
            },function(error){
                    console.info("error", error);
            });
            }
        }
	},function(error){
			console.info("error", error);
    });
    
}
function SuspenderActivarMenuPrincipal(id){
    var funcionAjax=$.ajax({
        url:"http://www.pardomanuel.xyz/restaurant/Usuarios/TraerUnUsuario/"+id,
        type:"get"
    }).then(function(respuesta){
        if(respuesta!=null){
            if(respuesta['tipo'] == 'socio'){
                alert("Error, no se puede suspender a un socio.");
            }else{
                var estadoOk = "";
            if(respuesta['estado'] == 'Activo'){
                estadoOk = 'suspendido';
            }else if(respuesta['estado'] == 'suspendido'){
                estadoOk = 'Activo';
            }
            data = 
            {
                "id": id,
                "estado": estadoOk
            }
            var funcionAjax=$.ajax({
                url:"http://www.pardomanuel.xyz/restaurant/Usuarios/SuspenderActivarUsuario",
                type:"put",
                data: data
            }).then(function(res){
                Empleados();
            },function(error){
                    console.info("error", error);
            });
            }
        }
	},function(error){
			console.info("error", error);
    });
    
}
function Borrar(id){
    if(confirm("¿Seguro que quiere borrar el empleado?")){
        data = 
        {
        "id": id
        }
    var funcionAjax=$.ajax({
        url:"http://www.pardomanuel.xyz/restaurant/Usuarios/BorrarUsuario",
        type:"delete",
        data: data
    }).then(function(respuesta){
        if(respuesta!=null){
            alert("Empleado borrado con éxito");
            Empleados();
            $('html, body').animate({
                scrollTop: $(document).height()
             }, 'slow');
        }
	},function(error){
			console.info("error", error);
    });
    }
}


function Decodificar(token){
    objJson=
    {
        "token": token
    }
     var funcionAjax=$.ajax({
            url:"http://www.pardomanuel.xyz/restaurant/Token/Obtener",
            type:"post",
            data: objJson
        }).then(function(respuesta){
            info = respuesta['usuario'];
            console.log(respuesta);
            return info;
        },function(error){
                console.info("error", error);
        });
        
}
function Codificar(){
    objJson=
    {
        "usuario": 'mpardo',
        "pass": '1234'
    }
     var funcionAjax=$.ajax({
            url:"http://www.pardomanuel.xyz/restaurant/Token/Crear",
            type:"post",
            data: objJson
        }).then(function(respuesta){
            localStorage.setItem("otroToken",respuesta['token']);
        },function(error){
                console.info("error", error);
        });
}
function ExportarExcel(){
        window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('#moduloMuestroEnDetalle').html()));
}
function ExportarPDF(){

	var doc = new jsPDF("p", "pt", "a4");
    doc.addHTML($('#moduloMuestroEnDetalle'), 15, 15, function() {
	doc.save('div.pdf');
	});
   
}
function Empleados(){
    document.getElementById('moduloModificoEmpleado').style.display = 'none';
    document.getElementById('moduloMuestroGraficoTorta').style.display='none';
    var modulo = document.getElementById('moduloMuestroEnDetalle');
    modulo.innerHTML = '<p align="left"><img src="images/gif/loading_spinner.gif" width="5%"></p>';
    document.getElementById('moduloMuestroMesas').style.display = 'none';
    document.getElementById('moduloTomarPedidos').style.display = 'none';
    document.getElementById('modulPedidosPendientes').style.display = 'none';
    document.getElementById('agregoTiempoPedido').style.display = 'none';
    document.getElementById('moduloMostrar').style.display = "none";
    document.getElementById('moduloCambioEstadoMesas').style.display="none";
    document.getElementById('btnCambioEstadoMesas').style.display="none";
    document.getElementById('moduloMuestroEnDetalle').style.display = 'block';
    var funcionAjax=$.ajax({
            url:"http://www.pardomanuel.xyz/restaurant/Usuarios/ListadoUsuarios",
            type:"get"
        }).then(function(respuesta){
            if(respuesta!=null){
                var tabla = '<br>';
                tabla+= '<input type="button" value="Nuevo Empleado"  data-toggle="modal" data-target="#modalForm"><br><br>';/*onclick="NuevoEmpleado()"*/
                tabla+= "<div class='modal fade' id='modalForm' role='dialog'>"+
    "<div class='modal-dialog'>"+
        "<div class='modal-content'>"+
            
            "<div class='modal-header'>"+
                "<button type='button' class='close' data-dismiss='modal'>"+
                    "<span aria-hidden='true'>×</span>"+
                    "<span class='sr-only'>Close</span>"+
                "</button>"+
                "<h4 class='modal-title' id='myModalLabel'>Nuevo Contacto</h4>"+
            "</div>"+
            
            
            "<div class='modal-body'>"+
                "<p class='statusMsg'></p>"+
                "<form role='form'>"+
                    "<div class='form-group'>"+/**/
                        "<label for='nuevoUsuario'>Usuario</label><br>"+
                        "<input type='text' class='form-control' id='nuevoUsuario' name='nuevoUsuario' placeholder='Usuario'/>"+
                    "</div>"+
                    "<div class='form-group'>"+
                        "<label for='nuevoPass'>Constraseña</label><br>"+
                        "<input type='text' class='form-control' id='nuevoPass' name='nuevoPass' placeholder='Pass'/>"+
                    "</div>"+
                    "<div class='form-group'>"+
                        "<label for='nuevoTipoEmpleado'>Tipo de Empleado</label>"+
                        "<select class='form-control' name='nuevoTipoEmpleado' id='nuevoTipoEmpleado'>"+
                        "<option value='mozo'>Mozo</option>"+
                        "<option value='bartender'>Bartender</option>"+
                        "<option value='cervecero'>Cervecero</option>"+
                        "<option value='cocinero'>Cocinero</option>"+
                        "<option value='socio'>Socio</option>"+
                        "</select>"+
                    "</div>"+
                    "<div class='form-group'>"+
                    "<label for='archivoImage'>Adjuntar Foto</label><br>"+
                    "<input type='file' name='archivoImage' id='archivoImage' />"+
                    "</div>"+
                "</form>"+
            "</div>"+
            
            
            "<div class='modal-footer'>"+
                "<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>"+
                "<button type='button' class='btn btn-black submitBtn' onclick='CargarNuevoEmpleado()'>Cargar Nuevo</button>"+
            "</div>"+
        "</div>"+
    "</div>"+
"</div>";
                tabla+='<div class="table-responsive">';
                tabla += '<table class="table w3-table-all" border=1 id="tblReporte">';
                if(respuesta.length>=1){
                    tabla+='<tr class="w3-black"><td><strong>Id</strong></td><td><strong>Usuario</strong></td><td><strong>Estado</strong></td><td><strong>Cargo</strong></td><td><strong>Foto</strong></td><td><strong>Suspender</strong></td><td><strong>Modificar</strong></td><td><strong>Borrar</strong></td></tr>';
                    for (i = 0; i < respuesta.length; i++) {
                        var path = "http://www.pardomanuel.xyz/restaurant/images/usuarios/"+respuesta[i]['foto'];
                        var linea = "";
                        if(respuesta[i]['estado'] == 'suspendido'){
                            linea = '<td class="w3-red">' + respuesta[i]['estado'] + '</td>';
                        }else{
                            linea = '<td>' + respuesta[i]['estado'] + '</td>';
                        }
                        tabla+='<tr><td>' + respuesta[i]['id'] + '</td>' +
                        '<td>' + respuesta[i]['usuario'] + '</td>' +
                        linea +
                        '<td>' + respuesta[i]['tipo'] + '</td>' +
                        '<td><img src='+path+' alt="img" id="imgListaEmpleados"></td>' +
                        '<td><input type="button" value="Suspender/Activar" onclick="SuspenderActivarMenuPrincipal('+respuesta[i]['id']+')"></td>' +
                        '<td><input type="button" value="Modificar" onclick="FrmModificarEmpleado('+respuesta[i]['id']+')"></td>'+
                        '<td><input type="button" value="Borrar" onclick="Borrar('+respuesta[i]['id']+')"></td></tr>';
                    }
                    tabla+='</table></div><br>';
                    modulo.innerHTML = tabla;
                    $('html, body').animate({
                        scrollTop: $(document).height()
                     }, 'slow');
                }
            }
            
        },function(error){
                console.info("error", error);
        });
}
function FrmModificarEmpleado(id){
    var funcionAjax=$.ajax({
        url:"http://www.pardomanuel.xyz/restaurant/Usuarios/TraerUnUsuario/"+id,
        type:"get"
    }).then(function(respuesta){
        foto = "images/usuarios/"+respuesta['foto'];
        var modulo = document.getElementById('moduloModificoEmpleado');
        modulo.innerHTML = '';
        document.getElementById('moduloModificoEmpleado').style.display = 'block';
        var codigo = "" +
        "<div class='form-row align-items-center' id='login'>"+
        "<form enctype='multipart/form-data' action=''>" +  
        "<div class='imgcontainer'>" +
        "<img src='"+foto+"' alt='Avatar' class='avatar'>" +
        "</div>" +
        "<div class='container' align='center'>"+
        "<label for='UnUsuario'><b>Usuario</b></label><br>"+
        "<input type='text' id='UnUsuario' value='"+respuesta['usuario']+"' name='UnUsuario' required><br>"+
        "<label for='UnPass'><b>Password</b></label><br>"+
        "<input type='text' id='UnPass' value='"+respuesta['pass']+"' name='UnPass' required><br><br>"+
        "<label for='UnselectTipoEmpleado'><b>Tipo Empleado</b></label><br>"+
        "<select name='UnselectTipoEmpleado' id='UnselectTipoEmpleado'>"+
        "<option value='mozo'>Mozo</option>"+
        "<option value='bartender'>Bartender</option>"+
        "<option value='cervecero'>Cervecero</option>"+
        "<option value='cocinero'>Cocinero</option>"+
        "<option value='socio'>Socio</option>"+
        "</select><br><br>"+
        "<input type='file' name='UnarchivoImage' id='UnarchivoImage' /><br>" +
        "<input type='button' class='button button1' id='logear' value='Modificar' onclick='ModificarEmpleado("+respuesta['id']+")'><br>"+
        "<br><div id='error'></div>"+
        "</div>"+
        "</form>"+
        "</div>";
        modulo.innerHTML = codigo;
        $('html, body').animate({
            scrollTop: $(document).height()
         }, 'slow');
    },function(error){
            console.info("error", error);
    });
}
function ModificarEmpleado(id){
    
    var funcionAjax=$.ajax({
        url:"http://www.pardomanuel.xyz/restaurant/Usuarios/TraerUnUsuario/"+id,
        type:"get"
    }).then(function(respuesta){
        if(document.getElementById("UnUsuario").value == '' || document.getElementById("UnPass").value == ''){
            alert("Error, complete los campos.")
        }else{
        var usuario = document.getElementById("UnUsuario").value;
        var pass = document.getElementById("UnPass").value;
        var tipo = document.getElementById("UnselectTipoEmpleado").value;
        var inputFileImage = document.getElementById("UnarchivoImage");
        var fotoAnterior = respuesta['foto'];
        var foto = inputFileImage.files[0];
        
        if(foto == null){
            data =
            {
            "id": id,
            "usuario": usuario,
            "pass": pass,
            "tipo": tipo
        }
        document.getElementById('moduloModificoEmpleado').innerHTML = '<p align="center"><img src="images/gif/loading_spinner.gif" width="5%"></p>'
                var funcionAjax=$.ajax({
                    url:"http://www.pardomanuel.xyz/restaurant/Usuarios/ModificarUsuarioSinFoto",
                    type:"put",
                    data: data
                    }).then(function(respuesta){
                        document.getElementById('moduloModificoEmpleado').style.display = 'none';
                        alert("Empleado modificado con éxito.");
                        Empleados();
                    },function(error){
                        console.info("error", error);
                });
        }else{
        var datosDelForm=new FormData();
        datosDelForm.append("foto",foto);
        datosDelForm.append("usuario",usuario);
        datosDelForm.append("pass",pass);
        datosDelForm.append("tipo",tipo);
        datosDelForm.append("id",id);
        datosDelForm.append("fotoAnterior",fotoAnterior);
        document.getElementById('moduloModificoEmpleado').innerHTML = '<p align="center"><img src="images/gif/loading_spinner.gif" width="5%"></p>'
        var funcionAjax=$.ajax({
            url:'http://www.pardomanuel.xyz/restaurant/Usuarios/ModificarUsuario',
            type:'post',
            contentType:false,
            data:datosDelForm,
            processData:false,
            cache:false
            }).then(function(respuesta){
            if(respuesta != null){
                document.getElementById('moduloModificoEmpleado').style.display = 'none';
                Empleados();
                alert("Empleado modificado con éxito.");
            }
        },function(error){
                console.info("error", error);
        });
        }
    }
    },function(error){
            console.info("error", error);
    });
    }
    
function NuevoEmpleado(){
    document.getElementById('moduloMuestroMesas').style.display = 'none';
    document.getElementById('moduloTomarPedidos').style.display = 'none';
    document.getElementById('modulPedidosPendientes').style.display = 'none';
    document.getElementById('agregoTiempoPedido').style.display = 'none';
    document.getElementById('moduloMostrar').style.display = "none";
    document.getElementById('moduloCambioEstadoMesas').style.display="none";
    document.getElementById('btnCambioEstadoMesas').style.display="none";
    var modulo = document.getElementById('moduloMuestroEnDetalle');
    modulo.innerHTML = '';
    document.getElementById('moduloMuestroEnDetalle').style.display = 'block';
/*
    var codigo = "<input type='button' value='Volver' onclick='Empleados()'>" +
    "<div class='form-row align-items-center' id='login'>"+
    "<form enctype='multipart/form-data' action=''>" +  
    "<div class='imgcontainer'>" +
    "<img src='/images/img_avatar2.png' alt='Avatar' class='avatar'>" +
    "</div>" +
    "<div class='container' align='center'>"+
    "<label for='nUsuario'><b>Usuario</b></label><br>"+
    "<input placeholder='Usuario' type='text' id='nUsuario' name='nUsuario' required><br>"+
    "<label for='nPass'><b>Password</b></label><br>"+
    "<input placeholder='pass' type='text' id='nPass' name='nPass' required><br><br>"+
    "<label for='selectTipoEmpleado'><b>Tipo Empleado</b></label><br>"+
    "<select name='selectTipoEmpleado' id='selectTipoEmpleado'>"+
    "<option value='mozo'>Mozo</option>"+
    "<option value='bartender'>Bartender</option>"+
    "<option value='cervecero'>Cervecero</option>"+
    "<option value='cocinero'>Cocinero</option>"+
    "<option value='socio'>Socio</option>"+
    "</select><br><br>"+
    "<input type='file' name='archivoImage' id='archivoImage' /><br>" +
    "<input type='button' class='button button1' id='logear' value='Cargar' onclick='CargarNuevoEmpleado()'><br>"+
    "<br><div id='error'></div>"+
    "</div>"+
    "</form>"+
    "</div>";*/
    var codigo = "<button class='btn btn-success btn-lg' data-toggle='modal' data-target='#modalForm'>" +
    "Open Contact Form"+
"</button>"+
"<div class='modal fade' id='modalForm' role='dialog'>"+
    "<div class='modal-dialog'>"+
        "<div class='modal-content'>"+
            
            "<div class='modal-header'>"+
                "<button type='button' class='close' data-dismiss='modal'>"+
                    "<span aria-hidden='true'>×</span>"+
                    "<span class='sr-only'>Close</span>"+
                "</button>"+
                "<h4 class='modal-title' id='myModalLabel'>Contact Form</h4>"+
            "</div>"+
            
            
            "<div class='modal-body'>"+
                "<p class='statusMsg'></p>"+
                "<form role='form'>"+
                    "<div class='form-group'>"+
                        "<label for='inputName'>Name</label>"+
                        "<input type='text' class='form-control' id='inputName' placeholder='Enter your name'/>"+
                    "</div>"+
                    "<div class='form-group'>"+
                        "<label for='inputEmail'>Email</label>"+
                        "<input type='email' class='form-control' id='inputEmail' placeholder='Enter your email'/>"+
                    "</div>"+
                    "<div class='form-group'>"+
                        "<label for='inputMessage'>Message</label>"+
                        "<textarea class='form-control' id='inputMessage' placeholder='Enter your message'></textarea>"+
                    "</div>"+
                "</form>"+
            "</div>"+
            
            
            "<div class='modal-footer'>"+
                "<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>"+
                "<button type='button' class='btn btn-primary submitBtn' onclick='submitContactForm()'>SUBMIT</button>"+
            "</div>"+
        "</div>"+
    "</div>"+
"</div>";

    modulo.innerHTML = codigo;
    /*
    $('html, body').animate({
        scrollTop: $(document).height()
     }, 'slow');*/
}


function CancelarPedido(id){
    if(confirm("¿Seguro que quiere cancelar el pedido?")){
        objJson=
            {
                "id": id
            }
            var funcionAjax=$.ajax({
                url:"http://www.pardomanuel.xyz/restaurant/Pedidos/CancelarPedido",
                type:"put",
                data: objJson
            }).then(function(respuesta){
                if(respuesta['resultado'] == true){
                    alert("Pedido cancelado con éxito.");
                    botonPedidosPendientes();
                }
            },function(error){
                    console.log(error);
            });
    }
}

function MostrarToken(){
    var miToken = localStorage.getItem("mitoken");

    objJson=
    {
        "token": miToken
    }
     var funcionAjax=$.ajax({
            url:"http://www.pardomanuel.xyz/restaurant/Token/Obtener",
            type:"post",
            data: objJson
        }).then(function(respuesta){
            console.log(respuesta);
        },function(error){
                console.info("error", error);
    });
}