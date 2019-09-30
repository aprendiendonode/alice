@extends('layouts.admin')

@section('contentheader_title')
  @if( auth()->user()->can('View customers invoices') )
    {{ trans('invoicing.customers_invoices') }} Contratos
  @else
  {{ trans('message.denied') }}
  @endif
@endsection

@section('breadcrumb_title')
  @if( auth()->user()->can('View customers invoices') )
    {{ trans('invoicing.customers_invoices') }} Contratos
  @else
  {{ trans('message.denied') }}
  @endif
@endsection

@section('content')
  @if( auth()->user()->can('View customers invoices') )
  <div class="row">
    <div class="col-md-12 grid-margin-onerem  stretch-card">
      <div class="card">
        <div class="card-body">
          <form id="form" name="form" enctype="multipart/form-data">
            {{ csrf_field() }}
          {{-- <p class="mt-2 card-title">Nuevo.</p> --}}
          {{-- <div class="d-flex justify-content-center pt-3"></div> --}}
          <input type="hidden"
              id="amount_total_tmp"
              name="amount_total_tmp"
              value="{{ old('amount_total_tmp',0) }}">


          <div class="row">
            <div class="col-md-6 col-xs-12">
              <label for="customer_id" class="control-label  my-2">Clientes:<span style="color: red;">*</span></label>
              <div class="input-group">
                <select class="custom-select" id="customer_id" name="customer_id">
                  <option value="" selected>Selecciona...</option>
                  @forelse ($customer as $customer_data)
                    {{-- <option value="{{ $banks_data->id  }}">{{ $banks_data->name }}</option> --}}
                    <option value="{{ $customer_data->id  }}">{{ $customer_data->name }}</option>
                  @empty
                  @endforelse
                </select>
                <div class="input-group-append">
                  {{-- <button class="btn btn btn-outline-primary btn-xs" type="button"><i class="fas fa-search"></i></button> --}}
                  <button class="btn btn-outline-info btn-xs" type="button"><i class="fas fa-plus-square"></i></button>
                </div>
              </div>
            </div>

            <div class="col-md-3 col-xs-12">
              <div class="form-group">
                <label for="cadena_id" class="control-label">Cadena:<span style="color: red;">*</span></label>
                <select id="cadena_id" name="cadena_id" class="form-control form-control-sm required" style="width:100%;">
                  <option value="">{{ trans('message.selectopt') }}</option>
                  @forelse ($cadenas as $cadenas_data)
                    <option value="{{ $cadenas_data->id  }}">{{ $cadenas_data->cadena }}</option>
                  @empty
                  @endforelse
                </select>
              </div>
            </div>
            <div class="col-md-3 col-xs-12">
              <div class="form-group">
                <label for="cont_maestro_id" class="control-label">Contrato maestro:<span style="color: red;">*</span></label>
                <select id="cont_maestro_id" name="cont_maestro_id" class="form-control form-control-sm required" style="width:100%;">
                </select>
              </div>
            </div>

          </div>
          <div class="row">
            <div class="col-md-3 col-xs-12">
              <div class="form-group">
                <label for="branch_office_id" class="control-label">Sucursal:<span style="color: red;">*</span></label>
                <select id="branch_office_id" name="branch_office_id" class="form-control form-control-sm required" style="width:100%;">
                  <option value="">{{ trans('message.selectopt') }}</option>
                  @forelse ($sucursal as $sucursal_data)
                    <option value="{{ $sucursal_data->id  }}">{{ $sucursal_data->name }}</option>
                  @empty
                  @endforelse
                </select>
              </div>
            </div>

            <div class="col-md-3 col-xs-12">
              <div class="form-group">
                <label for="currency_id" class="control-label">Moneda:<span style="color: red;">*</span></label>
                <select id="currency_id" name="currency_id" class="form-control form-control-sm required" style="width:100%;">
                  <option value="">{{ trans('message.selectopt') }}</option>
                  @forelse ($currency as $currency_data)
                    <option value="{{ $currency_data->id  }}">{{ $currency_data->name }}</option>
                  @empty
                  @endforelse
                </select>
              </div>
            </div>
            <div class="col-md-3 col-xs-12">
              <div class="form-group">
                <label for="currency_value">TC:<span style="color: red;">*</span></label>
                <input type="text" class="form-control form-control-sm" id="currency_value" name="currency_value" style="padding: 0.875rem 0.5rem;">
              </div>
            </div>
            <div class="col-md-3 col-xs-12">
              <div class="form-group">
                <label for="date">Fecha actual:<span style="color: red;">*</span></label>
                <input type="text" class="form-control form-control-sm" id="date" name="date">

                {{-- <input type="text" class="form-control" id="date" name="date" value="@php $date = new DateTime("now", new DateTimeZone('America/Mexico_City'));echo $date->format('Y-m-d H:i:s');@endphp"> --}}
              </div>
            </div>
            <div class="col-md-3 col-xs-12">
              <div class="form-group">
                <label for="payment_term_id" class="control-label">Termino de pago:<span style="color: red;">*</span></label>
                <select id="payment_term_id" name="payment_term_id" class="form-control required" style="width:100%;">
                  <option value="">{{ trans('message.selectopt') }}</option>
                  @forelse ($payment_term as $payment_term_data)
                  <option value="{{ $payment_term_data->id }}"> {{ $payment_term_data->name }} </option>
                  @empty
                  @endforelse
                </select>
              </div>
            </div>
            <div class="col-md-3 col-xs-12">
              <div class="form-group">
                <label for="date_due">Fecha Vencimiento:</label>
                <input type="text" class="form-control form-control-sm" id="date_due" name="date_due" value="">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3 col-xs-12">
              <div class="form-group">
                <label for="salesperson_id" class="control-label">Vendedor:<span style="color: red;">*</span></label>
                <select id="salesperson_id" name="salesperson_id" class="form-control form-control-sm required" style="width:100%;">
                  <option value="">{{ trans('message.selectopt') }}</option>
                  @forelse ($salespersons as $salespersons_data)
                    <option value="{{ $salespersons_data->id  }}">{{ $salespersons_data->name }}</option>
                  @empty
                  @endforelse
                </select>
              </div>
            </div>
            <div class="col-md-3 col-xs-12">
              <div class="form-group">
                <label for="payment_way_id" class="control-label">Forma de pago:<span style="color: red;">*</span></label>
                <select id="payment_way_id" name="payment_way_id" class="form-control form-control-sm required" style="width:100%;">
                  <option value="">{{ trans('message.selectopt') }}</option>
                  @forelse ($payment_way as $payment_way_data)
                  <option value="{{ $payment_way_data->id }}"> {{ $payment_way_data->name }} </option>
                  @empty
                  @endforelse
                </select>
              </div>
            </div>
            <div class="col-md-3 col-xs-12">
              <div class="form-group">
                <label for="payment_method_id" class="control-label">Metodo de pago:<span style="color: red;">*</span></label>
                <select id="payment_method_id" name="payment_method_id" class="form-control form-control-sm required" style="width:100%;">
                  <option value="">{{ trans('message.selectopt') }}</option>
                  @forelse ($payment_methods as $payment_methods_data)
                  <option value="{{ $payment_methods_data->id }}"> {{ $payment_methods_data->name }} </option>
                  @empty
                  @endforelse
                </select>
              </div>
            </div>
            <div class="col-md-3 col-xs-12">
              <div class="form-group">
                <label for="cfdi_use_id" class="control-label">Uso de cfdi:<span style="color: red;">*</span></label>
                <select id="cfdi_use_id" name="cfdi_use_id" class="form-control form-control-sm required" style="width:100%;">
                  <option value="">{{ trans('message.selectopt') }}</option>
                  @forelse ($cfdi_uses as $cfdi_uses_data)
                  <option value="{{ $cfdi_uses_data->id }}"> {{ $cfdi_uses_data->name }} </option>
                  @empty
                  @endforelse
                </select>
              </div>
            </div>
            <div class="col-md-12 col-xs-12">
              <div class="form-group">
                <label for="reference">Referencia:</label>
                <input type="text" class="form-control form-control-sm" id="reference" name="reference" value="">
              </div>
            </div>
          </div>

          <!---------------------------------------------------------------------------------->
          <div class="row mt-5">
            <div class="col-md-12">
              <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                  <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Productos</a>
                  <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">CFDI</a>
                </div>
              </nav>
              <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                  <!-------------------------------------------------------------------------------->
                  <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <div class="table-responsive" style="fontsize: 8px;">
                            <table class="table table-items table-condensed table-hover table-bordered table-striped jambo_table mt-5"
                                   id="items" style="min-width: 1800px;">
                                <thead>
                                <tr>
                                    <th width="5%"
                                        class="text-center">
                                        Opciones
                                    </th>
                                    <th width="12%"
                                        class="text-center">
                                        Sitio
                                    </th>
                                    <th class="text-left">
                                      Descripción
                                      <span class="required text-danger">*</span>
                                    </th>
                                    <th width="10%" class="text-center">
                                      Unidad de medida
                                      <span class="required text-danger">*</span>
                                    </th>
                                    <th width="12%"
                                        class="text-center">
                                        Prod/Serv SAT
                                        <span class="required text-danger">*</span>
                                    </th>
                                    <th width="8%"
                                        class="text-center">
                                        Cantidad<span class="required text-danger">*</span>
                                    </th>
                                    <th width="8%"
                                        class="text-center">
                                        Precio
                                        <span class="required text-danger">*</span>
                                    </th>
                                    <th width="8%"
                                        class="text-center text-nowrap">
                                          Desc. %
                                    </th>
                                    <th width="8%"
                                        class="text-center">
                                        Moneda<span class="required text-danger">*</span>
                                    </th>
                                    <th width="11%"
                                        class="text-center">Impuestos
                                    </th>
                                    <th width="9%"
                                        class="text-right">
                                        Total
                                    </th>
                                </tr>
                                </thead>
                                <tbody>

                                <!-- Items -->
                                @php
                                    $item_row = 0;
                                    $items = (empty(old('item')) ? [] : old('item'));
                                @endphp
                                @foreach ($items as $item_row => $item)
                                  @php
                                    $tmp_products = [];
                                  @endphp
                                  {{-- <tr id="item_row_{{ $item_row }}">
                                    <td class="text-center" style="vertical-align: middle;">
                                        <button type="button"
                                                onclick="$('#item_row_{{ $item_row }}').remove(); totalItem();"
                                                class="btn btn-xs btn-danger"
                                                style="margin-bottom: 0;">
                                                <i class="fa fa-trash-o"></i>
                                        </button>
                                        <!-- input hidden -->
                                        <input type="hidden" id="item_id_{{ $item_row }}"
                                               name="item[{{ $item_row }}][id]"
                                                value="{{ old('item.' . $item_row . '.id') }}">
                                        <!-- /.input hidden -->
                                    </td>
                                    <td>
                                      <div class="form-group form-group-sm">
                                        <select class="form-control form-control-sm"  id="item{{ $item_row.'[product_id]'}}" name="item[{{ $item_row }}][product_id]" required>
                                          <option selected="selected" value="">{{ trans('message.selectopt') }}</option>
                                          @forelse ($product as $product_data)
                                            <option value="{{ $product_data->id  }}">{{ $product_data->name }}</option>
                                          @empty
                                          @endforelse
                                        </select>
                                      </div>
                                    </td>
                                    <td>
                                      <div class="form-group form-group-sm">
                                        <input type="text" class="form-control form-control-sm text-center"
                                        id="item{{ $item_row.'['.$item.']'}}"
                                        name="item[{{ $item_row }}][name]"
                                        value="{{old('item.' . $item_row . '.name')}}"
                                        required />
                                      </div>
                                    </td>
                                    <td>
                                      <div class="form-group form-group-sm">
                                        <select class="form-control form-control-sm"  id="item{{ $item_row.'[unit_measure_id]'}}" name="item[{{ $item_row }}][unit_measure_id]" required>
                                          <option selected="selected" value="">{{ trans('message.selectopt') }}</option>
                                          @forelse ($unitmeasures as $unitmeasures_data)
                                            <option value="{{ $unitmeasures_data->id  }}">{{ $unitmeasures_data->name }}</option>
                                          @empty
                                          @endforelse
                                        </select>
                                      </div>
                                    </td>
                                    <td>
                                      <div class="form-group form-group-sm">
                                        <select class="form-control form-control-sm"  id="item{{ $item_row.'[sat_product_id]'}}" name="item[{{ $item_row }}][sat_product_id]" required>
                                          <option selected="selected" value="">{{ trans('message.selectopt') }}</option>
                                          @forelse ($satproduct as $satproduct_data)
                                            <option value="{{ $satproduct_data->id  }}">{{ $satproduct_data->name }}</option>
                                          @empty
                                          @endforelse
                                        </select>
                                      </div>
                                    </td>
                                    <td>
                                      <div class="form-group form-group-sm">
                                        <input type="text" class="form-control form-control-sm input-sm text-center"
                                        id="item{{ $item_row.'['.$item.']'}}"
                                        name="item[{{ $item_row }}][quantity]"
                                        value="{{old('item.' . $item_row . '.quantity')}}"
                                        required />
                                      </div>
                                    </td>
                                    <td>
                                      <div class="form-group form-group-sm">
                                        <input type="text" class="form-control form-control-sm input-sm text-center"
                                        id="item{{ $item_row.'['.$item.']'}}"
                                        name="item[{{ $item_row }}][price_unit]"
                                        value="{{old('item.' . $item_row . '.price_unit')}}"
                                        required />
                                      </div>
                                    </td>
                                    <td>
                                      <div class="form-group form-group-sm">
                                        <input type="text" class="form-control form-control-sm input-sm text-center"
                                        id="item{{ $item_row.'['.$item.']'}}"
                                        name="item[{{ $item_row }}][discount]"
                                        value="{{old('item.' . $item_row . '.discount')}}"
                                        required />
                                      </div>
                                    </td>
                                    <td></td>
                                    <td>
                                      <div class="form-group form-group-sm">
                                        <input type="text" class="form-control form-control-sm input-sm text-center"
                                        id="item{{ $item_row.'['.$item.']'}}"
                                        name="item[{{ $item_row }}][current]"
                                        value="{{old('item.' . $item_row . '.current')}}"
                                        required />
                                      </div>
                                    </td>
                                    <td>
                                      <div class="form-group form-group-sm">
                                        <select class="form-control form-control-sm input-sm"  id="item{{ $item_row.'[taxes]'}}" name="item[{{ $item_row }}][taxes]" required>
                                          <option selected="selected" value="">{{ trans('message.selectopt') }}</option>
                                          @forelse ($impuestos as $impuestos_data)
                                            <option value="{{ $impuestos_data->id  }}">{{ $impuestos_data->name }}</option>
                                          @empty
                                          @endforelse
                                        </select>
                                      </div>
                                    </td>
                                    <td class="text-right" style="padding-top: 11px;">
                                      <span id="item_txt_amount_untaxed_{{ $item_row }}">0</span>
                                    </td>
                                  </tr> --}}
                                @endforeach
                                {{-- @php
                                  $item_row++;
                                @endphp --}}
                                <!-- Agregar nuevo item -->
                                <tr id="add_item">
                                    <td class="text-center">
                                        <button type="button" onclick="addItem();"
                                                class="btn btn-xs btn-primary"
                                                style="margin-bottom: 0;">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </td>
                                    <td class="text-right" colspan="10"></td>
                                </tr>
                                <!-- Totales -->
                                <tr>
                                    <td></td>
                                    <td class="text-right" colspan="8" rowspan="3"
                                        style="vertical-align: middle">
                                    </td>
                                    <td class="text-right">
                                        <strong>Subtotal</strong>
                                    </td>
                                    <td class="text-right"><span id="txt_amount_untaxed">0</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-right">
                                        <strong>Impuesto</strong></td>
                                    <td class="text-right"><span id="txt_amount_tax">0</span></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-right">
                                        <strong>Total</strong></td>
                                    <td class="text-right"><span id="txt_amount_total">0</span></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>


                        <!--------------------------------------------------------------------------------->

                        <!--------------------------------------------------------------------------------->
                    </div>
                  </div>
                  <!-------------------------------------------------------------------------------->
                </div>
                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                  <!-------------------------------------------------------------------------------->
                  <div class="row">
                    <div class="col-md-4 col-xs-12">
                      <div class="form-group row">
                        <label for="cfdi_relation_id" class="col-md-12 col-form-label ml-0">Tipo de relación<span style="color: red;">*</span></label>
                        <div class="col-md-12 ml-0">
                          <select  id="cfdi_relation_id" name="cfdi_relation_id" class="form-control form-control-sm form-control-sm"  style="width: 100%;">
                            <option value="">{{ trans('message.selectopt') }}</option>
                            @forelse ($cfdi_relations as $cfdi_relations_data)
                            <option value="{{ $cfdi_relations_data->id }}"> [{{ $cfdi_relations_data->code}}]{{ $cfdi_relations_data->name }} </option>
                            @empty
                            @endforelse
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12 col-xs-12">
                      <div class="table-responsive">
                        <table class="table table-items table-condensed table-hover table-bordered table-striped mt-5"
                                id="items_relation">
                            <thead>
                            <tr>
                                <th width="5%"
                                    class="text-center">Opciones</th>
                                <th width="25%"
                                    class="text-center">
                                  CFDI
                                </th>
                                <th width="65%"
                                    class="text-center">
                                      UUID
                                    </th>
                            </tr>
                            </thead>
                            <tbody>
                            <!-- Items -->
                            @php
                                $item_relation_row = 1;
                                $items_relation = old('item_relation',[]);
                            @endphp
                            @foreach ($items_relation as $item_relation_row => $item)
                                @php
                                    $tmp_uuid = '';
                                    $tmp_customer_invoice_relations = [];
                                @endphp
                                <tr id="item_relation_row_{{ $item_relation_row }}">
                                    <td class="text-center"
                                        style="vertical-align: middle;">
                                        <button type="button"
                                                onclick="$('#item_relation_row_{{ $item_relation_row }}').remove();"
                                                class="btn btn-xs btn-danger"
                                                style="margin-bottom: 0;">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                        <!-- input hidden -->
                                        <input type="text"
                                                id="item_relation_id_{{ $item_relation_row }}"
                                                name="item_relation[{{ $item_relation_row }}][id]"
                                                value="{{ old('item_relation.' . $item_relation_row . '.id') }}">
                                        <!-- /.input hidden -->
                                    </td>
                                    <td class="text-center align-middle">

                                      <select class="form-control input-sm"  id="'item_relation_relation_id_' . $item_relation_row" name="old('item_relation.' . $item_relation_row . '.relation_id')" required>
                                        <option selected="selected" value="">{{ trans('message.selectopt') }}</option>

                                      </select>
                                    </td>
                                    <td class="text-center align-middle">
                                        <span id="item_relation_uuid_{{ $item_relation_row }}">{{$tmp_uuid}}</span>
                                    </td>
                                </tr>
                            @endforeach
                            @php
                                $item_relation_row++;
                            @endphp
                            <!-- /Items -->
                            <!-- Agregar nuevo item -->
                            <tr id="add_item_relation">
                                <td class="text-center">
                                    <button type="button"
                                            onclick="addItemCfdiRelation();"
                                            class="btn btn-xs btn-primary"
                                            style="margin-bottom: 0;">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </td>
                                <td class="text-right" colspan="2"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    </div>
                  </div>
                  <!-------------------------------------------------------------------------------->
                </div>

              </div>
            </div>
          </div>

          <!---------------------------------------------------------------------------------->
          <!-- Footer form -->
          <div class="ln_solid mt-5"></div>
          <div class="row">
            <div class="col-md-12 col-xs-12 text-right footer-form">
              <button type="submit" class="btn btn-outline-primary">@lang('general.button_save')</button>
              &nbsp;&nbsp;&nbsp;
              <button type="button" class="btn btn-outline-danger">@lang('general.button_discard')</button>
            </div>
          </div>
          <!-- /Footer form -->
          </form>
        </div>
      </div>
    </div>
  </div>
  @else
    @include('default.denied')
  @endif
