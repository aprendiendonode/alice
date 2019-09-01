@extends('layouts.admin')

@section('contentheader_title')
  {{ trans('header.dashboard') }}
@endsection

@section('breadcrumb_title')
  {{ trans('breadcrumb.home') }}
@endsection

@section('content')
  @if( auth()->user()->can('View dashboard pral') )
  <!-- <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body dashboard-tabs p-0">
        </div>
      </div>
    </div>
  </div> -->
  <div class="row">
    <div class="col-md-6 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <p class="card-title">Dashboard NPS del <span id="mes_nps" class="mes_nps"></span></p>
          <div id="cash-deposits-chart-legend" class="d-flex justify-content-center">
            <ul class="dashboard-chart-legend">
              <li><span style="background-color: #E73231 "></span><small id="dash_1" class="lead"></small> </li>
              <li><span style="background-color: #FFBF00 "></span><small id="dash_2" class="lead"></small> </li>
              <li><span style="background-color: #0B610B "></span><small id="dash_3" class="lead"></small> </li>
            </ul>
          </div>
          <div id="main_nps" style="width: 100%; min-height: 350px;"></div>
        </div>
      </div>
    </div>
    <div class="col-md-6 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <p class="card-title">Distribución de antenas por país</p>
          <div id="main_distribution" style="width: 100%; min-height: 350px;"></div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <p class="card-title">Tickets resueltos en el año.</p>
          <form class="form-inline">
            <div class="input-group mb-2 mr-sm-2">
              <h5>Seleccione un rango de fechas.</h5>
            </div>
            <div class="input-group mb-2 mr-sm-2">
              <input type="text" class="form-control" id="datepickerYear" name="datepickerYear">
              <div class="input-group-prepend">
                <span class="input-group-text">a</span>
              </div>
              <input type="text" class="form-control"id="datepickerYear3" name="datepickerYear3">
            </div>
            <div class="input-group mb-2 mr-sm-2">
              <button type="button" class="btn btn-outline-primary btn_graph1"> <i class="fas fa-filter" style="margin-right: 4px;"></i> Filtrar</button>
            </div>
          </form>
          <div id="maingraphicTicketsR" class="mt-4" style="width: 100%; min-height: 300px;"></div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <p class="card-title">Pagos registrados - 1 Semana atras de la fecha actual</p>
          <div class="table-responsive">
            <form name="generate_graph1" class="form-inline">
              <div class="input-group mb-2 mr-sm-2">
                <h5>Seleccione una fecha.</h5>
              </div>
              <div class="input-group mb-3">
                <input type="text" class="form-control" id="datepagos" name="datepagos" >
                <div class="input-group-append">
                  <button class="btn btn-outline-primary btn_graph2" type="button"><i class="fas fa-filter" style="margin-right: 4px;"></i> Filtrar</button>
                </div>
              </div>
            </form>
            <div class="table-responsive">
              <table id="example_payment" name='example_payment' class="table">
              <thead>
                <tr>
                  <th>Factura</th>
                  <th>Proveedor</th>
                  <th>Estatus</th>
                  <th>Realizo</th>
                  <th>Monto</th>
                  <th>Moneda</th>
                  <th>Fecha</th>
                  <th>Folio</th>
                  <th>Elaboro</th>
                  <th>Concepto</th>
                  <th>Fecha elaboro</th>
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
  @else
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card mb-3">
            <div class="card-header bg-primary text-white">Dashboard</div>
            <div class="card-body mb-3 mt-2">
              <i class="mdi mdi-bell-ring text-primary"></i> Has iniciado sesión!
              <br>
            </div>
            <span class="badge badge-secondary y-4">
              @php
              $dt = new DateTime(); echo $dt->format('d-m-Y H:i:s');
              @endphp
            </span>
          </div>

          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif
        </div>
      </div>
    </div>
  @endif
@endsection

