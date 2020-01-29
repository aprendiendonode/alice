<div class="row pl-3 pr-3">
  <div class="mx-auto pb-2">
    <button type="button" id="agregarHabitacion" class="btn btn-sm btn-outline-success font-weight-bold d-none" data-toggle="modal" data-target="#AgregarArea"><i class="fas fa-plus-square"></i> Nueva habitación</button>
    <button type="button" id="BtnGeneral" class="btn btn-sm btn-outline-warning font-weight-bold" data-toggle="modal" data-target="#VistaGeneral"><i class="fas fa-th-list"></i> Vista general</button>
    <button type="button" id="salvarMovimientos" class="btn btn-sm btn-outline-info font-weight-bold d-none"><i class="fas fa-expand-arrows-alt"></i> Sincronizar</button>
    <button type="button" id="descartarMovimientos" class="btn btn-sm btn-outline-danger font-weight-bold d-none"><i class="fas fa-compress-arrows-alt"></i> Descartar</button>
    <button type="button" id="leftPiso" class="btn btn-sm btn-outline-light font-weight-bold"><i class="fas fa-caret-square-left"></i></i></button>
    <button type="button" id="piso" class="btn btn-sm btn-outline-light font-weight-bold" data-toggle="modal" data-target="#ElegirPiso">Cargando...</button>
    <button type="button" id="rightPiso" class="btn btn-sm btn-outline-light font-weight-bold"><i class="fas fa-caret-square-right"></i></i></button>
  </div>
  <div id="containment-wrapper" style="width: 100%; height: 78vh; min-height: 400px;">
    <div id="mapa" style="border-radius: 10px; border: 2px solid #333333;">

    </div>
  </div>
  <!-- AgregarArea -->
  <div class="modal fade" id="AgregarArea" tabindex="-1" role="dialog" aria-labelledby="AgregarAreaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="AgregarAreaLabel"><i class="fas fa-plus-square"></i> Nueva habitación</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text">Nombre:</span>
            </div>
            <input id="nombreArea" type="text" class="form-control" placeholder="Nombre o número de la habitación" aria-label="Nombre o número de la habitación">
          </div>
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text">Ubicación (Nombre o número del piso):</span>
            </div>
            <select id="pisoAgregarArea" class="form-control" style="width: 100%;">

            </select>
          </div>
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text">Estado actual:</span>
            </div>
            <select id="estado" class="form-control">
              <option value="1">Disponible</option>
              <option value="2">Ocupada</option>
              <option value="0" selected>No disponible</option>
            </select>
          </div>
          <!--<div class="input-group mb-3">
  		  <div class="input-group-prepend">
  			<span class="input-group-text">Equipos activos en la habitación:</span>
  		  </div>
  		  <select id="equipos" class="form-control" style="width: 100%;" multiple="multiple">
  		    <option>Antena número 1</option>
  		    <option>Switch Cisco 2660</option>
  		    <option>Antena de largo alcance</option>
  		  </select>
  		</div>-->
          <div class="descartar d-none text-danger text-center">*No has sincronizado tus movimientos en el mapa*</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button id="AgregarAreaButton" type="button" class="btn btn-primary">Confirmar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- ElegirPiso -->
  <div class="modal fade" id="ElegirPiso" tabindex="-1" role="dialog" aria-labelledby="ElegirPisoLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ElegirPisoLabel"><i class="fas fa-clone"></i> Elegir piso</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="input-group mb-3">
            <select id="cambiarPiso" class="form-control" style="width: 100%;">

            </select>
          </div>
          <div class="descartar d-none text-danger text-center">*No has sincronizado tus movimientos en el mapa*</div>
        </div>
      </div>
    </div>
  </div>
  <!-- CambiarNombre -->
  <div class="modal fade" id="CambiarNombre" tabindex="-1" role="dialog" aria-labelledby="CambiarNombreLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="CambiarNombreLabel"><i class="fas fa-edit"></i> Cambiar nombre</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text">Nuevo nombre:</span>
            </div>
            <input id="nuevoNombre" type="text" class="form-control" aria-label="Nombre o número de la habitación">
          </div>
          <div class="descartar d-none text-danger text-center">*No has sincronizado tus movimientos en el mapa*</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button id="CambiarNombreButton" type="button" class="btn btn-primary">Confirmar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- CambiarEstado -->
  <div class="modal fade" id="CambiarEstado" tabindex="-1" role="dialog" aria-labelledby="CambiarEstadoLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="CambiarEstadoLabel"><i class="fas fa-sync-alt"></i> Cambiar estado</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text">Nuevo estado:</span>
            </div>
            <select id="nuevoEstado" class="form-control">
              <option value="1" selected>Disponible</option>
              <option value="2">Ocupada</option>
              <option value="0">No disponible</option>
            </select>
          </div>
          <div class="descartar d-none text-danger text-center">*No has sincronizado tus movimientos en el mapa*</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button id="CambiarEstadoButton" type="button" class="btn btn-primary">Confirmar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- EliminarArea -->
  <div class="modal fade" id="EliminarArea" tabindex="-1" role="dialog" aria-labelledby="EliminarAreaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="EliminarAreaLabel"><i class="fas fa-trash-alt"></i> Eliminar habitación</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-center">
          <p style="font-weight: bold;">¿Está seguro?<p>
              <p>!Este cambio es permanente y no podrá ser revertido!</p>
              <div class="descartar d-none text-danger text-center">*No has sincronizado tus movimientos en el mapa*</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button id="EliminarAreaButton" type="button" class="btn btn-primary">Confirmar</button>
        </div>
      </div>
    </div>
  </div>
  <!--Vista general-->
  <div class="modal fade" id="VistaGeneral" tabindex="-1" role="dialog" aria-labelledby="VistaGeneralLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title text-white" id="VistaGeneralLabel"><i class="far fa-eye"></i> Vista general</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <table id="lugares" class="table table-sm  table-borderless  w-100" style="">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Estado</th>
                <th>Piso</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>

            </tbody>

          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>


  <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" charset="utf-8"></script>
  <script src="/js/dashboard/vistageneral.js" charset="utf-8"></script>
  <link href="/css/toastr.css" rel="stylesheet">
  <script src="/js/toastr.js"></script>
  <script src="/js/jquery.ui.touch-punch.min.js"></script>
  <!--<script src="/socket.io/socket.io.js" charset="utf-8"></script>-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>
  <script>

    const socket = io();

    var hotel_id = 20;
    var width = 20;
    var height = 20;
    var left = 20;
    var _top = 20;
    var hotel_mapa = "20";

    var allAreas = null;
    var cambios = [];
    var pisoActual = "";

    $(window).load(function() {

      socket.emit('init', {

          hotel_id: hotel_id

      });

      socket.on('cambiosExternos', function() {

        toastr.warning('', '!Alguien ha modificado el mapa!', {
          "closeButton": false,
          "debug": false,
          "newestOnTop": false,
          "progressBar": false,
          "positionClass": "toast-top-right",
          "preventDuplicates": false,
          "onclick": null,
          "showDuration": "300",
          "hideDuration": "1000",
          "timeOut": "1500",
          "extendedTimeOut": "1000",
          "showEasing": "swing",
          "hideEasing": "linear",
          "showMethod": "fadeIn",
          "hideMethod": "fadeOut"
        });

      });

      $("#pisoAgregarArea").select2({
        dropdownParent: $('#AgregarArea'),
        placeholder: 'Piso 1',
        tags: true
      });

      $("#cambiarPiso").select2({
        dropdownParent: $('#ElegirPiso')
      });

      /*$("#equipos").select2({
        placeholder: 'Ninguno',
        allowClear: true
      });*/

    });

  </script>
  <script src="/js/dashboard/context-menu.js"></script>
  <script src="/js/dashboard/mapa.js"></script>
  <script src="/js/dashboard/areas.js"></script>
  <script src="/js/dashboard/pisos.js"></script>
</div>
