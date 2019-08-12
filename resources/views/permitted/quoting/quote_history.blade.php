@extends('layouts.admin')

@section('contentheader_title')
  {{-- @if( auth()->user()->can('View cover') )
    {{ trans('message.breadcrumb_document_create') }}
  @else
    {{ trans('message.denied') }}
  @endif --}}
  Historial de compras
@endsection

@section('breadcrumb_title')
   @if( auth()->user()->can('View Document P') )
    Historial de compras
    @else
      {{ trans('message.denied') }}
    @endif
@endsection

@section('content')
  <form id="form_edit_docp" class="" action="/edit_cart_quoting" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="id_docp" id="id_docp" value="">
  </form>
  @include('permitted.documentp.modal_documentp')
      <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
          <div class="row">
            <form id="search_info" name="search_info" class="form-inline" method="post">
              {{ csrf_field() }}
              {{-- <div class="col-sm-2">
                <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                  <input id="date_to_search" type="text" class="form-control" name="date_to_search">
                </div>
              </div>
              <div class="col-sm-10">
                <button id="boton-aplica-filtro" type="button" class="btn btn-info filtrarDashboard">
                  <i class="glyphicon glyphicon-filter" aria-hidden="true"></i>  Filtrar
                </button>
              </div> --}}
            </form>
          </div>
        </div>
      </div>
      <br>
      <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 pt-10">
          <div class="table-responsive">
            <table id="table_quoting" class="table table-striped table-bordered table-hover table-condensed">
              <thead>
                <tr style="background: #0E2A38;color: white;">
                  <th> <small>Fecha de solicitud</small> </th>
                  <th> <small>Nombre del proyecto</small> </th>
                  <th> <small>$ EA USD</small> </th>
                  <th> <small>$ ENA USD</small> </th>
                  <th> <small>$ MO USD</small> </th>
                  <th> <small>Solicitó</small> </th>
                  <th> <small>Estatus</small> </th>
                  <th> <small>Versión</small> </th>
                  <th> <small>Tipo Doc.</small> </th>
                  <th> <small></small> </th>
                </tr>
              </thead>
              <tbody>
              </tbody>
              <tfoot id='tfoot_average'>
                <tr>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
@endsection

@push('scripts')
  <script src="{{ asset('plugins/momentupdate/moment.js') }}" type="text/javascript"></script>
  <script src="{{ asset('plugins/momentupdate/moment-with-locales.js') }}" type="text/javascript"></script>
  <script src="{{ asset('bower_components/datatables_bootstrap_4/datatables.js')}}" charset="utf-8"></script>
  <link rel="stylesheet" type="text/css" href="{{ asset('css/documentp.css')}}" >
  <script src="{{ asset('js/admin/quoting/requests_quoting_all.js')}}"></script>
  <script src="{{ asset('js/admin/quoting/request_modal_quoting.js')}}"></script>

@endpush