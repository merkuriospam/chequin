@extends('layouts.ring')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!--Grid column-->
        <div class="col-md-12">
            <!--Card-->
            <div class="card card-cascade">
                <!--Card image-->
                <div class="view overlay hm-white-slight">
                    <img src="{{ asset('media/img/DoorEntrance01.jpg') }}" class="img-fluid" alt="CheQuin {{ $lugar->nombre}}">
                    <a><div class="mask"></div></a>
                </div>
                <!--/.Card image-->
                <!--Card content-->
                <div class="card-body text-center">
                    <h4 class="card-title">
                        <strong>CheQuin {{ $lugar->nombre}}</strong>
                    </h4>
                    <?php if ($lugar->estado) { ?>
                    <div class="md-form">
                        <input name="remitente" id="remitente" type="text" id="form1" class="form-control">
                        <label for="form1" class="">Tu Nombre</label>
                    </div>
                    <div id="div_timbre" class="boton_timbre" onclick="visita.timbre('{{ $lugar->id}}')">Anunciarme</div>
                <?php } else { ?>
                    <h6>Este Chequin se encuentra Apagado</h6>
                <?php } ?>
                </div>
                <!--/.Card content-->
            </div>
            <!--/.Card-->
        </div>
        <!--Grid column-->
    </div>
</div>

<div class="modal fade" id="modalRta" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Respuesta de {{ $lugar->nombre }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="div_rta_texto"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
window.onbeforeunload = function() {
  return "Si refresca la pantalla no podran notificarlo. Esta seguro?";
};

var timer;
var estado_boton = 1;
var cuantas = 1;
var respuesta = false;
var timer_respuesta;
var intervalo = 5000;
var visita = {
    init: function() {
        console.log('Iniciando Visita...');
    },
    timbre: function(id) {
        var remitente = $('#remitente').val();
        if (remitente.length < 3) {
            visita.notificar('Ingrese un texto válido.');
            return false;
        }
        estado_boton = 0;
        cuantas = cuantas + 1;
        var parametros = {
            "remitente" : $('#remitente').val(),
            "referencia" : "{{$referencia}}"
        };
        $.ajax({
                data:  parametros,
                type:  'post',
                url:   BASE_URL+'/externos/timbre/'+id,
                success:  function (response) {
                    timer = setTimeout(visita.cambiar_estado_boton, 3000);
                    console.log(response);
                    //alert('Sonando');
                    timer_respuesta = setInterval(visita.buscar_respuesta, intervalo);
                },
                error: function(e) {
                    console.log(e.statusText);
                }
        });
        visita.render_estado_boton();      
    },
    cambiar_estado_boton: function() {
        console.log('Iniciando visita.cambiar_estado_boton...');
        estado_boton = 1;
        visita.render_estado_boton();
    },
    render_estado_boton: function() {
        if(estado_boton == 1) {
            $("#div_timbre").attr("onclick","visita.timbre('{{ $lugar->id}}')");
            $("#div_timbre").css("opacity", "1");
            $("#div_timbre").html("Anunciarme");
        } else {
            $("#div_timbre").attr("onclick","visita.notificar('Ya estoy Sonando')");
            $("#div_timbre").css("opacity", "0.3");
            $("#div_timbre").html("Sonando");
        }
    },
    notificar: function(mensaje) {
        alert(mensaje);
    },

    buscar_respuesta: function() {
        if (respuesta==false) {
            var parametros = {
                "referencia" : "{{$referencia}}"
            };
            $.ajax({
                    data:  parametros,
                    type:  'post',
                    url:   BASE_URL+'/externos/respuesta',
                    success:  function (response) {
                        timer = setTimeout(visita.cambiar_estado_boton, 3000);
                        //alert('Sonando');
                        if(response.estado) {
                            //visita.notificar(response.respuesta);
                            clearInterval(timer_respuesta);
                            $('#div_rta_texto').empty();
                            $('#div_rta_texto').html('<p>'+response.respuesta+'</p>');
                            $('#modalRta').modal('show');
                        }
                    },
                    error: function(e) {
                        alert(e.statusText);
                    }
            });

        }
    },
};
$(function() {
    visita.init();
});
</script>
@endpush