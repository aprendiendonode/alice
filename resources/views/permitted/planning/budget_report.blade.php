@extends('layouts.admin')

@section('contentheader_title')
  @if( auth()->user()->can('View annual budget report') )
    Reporte de presupuesto
  @else
    {{ trans('message.denied') }}
  @endif
@endsection

@section('breadcrumb_title')
   @if( auth()->user()->can('View annual budget report') )
    Reporte de presupuesto
    @else
      {{ trans('message.denied') }}
    @endif
@endsection

@section('content')
  @if( auth()->user()->can('View annual budget report') )
    <div class="modal fade" id="modal-view-algo">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Default Modal</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body">
            <!-- Contenido de modal. -->
            <input type="hidden" id="id_annex" name="id_annex">
            <form id="form_tc" class="form-inline">
              <span class="input-group-addon"><i class="fas fa-dollar-sign fa-3x"></i></span>
              <input id="tpgeneral" name="tpgeneral" type="number" class="form-control" placeholder="Tipo de cambio(pagos, viáticos)" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="10">
              <div class="input-group">
               <span class="input-group-addon"><i class="far fa-calendar-alt fa-3x"></i></span>
               <input id="date_to_search_tc" type="text" class="form-control date_plug" name="date_to_search_tc">
              </div>
              <button type="button" class="btn btn-primary btnupdetc">Update</button>

            </form>

              <div class="table-responsive">
                <div class="row fields_docm">
                  <div class="col-md-12">
                    <div class="form-group">
                      <h4 class="text-center text-danger">Tabla de conceptos.</h4>
                      <br>
                      <div id="presupuesto_anual">
                      <table id="table_desglose" name='table_desglose' class="display nowrap table table-bordered table-hover compact-tab w-100" cellspacing="0">
                        <input type='hidden' id='_tokenb' name='_tokenb' value='{!! csrf_token() !!}'>
                        <thead>
                          <tr class="bg-primary" style="background: #3D82C2">
                            <th> <small>Folio</small> </th>
                            <th> <small>Factura</small> </th>
                            <th> <small>Proveedor</small> </th>
                            <th> <small>Monto</small> </th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

          </div>
          <div class="modal-footer">
            <div class="row ">
              <div class="col-sm-12">
                <button type="button" class="btn btn-default closeModal pull-right" data-dismiss="modal">Close</button>
              </div>
              <!-- <div class="col-sm-3">
                <button type="submit" class="btn btn-warning pull-right">Save changes</button>
              </div> -->
            </div>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
  <!-- Contenido de presupuesto. -->
  <div class="container" style="width: 100%;">
      <div class="row">
          <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
            <div class="card">
              <div class="card-body">
                <div class="row">
                 <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                   <form id="search_info" name="search_info" class="form-inline" method="post">
                     {{ csrf_field() }}

                     <div class="col-sm-2">
                       <div class="input-group">
                         <span class="input-group-addon"><i class="fas fa-dollar-sign fa-3x"></i></span>
                         <input id="tpgeneral" name="tpgeneral" type="number" class="form-control" placeholder="Tipo de cambio(pagos, viáticos)" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="10">
                       </div>
                     </div>
                     <div class="col-sm-2">
                       <div class="input-group">
                         <span class="input-group-addon"><i class="far fa-calendar-alt fa-3x"></i></span>
                         <input id="date_to_search" type="text" class="form-control date_plug" name="date_to_search">
                       </div>
                     </div>
                     <div class="col-sm-8">
                       <button id="boton-aplica-filtro" type="button" class="btn btn-info filtrarBudgets">
                         <i class="fas fa-filter" aria-hidden="true"></i>  Filtrar
                       </button>
                     </div>
                   </form>
                 </div>

                </div>
                <div class="row">
                 <div class="col-sm-12 mt-10 form-inline" style="padding-left: 30px">
                  <!-- <form id="proc_update" class="form-inline"></form> -->
                  <label class="control-label">Actualizar información.</label>
                  <button id="boton-aplica-filtro" type="button" class="btn btn-default update_proc_sites">
                     <i class="fas fa-sync-alt" aria-hidden="true"></i>  Refrescar
                  </button>
                 </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                      <table id="table_budget" name='table_budget' class="display nowrap table table-bordered table-hover compact-tab w-100" cellspacing="0">
                        <input type='hidden' id='_tokenb' name='_tokenb' value='{!! csrf_token() !!}'>
                        <thead>
                          <tr class="bg-dark">
                            <!-- <th><small>ID</small></th> -->
                            <th> <small>Sitio</small> </th>
                            <th> <small>Anexo</small> </th>
                            <th> <small>ID ubicacion</small> </th>
                            <th> <small>Moneda</small> </th>
                            <th> <small>Renta</small> </th>
                            <th> <small>Pres. Mant.</small> </th>
                            <th> <small>Ejercido</small> </th>
                            <th> <small>Diferencia</small> </th>
                            <th> <small>Conceptos</small> </th>
                          </tr>
                        </thead>
                        <tbody>
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
  @else
    @include('default.denied')
  @endif
@endsection

@push('scripts')
  @if( auth()->user()->can('View projects docp') )

  <script src="{{ asset('plugins/momentupdate/moment.js') }}" type="text/javascript"></script>
  <script src="{{ asset('plugins/momentupdate/moment-with-locales.js') }}" type="text/javascript"></script>
  <script src="{{ asset('bower_components/datatables_bootstrap_4/datatables.js')}}" charset="utf-8"></script>
  <script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.10/js/dataTables.checkboxes.min.js"></script>
  <link type="text/css" href="css/bootstrap-editable.css" rel="stylesheet" />
  <script src="{{ asset('js/bootstrap-editable.js')}}"></script>
  <link rel="stylesheet" type="text/css" href="{{ asset('css/documentp.css')}}" >
  <script src="{{ asset('js/admin/planning/budget_report.js')}}"></script>
  @else
  @endif
@endpush