@endsection

@push('scripts')
  @if( auth()->user()->can('View customers invoices') )
  {{-- <link rel="stylesheet" href="{{ asset('bower_components/select2/dist/css/select2.min.css') }}" type="text/css" />
  <script src="{{ asset('bower_components/select2/dist/js/select2.full.min.js') }}" type="text/javascript"></script> --}}
  <link rel="stylesheet" href="{{ asset('plugins/select2/dist/css/select2.css') }}" type="text/css" />
  <link rel="stylesheet" href="{{ asset('plugins/select2/dist/css/select2-bootstrap.min.css') }}" type="text/css" />
  <script src="{{ asset('plugins/select2/dist/js/select2.full.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('plugins/select2/dist/js/i18n/es-MX.js') }}" type="text/javascript"></script>

  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/jquery-wizard-master/libs/formvalidation/formValidation.min.css')}}" >
  <script src="{{ asset('plugins/jquery-wizard-master/libs/formvalidation/formValidation.min.js')}}"></script>
  <script src="{{ asset('plugins/jquery-wizard-master/libs/formvalidation/bootstrap.min.js')}}"></script>

  <link href="{{ asset('bower_components/bootstrap4-toggle-master/css/bootstrap4-toggle.min.css')}}" rel="stylesheet" type="text/css">
  <script src="{{ asset('bower_components/bootstrap4-toggle-master/js/bootstrap4-toggle.min.js')}}"></script>

  <link href="{{ asset('bower_components/datatables_bootstrap_4/datatables.min.css')}}" rel="stylesheet" type="text/css">
  <script src="{{ asset('bower_components/datatables_bootstrap_4/datatables.min.js')}}"></script>

  <script src="{{ asset('plugins/momentupdate/moment.js')}}"></script>
  <link href="{{ asset('plugins/daterangepicker-master/daterangepicker.css')}}" rel="stylesheet" type="text/css">
  <script src="{{ asset('plugins/daterangepicker-master/daterangepicker.js')}}"></script>

  {{-- <link href="{{ asset('bower_components/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet" type="text/css"> --}}
  {{-- <script src="{{ asset('bower_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script> --}}
  {{-- <script src="{{ asset('js/admin/sales/customers_invoices.js')}}"></script> --}}
  <script src="{{ asset('plugins/jquery-wizard-master-two/jquery.validate.min.js')}}"></script>
  <script src="{{ asset('plugins/jquery-wizard-master-two/additional-methods.js')}}"></script>

  <script type="text/javascript">
  $(function() {
    //-----------------------------------------------------------
        $("#form").validate({
          ignore: "input[type=hidden]",
          errorClass: "text-danger",
          successClass: "text-success",
          errorPlacement: function (error, element) {
              var attr = $('[name="'+element[0].name+'"]').attr('datas');
              // console.log(element[0].name);
              // console.log(attr);
              // console.log($('[name="'+element[0].name+'"]'));
              if (element[0].id === 'fileInput') {
                error.insertAfter($('#cont_file'));
              }
              else {
                if(attr == 'sel_estatus'){
                  error.insertAfter($('#cont_estatus'));
                }
                else {
                  error.insertAfter(element);
                }
              }
            },
            rules: {
              cfdi_relation_id: {
                required: function(element) {
                  // console.log($(".verCfdiRelation").toArray().length);
                    if ($(".verCfdiRelation").toArray().length === 0) {
                      if ( $("#form select[name='cfdi_relation_id']").hasClass('required')){
                        $("#form select[name='cfdi_relation_id']").removeClass("required");
                        $("#form select[name='cfdi_relation_id']").removeClass("text-danger");
                        // console.log('false');
                        return false;
                      }
                    }
                    else {
                      $("#form select[name='cfdi_relation_id']").addClass("required");
                      // console.log('true');
                      return true;
                    }
                },
              }
            },
            messages: {
            },
            // debug: true,
            // errorElement: "label",
            submitHandler: function(e){
              var form = $('#form')[0];
              var formData = new FormData(form);
              $.ajax({
                type: "POST",
                url: "/sales/customer-invoices-store",
                data: formData,
                contentType: false,
                processData: false,
                success: function (data){
                  if (data == 'success') {
                    let timerInterval;
                    Swal.fire({
                      type: 'success',
                      title: 'La factura se ha generado con éxito!',
                      html: 'Se estan aplicando los cambios.',
                      timer: 2500,
                      onBeforeOpen: () => {
                        Swal.showLoading()
                        timerInterval = setInterval(() => {
                          Swal.getContent().querySelector('strong')
                        }, 100)
                      },
                      onClose: () => {
                        clearInterval(timerInterval)
                      }
                    }).then((result) => {
                      if (
                        // Read more about handling dismissals
                        result.dismiss === Swal.DismissReason.timer
                      ) {
                        window.location.href = "/sales/customer-invoices";
                      }
                    });
                  }
                  if (data == 'false') {
                    Swal.fire({
                       type: 'error',
                       title: 'Error encontrado..',
                       text: 'Error al crear el  CFDI!',
                     });
                  }
                  // console.log(data);
                },
                error: function (err) {
                  Swal.fire({
                     type: 'error',
                     title: 'Oops...',
                     text: err.statusText,
                   });
                }
              });
              // form.submit();
            }
        });
        //-----------------------------------------------------------
      });

      // var currency = {!! json_encode($currency) !!};
      // console.log(currency);
      var item_row = "{{ $item_row }}";
      var item_relation_row = "{{ $item_relation_row }}";
      var item_row_cfdi_relation = "{{ $item_relation_row }}";

      function addItem() {
          // var moneda_val = $('input[name="currency_id"]').val();
          // var tc_val = $('input[name="currency_value"]').val();
          // if (moneda_val == '' || tc_val == '') {
          //   Swal.fire({
          //      type: 'error',
          //      title: 'Oops...',
          //      text: 'Selecciona la moneda a usar e ingresa un TC',
          //    });
          // }
          // else {
          //   //#Solicitamos primero el tc a usar
          //   var html = '';
          //   html += '<tr id="item_row_' + item_row + '">';
          //   html += '<td class="text-center" style="vertical-align: middle;">';
          //   html += '<button type="button" onclick="$(\'#item_row_' + item_row + '\').remove(); totalItem();" class="btn btn-xs btn-danger" style="margin-bottom: 0;">';
          //   html += '<i class="fa fa-trash"></i>';
          //   html += '</button>';
          //   html += '<input type="hidden" name="item[' + item_row + '][id]" id="item_id_' + item_row + '" /> ';
          //   html += '</td>';
          //
          //   html += '<td>';
          //   html += '<div class="form-group form-group-sm">';
          //   html += '<div class="input-group input-group-sm">';
          //   html += '<select class="form-control form-control-sm col-product-id" name="item[' + item_row + '][product_id]" id="item_product_id_' + item_row + '" data-row="' + item_row + '">';
          //   html += '<option selected="selected" value="">@lang('message.selectopt')</option>';
          //   @forelse ($product as $product_data)
          //   html += '<option value="{{ $product_data->id  }}">{{ $product_data->name }}</option>';
          //   @empty
          //   @endforelse
          //   html += '</select>';
          //   html += '</div>';
          //   html += '</div>';
          //   html += '</td>';
          //
          //   html += '<td>';
          //   html += '<div class="form-group form-group-sm">';
          //   html += '<input class="form-control form-control-sm col-name-id" name="item[' + item_row + '][name]" id="item_name_' + item_row + '" placeholder="" required rows="2" autocomplete="off" />';
          //   html += '</input>';
          //   html += '</div>';
          //   html += '</td>';
          //
          //   html += '<td>';
          //   html += '<div class="form-group form-group-sm">';
          //   html += '<select class="form-control form-control-sm col-unit-measure-id" name="item[' + item_row + '][unit_measure_id]" id="item_unit_measure_id_' + item_row + '" required>';
          //   html += '<option selected="selected" value="">@lang('message.selectopt')</option>';
          //   @forelse ($unitmeasures as $unitmeasures_data)
          //     html += '<option value="{{ $unitmeasures_data->id  }}">{{ $unitmeasures_data->name }}</option>';
          //   @empty
          //   @endforelse
          //   html += '</select>';
          //   html += '</div>';
          //   html += '</td>';
          //
          //   html += '<td>';
          //   html += '<div class="form-group form-group-sm">';
          //   html += '<select class="form-control form-control-sm col-sat-product-id" name="item[' + item_row + '][sat_product_id]" id="item_sat_product_id_' + item_row + '" required>';
          //   html += '<option selected="selected" value="">@lang('message.selectopt')</option>';
          //   @forelse ($satproduct as $satproduct_data)
          //     html += '<option value="{{ $satproduct_data->id  }}">{{ $satproduct_data->name }}</option>';
          //   @empty
          //   @endforelse
          //   html += '</select>';
          //   html += '</div>';
          //   html += '</td>';
          //
          //   html += '<td>';
          //   html += '<div class="form-group form-group-sm">';
          //   html += '<input type="number" class="form-control form-control-sm text-right col-quantity" name="item[' + item_row + '][quantity]" id="item_quantity_' + item_row + '" required step="any" />';
          //   html += '</div>';
          //   html += '</td>';
          //
          //   html += '<td>';
          //   html += '<div class="form-group form-group-sm">';
          //   html += '<input type="number" class="form-control form-control-sm text-right col-price-unit" name="item[' + item_row + '][price_unit]" id="item_price_unit_' + item_row + '" required step="any" />';
          //   html += '</div>';
          //   html += '</td>';
          //
          //   html += '<td>';
          //   html += '<div class="form-group form-group-sm">';
          //   html += '<input type="number" class="form-control form-control-sm text-center col-discount" name="item[' + item_row + '][discount]" id="item_discount_' + item_row + '" step="any" />';
          //   html += '</div>';
          //   html += '</td>';
          //
          //   //
          //   /*<select id="currency_id" name="currency_id" class="form-control required" style="width:100%;">
          //     <option value="">{{ trans('message.selectopt') }}</option>
          //     @forelse ($currency as $currency_data)
          //       <option value="{{ $currency_data->id  }}">{{ $currency_data->name }}</option>
          //     @empty
          //     @endforelse
          //   </select>*/
          //
          //   html += '<td>';
          //   html += '<div class="form-group form-group-sm">';
          //
          //   html += '<select class="form-control form-control-sm col-current" name="item[' + item_row + '][current]" id="item_current_' + item_row + '" data-row="' + item_row + '" required>'
          //   html += '<option selected="selected" value="">@lang('message.selectopt')</option>';
          //   @forelse ($currency as $currency_data)
          //     html += '<option value="{{ $currency_data->id  }}">{{ $currency_data->name }}</option>';
          //   @empty
          //   @endforelse
          //   html += '</select>';
          //   // html += '<input type="number" class="form-control input-sm text-center col-current" name="item[' + item_row + '][current]" id="item_current_' + item_row + '" step="any" />';
          //   html += '</div>';
          //   html += '</td>';
          //
          //   //
          //
          //   html += '<td>';
          //   html += '<div class="form-group form-group-sm">';
          //   html += '<select class="form-control form-control-sm my-select2 col-taxes" name="item[' + item_row + '][taxes][]" id="item_taxes_' + item_row + '" multiple>';
          //   @forelse ($impuestos as $impuestos_data)
          //     html += '<option value="{{ $impuestos_data->id  }}">{{ $impuestos_data->name }}</option>';
          //   @empty
          //   @endforelse
          //     html += '</select>';
          //   html += '</div>';
          //   html += '</td>';
          //
          //   html += '<td class="text-right" style="padding-top: 11px;">';
          //   html += '<span id="item_txt_amount_untaxed_' + item_row + '">0</span>';
          //   html += '</td>';
          //   html += '</tr>';
          //   $("#form #items tbody #add_item").before(html);
          //   /* Configura lineas*/
          //   initItem();
          //   // totalItem();
          //   item_row++;
          // }
      }
      /*Selecciona moneda actual*/
      $(document).on('change', '#form #items tbody .col-current', function (e) {
          // let id = $(this).val();
          // console.log(id);
          let row = $(this).attr('data-row');
          console.log(row);
          totalItem();

      });
      function initItem() {
        /*Para impuestos*/
        $("#form #items tbody .my-select2").select2({
          theme: 'bootstrap',
          placeholder: 'Elija',
          dropdownAutoWidth : true,
          width: 'auto'
        });
        $("#form #items tbody .col-product-id").select2();
      }
      /*Selecciona producto*/
      $(document).on('select2:select', '#form #items tbody .col-product-id', function (e) {
          let id = $(this).val();
          let row = $(this).attr('data-row');
          // console.log(id);
          if (id) {
              $.ajax({
                  url: "/sales/products/get-product",
                  type: "GET",
                  dataType: "JSON",
                  data: "id=" + id,
                  success: function (data) {
                      $("#form #item_name_" + row).val(data[0].description);
                      $("#form #item_unit_measure_id_" + row).val(data[0].unit_measure_id);
                      $("#form #item_sat_product_id_" + row).val(data[0].sat_product_id);
                      $("#form #item_price_unit_" + row).val(data[0].price);
                      $("#form #item_current_" + row).val(data[0].currency_id);
                      initItem();
                      totalItem();
                  },
                  error: function (error, textStatus, errorThrown) {
                      if (error.status == 422) {
                          var message = error.responseJSON.error;
                          $("#general_messages").html(alertMessage("danger", message));
                      } else {
                          alert(errorThrown + "\r\n" + error.statusText + "\r\n" + error.responseText);
                      }
                  }
              });
          }
      });
      $(document).on("change", "#form #items tbody .col-taxes", function () {
          totalItem();
      });
      $(document).on("keyup", "#form #items tbody .col-quantity,#form #items tbody .col-price-unit,#form #items tbody .col-discount", function () {
          totalItem();
      });
      $('#currency_id').on("change", function(){
        var valor = $(this).val();
        var token = $('input[name="_token"]').val();
        if (valor === '1') {
          $('#currency_value').val('1');
        }else{
          $.ajax({
              url: "/sales/customer-invoices/currency_now",
              type: "POST",
              // dataType: "JSON",
              data: { _token : token, id_currency: valor },
              success: function (data) {
                console.log(data);
                $('#currency_value').val(data);
              },
              error: function (error, textStatus, errorThrown) {
                  if (error.status == 422) {
                      var message = error.responseJSON.error;
                      $("#general_messages").html(alertMessage("danger", message));
                  } else {
                      alert(errorThrown + "\r\n" + error.statusText + "\r\n" + error.responseText);
                  }
              }
          });
        }

      });

      function totalItem() {
        $.ajax({
            url: "/sales/customer-invoices/total-lines",
            type: "POST",
            dataType: "JSON",
            data: $("#form").serialize(),
            success: function (data) {
                if (data) {
                    console.log(data);
                    $.each(data.items, function (key, value) {
                        $("#item_txt_amount_untaxed_" + key).html(value);
                    });
                    $("#form #txt_amount_untaxed").html(data.amount_untaxed);
                    $("#form #txt_amount_tax").html(data.amount_tax);
                    $("#form #txt_amount_total").html(data.amount_total);
                    $("#form input[name='amount_total_tmp']").val(data.amount_total_tmp)
                }
            },
            error: function (error, textStatus, errorThrown) {
                if (error.status == 422) {
                    var message = error.responseJSON.error;
                    $("#general_messages").html(alertMessage("danger", message));
                } else {
                    alert(errorThrown + "\r\n" + error.statusText + "\r\n" + error.responseText);
                }
            }
        });
      }

      $(function () {
        $("#form input[name='date']").daterangepicker({
            singleDatePicker: true,
            timePicker: true,
            timePicker24Hour: true,
            showDropdowns: true,
            minDate: moment(),
            maxDate : moment().add(3, 'days'),
            locale: {
                format: "DD-MM-YYYY HH:mm:ss"
            },
            autoUpdateInput: true
        }, function (chosen_date) {
            $("#form input[name='date']").val(chosen_date.format("DD-MM-YYYY HH:mm:ss"));
        });
        /*Configura datepicker*/
        $("#form input[name='date_due']").daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minDate: moment(),
            locale: {
                format: "DD-MM-YYYY"
            },
            autoUpdateInput: false,
        }, function (chosen_date) {
            $("#form input[name='date_due']").val(chosen_date.format("DD-MM-YYYY"));
        });
        /* Configura select2 para buscar cliente*/
        $("#form select[name='customer_id']").select2({
            theme: "bootstrap",
            placeholder: "Selecciona",
            dropdownAutoWidth : true,
            width: "80%",
            height: "110%"
            // allowClear: true,
        }).on("change", function () {
          let id = $(this).val();
          if (id) {
              //Si cambia de cliente necesito reiniciar la pestaña de cfdi, dado que solo las facturas del cliente mismo cliente se pueden enlazar
              for (var i = 1; i <= item_relation_row; i++) {
                if ($('#item_relation_row_'+i).length){
                  $('#item_relation_row_'+i).remove();
                }
              }
              item_relation_row = 1 ;


              $.ajax({
                  url: "{{ route('customers/get-customer') }}",
                  type: "GET",
                  dataType: "JSON",
                  data: "id=" + id,
                  success: function (data) {
                      $("#form select[name='salesperson_id']").val(data.salesperson_id);
                      $("#form select[name='payment_term_id']").val(data.payment_term_id);
                      $("#form select[name='payment_way_id']").val(data.payment_way_id);
                      $("#form select[name='payment_method_id']").val(data.payment_method_id);
                      $("#form select[name='cfdi_use_id']").val(data.cfdi_use_id);
                  },
                  error: function (error, textStatus, errorThrown) {
                      if (error.status == 422) {
                          var message = error.responseJSON.error;
                          $("#general_messages").html(alertMessage("danger", message));
                      } else {
                          alert(errorThrown + "\r\n" + error.statusText + "\r\n" + error.responseText);
                      }
                  }
              });
          }
        });

        /* Eventos en items cfdi relation */
        /*Selecciona factura*/
        $(document).on('select2:select', '#form #items_relation tbody .col-relation-id', function (e) {
          let id = $(this).val();
          let row = $(this).attr('data-row');
          if (id) {
              $.ajax({
                  url: "/sales/customer-invoices/get-customer-invoice",
                  type: "GET",
                  dataType: "JSON",
                  data: "id=" + id,
                  success: function (data) {
                      $("#form #item_relation_uuid_" + row).html(data.uuid);
                  },
                  error: function (error, textStatus, errorThrown) {
                      if (error.status == 422) {
                          var message = error.responseJSON.error;
                          $("#general_messages").html(alertMessage("danger", message));
                      } else {
                          alert(errorThrown + "\r\n" + error.statusText + "\r\n" + error.responseText);
                      }
                  }
              });
          }
        });
      });

      function addItemCfdiRelation() {
            let client = $("#form select[name='customer_id']").val();

            if (client != '') {
              var html = '';

              html += '<tr id="item_relation_row_' + item_relation_row + '">';
              html += '<td class="text-center" style="vertical-align: middle;">';
              html += '<button type="button" onclick="$(\'#item_relation_row_' + item_relation_row + '\').remove();" class="btn btn-xs btn-danger verCfdiRelation" style="margin-bottom: 0;">';
              html += '<i class="fa fa-trash"></i>';
              html += '</button>';
              html += '<input type="hidden" name="item_relation[' + item_relation_row + '][id]" id="item_relation_id_' + item_relation_row + '" /> ';
              html += '</td>';
              html += '<td class="text-center align-middle">';
              html += '<div class="form-group form-group-sm">';
              html += '<select class="form-control input-sm col-relation-id" name="item_relation[' + item_relation_row + '][relation_id]" id="item_relation_relation_id_' + item_relation_row + '" data-row="' + item_relation_row + '" required>';
              html += '<option selected="selected" value="">Elija</option>';
              html += '</select>';
              html += '</div>';
              html += '</td>';
              html += '<td class="text-center align-middle">';
              html += '<span id="item_relation_uuid_' + item_relation_row + '"></span>';
              html += '</td>';
              html += '</tr>';

              $("#form #items_relation tbody #add_item_relation").before(html);

              /* Configura select2 */
              initItemCfdiRelation();

              item_relation_row++;

              if ( !$("#form select[name='cfdi_relation_id']").hasClass('required')){
                $("#form select[name='cfdi_relation_id']").addClass("required");
              }
            }
            else {
              Swal.fire({
                 type: 'error',
                 title: 'Oops...',
                 text: 'Selecciona un cliente primero',
               });
            }
      }
      function initItemCfdiRelation() {
        /*Busqueda de facturas*/
        $("#form #items_relation tbody .col-relation-id").select2({
            ajax: {
                url: "/sales/customer-invoices/autocomplete-cfdi",
                dataType: "JSON",
                delay: 250,
                data: function (params) {
                    return {
                        // term: $.trim(params.term),//LA PALABRA A BUSCAR
                        customer_id: $("#form select[name='customer_id']").val(),
                    };
                },
                processResults: function (data, page) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            placeholder: "Selecciona",
            theme: "bootstrap",
            width: "auto",
            dropdownAutoWidth: true,
        });
      }

      $('#cadena_id').on("change", function(){
        var valor = $(this).val();
        var token = $('input[name="_token"]').val();
        if (valor === '') {
          $('#cont_maestro_id').empty();
          $('#cont_maestro_id').append('<option value="" selected>Elije</option>');
        }else{
          $.ajax({
              url: "/sales/customer-invoices-cont-search",
              type: "POST",
              data: { _token : token, id_search: valor },
              success: function (data) {
                datax = JSON.parse(data);
                countH = datax.length;
                $('#cont_maestro_id').empty();
                $('#cont_maestro_id').append('<option value="" selected>Elije</option>');
                for (var i = 0; i < countH; i++) {
                    $("#cont_maestro_id option").prop("selected", false);
                    $('#cont_maestro_id').append('<option value="'+datax[i].contract_master_id+'">'+ datax[i].key +'</option>');
                }
              },
              error: function (error, textStatus, errorThrown) {
                  if (error.status == 422) {
                      var message = error.responseJSON.error;
                      $("#general_messages").html(alertMessage("danger", message));
                  } else {
                      alert(errorThrown + "\r\n" + error.statusText + "\r\n" + error.responseText);
                  }
              }
          });
        }

      });

      $('#cont_maestro_id').on('change', function(){
        //$("#items > tbody:last").children().remove();
        getDataContractAnnexes();
      })

      function getDataContractAnnexes(){
        var token = $('input[name="_token"]').val();
        var cadena_id = $('#cadena_id').val();
        var cont_maestro_id = $('#cont_maestro_id').val();
        $.ajax({
            url: "/sales/customer-data-annexes",
            type: "POST",
            data: { _token : token, cadena_id: cadena_id, contract_master_id: cont_maestro_id},
            success: function (data) {
              console.log(data);
              var moneda_val = $('input[name="currency_id"]').val();
              var tc_val = $('input[name="currency_value"]').val();
              if (moneda_val == '' || tc_val == '') {
                Swal.fire({
                   type: 'error',
                   title: 'Oops...',
                   text: 'Selecciona la moneda a usar e ingresa un TC',
                 });
              }
              else {
                //#Solicitamos primero el tc a usar
                data.forEach(function(key,i) {
                  var html = '';
                  html += '<tr id="item_row_' + item_row + '">';
                  html += '<td class="text-center" style="vertical-align: middle;">';
                  html += '<button type="button" onclick="$(\'#item_row_' + item_row + '\').remove(); totalItem();" class="btn btn-xs btn-danger" style="margin-bottom: 0;">';
                  html += '<i class="fa fa-trash"></i>';
                  html += '</button>';
                  html += '<input type="hidden" name="item[' + i + '][id]" id="item_id_' + item_row + '" /> ';
                  html += '</td>';

                  html += '<td>';
                  html += '<div class="form-group form-group-sm">';
                  html += '<div class="input-group input-group-sm">';
                  html +=  key.sitio
                  html += '</div>';
                  html += '</div>';
                  html += '</td>';

                  html += '<td>';
                  html += '<div class="form-group form-group-sm">';
                  html += '<input class="form-control form-control-sm col-name-id" name="item[' + item_row + '][name]" id="item_name_' + item_row + '" placeholder="" required rows="2" autocomplete="off" />';
                  html += '</input>';
                  html += '</div>';
                  html += '</td>';

                  html += '<td>';
                  html += '<div class="form-group form-group-sm">';
                  html += '<select class="form-control form-control-sm col-unit-measure-id" name="item[' + item_row + '][unit_measure_id]" id="item_unit_measure_id_' + item_row + '" required>';
                  html += '<option selected="selected" value="">@lang('message.selectopt')</option>';
                  @forelse ($unitmeasures as $unitmeasures_data)
                    html += '<option value="{{ $unitmeasures_data->id  }}">{{ $unitmeasures_data->name }}</option>';
                  @empty
                  @endforelse
                  html += '</select>';
                  html += '</div>';
                  html += '</td>';

                  html += '<td>';
                  html += '<div class="form-group form-group-sm">';
                  html += '<select class="form-control form-control-sm col-sat-product-id" name="item[' + item_row + '][sat_product_id]" id="item_sat_product_id_' + item_row + '" required>';
                  html += '<option selected="selected" value="">@lang('message.selectopt')</option>';
                  @forelse ($satproduct as $satproduct_data)
                    html += '<option value="{{ $satproduct_data->id  }}">{{ $satproduct_data->name }}</option>';
                  @empty
                  @endforelse
                  html += '</select>';
                  html += '</div>';
                  html += '</td>';

                  html += '<td>';
                  html += '<div class="form-group form-group-sm">';
                  html += '<input type="number" class="form-control form-control-sm text-right col-quantity" value="1"  name="item[' + item_row + '][quantity]" id="item_quantity_' + item_row + '" required step="any" />';
                  html += '</div>';
                  html += '</td>';

                  html += '<td>';
                  html += '<div class="form-group form-group-sm">';
                  html += '<input type="number" class="form-control form-control-sm text-right col-price-unit" value="' + key.monto + '" name="item[' + item_row + '][price_unit]" id="item_price_unit_' + item_row + '" required step="any" />';
                  html += '</div>';
                  html += '</td>';

                  html += '<td>';
                  html += '<div class="form-group form-group-sm">';
                  html += '<input type="number" class="form-control form-control-sm text-center col-discount" name="item[' + item_row + '][discount]" id="item_discount_' + item_row + '" step="any" />';
                  html += '</div>';
                  html += '</td>';

                  html += '<td>';
                  html += '<div class="form-group form-group-sm">';
                  html += '<select class="form-control form-control-sm col-current" name="item[' + item_row + '][current]" id="item_current_' + item_row + '" data-row="' + item_row + '" required>'
                  html += '<option selected="selected" value="">@lang('message.selectopt')</option>';
                  @forelse ($currency as $currency_data)
                    html += '<option value="{{ $currency_data->id  }}">{{ $currency_data->name }}</option>';
                  @empty
                  @endforelse
                  html += '</select>';
                  // html += '<input type="number" class="form-control input-sm text-center col-current" name="item[' + item_row + '][current]" id="item_current_' + item_row + '" step="any" />';
                  html += '</div>';
                  html += '</td>';

                  //

                  html += '<td>';
                  html += '<div class="form-group form-group-sm">';
                  html += '<select class="form-control form-control-sm my-select2 col-taxes" name="item[' + item_row + '][taxes][]" id="item_taxes_' + item_row + '" multiple>';
                  @forelse ($impuestos as $impuestos_data)
                    html += '<option value="{{ $impuestos_data->id  }}">{{ $impuestos_data->name }}</option>';
                  @empty
                  @endforelse
                    html += '</select>';
                  html += '</div>';
                  html += '</td>';

                  html += '<td class="text-right" style="padding-top: 11px;">';
                  html += '<span id="item_txt_amount_untaxed_' + i + '">0</span>';
                  html += '</td>';
                  html += '</tr>';
                  $("#form #items tbody #add_item").before(html);
                  /* Configura lineas*/
                  initItem();
                  $("#item_current_"+item_row+" option[value='" + key.currency_id +"']").attr('selected', true);
                  item_row++;
                });
                totalItem();
              }
            },
            error: function (error, textStatus, errorThrown) {
              console.log(error)
            }
        });

      }

  </script>

  <style media="screen">
    .btn-xs {
      padding: .35rem .4rem .25rem !important;
    }
    input {
      padding-left: 0px !important;
      padding-right: : 0px !important;
    }
    .datepicker td, .datepicker th {
        width: 1.5em !important;
        height: 1.5em !important;
    }

    #items th, #items td {
    padding: .75rem 0.7375rem;
    vertical-align: top;
    border-top: 1px solid #f3f3f3;
}
    /* .select2-selection__rendered {
      line-height: 44px !important;
      padding-left: 20px !important;
    }
    .select2-selection {
      height: 42px !important;
    }
    .select2-selection__arrow {
      height: 36px !important;
    } */
  </style>
  @else
  @endif
@endpush
