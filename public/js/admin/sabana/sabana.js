$(function() {
  $(".first_tab").champ();
  $(".select2").select2();
  $("#cliente").on('change', function(e) {
    var _token = $('input[name="_token"]').val();
    var cliente = $('#cliente').val();
    $(".first_tab").addClass("d-none");
    $("#cargando").removeClass("d-none");
    $.ajax({
      type: "POST",
      url: "/informacionCliente",
      data: { cliente : cliente, _token : _token },
      success: function (data){
        //console.log(data);
        $("#imagenCliente").attr("src", "../images/hotel/" + data[0].dirlogo1);
        $("#itcCliente").text(data[1].name + " -> " + data[1].email);
        $("#cuartosCliente").text(data[0].num_hab == null ? "Sin informacion" : data[0].num_hab);
        $("#telefonoCliente").text(data[0].Telefono);
        $("#direccionCliente").text(data[0].Direccion);
        $("#correoCliente").text(data[2].correo == null ? "Sin informacion" : data[2].correo);
        $("#cargando").addClass("d-none");
        $(".first_tab").removeClass("d-none");
      },
      error: function (data) {
        console.log('Error:', data);
      }
    });
    get_contracts(cliente);
    get_info_equipments(cliente);

get_info_equipments(cliente);
get_nps_hotel(cliente);
get_nps_comment(cliente);
get_graph_equipments(cliente);
get_table_budget(cliente);
get_table_tickets(cliente);
get_graph_tickets_type(cliente);
get_graph_tickets_status(cliente);
getFoliosByHotel(cliente);
getViaticsByHotel(cliente);
  });

  function get_contracts(cliente) {
    var _token = $('meta[name="csrf-token"]').attr('content');
    var id = cliente;
    $.ajax({
      type: "POST",
      url: "/get_all_contracts_by_hotel",
      data: { _token : _token, id: id },
      success: function (data){

        table_masters(data, $("#all_contracts"));

      },
      error: function (data) {
        console.log('Error:', data);
      }
    });
  }


  function get_nps_hotel(idcadena){
    var _token = $('meta[name="csrf-token"]').attr('content');
    var id= idcadena;
    //console.log(id);
    $.ajax({
      type: "POST",
      url: "/get_nps_hotel",
      data: { _token : _token, id: id },
      success: function (data){
        //console.log(data);
        //console.log(data[0]['nps']);
        //graph_nps_hotel('main_nps',data[0]['nps']);
        graph_gauge_hotel('main_nps_hotel', 'NPS', '100', '100', data[0]['nps']);
        $('#total_promotores').text(data[0]['pr']);
        $('#total_pasivos').text(data[0]['ps']);
        $('#total_detractores').text(data[0]['d']);
        $('#total_survey').text(data[0]['enviadas']);
        $('#answered').text(data[0]['respondieron']);
        $('#unanswered').text(data[0]['abstenidos']);
      },
      error: function (data) {
        console.log('Error:', data);
      }
    });

  }

  function table_masters(datajson, table){
    table.DataTable().destroy();
    var vartable = table.dataTable(Configuration_table_contracts);
    vartable.fnClearTable();
    $.each(datajson, function(index, status){
    var estado = '';

    switch (status.estatus) {
      case 'Activo':
      estado ='<span class="badge badge-pill text-white bg-success">Activo</span>';
        break;
      case 'Pausado':
        estado ='<span class="badge badge-pill text-white bg-warning">Pausado</span>';
        break;
      case 'Cancelado':
        estado ='<span class="badge badge-pill text-white bg-danger">Cancelado</span>';
        break;
      case 'Terminado':
        estado ='<span class="badge badge-pill text-white bg-dark">Terminado</span>';
        break;
      default:
    }
      vartable.fnAddData([
        status.key,
        status.razon,
        status.cliente,
        status.email,
        status.telephone,
        status.resguardo,
        estado,
        status.xvenc,
        "<button id='verAnexos~"+status.id+"~"+status.key+"' class='verAnexos btn btn-info'><i class='fas fa-file-signature'></i></button>"
      ]);
    });
  }

  function table_annexes(datajson, table){
    table.DataTable().destroy();
    var vartable = table.dataTable(Configuration_table_contracts);
    vartable.fnClearTable();
    $.each(datajson, function(index, status){
      var estado = '';

      switch (status.estatus) {
        case 'Activo':
        estado ='<span class="badge badge-pill text-white bg-success">Activo</span>';
          break;
        case 'Pausado':
          estado ='<span class="badge badge-pill text-white bg-warning">Pausado</span>';
          break;
        case 'Cancelado':
          estado ='<span class="badge badge-pill text-white bg-danger">Cancelado</span>';
          break;
        case 'Terminado':
          estado ='<span class="badge badge-pill text-white bg-dark">Terminado</span>';
          break;
        default:
      }
      vartable.fnAddData([
        status.key,
        status.date_signature,
        status.date_scheduled_start,
        status.date_scheduled_end,
        status.date_real,
        status.pesos,
        status.dolares,
        estado
      ]);
    });
  }

  $(document).on("click", ".verAnexos", function() {
    var cm = $(this)[0].id.split("~");
    $('#anexosModalLabel').text('Anexos del contrato maestro: '+cm[2]);
    var _token = $('meta[name="csrf-token"]').attr('content');
    var id = cm[1];
    $.ajax({
      type: "POST",
      url: "/get_all_annexes_by_master",
      data: { _token : _token, id: id },
      success: function (data){

        //console.log(data);

        //Montos de diferentes filas montados en la misma tabla
        var correctData = [], savedKeys = [];

        data.forEach(function(row) {
          var i = savedKeys.indexOf(row.key);
          if(i < 0) {
            if(row.currency.startsWith("MXN")) {
              row.pesos = row.quantity;
              row.dolares = null;
            } else {
              row.dolares = row.quantity;
              row.pesos = null;
            }
            correctData.push(row);
            savedKeys.push(row.key);
          } else {
            if(row.currency.startsWith("MXN")) {
              correctData[i].pesos = row.quantity;
            } else {
              correctData[i].dolares = row.quantity;
            }
          }
        });

        //console.log(correctData);

        table_annexes(correctData, $("#all_annexes"));

        $('#anexosModal').modal('show');

      },
      error: function (data) {
        console.log('Error:', data);
      }
    });
  });

  function get_nps_comment(idcadena){
    var _token = $('meta[name="csrf-token"]').attr('content');
    var id= idcadena;
    //console.log(id);
    $.ajax({
      type: "POST",
      url: "/get_nps_comment_hotel",
      data: { _token : _token, id: id },
      success: function (data){
        //console.log(data);
        table_comments_hotel(data,$('#nps_comments'));
      },
      error: function (data) {
        console.log('Error:', data);
      }
    });

  }

  function get_info_equipments(cliente) {
    var _token = $('meta[name="csrf-token"]').attr('content');
    var id= cliente;
    $.ajax({
      type: "POST",
      url: "/get_all_equipmentsbyhotel",
      data: { _token : _token, id: id },
      success: function (data){
        $('.divEQ').addClass('tableFixHead');
        table_equipments(data, $("#all_equipments"));

      },
      error: function (data) {
        console.log('Error:', data);
      }
    });

  }

  function get_graph_equipments(idcadena) {
    var _token = $('meta[name="csrf-token"]').attr('content');
    var id= idcadena;
    $.ajax({
      type: "POST",
      url: "/get_graph_equipments",
      data: { _token : _token, id: id },
      success: function (data){
        //$('.divEQ').addClass('tableFixHead');
        //table_equipments(data, $("#all_equipments"));
        //console.log(data);
        graph_equipments('graph_equipments',data,"Clasificación","Tipo de equipo");
      },
      error: function (data) {
        console.log('Error:', data);
      }
    });

  }

  function table_comments_hotel(datajson, table){
    table.DataTable().destroy();
    var vartable = table.dataTable(Configuration_table);
    vartable.fnClearTable();
    //console.log(datajson);
    $.each(datajson, function(index, status){

      var span_calificacion = '';
      full_star ='<span class="fas fa-star text-warning"></span>';
      empty_star='<span class="far fa-star "></span>';
      switch (status.nps ) {
        case 'Pr':
        span_calificacion = full_star.repeat(5);
          break;
        case 'Ps':
          span_calificacion = full_star.repeat(3) + empty_star.repeat(2);
          break;
        case 'D':
            span_calificacion = empty_star.repeat(5);
          break;
        default:

      }
      vartable.fnAddData([
        status.cliente,
        status.sitio,
        span_calificacion,
        status.it,
        status.comentario,
        status.fecha

      ]);
    });
  }

  function table_equipments(datajson, table){
    table.DataTable().destroy();
    var vartable = table.dataTable(Configuration_table_equipments);
    vartable.fnClearTable();
    $.each(datajson, function(index, status){
      var span_identificador = '';
      if (status.estado == '1') { span_identificador = '<span class="badge badge-pill badge-success">Activo en Sitio</span>';}
else  if (status.estado == '2') { span_identificador = '<span class="badge badge-pill badge-danger">Baja</span>';}
else  if (status.estado == '3') { span_identificador = '<span class="badge badge-pill badge-warning text-white">Bodega</span>';}
else  if (status.estado == '4') { span_identificador = '<span class="badge badge-pill badge-dark">Stock</span>';}
else  if (status.estado == '5') { span_identificador = '<span class="badge badge-pill badge-info">Prestamo</span>';}
else  if (status.estado == '6') { span_identificador = '<span class="badge badge-pill badge-primary">Venta</span>';}
else  if (status.estado == '7') { span_identificador = '<span class="badge badge-pill badge-info">Propiedad del Cliente</span>';}
else  if (status.estado == '8') { span_identificador = '<span class="badge badge-pill badge-secondary">Demo</span>';}
else  if (status.estado == '9') { span_identificador = '<span class="badge badge-pill badge-secondary">Asignado [SITWIFI]</span>';}
else  if (status.estado == '10') { span_identificador = '<span class="badge badge-pill badge-danger">Descontinuado</span>';}
      vartable.fnAddData([
        status.tipo,
        status.modelo,
        status.MAC,
        status.Serie,
        status.Descripcion,
        span_identificador,
        status.Fecha_Registro,
        status.Fecha_Baja

      ]);
    });
  }


  function get_table_budget(idcadena){
    var _token = $('meta[name="csrf-token"]').attr('content');
    var id= idcadena;
    $.ajax({
        type: "POST",
        url: "/get_budget_annual_hotel",
        data: { id: id, _token : _token },
        success: function (data){
          //console.log(data);
          generate_table_budget(data, $('#table_budget_site'));
          //document.getElementById("table_budget_wrapper").childNodes[0].setAttribute("class", "form-inline");
        },
        error: function (data) {
          console.log('Error:', data);
        }
    });
  }

  function get_table_tickets(idcadena){
    var _token = $('meta[name="csrf-token"]').attr('content');
    var id= idcadena;
    $.ajax({
        type: "POST",
        url: "/get_tickets_by_hotel",
        data: { id: id, _token : _token },
        success: function (data){
          //console.log(data);
          generate_table_tickets(data, $('#table_tickets_site'));
          //document.getElementById("table_budget_wrapper").childNodes[0].setAttribute("class", "form-inline");
        },
        error: function (data) {
          console.log('Error:', data);
        }
    });
  }

  function get_graph_tickets_type(idcadena){
    var _token = $('meta[name="csrf-token"]').attr('content');
    var id= idcadena;
    $.ajax({
        type: "POST",
        url: "/get_ticketsxtipo_hotel",
        data: { id: id, _token : _token },
        success: function (data){
          //console.log(data);
          graph_tickets_type('graph_type_tickets',data);
          //document.getElementById("table_budget_wrapper").childNodes[0].setAttribute("class", "form-inline");
        },
        error: function (data) {
          console.log('Error:', data);
        }
    });
  }

  function get_graph_tickets_status(idcadena){
    var _token = $('meta[name="csrf-token"]').attr('content');
    var id= idcadena;
    $.ajax({
        type: "POST",
        url: "/get_ticketsxstatus_hotel",
        data: { id: id, _token : _token },
        success: function (data){
          //console.log(data);
          graph_tickets_status('graph_status_tickets',data);
          //document.getElementById("table_budget_wrapper").childNodes[0].setAttribute("class", "form-inline");
        },
        error: function (data) {
          console.log('Error:', data);
        }
    });
  }


function generate_table_budget(datajson, table){
  table.DataTable().destroy();
  var vartable = table.dataTable(Configuration_table);
  vartable.fnClearTable();
  $.each(datajson, function(index, data){
    vartable.fnAddData([
      //data.id,
      //data.Nombre_hotel,
      data.key,
      data.id_ubicacion,
      data.moneda,
      '<span class="">' + data.equipo_activo_monto + '</span>',
      '<span class="">' + data.equipo_no_activo_monto + '</span>',
      '<span class="">' + data.licencias_monto + '</span>',
      '<span class="">' + data.mano_obra_monto + '</span>',
      '<span class="">' + data.enlaces_monto + '</span>',
      '<span class="">' + data.viaticos_monto + '</span>',
      '<a href="javascript:void(0);" onclick="enviar_pres(this)" value="'+data.hotel_id+'" class="btn btn-success btn-sm" role="button" data-target="#modal-concept"><span class="fas fa-eye"></span></a>',
    ]);
  });
}

function generate_table_tickets(datajson, table){
  table.DataTable().destroy();
  var vartable = table.dataTable(Configuration_table);
  vartable.fnClearTable();
  //color: ['#474B4F','#ff5400','#e92e29','#FFDE40','#4dd60d','#00BFF3','#096dc9','#f90000'],
  var type='';
  var status='';
  var priority='';
  $.each(datajson, function(index, data){
    switch (data.type) {
      case 'incident':
      type='<span class="badge badge-pill text-white" style="background-color:#ff5400">incident</span>';
        break;
      case 'problem':
      type='<span class="badge badge-pill text-white" style="background-color:#e92e29">problem</span>';
        break;
      case 'question':
      type='<span class="badge badge-pill text-white" style="background-color:#FFDE40">question</span>';
        break;
      case 'task':
      type='<span class="badge badge-pill text-white" style="background-color:#4dd60d">task</span>';
        break;
      case '':
      type='<span class="badge badge-pill text-white" style="background-color:#474B4F">other</span>';
        break;
    }
      switch (data.status) {
        case 'solved':
        status='<span class="badge badge-pill text-white bg-primary" >solved</span>';
          break;
        case 'open':
        status='<span class="badge badge-pill text-white" style="background-color:#4dd60d">open</span>';
          break;
        case 'closed':
        status='<span class="badge badge-pill text-white" style="background-color:#474B4F">closed</span>';
          break;
      }

      switch (data.priority) {
        case 'high':
        priority='<span class="badge badge-pill text-white" style="background-color:#ff5400">high</span>';
          break;
        case 'urgent':
        priority='<span class="badge badge-pill text-white" style="background-color:#e92e29">urgent</span>';
          break;
        case 'low':
        priority='<span class="badge badge-pill bg-secondary text-white" >low</span>';
          break;
        case 'normal':
        priority='<span class="badge badge-pill text-white bg-primary" >normal</span>';
          break;
        case '':
        priority='<span class="badge badge-pill text-white" style="background-color:#474B4F">not assigned</span>';
          break;
      }

    vartable.fnAddData([
      '<small>'+data.id_ticket+'</small>',
      type,
      '<small>'+data.subject+'</small>',
      status,
      priority,
      '<small>'+data.via_channel+'</small>',
      '<small>'+data.satisfaction_rating+'</small>',
	    '<small>'+ data.via_from_name+'</small>',
      '<small>'+data.created_at+'</small>',
      '<small>'+data.itc+'</small>'
    ]);
  });
}

function getFoliosByHotel(cliente){
  var id = cliente;
  var _token = $('input[name="_token"]').val();
  $.ajax({
    type: "POST",
    url: "/get_payment_folios_gastos",
    data: { id : id, _token : _token },
    success: function (data){
      console.log(data); //Ya tenemos la conversión a pesos de los dólares pero no es exacta

      var dataMXN = [], savedGastosMXN = [], dataUSD = [], savedGastosUSD = [];

      data.forEach(function(row){

        if(row.monto_str.split(" ")[1] == "MXN") {
          var i = savedGastosMXN.indexOf(row.name_cc.toLowerCase().trim());
          if(i < 0) {
            dataMXN.push({
              cantidad: parseFloat(row.monto_str.split(" ")[0].replace(",","")),
              tipo: row.name_cc
            });
            savedGastosMXN.push(row.name_cc.toLowerCase().trim());
          } else {
            dataMXN[i].cantidad += parseFloat(row.monto_str.split(" ")[0].replace(",",""));
          }
        } else {
          var i = savedGastosUSD.indexOf(row.name_cc.toLowerCase().trim());
          if(i < 0) {
            dataUSD.push({
              cantidad: parseFloat(row.monto_str.split(" ")[0].replace(",","")),
              tipo: row.name_cc
            });
            savedGastosUSD.push(row.name_cc.toLowerCase().trim());
          } else {
            dataUSD[i].cantidad += parseFloat(row.monto_str.split(" ")[0].replace(",",""));
          }
        }

      });

      console.log(dataMXN);
      console.log(dataUSD);
      graph_equipments('graph_payments1', dataMXN, "", "PESOS"); //El string PESOS no debe ser cambiado!
      graph_equipments('graph_payments2', dataUSD, "", "DÓLARES"); //El string DÓLARES no debe ser cambiado!
      payments_table(data, $("#table_pays"));
    },
    error: function (data) {
      console.log('Error:', data);
    }
  });
}

function getViaticsByHotel(cliente){
  var id = cliente;
  var _token = $('input[name="_token"]').val();
  $.ajax({
    type: "POST",
    url: "/get_viatics_gastos",
    data: { id : id, _token : _token },
    success: function (data){
      //console.log(data);

      var data2 = [], savedGastos = [];

      data.forEach(function(row){
        var i = savedGastos.indexOf(row.name.toLowerCase().trim());
        if(i < 0) {
          data2.push({
            cantidad: row.aprobado,
            tipo: row.name
          });
          savedGastos.push(row.name.toLowerCase().trim());
        } else {
          data2[i].cantidad += row.aprobado;
        }
      });

      //console.log(data2);
      graph_equipments('graph_viatics', data2, "", "PAGADOS"); //El string PAGADOS no debe ser cambiado!
      viatics_table(data, $("#table_viatics"));
    },
    error: function (data) {
      console.log('Error:', data);
    }
  });
}

function viatics_table(datajson, table){
  table.DataTable().destroy();
  var vartable = table.dataTable(Configuration_table_contracts);
  vartable.fnClearTable();
  $.each(datajson, function(index, status){
  vartable.fnAddData([
    status.folio,
    status.name,
    status.date_start,
    status.date_end,
    "$" + status.solicitado.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + " MXN",
    "$" + status.aprobado.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + " MXN",
    '<span class="badge  badge-pill badge-success">'+status.estado+'</span>',
    status.usuario,
    '<a href="javascript:void(0);" onclick="enviar_via(this)" value="'+status.id+'" class="btn btn-default btn-sm" role="button"><span class="far fa-edit"></span></a>',
    ]);
  });
}

function payments_table(datajson, table){
  table.DataTable().destroy();
  var vartable = table.dataTable(Configuration_table_contracts);
  vartable.fnClearTable();
  //console.log(datajson);
  $.each(datajson, function(index, value){
    vartable.fnAddData([
      value.factura,
      value.proveedor,
      '<span class="badge badge-pill  badge-success">'+value.estatus+'</span>',
      value.monto_str,
      value.elaboro,
      value.fecha_solicitud,
      value.fecha_limite,
      value.key_cc,
      value.name_cc,
      '<a href="javascript:void(0);" onclick="enviar(this)" value="'+value.id+'" class="btn btn-default btn-sm" role="button"><i class="far fa-edit" aria-hidden="true"></i></a>',
      ]);
  });
  $('.no_aprobar_en_gastos').addClass("d-none");
}

  var Configuration_table = {
    "order": [[ 0, "asc" ]],
    paging: true,
    //"pagingType": "simple",
    Filter: true,
    searching: true,
    ordering: false,
    "select": false,
    "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
    dom:"<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
         "<'row'<'col-sm-12'tr>>" +
         "<'row'<'col-sm-12 col-md-4'i><'col-sm-12 col-md-12'p>>",
    buttons: [],
    bInfo: true,
    language:{
      "sProcessing":     "Procesando...",
      "sLengthMenu":     "Mostrar _MENU_ registros",
      "sZeroRecords":    "No se encontraron resultados",
      "sEmptyTable":     "Ningún dato disponible",
      "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
      "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
      "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
      "sInfoPostFix":    "",
      "sSearch":         "<i class='fa fa-search'></i> Buscar:",
      "sUrl":            "",
      "sInfoThousands":  ",",
      "sLoadingRecords": "Cargando...",
        "oPaginate": {
          "sFirst":    "Primero",
          "sLast":     "Último",
          "sNext":     "Siguiente",
          "sPrevious": "Anterior"
      },
      "oAria": {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      }
    }
  }

  var Configuration_table_equipments = {
    "order": [[ 0, "asc" ]],
    paging: true,
    //"pagingType": "simple",
    Filter: true,
    searching: true,
    ordering: true,
    "select": false,
    "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
    "columnDefs": [
                { //Subida 1
                    "targets": 0,
                    "width": "1%",
                    "className": "text-center",
                },
                {
                    "targets": 1,
                    "width": "1%",
                    "className": "text-center",
                },
                {
                    "targets": 2,
                    "width": "3%",
                    "className": "text-center",
                },
                {
                    "targets": 3,
                    "width": "1%",
                    "className": "text-center",
                },
                {
                    "targets": 4,
                    "width": "1%",
                    "className": "text-center",
                },
                {
                    "targets": 5,
                    "width": "0.2%",
                    "className": "text-center",
                },
                {
                    "targets": 6,
                    "width": "0.2%",
                    "className": "text-center",
                },
                {
                    "targets": 7,
                    "width": "0.2%",
                    "className": "text-center",
                }
            ],
            "select": {
                'style': 'multi',
            },
    // "columnDefs": [
    //     {
    //       "targets": 0,
    //       "width": "1%",
    //     },
    // ],
    // "select": {
    //     'style': 'multi',
    // },
    //ordering: false,
    //"pageLength": 5,
    dom:"<'row'<'col-sm-6'B><'col-sm-2'l><'col-sm-4'f>>" +
          "<'row'<'col-sm-12'tr>>" +
          "<'row'<'col-sm-5'i><'col-sm-7'p>>",

    bInfo: true,
    buttons: [
      {
        extend: 'excelHtml5',
        text: '<i class="fas fa-file-excel"></i> Excel',
        titleAttr: 'Excel',
        title: function ( e, dt, node, config ) {
          return 'Reporte de equipos.';
        },
        init: function(api, node, config) {
           $(node).removeClass('btn-default')
        },
        exportOptions: {
            columns: [ 0,1,2,3,4,5,6,7],
            modifier: {
                page: 'all',
            }
        },
        className: 'btn btn-sm btn-success',
      },
      {
        extend: 'csvHtml5',
        text: '<i class="fas fa-file-csv"></i> CSV',
        titleAttr: 'CSV',
        title: function ( e, dt, node, config ) {
          return 'Reporte de equipos.';
        },
        init: function(api, node, config) {
           $(node).removeClass('btn-default')
        },
        exportOptions: {
            columns: [ 0,1,2,3,4,5,6,7],
            modifier: {
                page: 'all',
            }
        },
        className: 'btn btn-sm btn-info',
      },
      {
        extend: 'pdf',
        text: '<i class="fas fa-file-pdf"></i>  PDF',
        title: function ( e, dt, node, config ) {
          return 'Reporte de equipos.';
        },
        init: function(api, node, config) {
           $(node).removeClass('btn-default')
        },
        exportOptions: {
            columns: [ 0,1,2,3,4,5,6,7],
            modifier: {
                page: 'all',
            }
        },
        className: 'btn btn-sm btn-danger',
      },
  /*    {
        extend: 'print',
        text: '<i class="fas fa-file"></i>  PRINT',
        title: function ( e, dt, node, config ) {
          return 'Reporte de equipos.';
        },
        init: function(api, node, config) {
           $(node).removeClass('btn-default')
        },
        exportOptions: {
            columns: [ 0,1,2,3,4,5,6,7],
            modifier: {
                page: 'all',
            }
        },
        className: 'btn btn-sm btn-warning',
      }*/
    ],
    language:{
      "sProcessing":     "Procesando...",
      "sLengthMenu":     "Mostrar _MENU_ registros",
      "sZeroRecords":    "No se encontraron resultados",
      "sEmptyTable":     "Ningún dato disponible",
      "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
      "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
      "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
      "sInfoPostFix":    "",
      "sSearch":         "<i class='fa fa-search'></i> Buscar:",
      "sUrl":            "",
      "sInfoThousands":  ",",
      "sLoadingRecords": "Cargando...",
        "oPaginate": {
          "sFirst":    "Primero",
          "sLast":     "Último",
          "sNext":     "Siguiente",
          "sPrevious": "Anterior"
      },
      "oAria": {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      }
    }
  }

  function graph_gauge_hotel(title, grapname, valuemin, valuemax, valor) {
    //$('#'+title).width($('#'+title).width());
    //$('#'+title).height($('#'+title).height());

   var chart = document.getElementById(title);
    var resizeMainContainer = function () {
     chart.style.width = 320+'px';
     chart.style.height = 320+'px';
   };
    resizeMainContainer();
      var myChart = echarts.init(chart);
    var option = {
      tooltip : {
          formatter: "{a} <br/>{b} : {c}"
      },
      grid: {
          show : true,
          containLabel: false,
          backgroundColor: 'transparent',
          borderColor: '#ccc',
          borderWidth: 0,
          top: -10,
          x: 5,
          y: 0,
          x2: 5,
          y2: 0,
      },
      toolbox: {
          feature: {
            restore : {show: false, title : 'Recargar'},
            saveAsImage : {show: false , title : 'Guardar'}
          }
      },
      series: [
          {
              name: grapname,
              type: 'gauge',
              splitNumber: 20,
              min: -valuemin,
              max: valuemax,
              detail: {formatter:'{value}'},
              data: [{value: valor, name: grapname}],
              axisLine: {            // 坐标轴线
                  lineStyle: {       // 属性lineStyle控制线条样式
                      color: [[0.85, '#f0120a'],[0.90, '#f5e60c'],[1, '#0fe81e']],
                      width: 30
                  }
              },
              axisLabel: {           // 坐标轴文本标签，详见axis.axisLabel
                  textStyle: {       // 其余属性默认使用全局文本样式，详见TEXTSTYLE
                      color: 'auto',
                      fontSize: 10,

                  }
              },
          }
      ]
    };

    myChart.setOption(option);

    $(window).on('resize', function(){
        if(myChart != null && myChart != undefined){


           //chart.style.width = 100+'%';
           //chart.style.height = 100+'%';
           chart.style.width = $(window).width()*0.5;
           chart.style.height = $(window).width()*0.5;
            myChart.resize();

        }
    });
  }


function graph_equipments(title,data,text,subtext) {
  //$('#'+title).width($('#'+title).width());
  //$('#'+title).height($('#'+title).height());

 var chart = document.getElementById(title);
  var resizeMainContainer = function () {
   chart.style.width = 720+'px';
   chart.style.height = 320+'px';
 };
  resizeMainContainer();
    var myChart = echarts.init(chart);
    var group=[];
    var i=0;

    data.forEach(function(element){
    if(subtext == "PAGADOS" || subtext == "PESOS") group[i] = {value:element.cantidad,name: element.tipo+' ($'+element.cantidad.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+' MXN)'};
    else if(subtext == "DÓLARES") group[i] = {value:element.cantidad,name: element.tipo+' ($'+element.cantidad.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+' USD)'};
    else group[i] = {value:element.cantidad,name: element.tipo+' ('+element.cantidad+')'};
    i++;
    });

    option = {
        title : {
            text: text,
            subtext: subtext,
            x:'center'
        },
         //color: ['#AD50D0','#00EEB1','#00CAE5','#DB3841','#D87DAF','#2B4078','#AD50D0','#AD50D0'],
         color: ['#00BFF3','#EF5BA1','#FFDE40','#474B4F','#ff5400','#4dd60d','#096dc9','#f90000'],
        tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        legend: {
            orient: 'vertical',
            left: 'right',

        },
        series : [
            {
                name: subtext,
                type: 'pie',
                radius : '55%',
                center: ['50%', '60%'],
                data:group,
                itemStyle: {
                    emphasis: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }
        ]
    };


  myChart.setOption(option);

  $(window).on('resize', function(){
      if(myChart != null && myChart != undefined){
         //chart.style.width = 100+'%';
         //chart.style.height = 100+'%';
         chart.style.width = $(window).width()*0.5;
         chart.style.height = $(window).width()*0.5;
          myChart.resize();

      }
  });
}


function graph_tickets_type(title,data) {
  //$('#'+title).width($('#'+title).width());
  //$('#'+title).height($('#'+title).height());

 var chart = document.getElementById(title);
  var resizeMainContainer = function () {
   chart.style.width = 420+'px';
   chart.style.height = 320+'px';
 };
  resizeMainContainer();
    var myChart = echarts.init(chart);
    var group=[];
    var titles=[];
    var i=0;

    data.forEach(function(element){
    element.type=="" ?  element.type="other": element.type;
    group[i] ={name: element.type,type: 'bar',label: {normal: {show: true,position: 'inside'}},data: [element.cantidad]};
    titles[i] = element.type;
    i++;
    });
    //console.log(titles);
    //console.log(group);


    option = {
        title: {
            text: 'Tickets por tipo',
            x:'center'
        },
        legend: {
            data: titles,
            orient: 'vertical',
            right: -10,
            top: 40,
            bottom: 20,

        },
        //color: ['#00BFF3','#EF5BA1','#FFDE40','#474B4F','#ff5400','#4dd60d','#096dc9','#f90000'],
        color: ['#474B4F','#ff5400','#e92e29','#FFDE40','#4dd60d','#00BFF3','#096dc9','#f90000'],
        toolbox: {},
        tooltip: {},
        xAxis: {type: 'category'},
        yAxis: {},
        series:group,
    };


  myChart.setOption(option);

  $(window).on('resize', function(){
      if(myChart != null && myChart != undefined){
         //chart.style.width = 100+'%';
         //chart.style.height = 100+'%';
         chart.style.width = $(window).width()*0.5;
         chart.style.height = $(window).width()*0.5;
          myChart.resize();

      }
  });
}

function graph_tickets_status(title,data) {
  //$('#'+title).width($('#'+title).width());
  //$('#'+title).height($('#'+title).height());

 var chart = document.getElementById(title);
  var resizeMainContainer = function () {
   chart.style.width = 420+'px';
   chart.style.height = 320+'px';
 };
  resizeMainContainer();
    var myChart = echarts.init(chart);
    var group=[];
    var i=0;

    data.forEach(function(element){
    group[i] = {value:element.cantidad,name: element.status+' ('+element.cantidad+')'};
    i++;
    });

    /*option = {
        title : {
            text: 'Clasificación',
            subtext: 'Tipo de equipo',
            x:'center'
        },
         //color: ['#AD50D0','#00EEB1','#00CAE5','#DB3841','#D87DAF','#2B4078','#AD50D0','#AD50D0'],
         color: ['#00BFF3','#EF5BA1','#FFDE40','#474B4F','#ff5400','#4dd60d','#096dc9','#f90000'],
        tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        legend: {
            orient: 'vertical',
            left: 'right',

        },
        series : [
            {
                name: 'Tipo de equipo',
                type: 'pie',
                radius : '55%',
                center: ['50%', '60%'],
                data:group,
                itemStyle: {
                    emphasis: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }
        ]
    }; */

    option = {
      title : {
          text: 'Tickets por estado',
          x:'center'
      },
        tooltip: {
            trigger: 'item',
            formatter: "{a} <br/>{b}: {c} ({d}%)"
        },
        legend: {
            orient: 'vertical',
            x: 'right',
            //data:['1','2','3','4','5']
        },
        color: ['#00BFF3','#EF5BA1','#FFDE40','#474B4F','#ff5400','#4dd60d','#096dc9','#f90000'],
        series: [
            {
                name:'Estado',
                type:'pie',
                radius: ['50%', '70%'],
                avoidLabelOverlap: false,
                label: {
                    normal: {
                        show: false,
                        position: 'center'
                    },
                    emphasis: {
                        show: true,
                        textStyle: {
                            fontSize: '25',
                            fontWeight: 'bold'
                        }
                    }
                },
                labelLine: {
                    normal: {
                        show: false
                    }
                },
                data:group
            }
        ]
    };


  myChart.setOption(option);

  $(window).on('resize', function(){
      if(myChart != null && myChart != undefined){
         //chart.style.width = 100+'%';
         //chart.style.height = 100+'%';
         chart.style.width = $(window).width()*0.5;
         chart.style.height = $(window).width()*0.5;
          myChart.resize();

      }
  });
}

  var Configuration_table_contracts = {
    "order": [[ 0, "asc" ]],
    paging: true,
    //"pagingType": "simple",
    Filter: true,
    searching: true,
    ordering: true,
    "select": false,
    "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
    // "columnDefs": [
    //     {
    //       "targets": 0,
    //       "width": "1%",
    //     },
    // ],
    // "select": {
    //     'style': 'multi',
    // },
    //ordering: false,
    //"pageLength": 5,
    dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
         "<'row'<'col-sm-12'tr>>" +
         "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

    bInfo: true,
    "createdRow": function ( row, data, index ) {

  },
    "footerCallback": function(row, data, start, end, display){

    },
    language:{
      "sProcessing":     "Procesando...",
      "sLengthMenu":     "Mostrar _MENU_ registros",
      "sZeroRecords":    "No se encontraron resultados",
      "sEmptyTable":     "Ningún dato disponible",
      "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
      "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
      "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
      "sInfoPostFix":    "",
      "sSearch":         "<i class='fa fa-search'></i> Buscar:",
      "sUrl":            "",
      "sInfoThousands":  ",",
      "sLoadingRecords": "Cargando...",
        "oPaginate": {
          "sFirst":    "Primero",
          "sLast":     "Último",
          "sNext":     "Siguiente",
          "sPrevious": "Anterior"
      },
      "oAria": {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      }
    }
  }

});


function enviar_pres(e) {
  var valor= e.getAttribute('value');
  $('#id_annex').val(valor);
  $('#modal-view-presupuesto').modal('show');
  $('.modal-title').text('Presupuesto');
  // $('#modal-view-presupuesto').modal('hide');
  get_table_estimation(valor);
  //console.log(valor);
}

function get_table_estimation(id_anexo){
  //console.log(id_anexo);
  var _token = $('meta[name="csrf-token"]').attr('content');
  const headers = new Headers({
    "Accept": "application/json",
    "X-Requested-With": "XMLHttpRequest",
    "X-CSRF-TOKEN": _token
  });
  //var id_anexo = $('#id_annex').val();
  var init = { method: 'get',
               headers: headers,
               credentials: "same-origin",
               cache: 'default' };
  if(id_anexo != null && id_anexo != undefined){
    fetch(`/estimation_site_table/id_anexo/${id_anexo}`, init)
      .then(response => {
        return response.text();
      })
      .then(data => {
        $('#presupuesto_anual').html('');
        $('#presupuesto_anual').html(data);
      })
      .catch(error => {
        console.log(error);
      })
  }
}

$('.closeModal').on('click', function(){
  $('#tpgeneral').val('');
  $('#date_to_search_tc').val('');
  $('#presupuesto_anual').html('');
});

function enviar_via(e){
  var valor= e.getAttribute('value');
  var _token = $('input[name="_token"]').val();
  var fecha = $('#date_to_search').val();
  $('.no_aprobar_en_gastos').addClass("d-none");
  cabecera_viatic(valor, _token);
  cuerpo_viatic(valor, _token);
  timeline(valor, _token);
  $('#obj').val(valor);
  // totales_concept_zsa(valor, _token);
  $('#modal-view-viatics').modal('show');
}
// Exportacion del pdf
$('.btn-export').on('click', function(){
    $("#captura_table_general").hide();
    $(".hojitha").css("border", "");
    html2canvas(document.getElementById("captura_pdf_general")).then(function(canvas) {
      var ctx = canvas.getContext('2d');
      ctx.rect(0, 0, canvas.width, canvas.height);
          var imgData = canvas.toDataURL("image/jpeg", 1.0);
          var correccion_landscape = 0;
          var correccion_portrait = 0;
          if(canvas.height > canvas.width) {
              var orientation = 'portrait';
              correccion_portrait = 1;
              correccion_landscape = 0;
              var imageratio = canvas.height/canvas.width;
          }
          else {
              var orientation = 'landscape';
              correccion_landscape = 0;
              correccion_portrait = 0;
              var imageratio = canvas.width/canvas.height;
          }
          if(canvas.height < 900) {
              fontsize = 16;
          }
          else if(canvas.height < 2300) {
              fontsize = 11;
          }
          else {
              fontsize = 6;
          }

          var margen = 0;//pulgadas

          // console.log(canvas.width);
          // console.log(canvas.height);

         var pdf  = new jsPDF({
                      orientation: orientation,
                      unit: 'in',
                      format: [16+correccion_portrait, (16/imageratio)+margen+correccion_landscape]
                    });

          var widthpdf = pdf.internal.pageSize.width;
          var heightpdf = pdf.internal.pageSize.height;
          pdf.addImage(imgData, 'JPEG', 0, margen, widthpdf, heightpdf-margen);
          pdf.save("Solicitud de viaticos.pdf");
          $(".hojitha").css("border", "1px solid #ccc");
          $(".hojitha").css("border-bottom-style", "hidden");
    });
});