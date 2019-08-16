@extends('layouts.admin')

@section('contentheader_title')
  @if( auth()->user()->can('View Document P') )
    Documento A
  @else
    {{ trans('message.denied') }}
  @endif
@endsection

@section('breadcrumb_title')
  @if( auth()->user()->can('View Document P') )
    Documento A
  @else
    {{ trans('message.denied') }}
  @endif
@endsection

@section('content')
  @if( auth()->user()->can('View Edit Document P') )
      <!-- Validation wizard -->
      <form id="form_edit_cotizador" class="" action="/edit_cart_quoting" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="id_docp2" id="id_docp2" value="{{$document[0]->id}}">
      </form>
      <div class="row" id="validation">
          <div class="col-12">
            <div class="card">
              <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="m-0">DOCUMENTO A (KICK-OFF)</h5>
                <h5 class="m-0"> PROYECTO: {{$document[0]->nombre_proyecto}}</h5>
              </div>
              <div class="card-body">
                <form id="form_kickoff" method="post">
                  <input type="hidden" name="id" id="id" value="{{$document[0]->id}}">
                  <div class="row">
                  <!--INFO-->
                  <div class="col-12">
                    <div class="form-inline d-flex justify-content-space-between align-items-center">
                      <label class="mr-sm-2">ID Proyecto (Almacén)</label>
                      <input type="text" class="form-control form-control-sm mb-2 mr-sm-2" id="id_proyecto_almacen" value="{{$document[0]->id_proyecto_almacen}}" name="id_proyecto_almacen">
                      <label class="mr-sm-2" for="">No. Oportunidad</label>
                      <input type="text" class="form-control form-control-sm mb-2 mr-sm-2" id="num_oportunidad" name="num_oportunidad" value="{{$document[0]->num_oportunidad}}">
                      <label class="mr-sm-2" for="">Sitios</label>
                      <input style="width:70px" type="text" class="form-control form-control-sm mb-2 mr-sm-2" id="sitios" name="sitios" value="{{$document[0]->sitios}}">
                      <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Cotizador" onclick="editar_cotizador(this)" data-id="{{$document[0]->id}}"  value="{{$document[0]->id}}" class="btn btn-info btn-dark text-white"><span class="fa fa-calculator"></span> Ir a cotizador</a>
                    </div>
                  </div>
                  <!--ADMINISTRACION-->
                  <div class="col-12 col-md-4 p-2">
                    <div class="row">
                      <div class="col-12">
                        <h4 class="text-danger text-center">Administración</h4>
                      </div>
                    </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col text-center bg-blue">
                          <label class="m-0" for="">Perfil del cliente</label>
                        </div>
                      </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-4">
                          <label for="">Vertical</label>
                        </div>
                        <div class="col-8">
                          <input id="vertical" name="vertical" disabled type="text" class="form-control form-control-sm" value="{{$document[0]->vertical}}">
                        </div>
                      </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-4">
                          <label for="">Grupo</label>
                        </div>
                        <div class="col-8">
                          <input id="nombre_grupo" name="nombre_grupo" disabled type="text" class="form-control form-control-sm" value="{{$document[0]->nombre_grupo}}">
                        </div>
                      </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-4">
                          <label for="">R.F.C.</label>
                        </div>
                        <div class="col-8">
                          <input id="rfc" name="rfc" value="{{$kickoff_perfil_cliente->rfc}}" type="text" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-4">
                          <label for="">Razón social</label>
                        </div>
                        <div class="col-8">
                          <input id="razon_social" name="razon_social" value="{{$kickoff_perfil_cliente->razon_social}}" type="text" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-4">
                          <label for="">Edo/Municipio</label>
                        </div>
                        <div class="col-8">
                          <input id="edo_municipio" name="edo_municipio" value="{{$kickoff_perfil_cliente->edo_municipio}}" type="text" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-4">
                          <label for="">Contácto</label>
                        </div>
                        <div class="col-8">
                          <input id="contacto" name="contacto" id="contacto" name="contacto" value="{{$kickoff_perfil_cliente->contacto}}" type="text" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-4">
                          <label for="">Puesto</label>
                        </div>
                        <div class="col-8">
                          <input id="puesto" name="puesto" value="{{$kickoff_perfil_cliente->puesto}}" type="text" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-4">
                          <label for="">Teléfono</label>
                        </div>
                        <div class="col-8">
                          <input id="telefono" name="telefono" value="{{$kickoff_perfil_cliente->telefono}}" type="text" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-4">
                          <label for="">Email</label>
                        </div>
                        <div class="col-8">
                          <input id="email" name="email" value="{{$kickoff_perfil_cliente->email}}" type="text" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-4">
                          <label for="">Dirección</label>
                        </div>
                        <div class="col-8">
                          <input id="direccion" name="direccion" value="{{$kickoff_perfil_cliente->direccion}}" type="text" class="form-control form-control-sm">
                        </div>
                      </div>
                  </div>
                  <!--COMERCIAL-->
                  <div class="col-12 col-md-8 p-2">
                    <div class="row">
                      <div class="col-12">
                        <h4 class="text-danger text-center">Comercial</h4>
                      </div>
                    </div>
                      <div class="row">
                        <div class="col-12 col-md-6">
                          <div class="form-row d-flex align-items-center mb-2">
                            <div class="col text-center bg-blue">
                              <label for="">Datos del contrato</label>
                            </div>
                          </div>
                          <div class="form-row d-flex align-items-center mb-2">
                            <div class="col-4">
                              <label for="">No. De contrato/PO</label>
                            </div>
                            <div class="col-8">
                              <input id="num_contrato" name="num_contrato" value="{{$kickoff_contrato->num_contrato}}" type="text" class="form-control form-control-sm">
                            </div>
                          </div>
                          <div class="form-row d-flex align-items-center mb-2">
                            <div class="col-4">
                              <label for="">Fecha de Inicio</label>
                            </div>
                            <div class="col-8">
                              <input type="date" id="fecha_inicio" name="fecha_inicio" value="{{$kickoff_contrato->fecha_inicio}}" class="form-control form-control-sm">
                            </div>
                          </div>
                          <div class="form-row d-flex align-items-center mb-2">
                            <div class="col-4">
                              <label for="">Fecha de término</label>
                            </div>
                            <div class="col-8">
                              <input id="fecha_termino" name="fecha_termino" value="{{$kickoff_contrato->fecha_termino}}" type="date" class="form-control form-control-sm">
                            </div>
                          </div>
                          <div class="form-row d-flex align-items-center mb-2">
                            <div class="col-4">
                              <label for="">Vigencia</label>
                            </div>
                            <div class="col-8">
                              <input type="text" disabled class="form-control form-control-sm" value="{{$document[0]->plazo}} meses">
                            </div>
                          </div>
                          <div class="form-row d-flex align-items-center mb-2">
                            <div class="col-4">
                              <label for="">Fecha de entrega</label>
                            </div>
                            <div class="col-8">
                              <input id="fecha_entrega" name="fecha_entrega" value="{{$kickoff_contrato->fecha_entrega}}" type="date" class="form-control form-control-sm">
                            </div>
                          </div>
                          <div class="form-row d-flex align-items-center mb-2">
                            <div class="col-4">
                              <label for="">VTC (USD)</label>
                            </div>
                            <div class="col-8">
                              <input id="vtc" name="vtc" disabled type="text" class="form-control form-control-sm" value="{{$vtc}}">
                            </div>
                          </div>
                          <div class="form-row d-flex align-items-center mb-2">
                            <div class="col-4">
                              <label for="">Servicio</label>
                            </div>
                            <div class="col-8">
                              <input id="servicio" name="servicio" value="{{$kickoff_contrato->servicio}}" type="text" class="form-control form-control-sm">
                            </div>
                          </div>
                          <div class="form-row d-flex align-items-center mb-2">
                            <div class="col-4">
                              <label for="">Autorización (Sitwifi)</label>
                            </div>
                            <div class="col-8">
                              <input id="autorizacion_sitwifi" name="autorizacion_sitwifi" value="{{$kickoff_contrato->autorizacion_sitwifi}}" type="text" class="form-control form-control-sm">
                            </div>
                          </div>
                          <div class="form-row d-flex align-items-center mb-2">
                            <div class="col-4">
                              <label for="">Autorización (Cliente)</label>
                            </div>
                            <div class="col-8">
                              <input id="autorizacion_cliente" name="autorizacion_cliente" value="{{$kickoff_contrato->autorizacion_cliente}}" type="text" class="form-control form-control-sm">
                            </div>
                          </div>
                        </div>
                        <div class="col-12 col-md-6">
                          <div class="form-row d-flex align-items-center mb-2">
                            <div class="col text-center bg-blue">
                              <label for="">Condiciones comerciales</label>
                            </div>
                          </div>
                          <div class="form-row d-flex align-items-center mb-2">
                            <div class="col-4">
                              <label for="">Renta mensual</label>
                            </div>
                            <div class="col-8">
                              <input id="servicio_mensual" name="servicio_mensual" disabled type="text" class="form-control form-control-sm" value="{{$document[0]->servicio_mensual}}">
                            </div>
                          </div>
                          <div class="form-row d-flex align-items-center mb-2">
                            <div class="col-4">
                              <label for="">Tipo de cambio</label>
                            </div>
                            <div class="col-8">
                              <input id="tipo_cambio" name="tipo_cambio" disabled type="text" class="form-control form-control-sm" value="{{$tipo_cambio}}">
                            </div>
                          </div>
                          <div class="form-row d-flex align-items-center mb-2">
                            <div class="col-4">
                              <label for="">Capex</label>
                            </div>
                            <div class="col-8">
                              <input id="capex" name="capex" type="text" disabled class="form-control form-control-sm" value="{{$document[0]->capex}}">
                            </div>
                          </div>
                          <div class="form-row d-flex align-items-center mb-2">
                            <div class="col-4">
                              <label for="">Mantenimiento por vigencia</label>
                            </div>
                            <div class="col-8">
                              <input id="mantenimiento_vigencia" name="mantenimiento_vigencia" value="{{$kickoff_contrato->mantenimiento_vigencia}}" type="number" class="form-control form-control-sm">
                            </div>
                          </div>
                          <div class="form-row d-flex align-items-center mb-2">
                            <div class="col-4">
                              <label for="">Tipo de adquisición</label>
                            </div>
                            <div class="col-8">
                              <select id="tipo_adquisicion" name="tipo_adquisicion" type="text" class="form-control form-control-sm">
                                <option value="">Elegir</option>
                                <option value="Venta directa">Venta directa</option>
                                <option value="Arrendamiento">Arrendamiento</option>
                              </select>
                            </div>
                          </div>
                          <div class="form-row d-flex align-items-center mb-2">
                            <div class="col-4">
                              <label  for="">Tipo de pago</label>
                            </div>
                            <div class="col-8">
                              <select id="tipo_pago" name="tipo_pago" type="text" class="form-control form-control-sm">
                                <option value="">Elegir</option>
                                <option value="Transferencia">Transferencia</option>
                                <option value="Efectivo">Efectivo</option>
                                <option value="Contado">Contado</option>
                              </select>
                            </div>
                          </div>
                          <div class="form-row d-flex align-items-center mb-2">
                            <div class="col text-center bg-blue">
                              <label for="">Comisión</label>
                            </div>
                          </div>
                          <div class="form-row d-flex align-items-center mb-2">
                            <div class="col-4">
                              <label  for="">Vendedor</label>
                            </div>
                            <div class="col-8">
                              <select id="vendedor" name="vendedor" type="text" class="form-control form-control-sm">
                                <option value="4">Sin asignar</option>
                                <option value="387">Betzabe Pagaza</option>
                                <option value="386">Bruno Reyes</option>
                                <option value="17">Carlos Mata</option>
                                <option value="379">Estrella Esquivel</option>
                                <option value="385">Martha Brito</option>
                                <option value="92">Omar López</option>
                              </select>
                            </div>
                          </div>
                          <div class="form-row d-flex align-items-center mb-2">
                            <div class="col-4">
                              <label  for="">Inside Sales</label>
                            </div>
                            <div class="col-8">
                              <select id="inside_sales" name="inside_sales" type="text" class="form-control form-control-sm">
                                <option value="4">Sin asignar</option>
                                <option value="79">Jaqueline Ramírez</option>
                                <option value="78">Jessica González</option>
                                <option value="310">Roberto Gómez</option>
                                <option value="56">Paola Ku</option>
                              </select>
                            </div>
                          </div>
                          <div class="form-row d-flex align-items-center mb-2">
                            <div class="col-4">
                              <label  for="">Contácto</label>
                            </div>
                            <div class="col-8">
                              <input id="contacto_comercial" name="contacto_comercial" value="{{ $kickoff_contrato->contacto }}" type="text" class="form-control form-control-sm">
                            </div>
                          </div>
                          <div class="form-row d-flex align-items-center mb-2">
                            <div class="col-4">
                              <label  for="">Cierre</label>
                            </div>
                            <div class="col-8">
                              <input id="cierre" name="cierre" type="text" class="form-control form-control-sm">
                            </div>
                          </div>
                        </div>
                      </div>
                  </div>
                  <!--INSTALACIONES-->
                  <div class="col-12 col-md-6 p-2">
                    <div class="row">
                      <div class="col-12">
                        <h4 class="text-danger text-center">Instalaciones</h4>
                      </div>
                    </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-6">
                          <label for="">Fecha de inicio de instalación</label>
                        </div>
                        <div class="col-6">
                          <input id="fecha_inicio_instalacion" name="fecha_inicio_instalacion" value="{{$kickoff_instalaciones->fecha_inicio}}" type="date" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-6">
                          <label for="">Fecha de término de instalación</label>
                        </div>
                        <div class="col-6">
                          <input id="fecha_termino_instalacion" name="fecha_termino_instalacion" value="{{$kickoff_instalaciones->fecha_termino}}" type="date" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-6">
                          <label for="">Cantidad de Antenas</label>
                        </div>
                        <div class="col-6">
                          <input readonly type="text" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-6">
                          <label for="">Internas</label>
                        </div>
                        <div class="col-6">
                          <input readonly type="text" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-6">
                          <label readonly for="">Externas</label>
                        </div>
                        <div class="col-6">
                          <input readonly type="text" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-6">
                          <label for="">Viaticos / Pago a Proveedor</label>
                        </div>
                        <div class="col-6">
                          <input id="viaticos_proveedor" name="viaticos_proveedor" value="{{$kickoff_instalaciones->viaticos_proveedor}}" type="number" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-6">
                          <label for="">Calidad de contratista</label>
                        </div>
                        <div class="col-6">
                          <input id="calidad_contratista" name="calidad_contratista" value="{{$kickoff_instalaciones->calidad_contratista}}" type="text" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-6">
                          <label for="">Fecha de Mantenimiento / Garantia</label>
                        </div>
                        <div class="col-6">
                          <input id="fecha_mantenimiento" name="fecha_mantenimiento" value="{{$kickoff_instalaciones->fecha_mantenimiento}}" type="date" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-6">
                          <label for="">Fecha de Acta de Entrega de la Instalación</label>
                        </div>
                        <div class="col-6">
                          <input id="fecha_acta_entrega" name="fecha_acta_entrega" value="{{$kickoff_instalaciones->fecha_acta_entrega}}" type="date" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-6">
                          <label for="">Fecha de entrega de Memoria Técnica</label>
                        </div>
                        <div class="col-6">
                          <input id="fecha_entrega_memoria_tecnica" name="fecha_entrega_memoria_tecnica" value="{{$kickoff_instalaciones->fecha_entrega_memoria_tecnica}}" type="date" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-6">
                          <label for="">Lugar de instalación: (locál / Foranea)</label>
                        </div>
                        <div class="col-6">
                          <select class="form-control form-control-sm" id="lugar_instalacion" name="lugar_instalacion">
                            @foreach ($installation as $installation_data)
                              @if($installation_data->id == $document[0]->lugar_instalacion_id)
                                <option selected value="{{$installation_data->id}}">{{$installation_data->name}}</option>
                              @else
                                <option value="{{$installation_data->id}}">{{$installation_data->name}}</option>
                              @endif
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-6">
                          <label for="">Observaciones</label>
                        </div>
                        <div class="col-6">
                          <input id="observaciones" name="observaciones" value="{{$kickoff_instalaciones->observaciones}}"  type="text" class="form-control form-control-sm">
                        </div>
                      </div>
                  </div>
                  <!--SOPORTE-->
                  <div class="col-12 col-md-6 p-2">
                    <div class="row">
                      <div class="col-12">
                        <h4 class="text-danger text-center">Soporte</h4>
                      </div>
                    </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-4">
                          <label for="">Licencias</label>
                        </div>
                        <div class="col-8">
                          <input id="licencias" name="licencias" value="{{$kickoff_soporte->licencias}}" type="text" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-4">
                          <label for="">Proveedor Enlace SITWIFI (Operador)</label>
                        </div>
                        <div class="col-8">
                          <input id="proveedor_enlace" name="proveedor_enlace" value="{{$kickoff_soporte->proveedor_enlace}}" type="text" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-4">
                          <label for="">Importe de Enlace</label>
                        </div>
                        <div class="col-8">
                          <input id="enlace" name="enlace" disabled type="number" class="form-control form-control-sm" value="{{$document[0]->enlace}}">
                        </div>
                      </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-4">
                          <label for="">Plazo del Enlace</label>
                        </div>
                        <div class="col-8">
                          <input id="plazo_enlace" name="plazo_enlace" value="{{$kickoff_soporte->plazo_enlace}}" type="text" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-4">
                          <label for="">Fecha de Mantenimiento / Visitas</label>
                        </div>
                        <div class="col-8">
                          <input id="fecha_mantenimiento_soporte" name="fecha_mantenimiento_soporte" value="{{$kickoff_soporte->fecha_mantenimiento}}" type="text" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-4">
                          <label for="">IT Concierge asignado</label>
                        </div>
                        <div class="col-8">
                          <input id="itconcierge" name="itconcierge" disabled type="text" class="form-control form-control-sm" value="{{$document[0]->ITC}}">
                        </div>
                      </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-4">
                          <label for="">Cantidad de equipos monitoreados</label>
                        </div>
                        <div class="col-8">
                          <input id="cantidad_equipos_monitoriados" name="cantidad_equipos_monitoriados" value="{{$kickoff_soporte->cantidad_equipos_monitoriados}}" type="text" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-4">
                          <label for="">Nombre de TI del Cliente</label>
                        </div>
                        <div class="col-8">
                          <input id="nombre_ti_cliente" name="nombre_ti_cliente" value="{{$kickoff_soporte->nombre_ti_cliente}}" type="text" class="form-control form-control-sm">
                        </div>
                      </div>
                      <!--COMPRAS-->
                      <div class="col-12">
                        <h4 class="text-danger text-center">Compras</h4>
                      </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-6">
                          <label for="">Fecha de entrega de Equipo Activo</label>
                        </div>
                        <div class="col-6">
                          <input id="fecha_entrega_ea" name="fecha_entrega_ea" value="{{$kickoff_compras->fecha_entrega_ea}}" type="date" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-6">
                          <label for="">Tiempo de Entrega de Equipo Activo</label>
                        </div>
                        <div  class="col-6">
                          <input id="fecha_entrega_ena" name="fecha_entrega_ena" value="{{$kickoff_compras->fecha_entrega_ena}}" type="date" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-6">
                          <label for="">Fecha de operación (Enlace)</label>
                        </div>
                        <div class="col-6">
                          <input id="fecha_entrega_operacion_enlace" name="fecha_entrega_operacion_enlace" value="{{$kickoff_compras->fecha_operacion_enlace}}" type="date" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-6">
                          <label for="">Fecha de contratación (Enlace)</label>
                        </div>
                        <div class="col-6">
                          <input id="fecha_contratacion_enlace" name="fecha_contratacion_enlace" value="{{$kickoff_compras->fecha_contratacion_enlace}}" type="date" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="form-row d-flex align-items-center mb-2">
                        <div class="col-12 d-flex justify-content-end">
                          <button class="btn btn-dark mt-3" type="submit" name="button"> Guardar</button>
                        </div>
                      </div>
                    </div>
                  </div><!--row-->
                  <!--PROVEDDORES/INVERSION-->
                  <div class="row">
                    <div class="col-12 col-md-4 p-2">
                        <div class="form-row d-flex align-items-center mb-2">
                          <div class="col text-center bg-blue">
                            <label class="m-0" for="">Proveedores</label>
                          </div>
                        </div>
                        <div class="form-row d-flex align-items-center mb-2">
                          <div class="col-4">
                            <label for="">Proveedor 1</label>
                          </div>
                          <div class="col-8">
                            <input id="proveedor1" name="proveedor1" value="{{$kickoff_compras->proveedor1}}" type="text" class="form-control form-control-sm">
                          </div>
                        </div>
                        <div class="form-row d-flex align-items-center mb-2">
                          <div class="col-4">
                            <label for="">Proveedor 2</label>
                          </div>
                          <div class="col-8">
                            <input id="proveedor2" name="proveedor2" value="{{$kickoff_compras->proveedor2}}" type="text" class="form-control form-control-sm">
                          </div>
                        </div>
                        <div class="form-row d-flex align-items-center mb-2">
                          <div class="col-4">
                            <label for="">Proveedor 3</label>
                          </div>
                          <div class="col-8">
                            <input id="proveedor3" name="proveedor3" value="{{$kickoff_compras->proveedor3}}" type="text" class="form-control form-control-sm">
                          </div>
                        </div>
                        <div class="form-row d-flex align-items-center mb-2">
                          <div class="col-4">
                            <label for="">Proveedor 4</label>
                          </div>
                          <div class="col-8">
                            <input id="proveedor4" name="proveedor4" value="{{$kickoff_compras->proveedor4}}" type="text" class="form-control form-control-sm">
                          </div>
                        </div>
                        <div class="form-row d-flex align-items-center mb-2">
                          <div class="col-4">
                            <label for="">Proveedor 5</label>
                          </div>
                          <div class="col-8">
                            <input id="proveedor5" name="proveedor5" value="{{$kickoff_compras->proveedor5}}" type="text" class="form-control form-control-sm">
                          </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 p-2">
                        <div class="form-row d-flex align-items-center mb-2">
                          <div class="col text-center bg-blue">
                            <label class="m-0" for="">Inversión inicial del contrato USD</label>
                          </div>
                        </div>
                        <div class="form-row d-flex align-items-center mb-2">
                          <div class="col-6">
                            <label for="">Equipo Activo</label>
                          </div>
                          <div class="col-6">
                            <input id="" name="" readonly type="text" class="form-control form-control-sm text-right font-weight-bold" value="${{number_format($document[0]->total_ea, 2, '.', ',')}}">
                          </div>
                        </div>
                        <div class="form-row d-flex align-items-center mb-2">
                          <div class="col-6">
                            <label for="">Equipo No Activo</label>
                          </div>
                          <div class="col-6">
                            <input id="" name="" readonly type="text" class="form-control form-control-sm text-right font-weight-bold" value="${{number_format($document[0]->total_ena, 2, '.', ',')}}">
                          </div>
                        </div>
                        <div class="form-row d-flex align-items-center mb-2">
                          <div class="col-6">
                            <label for="">Mano de obra</label>
                          </div>
                          <div class="col-6">
                            <input type="text" readonly class="form-control form-control-sm text-right font-weight-bold" value="${{number_format($document[0]->total_mo, 2, '.', ',')}}">
                          </div>
                        </div>
                        <div class="form-row d-flex align-items-center mb-2">
                          <div class="col-6">
                            <label for="">Total</label>
                          </div>
                          <div class="col-6">
                            <input type="text" readonly class="form-control form-control-sm text-right font-weight-bold" value="${{number_format($document[0]->total_usd, 2, '.', ',')}}">
                          </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 p-2">
                        <div class="form-row d-flex align-items-center mb-2">
                          <div class="col text-center bg-blue">
                            <label class="m-0" for="">Real Invertido USD</label>
                          </div>
                        </div>
                        <div class="form-row d-flex align-items-center mb-2">
                          <div class="col-6">
                            <label for="">Equipo Activo</label>
                          </div>
                          <div class="col-6">
                            <input id="" name="" readonly type="text" class="form-control form-control-sm text-right font-weight-bold" value="$0.00">
                          </div>
                        </div>
                        <div class="form-row d-flex align-items-center mb-2">
                          <div class="col-6">
                            <label for="">Equipo No Activo</label>
                          </div>
                          <div class="col-6">
                            <input id="" name="" readonly type="text" class="form-control form-control-sm text-right font-weight-bold" value="$0.00">
                          </div>
                        </div>
                        <div class="form-row d-flex align-items-center mb-2">
                          <div class="col-6">
                            <label for="">Mano de obra</label>
                          </div>
                          <div class="col-6">
                            <input type="text" readonly class="form-control form-control-sm text-right font-weight-bold" value="$0.00">
                          </div>
                        </div>
                        <div class="form-row d-flex align-items-center mb-2">
                          <div class="col-6">
                            <label for="">Total</label>
                          </div>
                          <div class="col-6">
                            <input type="text" readonly class="form-control form-control-sm text-right font-weight-bold" value="$0.00">
                          </div>
                        </div>
                        <div class="form-row d-flex align-items-center mb-2">
                          <div class="col-6">
                            <label for="">Diferencia</label>
                          </div>
                          <div class="col-6">
                            <input type="text" readonly class="form-control form-control-sm text-right font-weight-bold" value="$0.00">
                          </div>
                        </div>
                    </div>
                  </div>
                  <!--Firmas-->
                  <div class="row text">
                    <div class="d-block">
                      <div class="form-check form-check-flat form-check-success ml-5">
                        <label class="form-check-label">
                          @if (auth()->user()->can('Aprobacion administracion') && $kickoff_approvals->administracion == 1)
                            <input id="check_administracion" type="checkbox" class="form-check-input" disabled checked>
                          @elseif(auth()->user()->can('Aprobacion administracion') && $kickoff_approvals->administracion == 0)
                            <input id="check_administracion" type="checkbox" class="form-check-input">
                          @endif
                          María  de Jesús Ortíz
                      </div>
                      <div class="text-center">
                        <p class="text-secondary">Dirección de Administración y Finanzas</p>
                        @if ($kickoff_approvals->administracion == 1)
                          <span class="badge badge-success badge-pill text-white">Autorizado</span>
                        @endif
                      </div>
                    </div>
                    <div class="d-block">
                      <div class="form-check form-check-flat form-check-success ml-5">
                        <label class="form-check-label">
                          @if (auth()->user()->can('Aprobacion comercial') && $kickoff_approvals->comercial == 1)
                            <input id="check_comercial" type="checkbox" class="form-check-input" disabled checked>
                          @elseif(auth()->user()->can('Aprobacion comercial') && $kickoff_approvals->comercial == 0)
                            <input id="check_comercial" type="checkbox" class="form-check-input">
                          @endif
                          Carlos Mata
                      </div>
                      <div class="text-center">
                        <p class="text-secondary ml-5">Gerente Comercial</p>
                        @if ($kickoff_approvals->comercial == 1)
                          <span class="badge badge-success badge-pill text-white ml-5">Autorizado</span>
                        @endif
                      </div>
                    </div>
                    <div class="d-block">
                      <div class="form-check form-check-flat form-check-success ml-5">
                        <label class="form-check-label">
                          @if (auth()->user()->can('Aprobacion proyectos') && $kickoff_approvals->proyectos == 1)
                            <input id="check_proyectos" type="checkbox" class="form-check-input" disabled checked>
                          @elseif(auth()->user()->can('Aprobacion proyectos') && $kickoff_approvals->proyectos == 0)
                            <input id="check_proyectos" type="checkbox" class="form-check-input">
                          @endif
                          Aaron Arciga
                      </div>
                      <div class="text-center">
                        <p class="text-secondary ml-5">Gerente Proyectos</p>
                        @if ($kickoff_approvals->proyectos == 1)
                          <span class="badge badge-success badge-pill text-white ml-5">Autorizado</span>
                        @endif
                      </div>
                    </div>
                    <div class="d-block">
                      <div class="form-check form-check-flat form-check-success ml-5">
                        <label class="form-check-label">
                          @if (auth()->user()->can('Aprobacion soporte') && $kickoff_approvals->soporte == 1)
                            <input id="check_soporte" type="checkbox" class="form-check-input" disabled checked>
                          @elseif(auth()->user()->can('Aprobacion soporte') && $kickoff_approvals->soporte == 0)
                            <input id="check_soporte" type="checkbox" class="form-check-input">
                          @endif
                          Ricardo Delgado
                      </div>
                      <div class="text-center">
                        <p class="text-secondary ml-5">Gerente Soporte</p>
                        @if ($kickoff_approvals->soporte == 1)
                          <span class="badge badge-success badge-pill text-white ml-5">Autorizado</span>
                        @endif
                      </div>
                    </div>
                    <div class="d-block">
                      <div class="form-check form-check-flat form-check-success ml-5">
                        <label class="form-check-label">
                          @if (auth()->user()->can('Aprobacion planeacion') && $kickoff_approvals->planeacion == 1)
                            <input id="check_planeacion" type="checkbox" class="form-check-input" disabled checked>
                          @elseif(auth()->user()->can('Aprobacion planeacion') && $kickoff_approvals->planeacion == 0)
                            <input id="check_planeacion" type="checkbox" class="form-check-input">
                          @endif
                          Manuel F. Moreno
                      </div>
                      <div class="text-center">
                        <p class="text-secondary ml-5">Planeación y estrategía</p>
                        @if ($kickoff_approvals->planeacion == 1)
                          <span class="badge badge-success badge-pill text-white ml-5">Autorizado</span>
                        @endif
                      </div>
                    </div>

                  </div>

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
  @if( auth()->user()->can('View Edit Document P') )
    <link href="/plugins/sweetalert-master/dist/sweetalert.css" rel="stylesheet" type="text/css" />
    <script src="/plugins/sweetalert-master/dist/sweetalert-dev.js"></script>
    <script src="{{ asset('/plugins/momentupdate/moment-with-locales.js')}}"></script>
    <link rel="stylesheet" href="{{ asset('bower_components/select2/dist/css/select2.min.css') }}" type="text/css" />
    <script src="{{ asset('bower_components/select2/dist/js/select2.full.min.js') }}" type="text/javascript"></script>

    <link rel="stylesheet" type="text/css" href="{{ asset('css/documentp.css')}}" >
    <!-- FormValidation plugin and the class supports validating Bootstrap form -->
    <script src="{{ asset('plugins/jquery-wizard-master/libs/formvalidation/formValidation.min.js')}}"></script>
    <script src="{{ asset('plugins/jquery-wizard-master/libs/formvalidation/bootstrap.min.js')}}"></script>
    <link type="text/css" href="css/bootstrap-editable.css" rel="stylesheet" />
    <script src="{{ asset('js/bootstrap-editable.js')}}"></script>

    <link rel="stylesheet" type="text/css" href="{{ asset('css/documentp.css')}}" >

  @else
    @include('default.denied')
  @endif
  <script type="text/javascript" src="{{asset('js/admin/quoting/kickoff.js')}}"></script>
  {{-- @if( auth()->user()->can('View level zero documentp notification') )
    <script type="text/javascript" src="{{asset('js/admin/documentp/edit_documentp_itc.js?v=1.0.2')}}"></script>
  @elseif ( auth()->user()->can('View level one documentp notification') )
    <script type="text/javascript" src="{{asset('js/admin/documentp/edit_documentp_comercial.js?v=1.0.2')}}"></script>
  @elseif ( auth()->user()->can('View level two documentp notification') )
    <script type="text/javascript" src="{{asset('js/admin/documentp/edit_documentp_comercial.js?v=1.0.2')}}"></script>
  @elseif ( auth()->user()->can('View level three documentp notification') )
    <script type="text/javascript" src="{{asset('js/admin/documentp/edit_documentp_comercial.js?v=1.0.2')}}"></script>
  @else
    @include('default.denied')
  @endif --}}
  <style media="screen">

  input[type="checkbox"]:disabled {
      color: green !important;
      cursor: none;
  }


    form label{
      margin-bottom: 0 !important;
      font-size: 0.85rem;
      color:
    }

    .form-control-sm{
      height: 2rem !important;
    }

    .bg-blue{
      background: #0686CC;
      color: white;
      font-weight: bold;
    }
  </style>
@endpush
