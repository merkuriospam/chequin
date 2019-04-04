var push;
//var disco;
var lugares = [];
var visitas = [];
var tokenFCM = null;
var soyCordova = false;
var usuario = null;
var disco = window.localStorage;
var CONTENEDOR = "#main_wrapper";

var app = {
    estado: {
        "lista" : false,
        "update_historial" : false,
    },
    init: function() {
        /*if (APP_DEBUG)  {
            app.onDeviceReady();
        } else {
            document.addEventListener('deviceready', app.onDeviceReady.bind(this), false);
        }*/
        document.addEventListener('deviceready', app.onDeviceReady.bind(this), false);
        //app.onDeviceReady();
    },
    onDeviceReady: function() {
        if (typeof(cordova) == "object") soyCordova = true;
        app.receivedEvent('deviceready');
        //window.alert = navigator.notification.alert;
        //disco = window.localStorage;
        app.router_init();
    },
    router_init: function() {
        enrutador = new Enrutador();
        Backbone.history.start();
        url_actual = Backbone.history.getFragment();
        enrutador.navigate(url_actual, {trigger: true});
    },
    receivedEvent: function(id) {
        debug('Received Event: ' + id);
    },
    verificarToken: function() {
        if (token == null) {
            if (disco.getItem("token") != null) {
                token = disco.getItem("token");
                usuario = JSON.parse(disco.getItem("usuario"));
            } else {
                return false;
            }
        }
       return true;
    },
    borrarSesion: function() {
        token = (function () { return; })();
        //$.removeCookie('token');
        disco.removeItem("token");
        //disco.removeItem("visitas");
        disco.clear();
        lugares = [];
        visitas = [];
        usuario = null;
        app.toggleLogueo();
    },
    salir: function() {
        app.borrarSesion();
        if (navigator.app) {
           navigator.app.exitApp();
        }
        else if (navigator.device) {
            navigator.device.exitApp();
        }
        app.toggleLogueo();
    },
    toggleLogueo: function() {
        if (app.verificarToken) {
            app.buscarLugares();
            $('#consola').html('Conectado');
            $('#nav-sesion-togler').html('<i class="ion-log-out"></i> Salir');
            $("#nav-sesion-togler").attr("onclick","app.salir()");
            //$('#bienvenida').html(usuario.name);
        } else {
            $('#consola').html('Desconectado');
            $('#nav-sesion-togler').html('<i class="ion-log-in"></i> Ingresar');
            $("#nav-sesion-togler").attr("onclick","app.fbLogin()");
            $('#bienvenida').html('Bienvenide');
        }
    },
    fbLogin: function() {
        debug('Ini fbLogin');
        openFB.login(
            function(response) {
                if(response.status === 'connected') {
                    //alert('Facebook login succeeded, got access token: ' + response.authResponse.accessToken);
                    console.log(response);
                    app.fbGetInfo();
                } else {
                    alert('Facebook login failed: ' + response.error);
                }
            }, {scope: 'email'});
    }, 
    fbGetInfo: function() {
        debug('Ini fbGetInfo');
        openFB.api({
            path: '/me',
            params: { fields: 'name,email' },
            success: function(data) {
                console.log(JSON.stringify(data));
                app.fbIdentificate(data);
                //document.getElementById("userName").innerHTML = data.name + ' - ' + data.email;
                //document.getElementById("userPic").src = 'http://graph.facebook.com/' + data.id + '/picture?type=small';
            },
            error: app.fbErrorHandler});
    },  
    fbReadPermissions: function() {
        openFB.api({
            method: 'GET',
            path: '/me/permissions',
            success: function(result) {
                alert(JSON.stringify(result.data));
            },
            error: app.fbErrorHandler
        });
    },
    fbRevoke: function() {
        openFB.revokePermissions(
                function() {
                    alert('Permissions revoked');
                },
                app.fbErrorHandler);
    },
    fbLogout: function() {
        openFB.logout(
                function() {
                    alert('Logout successful');
                },
                app.fbErrorHandler);
    },
    fbErrorHandler: function(error) {
        alert(error.message);
    },
    fbIdentificate: function(data) {
        console.log('fbIdentificate');
        var parametros = {
            "email" : data.email,
            "password" : data.id,
            "name" : data.name,
            "fb" : true
        };
        $.ajax({
                data:  parametros, //datos que se envian a traves de ajax
                url:   BASE_URL+'/externos/registrate', //archivo que recibe la peticion
                type:  'post', //método de envio
                success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve
                    $('#slide-out').sideNav('hide');
                    token = response.token;
                    //$.cookie("token", token);
                    disco.setItem("token", token);
                    app.toggleLogueo();
                    app.fcm_getToken();
                    app.usuario_datos();
                    debug('fbIdentificate success');
                },
                error: function(e) {
                    console.log(e.statusText);
                }
        });
        //document.getElementById("userName").innerHTML = data.name + ' - ' + data.email;
        //document.getElementById("avatar-logo").src = 'http://graph.facebook.com/' + data.id + '/picture?type=small';
    },
    usuario_datos: function() {
        debug('usuario_datos');
        var parametros = {
            "token" : token,
        };
        $.ajax({
            data:  parametros,
            url:   BASE_URL+'/externos/cuenta',
            type:  'post',
            success:  function (response) {
                usuario = response;
                disco.setItem("usuario", JSON.stringify(usuario));
            },
            error: function(e) {
                debug(e.statusText);
            }
        });
    },
    playAudio: function(url) {
        var mp3URL = app.getMediaURL(url);
        var mymedia = new Media(mp3URL, null, app.mediaError);
        mymedia.play();
    },
    getMediaURL: function(s) {
        if(device.platform.toLowerCase() === "android") return "/android_asset/www/" + s;
        return s;
    },
    mediaError: function(e) {
        alert('Media Error');
        alert(JSON.stringify(e));
    },
    buscarLugares: function() {
        var parametros = {
            "token" : token
        };
        $.ajax({
                data:  parametros,
                url:   BASE_URL+'/externos/lugares',
                type:  'post',
                success:  function (response) {
                    lugares = response.lugares;
                    disco.setItem("lugares", JSON.stringify(lugares));
                    listaLugares = "";
                    $.each(lugares, function(index,e) {
                        listaLugares = listaLugares 
                        //+ '<li class="list-group-item active" data-toggle="collapse" data-target="#cardLugar">'
                        //+ '<span>'+e.nombre+'</span>'
                        /*+ '<span onclick="app.compartir(\''+e.slug+'\')" style="float:right">Compartir</span>'*/
                        /*+ '<span style="float:right">Editar</span>'*/
                        //+ '</li>';
                        + '<div class="row rlugares">'
                        + '<div class="col s10">'
                        + '<i class="ion-android-notifications"></i>  <span onclick="app.timbre_editar('+e.id+')">'+e.nombre+'</span>'
                        + '<br><i class="ion-link"></i> <span>'+BASE_URL+'/de/'+lugares[index].slug+'</span>'
                        + '</div>'
                        + '<div class="col s2 dv_share" onclick="app.compartir(\''+lugares[index].slug+'\')"><i class="ion-android-share-alt"></i>'
                        + '</div>'
                        + '</div>';

                    });
                    listaLugares += "";
                    $("#div_lista_lugares").html(listaLugares);
                    /*linkLugar = '<h3>Dirección de tu timbre</h3>'
                    linkLugar += '<span>'+BASE_URL+'/de/'+lugares[0].slug+'</span>';
                    linkLugar += '<p><button onclick="app.compartir(\''+lugares[0].slug+'\')" class="btn btn-secondary w-100">Compartir</button></p>';
                    $("#link_timbre").html(linkLugar);*/
                    //app.reiniciar_plugins();
                    $('.tmodal').leanModal();

                },
                error: function(e) {
                    console.log(e.statusText);
                    app.borrarSesion();
                }
        });        
    },
    compartir: function(slug) {
        slug = typeof slug !== 'undefined' ? slug : null;
        if (slug==null) {
            var item = _.find(lugares, { id: parseInt($('#timbre_id').val()) });
            var slug = item.slug;            
        }
        var share_link = BASE_URL+'/de/'+slug;
        var options = {
          message: 'Cuando llegues anunciate visitando...',
          subject: slug,
          url: share_link,
        };
        debug(slug);
        window.plugins.socialsharing.shareWithOptions(options);
    },
    notificar: function(mensaje) {
        //alert(mensaje);
        if (soyCordova) {
            navigator.notification.alert(mensaje);
        } else {
            alert(mensaje);
        }
        console.log(mensaje);
    },
    /*fcm_getToken: function() {
        window.FirebasePlugin.getToken(function(token) {
            tokenFCM = token;
            app.fcm_guardarToken();
            console.log(token);
        }, function(error) {
            alertar(error);
        });
        window.FirebasePlugin.onTokenRefresh(function(token) {
            tokenFCM = token;
            app.fcm_guardarToken();
            console.log(token);
        }, function(error) {
            alertar(error);
        });
        window.FirebasePlugin.onNotificationOpen(function(notification) {
            console.log(notification);
            //app.playAudio('media/buzzer-01.mp3');
            var sello = new Date().toLocaleString();
            var txt = $("#historial");
            txt.val( txt.val() + "Visita de " + notification.data + " - " + sello + "\n");
        }, function(error) {
            alertar(error);
        });
    },*/
    fcm_getToken: function() {
        debug('fcm_getToken');
        var push = PushNotification.init({
          android: {
              sound: 'true',
              vibrate: 'true'
          },
          ios: {
              alert: 'true',
              badge: 'true',
              sound: 'true'
          }
        });
        push.on('registration', function (data) {
            tokenFCM = data.registrationId;
            app.fcm_guardarToken();
        });
        push.on('notification', function (data) {
            debug(data);
            app.playAudio('media/dingdong.mp3');
            //app.historial_update(data.message);

            var ref = data.additionalData.referencia;
            app.historial_update();
        });
        push.on('error', (e) => {
            alertar(e.message);
        });
    },
    fcm_guardarToken: function() {
        var parametros = {
            "token" : token,
            "fcm_token" : tokenFCM
        };
        $.ajax({
                data:  parametros, //datos que se envian a traves de ajax
                url:   BASE_URL+'/externos/fcm', //archivo que recibe la peticion
                type:  'post', //método de envio
                success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve
                    console.log(response)
                },
                error: function(e) {
                    console.log(e.statusText);
                }
        });
    },
    historial_update: function() {
        /*var sello = new Date().toLocaleString();
        visitas = (disco.getItem("visitas") != null) ? JSON.parse(disco.getItem("visitas")) : [];
        if (visitas.length == MAX_HIST) visitas.pop();
        var visita = { "mensaje" : mensaje, "fecha" : sello };
        visitas.unshift(visita);
        disco.setItem("visitas", JSON.stringify(visitas));

        if(app.estado.lista == true) {
            app.historial_render();
        } else {
            app.estado.update_historial = true;
        }
        */
        var parametros = {
            "token" : token
        };
        $.ajax({
                data:  parametros, //datos que se envian a traves de ajax
                url:   BASE_URL+'/externos/historial', //archivo que recibe la peticion
                type:  'post', //método de envio
                success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve
                    debug(response)
                    visitas = response.visitas;
                    app.historial_render();
                },
                error: function(e) {
                    debug(e.statusText);
                }
        });
    },
    historial_render: function() {
        app.estado.update_historial = false;
        $('.timeline').empty();
        $.each(visitas, function( key, visita ) {
            var cad = '';
            cad += '<div class="media-top-object item_visita">';
            cad += '<div class="dot primary-color"></div>';
            cad += '<span class="grey-text">' + moment(visita.created_at, 'DD-MM-YYYY HH:mm:ss').format('HH:mm') + ' hs.</span>';
            //cad += '<span class="grey-text">' + visita.fecha + '</span>';
            cad += '<div class="media-body">';
            cad += '  <span><strong>' + visita.texto + '</strong></span>';
            cad += '  <input class="npt_timeline" id="npt_'+visita.referencia+'" name="respuesta" value="Voy" />';
            cad += '  <i onclick="app.responder(\''+visita.referencia+'\')" class="ion-android-send i_send"></i>';
            cad += '</div>';
            cad += '</div>';
            $('.timeline').append(cad);
        });
    },
    reiniciar_plugins: function() {
        // Modal
        //$('.modal-trigger').leanModal();
    },
    limpiar: function(destino) {
        destino = typeof destino !== 'undefined' ? destino : CONTENEDOR;
        $( destino ).empty();
    },
    inicio_render: function() {
        app.limpiar();
        var tmpl_data = {};
        var plantilla = _.template($('#inicio').html());
        $(CONTENEDOR).append(plantilla(tmpl_data));

        openFB.init({appId: FB_APP_ID});
        if (app.verificarToken()) {
             app.toggleLogueo();          
             if (ENTORNO=='server') app.fcm_getToken();
             if (ENTORNO!='server') app.usuario_datos();
        }
        app.estado.lista = true;
        //visitas = (disco.getItem("visitas") != null) ? JSON.parse(disco.getItem("visitas")) : [];
        //app.historial_render();
        app.historial_update();

        $('#bt_timbre_nuevo').on('click', function () { app.timbre_editar(null); debug('aprete'); });

    },
    timbre_editar: function(id) {
        var item = _.find(lugares, { id: id });
        $("#modalTimbre").openModal({
            ready: function() {
                debug("Modal Ready");
            },            
        });
        if (item) {
            $('#timbre_nombre').val(item.nombre);
            $("label[for='timbre_nombre']").toggleClass( "active", true )
            var iestado = (parseInt(item.estado)==1) ? true : false ;
            $('#timbre_estado').prop('checked', iestado);
            $('#timbre_id').val(item.id);
        } else {
            $('#timbre_nombre').val('');
            $("label[for='timbre_nombre']").toggleClass( "active", false );
            //$('#timbre_estado').is(':checked');
            $('#timbre_estado').prop('checked', false);
            $('#timbre_id').val('');
        }
        //$("#modalTimbre").closeModal();
        //var ckeckpoints = _.where(ruta_actual.posiciones, {tipo: "checkpoint"});
    },
    guardarTimbre: function() {
        $('#modalTimbre').closeModal();
        if (app.verificarToken() == true) {
            /*var parametros = {
                "token" : token,
                "nombre" : $('#nombre').val()
            };*/
            var parametros = new FormData();
            parametros.append('token', token);
            parametros.append('nombre', $('#timbre_nombre').val());
            var testado = ($('#timbre_estado').is(':checked')) ? 1 : 0 ;
            parametros.append('estado', testado);
            parametros.append('id', $('#timbre_id').val());
            //parametros.append('imagen', $('#imagen').files[0]);
            $.ajax({
                    data:  parametros,
                    url:   BASE_URL+'/externos/timbre_update',
                    type:  'post',
                    processData: false,
                    contentType: false,
                    success:  function (response) {
                        if (_.has(response, "error")) {
                            alertar('Ese timbre ya existe. Probá con otro nombre.')
                        } else {
                            alertar('Se grabaron los cambios');
                            app.buscarLugares();                            
                        }
                    },
                    error: function(e) {
                        alertar(e.statusText);
                        console.log(e.statusText);
                        //app.ingresar();
                    }
            });   
        } else {
            alertar('La sesión expiro. Ingrese nuevamente');
        }
    },
    eliminarTimbre: function() {
        $('#modalTimbre').closeModal();
        if (app.verificarToken() == true) {
            var parametros = new FormData();
            parametros.append('token', token);
            parametros.append('id', $('#timbre_id').val());
            $.ajax({
                    data:  parametros,
                    url:   BASE_URL+'/externos/timbre_delete',
                    type:  'post',
                    processData: false,
                    contentType: false,
                    success:  function (response) {
                        if (_.has(response, "error")) {
                            alertar('Ocurrió un error')
                        } else {
                            alertar('Se grabaron los cambios');
                            app.buscarLugares();                            
                        }
                    },
                    error: function(e) {
                        alertar(e.statusText);
                        console.log(e.statusText);
                        //app.ingresar();
                    }
            });   
        } else {
            alertar('La sesión expiro. Ingrese nuevamente');
        }
    }, 
    responder: function(ref) {
        debug(ref);
        var rta = document.getElementById("npt_"+ref).value;
        debug(rta);
        var parametros = {
            "token" : token,
            "referencia" : ref,
            "respuesta" : rta,
        };
        $.ajax({
                data:  parametros, //datos que se envian a traves de ajax
                url:   BASE_URL+'/externos/responder', //archivo que recibe la peticion
                type:  'post', //método de envio
                success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve
                    debug(response)
                    app.notificar('Respuesta enviada');
                    app.historial_update();
                },
                error: function(e) {
                    debug(e.statusText);
                }
        });
    }
};

$(function() {
    app.init();
});