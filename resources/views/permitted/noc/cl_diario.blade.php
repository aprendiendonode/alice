@extends('layouts.admin')

@section('contentheader_title')
  @if( auth()->user()->can('View noc') )
    <strong>CL diario</strong>
  @else
    {{ trans('message.denied') }}
  @endif
@endsection

@section('contentheader_description')
  @if( auth()->user()->can('View noc') )

  @else
    {{ trans('message.denied') }}
  @endif
@endsection

@section('breadcrumb_title')
  @if( auth()->user()->can('View noc') )
    NOC
  @else
    {{ trans('message.denied') }}
  @endif
@endsection

@section('content')

<div class="row">
    <div class="col-md-12 grid-margin-onerem  stretch-card">
        <div class="card">
            <div class="card-body">
              <div class="text-center">
                <h4>Check List actividades diarias del ITC</h4>
                <div class="row pb-3">
                  <div class="row pb-3 w-100">
                    <div class="col-md-4">

                    </div>
                    <div class="col-md-3 ">
                      <div class="form-group" id="date_from">
                        <label class="control-label" for="date_to_search">
                          {{ __('general.date_from') }}
                        </label>
                        <div class="input-group mb-3">
                          <input type="text"  datas="filter_date_from" id="date_to_search" name="date_to_search" class="form-control form-control-sm" placeholder="" value="" required>
                          <div class="input-group-append">
                            <span class="input-group-text white"><i class="fa fa-calendar"></i></span>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-3  pt-4">
                      <button id="btn-filtro"  type="button"
                              class="btn btn-primary" >
                          <i class="fas fa-filter"> Filtrar</i>
                      </button>
                    </div>
                  </div>
                </div>
                <br>
                <table id="table_cl_diario" class="table table-striped table-bordered compact-tab table-hover">
                  <thead class="bg-aqua text-center">
                    <tr>
                      <th>Nombre.</th>
                      <th>Revisar Calendario de citas de hoy - Planear Mi dia</th>
                      <th>Seguimiento, documentación y cierre de tickets</th>
                      <th>Uso del Uniforme de ITC</th>
                      <th>Uso de llave de ITC en el uniforme</th>
                      <th>Asistencia al gym</th>
                      <th>Mantener y dejar ordenado sus lugares de trabajo (no almacenar cajas ni equipo)</th>
                      <th>Trato cordial y amable a todos</th>
                      <th>Revisar Calendario de citas de los siguientes 2 dias</th>
                      <th>Limpiar y diagnosticar equipos dañado y entregar a almacén.</th>
                      <th>Fecha</th>
                    </tr>
                  </thead>
                  <tbody class="text-center"style="font-size: 11px;">

                  </tbody>
                </table>
                </div>

  <div class="row pt-4">
      <div class="col-md-12">

          <div class="text-center">
              <h4>Check List por cliente(entrega el día 5 del mes)</h4>
              <br>
              <table id="table_cl_5" class="table table-striped table-bordered compact-tab table-hover w-100">
                  <thead class="bg-aqua text-center">
                      <tr>
                          <th>Nombre</th>
                          <th>Nombre sitio</th>
                          <th>Reporte de elaborado y entregado al cliente o en la carpeta de acceso al cliente</th>
                          <th>NPS contestado</th>
                          <th>Factura entregada al cliente</th>
                          <th>Memoria eécnica actualizada</th>
                          <th>Inventario actualizado</th>
                          <th>Fecha</th>
                      </tr>
                  </thead>
                  <tbody class="text-center" style="font-size: 11px;">

                  </tbody>
              </table>
          </div>

      </div>
  </div>

                <div class="row pt-4">
                  <div class="col-md-12">
                    <div class="text-center">
                      <h4>Check List por cliente (entrega el dia 20 del mes)</h4>
                      <br>
                      <div class="table-responsive">


                      <table id="table_cl_20" class="table table-striped table-bordered compact-tab table-hover">
                        <thead class="bg-aqua text-center">
                          <tr>
                              <th>Nombre</th>
                              <th>Nombre sitio</th>
                              <th>Visita a cliente</th>
                              <th>Revisar y Asegurar disponibilidad del 98 % del equipo activo en sitio</th>
                              <th>Detecta oportunidades del cliente</th>
                              <th>Revisión de Información del cliente en Alice (Dashboard del cliente)</th>
                              <th>Detecta oportunidades de clientes nuevos  en el trayecto de visita a clientes asignados</th>
                              <th>Mantenimiento Preventivo o correctivo a  MDF/IDF (de acuerdo a calendario)</th>
                              <th>Realizar  Backup de equipos de comunicaciones ZD, SonicWall, ZQ, SW, etc.</th>
                              <th>Revisar y renovar licencia de ZD (si corresponde)</th>
                              <th>Cliente al corriente en el pago de factura del mes</th>
                              <th>Fecha</th>
                          </tr>
                        </thead>
                        <tbody class="text-center"style="font-size: 11px;">


                        </tbody>
                      </table>
                      </div>
                    </div>
                  </div>

                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@push('scripts')

    <link rel="stylesheet" href="{{ asset('bower_components/select2/dist/css/select2.min.css') }}" type="text/css" />
    <script src="{{ asset('bower_components/select2/dist/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/jquery-wizard-master-two/jquery.validate.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <link href="{{ asset('bower_components/datatables_bootstrap_4/datatables.min.css')}}" rel="stylesheet" type="text/css">
    <script src="{{ asset('bower_components/datatables_bootstrap_4/datatables.min.js')}}"></script>
    <script src="{{ asset('js/admin/noctools/checklist.js?v=3.3.1')}}"></script>

    <style media="screen">
    .tableFixHead          { overflow-y: auto; height: 620px; }
    .tableFixHead thead th { position: sticky !important; top: 0; }
    .bg-aqua{
    background: #02948c;
    }
    </style>
@endpush
