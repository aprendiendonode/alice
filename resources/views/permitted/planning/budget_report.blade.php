@extends('layouts.app')

@section('contentheader_title')
  @if( auth()->user()->can('View annual budget report') )
    {{ trans('planning.budget_site') }}
  @else
    {{ trans('planning.budget_site') }}
  @endif
@endsection

@section('contentheader_description')
  @if( auth()->user()->can('View annual budget report') )
    {{ trans('planning.subtitle_budget') }}
  @else
    {{ trans('planning.subtitle_budget') }}
  @endif
@endsection

@section('breadcrumb_ubication')
  @if( auth()->user()->can('View annual budget report') )
    {{ trans('planning.breadcrumb_budget_site') }}
  @else
    {{ trans('planning.breadcrumb_budget_site') }}
  @endif
@endsection

@section('content')
  @if( auth()->user()->can('View annual budget report') )
  <!-- Modal -->
    <div class="modal fade" id="modal-view-algo">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Default Modal</h4>
          </div>
          <div class="modal-body">
            <!-- Contenido de modal. -->
            <input type="hidden" id="id_annex" name="id_annex">
            <form id="form_tc" class="form-inline">
              <input id="tpgeneral" name="tpgeneral" type="number" class="form-control" placeholder="Tipo de cambio(pagos, viáticos)" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="10">
              <div class="input-group">
               <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
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
                      <table id="table_desglose" name='table_desglose' class="display nowrap table table-bordered table-hover" width="100%" cellspacing="0">
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
            <div class="box box-solid">
              <div class="box-header with-border">
                <i class="fa fa-money"></i>
                <h3 class="box-title">{{ trans('planning.box_title')}}</h3>
              </div>
              <div class="box-body">
                <div class="row">
                 <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                   <form id="search_info" name="search_info" class="form-inline" method="post">
                     {{ csrf_field() }}

                     <div class="col-sm-2">
                       <div class="input-group">
                         <span class="input-group-addon"><i class="fa fa-money"></i></span>
                         <input id="tpgeneral" name="tpgeneral" type="number" class="form-control" placeholder="Tipo de cambio(pagos, viáticos)" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="10">
                       </div>
                     </div>
                     <div class="col-sm-2">
                       <div class="input-group">
                         <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                         <input id="date_to_search" type="text" class="form-control date_plug" name="date_to_search">
                       </div>
                     </div>
                     <div class="col-sm-8">
                       <button id="boton-aplica-filtro" type="button" class="btn btn-info filtrarBudgets">
                         <i class="glyphicon glyphicon-filter" aria-hidden="true"></i>  Filtrar
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
                     <i class="glyphicon glyphicon-refresh" aria-hidden="true"></i>  Refrescar
                  </button>
                 </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                      <table id="table_budget" name='table_budget' class="display nowrap table table-bordered table-hover" width="100%" cellspacing="0">
                        <input type='hidden' id='_tokenb' name='_tokenb' value='{!! csrf_token() !!}'>
                        <thead>
                          <tr class="bg-primary" style="background: #3D82C2">
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
  @if( auth()->user()->can('View annual budget report') )
  <link href="/plugins/sweetalert-master/dist/sweetalert.css" rel="stylesheet" type="text/css" />
  <script src="/plugins/sweetalert-master/dist/sweetalert-dev.js"></script>
  <!-- FormValidation -->
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/jquery-wizard-master/libs/formvalidation/formValidation.min.css')}}" >
  <script src="{{ asset('plugins/jquery-wizard-master/libs/formvalidation/formValidation.min.js')}}"></script>
  <script src="{{ asset('plugins/jquery-wizard-master/libs/formvalidation/bootstrap.min.js')}}"></script>

  <script src="{{ asset('plugins/jquery-wizard-master-two/jquery.validate.min.js')}}"></script>
  <script src="{{ asset('plugins/jquery-wizard-master-two/additional-methods.js')}}"></script>
  <link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.10/css/dataTables.checkboxes.css" rel="stylesheet" />
  <script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.10/js/dataTables.checkboxes.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
  <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-3-right-offset.css')}}" >
  <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-extras-margins-padding.css')}}" >

  <script src="{{ asset('plugins/momentupdate/moment.js') }}" type="text/javascript"></script>
  <script src="{{ asset('plugins/momentupdate/moment-with-locales.js') }}" type="text/javascript"></script>

  <script src="{{ asset('js/admin/planning/budget_report.js')}}"></script>
  @else
  @endif
@endpush
