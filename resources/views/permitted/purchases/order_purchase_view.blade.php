@extends('layouts.admin')

@section('contentheader_title')
  @if( auth()->user()->can('Create purchase orders') )
    Compras
  @else
  {{ trans('message.denied') }}
  @endif
@endsection

@section('breadcrumb_title')
  @if( auth()->user()->can('Create purchase orders') )
    Compras
  @else
  {{ trans('message.denied') }}
  @endif
@endsection

@section('content')
  @if( auth()->user()->can('Create purchase orders') )
  <div class="row">
    <div class="col-md-12 grid-margin-onerem  stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="mt-4 text-dark">Crear orden de compra</h4>
          <form id="form" name="form" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-6 col-xs-12">
                  <label for="id_doc" class="control-label  my-2">Documentos P / M:<span style="color: red;">*</span></label>
                  <div class="input-group">
                    <select class="custom-select select2" id="id_doc" name="id_doc">
                      <option value="" selected>Selecciona...</option>
                      @forelse ($projects as $projects_data)
                        <option value="{{ $projects_data->id  }}">{{ $projects_data->folio }}</option>
                      @empty
                      @endforelse
                    </select>                   
                  </div>
                </div> 
                <div class="col-md-6 col-xs-12">
                  <label for="provider_id" class="control-label  my-2">Proveedores:<span style="color: red;">*</span></label>
                  <div class="input-group">
                    <select required class="custom-select select2" id="provider_id" name="provider_id">
                      <option value="" selected>Selecciona...</option>
                      @forelse ($providers as $provider_data)
                        <option value="{{ $provider_data->id  }}">{{ $provider_data->name }}</option>
                      @empty
                      @endforelse
                    </select>                      
                  </div>
                </div> 
                <div class="col-md-6 col-xs-12">
                  <label for="provider_id" class="control-label  my-2">Proveedor diverso:</label>
                  <div class="input-group">
                    <select required class="custom-select select2" id="provider_divesos_id" name="provider_divesos_id">
                      <option value="" selected>Selecciona...</option>
                     
                    </select>                      
                  </div>
                </div> 
            </div>  
             
            <div class="row mt-3">
                <div class="col-md-6 col-xs-12">
                  <label for="address_delivery_id" class="control-label  my-2">Domicilio de entrega:<span style="color: red;">*</span></label>
                  <div class="input-group">
                    <select class="custom-select required" id="address_delivery_id" name="address_delivery_id">
                      @forelse ($direcciones as $direcciones_data)
                        <option value="{{ $direcciones_data->id  }}">{{ $direcciones_data->address }}</option>
                      @empty
                      @endforelse
                    </select>    
                    <div class="input-group-append">
                      <button id="modal-address-button" type="button" class="btn btn-outline-info btn-xs"><i class="fas fa-plus-square"></i></button>
                    </div>         
                  </div>
                  
                </div> 
                
                <div class="col-md-6 col-xs-12">
                  <label for="date_delivery" class="control-label  my-2">Fecha de entrega:<span style="color: red;">*</span></label>
                    <div class="input-group mb-3">
                      <input type="text"  datas="date_delivery" id="date_delivery" name="date_delivery" class="form-control" placeholder="" value="" required>
                      <div class="input-group-append">
                        <span class="input-group-text white"><i class="fa fa-calendar"></i></span>
                      </div>
                    </div>
                </div>
            </div>         

            <div class="row">
              <div class="col-md-12 col-xs-12">
                <div class="form-group">
                  <label for="reference">Referencia:</label>
                  <input type="text" class="form-control" id="reference" name="reference" value="">
                </div>
              </div>
            </div>

            <!---------------------------------------------------------------------------------->
            <div class="row mt-5">
                <div class="table-responsive">
                    <table id="tabla_productos" class="table table-condensed table-sm">
                      <thead>
                        <tr style="background: #496E7D;color:white;font-size:10px;">
                          <th></th>
                    
                          <th class="text-center">Cant. req.</th>
                          <th class="text-center">Cantidad</th>
                          <th width="250px">Producto</th>
                          <th>Costo unitario</th>
                          <th>Modeda</th>
                          <th>Subtotal</th>
                          <th>% Desc.</th>
                          <th>Total Des.</th>
                          <th>Total C/Desc.</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                      <tfoot class="text-right" style="font-size:18px;border-color:transparent">
                        <tr>
                          <td colspan="5"></td> <td style="font-weight:bold;border-color:transparent" colspan="4">SUBTOTAL:</td>
                          <td  style="font-weight:bold;" colspan="1">$ <span id="subtotal">0.00</span></td>
                        </tr>
                        <tr>
                            <td colspan="5"></td> <td style="font-weight:bold;border-color:transparent" colspan="4">DESCUENTO:</td>
                            <td  style="font-weight:bold;" colspan="1">$ <span id="descuento">0.00</span></td>
                        </tr>                     
                        <tr>
                            <td colspan="5"></td> <td style="font-weight:bold;border-color:transparent" colspan="4">I.V.A:</td>
                            <td  style="font-weight:bold;" colspan="1">$ <span id="iva">0.00</span></td>
                        </tr>
                        <tr>
                            <td colspan="5"></td> <td style="font-weight:bold;border-color:transparent" colspan="4">TOTAL:</td>
                            <td  style="font-weight:bold;" colspan="1">$ <span id="total">0.00</span></td>
                        </tr>
                      </tfoot>
                    </table>
                </div>
            </div>
            <!---------------------------------------------------------------------------------->
          <!-- Footer form -->
            <div class="ln_solid mt-5"></div>
            <div class="row">
              <div class="col-md-12 col-xs-12 text-right footer-form">
                <button type="submit" class="btn btn-outline-dark">@lang('general.button_save')</button>       
              </div>
            </div>
          <!-- /Footer form -->
          </form>
        </div>
      </div>
    </div>
  </div>
  <!------------------------- MODAL DE DIRECCIONES ---------------------------->
  <div class="modal fade" id="modalAddress" tabindex="-1" role="dialog" aria-labelledby="modalAddressLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalAddressLabel">Agregar direccion de entrega</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="form-add-address-delivery">
          {{ csrf_field() }}
        <div class="modal-body">         
          <div class="form-group">
            <label for="new_address" class="col-form-label">Direccion:</label>
            <input required type="text" class="form-control form-control-sm" id="new_address" name="new_address">
          </div>         
        </div>
        <div class="modal-footer">    
          <button type=submit" class="btn btn-primary">Guardar direccion</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
  @if( auth()->user()->can('Create purchase orders') )
  <link rel="stylesheet" href="{{ asset('plugins/select2/dist/css/select2.css') }}" type="text/css" />
  <link rel="stylesheet" href="{{ asset('plugins/select2/dist/css/select2-bootstrap.min.css') }}" type="text/css" />
  <script src="{{ asset('plugins/select2/dist/js/select2.full.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('plugins/select2/dist/js/i18n/es-MX.js') }}" type="text/javascript"></script>

  <script src="{{ asset('plugins/momentupdate/moment.js')}}"></script>
  <link href="{{ asset('plugins/daterangepicker-master/daterangepicker.css')}}" rel="stylesheet" type="text/css">
  <script src="{{ asset('plugins/daterangepicker-master/daterangepicker.js')}}"></script>
  
  <script src="{{ asset('plugins/jquery-wizard-master-two/jquery.validate.min.js')}}"></script>
  <script src="{{ asset('plugins/jquery-wizard-master-two/additional-methods.js')}}"></script>
  <script src="{{ asset('js/admin/purchases/purchases_orders.js?v=1.5')}}"></script>

  <style media="screen">
    .white {background-color: #ffffff;}
    
    .select2-selection__rendered {
      line-height: 38px !important;
      padding-left: 15px !important;
    }

    .select2-selection {
      height: 36px !important;
    }

    .select2-selection__arrow {
      height: 30px !important;
    }

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
    
  </style>
  @else
  @endif
@endpush