@push('scripts')
  @if( auth()->user()->can('View dashboard pral') )
    <link href="{{ asset('bower_components/datatables_bootstrap_4/datatables.min.css')}}" rel="stylesheet" type="text/css">
    <script src="{{ asset('bower_components/datatables_bootstrap_4/datatables.min.js')}}"></script>
    <script src="{{ asset('plugins/momentupdate/moment.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/momentupdate/moment-with-locales.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/admin/home_graphics.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/admin/config_table.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
      $(function() {
        var yearnow = moment().format("YYYY");
        var yearminus = moment().subtract(1, 'years').format('YYYY');
        var daynow = moment().format("YYYY-MM-DD");
        $('#datepickerYear').val(yearminus);
        $('#datepickerYear').children().val(yearminus);
        $('#datepickerYear3').val(yearnow);
        $('#datepickerYear3').children().val(yearnow);
        $('#datepickerYear').datepicker({
          language: 'es',
          format: "yyyy",
          viewMode: "years",
          minViewMode: "years",
          startDate: "2013",
          endDate: yearnow,
        });
        $('#datepickerYear3').datepicker({
          language: 'es',
          format: "yyyy",
          viewMode: "years",
          minViewMode: "years",
          startDate: "2013",
          endDate: yearnow,
        });
        $('#datepagos').datepicker({
          language: 'es',
          format: "yyyy-mm-dd",
          viewMode: "days",
          minViewMode: "days",
          endDate: '1m',
          autoclose: true,
          clearBtn: true
        });
        $('#datepagos').val(daynow);
        data_nps();
        graph_apps();
        graph_graph1();
        get_info_payments();
      });
      var _token = $('meta[name="csrf-token"]').attr('content');
      var d = new Date();
      var month = d.getMonth();
      var day = d.getDate();
      var output = d.getFullYear() + '-' +((''+month).length<2 ? '0' : '') + month;
      $('.mes_nps').text(output);

      $('.btn_graph1').on('click', function(e){
        graph_graph1();
      });
      $('.btn_graph2').on('click', function(e){
        get_info_payments();
      });
      function graph_graph1() {
        var fecha_inicio = "";
        var fecha_final = "";
        var dataTicketYearNowP = [];
        var dataTicketMesNowP = [];
        var dataTicketYearLastP = [];
        var dataTicketMesLastP = [];
        var promYearnow2 = 0;
        var promYearLast2 = 0;
        var totalnow = 0;
        var totallast = 0;

        var input1 = $('#datepickerYear').val();
        var input2 = $('#datepickerYear3').val();

        if (input1 < input2) {  fecha_inicio = input1;  fecha_final = input2; }
        else{ fecha_inicio = input2;  fecha_final = input1; }

        $.ajax({
          url: "/dataTicketYearNowP",
          type: "POST",
          data: { input : fecha_final, _token : _token },
          success: function (data) {
            $.each(JSON.parse(data), function(index, dataX){
              dataTicketYearNowP.push(dataX.tickets);
              dataTicketMesNowP.push(dataX.MES);
              totalnow = totalnow + parseInt(dataX.tickets);
            });
            promYearnow2 = (totalnow / (dataTicketMesNowP.length)).toFixed(2);
          },
          error: function (data) {
            console.log('Error:', data);
          }
        });
        $.ajax({
          type: "POST",
          url: "/dataTicketYearLastP",
          data: { input : fecha_inicio, _token : _token },
          success: function (data){
            $.each(JSON.parse(data), function(index, dataInfo){
              dataTicketYearLastP.push(dataInfo.tickets);
              dataTicketMesLastP.push(dataInfo.MES);
              totallast = totallast + parseInt(dataInfo.tickets);
            });
            promYearLast2 = (totallast / (dataTicketMesLastP.length)).toFixed(2);
            graph_barras_uno_zendesk('maingraphicTicketsR',   fecha_inicio, fecha_final, totallast, promYearLast2, totalnow, promYearnow2, dataTicketYearLastP, dataTicketYearNowP, '');
          },
          error: function (data) {
            console.log('Error:', data);
          }
        });
      }
      function get_info_payments() {
        var _token = $('meta[name="csrf-token"]').attr('content');
        var datanow = $('#datepagos').val();
        $.ajax({
          type: "POST",
          url: "/data_get_payment_all_week",
          data: { _token : _token, date: datanow },
          success: function (data){
            table_payments(data, $("#example_payment"));
          },
          error: function (data) {
            console.log('Error:', data);
          }
        });
      }
      function table_payments(datajson, table){
        table.DataTable().destroy();
        var vartable = table.dataTable(Configuration_table_payment);
        vartable.fnClearTable();
        $.each(JSON.parse(datajson), function(index, status){
          var badge = '<span class="badge badge-secondary badge-pill text-uppercase text-white">No disponible</span>';
          if (status.estatus == "Elaboro"){
            badge = '<span class="badge badge-dark badge-pill text-uppercase text-white">Habilitado</span>';
          }
          if (status.estatus == "Reviso"){
            badge = '<span class="badge badge-info badge-pill text-uppercase text-white">Habilitado</span>';
          }
          if (status.estatus == "Autorizo"){
            badge = '<span class="badge badge-warning badge-pill text-uppercase text-white">Habilitado</span>';
          }
          if (status.estatus == "Pagado"){
            badge = '<span class="badge badge-success badge-pill text-uppercase text-white">Habilitado</span>';
          }
          if (status.estatus == "Denegado"){
            badge = '<span class="badge badge-danger badge-pill text-uppercase text-white">Habilitado</span>';
          }
          if (status.estatus == "Programado"){
            badge = '<span class="badge badge-info badge-pill text-uppercase text-white">Programado</span>';
          }
          vartable.fnAddData([
          status.factura,
          status.proveedor,
          badge,
          status.realizo,
          status.monto,
          status.monto_str,
          status.fecha,
          status.folio,
          status.elaboro,
          status.concepto,
          status.fecha_elaboro,
          ]);
        });
      }
      function data_nps(){
        $.ajax({
          type: "POST",
          url: '/data_summary_info_nps',
          data: { _token : _token,  date_to_search: output},
          success: function (data) {
            $.each(JSON.parse(data), function(index, status){
              if(status.Concepto == 'Promotores') {   $('#dash_3').text(status.Count); }
              if(status.Concepto == 'Pasivos') {   $('#dash_2').text(status.Count); }
              if(status.Concepto == 'Detractores') {   $('#dash_1').text(status.Count); }
              if(status.Concepto == 'NPS') {   graph_gauge_dahs('main_nps', 'NPS', '100', '100', status.Count); }
            });
          },
          error: function (data) {
            menssage_toast('Mensaje', '2', 'Operation Abort' , '3000');
          }
        })
      }
      function graph_apps() {
        var data_count1 = [];
        var data_name1 = [];
        $.ajax({
          type: "POST",
          url: "/get_graph_pais_distribution",
          data: { _token : _token },
          success: function (data){
            $.each(JSON.parse(data),function(index, objdata){
              data_name1.push(objdata.pais + ' = ' + objdata.antenas);
              data_count1.push({ value: objdata.antenas, name: objdata.pais + ' = ' + objdata.antenas},);
            });
            graph_douhnut_defaultdes('main_distribution', '', '', data_name1, data_count1);
          },
          error: function (data) {
            console.log('Error:', data);
          }
        });
      }
    </script>
  @endif
@endpush
