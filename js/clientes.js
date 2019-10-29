onload = function(){
    var moduloMostrar = document.getElementById('moduloMuestroDatosAlCliente');
    var moduloFrm = document.getElementById('moduloFrmClientes');
    moduloMostrar.style.display = 'none';
    moduloFrm.style.display = 'block';
    document.getElementById('moduloEncuesta').style.display = 'none';
}
function VerEstadoPedido(){
    var moduloMostrar = document.getElementById('moduloMuestroDatosAlCliente');
    var moduloFrm = document.getElementById('moduloFrmClientes');
    var codigoMesa = document.getElementById('codMesa').value;
    var codigoPedido = document.getElementById('codPedido').value;
    
    objJson=
    {
        "codigoMesa": codigoMesa,
        "codigoPedido": codigoPedido
    }
    var funcionAjax=$.ajax({
        url:"http://www.pardomanuel.xyz/restaurant/Clientes/EstadoPedido",
		type:"post",
		data:objJson,
    }).then(function(respuesta){
        if(respuesta.length == 0){
            alert("ERROR");
        }else if(respuesta[0]['id'] == codigoPedido && respuesta[0]['codigo'] == codigoMesa){
            moduloFrm.style.display = 'none';
            moduloMostrar.style.display = 'block';
            var codigo = '';
            codigo+='<div class="container"><div class="row"><div class="col-md-12">';
            if(respuesta[0]['termino'] == 'no'){
                var i;
                codigo += "<h2>Bienvenido " + respuesta[0]['nombre'] + "<br>";
                codigo += "<h3>Pedido Nro: " + respuesta[0]['id'] + "<br>";
                codigo+='<div class="table-responsive">';
                codigo += '<table class="table w3-table-all" border=1 id="tblReporte">';
                codigo+= '<tr class="w3-black"><td><strong>Producto</strong></td>'+
                    '<td><strong>Estado</strong></td> <td><strong>Se empezo a Preparar</strong></td> <td><strong>Hora a Entregar</strong></td>' +
                    '<td><strong>Hora Entregado</strong></td></tr>';
                for(var i=0; i < respuesta.length; i++){ 
                    codigo+='<tr><td>' + respuesta[i]['descripcion'] + '</td>'+
                        '<td>' + respuesta[i]['estado'] + '</td>' + 
                        '<td>' + respuesta[i]['horaInicio'] + '</td>' + 
                        '<td>' + respuesta[i]['horaPrometida'] + '</td>' +
                        '<td>' + respuesta[i]['horaEntrega'] + '</td></tr>';
                }
                codigo+= '</table></div>';
                codigo+='<br><input type="button" id="btnVolver" value="Volver" onclick="Volver()"></div></div></div>';
                moduloMostrar.innerHTML = codigo;
                console.log(respuesta);
            }else if(respuesta[0]['termino'] == 'si'){
                codigo='<div class="container"><div class="row"><div class="col-md-12">';
                codigo+= '<form action="">'+
                '<h3>BIENVENIDO</h3><br>'+
                '<h4>Ingrese Valores del 1 al 10:</h4><br>'+
                '<label for="puntosMesa">MESA:</label><br>'+
                '<input class="input-number" placeholder="puntos mesa" type="text" id="puntosMesa" pattern="[0-9]{1,2}" title="Uno, o dos dígitos numéricos." onKeyPress="return soloNumeros(event)" maxlength="2"><br>'+
                '<label for="puntosRestaurante">RESTAURANTE:</label><br>'+
                '<input class="input-number" placeholder="puntos restaurante" type="text" id="puntosRestaurante" pattern="[0-9]{1,2}" title="Uno, o dos dígitos numéricos." onKeyPress="return soloNumeros(event)" maxlength="2"><br>'+
                '<label for="puntosMozo">MOZO:</label><br>'+
                '<input class="input-number" placeholder="puntos mozo" type="text" id="puntosMozo" pattern="[0-9]{1,2}" title="Uno, o dos dígitos numéricos." onKeyPress="return soloNumeros(event)" maxlength="2"><br>'+
                '<label for="puntosCocinero">COCINERO:</label><br>'+
                '<input class="input-number" placeholder="puntos cocinero" type="text" id="puntosCocinero" pattern="[0-9]{1,2}" title="Uno, o dos dígitos numéricos." onKeyPress="return soloNumeros(event)" maxlength="2"><br>'+
                '<label for="experiencia">Cuentenos brevemente su experiencia por favor:</label><br>'+
                '<textarea id="experiencia" rows="10" cols="40" maxlength="60">Escribe aquí tus comentarios</textarea><br>'+
                '<br>'+
                '<input type="button" id="btnEncuesta" value="Subir Encuesta" onclick="SubirEncuesta('+respuesta[0]['id']+')">  '+
                '<br><div id="error"></div>'+
                '</form>';
                codigo+='</div></div></div>';
                moduloMostrar.innerHTML = codigo;
            }
        }else if(respuesta[0]['id'] == codigoPedido || respuesta[0]['codigo'] == codigoMesa){
             alert("ERROR, NO COINCIDE EL CODIGO DEL PEDIDO CON EL DE LA MESA.");
        }
	},function(error){

			console.info("error", error);
            

    });
}

function Volver(){
    window.location="/clientes.html";
}
function encuesta(){
    document.getElementById('moduloMuestroDatosAlCliente').style.display = 'none';
    var moduloMostrar = document.getElementById('moduloEncuesta');
    moduloMostrar.style.display = 'block';
}
function SubirEncuesta(idPedido){
    var puntosMesa = document.getElementById('puntosMesa').value;
    puntosMesa = parseInt(puntosMesa);
    var puntosRestaurante = document.getElementById('puntosRestaurante').value;
    puntosRestaurante = parseInt(puntosRestaurante);
    var puntosMozo = document.getElementById('puntosMozo').value;
    puntosMozo = parseInt(puntosMozo);
    var puntosCocinero = document.getElementById('puntosCocinero').value;
    puntosCocinero = parseInt(puntosCocinero);
    var experiencia = document.getElementById('experiencia').value;
    if(document.getElementById('puntosMesa').value == '' || document.getElementById('puntosRestaurante').value == '' || document.getElementById('puntosMozo').value == '' || document.getElementById('puntosCocinero').value == '' || document.getElementById('experiencia').value == ''){
        alert("Error, complete por favor todos los campos.")
    }else if(puntosMesa > 10 || puntosRestaurante > 10 || puntosMozo > 10 || puntosCocinero > 10){
        alert("Error, ingrese valores del 1 al 10.");
    }else{
        
        
        objJson=
        {
            "idPedido": idPedido,
            "puntosMesa": puntosMesa,
            "puntosRestaurante": puntosRestaurante,
            "puntosMozo": puntosMozo,
            "puntosCocinero": puntosCocinero,
            "experiencia": experiencia,
        }
        var funcionAjax=$.ajax({
            url:"http://www.pardomanuel.xyz/restaurant/Encuestas/IngresarEncuesta",
            type:"post",
            data:objJson,
        }).then(function(respuesta){ 
                alert("Encuesta completada con exito, muchas gracias. ");
                window.location="clientes.html";
        },function(error){

                console.info("error", error);
                

        });
        }
}