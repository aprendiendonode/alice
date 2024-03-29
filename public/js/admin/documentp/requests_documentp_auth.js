$(function() {
  moment.locale('es');
  $('#date_to_search').datepicker({
    language: 'es',
    format: "yyyy-mm",
    viewMode: "months",
    minViewMode: "months",
    endDate: '1m',
    autoclose: true,
    clearBtn: true
  });
  $('#date_to_search').val('').datepicker('update');
  table_permission_zero();

});

$("#boton-aplica-filtro").click(function(event) {
  table_permission_zero();
});

function table_permission_zero() {
  var objData = $('#search_info').find("select,textarea, input").serialize();
  $.ajax({
      type: "POST",
      url: "/get_documentp_auth",
      data: objData,
      success: function (data){
        documentp_table(data, $("#table_documentp"));
      },
      error: function (data) {
        console.log('Error:', data);
      }
  });
}

function documentp_table(datajson, table){
  table.DataTable().destroy();
  var vartable = table.dataTable(Configuration_table_responsive_documentp);
  vartable.fnClearTable();
  $.each(datajson, function(index, data){
    let type_doc = '';
    if(data.doc_type == 1){
      type_doc = 'P';
    }else{
      type_doc = 'M';
    }
  vartable.fnAddData([
    data.id,
    data.fecha,
    data.nombre_proyecto,
    '$' + data.total_ea.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),
    '$' + data.total_ena.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),
    '$' + data.total_mo.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),
    data.elaboro,
    '<span class="badge badge-success badge-pill">'+data.status+'</span>',
    data.num_edit,
    parseInt(data.porcentaje_compra) + '%',
    data.atraso,
    type_doc,
    data.prioridad,
    `<div class="btn-group">
        <button id="btnGroupDrop1" type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-ellipsis-h"></i>
        </button>
        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
            <a class="dropdown-item" href="javascript:void(0);" onclick="editar(this)" data-id="${data.id}" data-cart="${data.documentp_cart_id}" value="${data.id}"><span class="fa fa-edit"></span> Editar</a>
            <a class="dropdown-item" href="javascript:void(0);" onclick="enviar(this)" data-id="${data.id}"  data-cart="${data.documentp_cart_id}" value="${data.id}"><i class="fas fa-shopping-cart"></i> Ver productos</a>
            <a class="dropdown-item" target="_blank" href="/documentp_invoice/${data.id}/${data.documentp_cart_id}"><span class="far fa-file-pdf"></span> Imprimir productos</a>
        </div>
    </div>`,
    data.status,
    ]);
  });
}
var Configuration_table_responsive_documentp= {
        "order": [[ 0, "desc" ]],
        "select": true,
        "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "columnDefs": [
          {
              "targets": 0,
              "checkboxes": {
                'selectRow': true
              },
              "width": "0.1%",
              "createdCell": function (td, cellData, rowData, row, col){
                if ( cellData > 0 ) {
                  if(rowData[14] != 'Autorizado'){
                    this.api().cell(td).checkboxes.disable();
                  }
                }
              },
              "className": "text-center",
          },
            {
              "targets": 1,
              "width": "0.5%",
              "className": "text-center cell-name",
            },
            {
              "targets": 2,
              "width": "1.2%",
              "className": "text-center cell-name",
            },
            {
              "targets": 3,
              "width": "0.5%",
              "className": "text-right cell-price",
            },
            {
              "targets": 4,
              "width": "0.5%",
              "className": "text-right cell-price",
            },
            {
              "targets": 5,
              "width": "0.3%",
              "className": "text-right cell-price",
            },
            {
              "targets": 6,
              "width": "1%",
              "className": "text-center cell-name",
            },
            {
              "targets": 7,
              "width": "0.1%",
              "className": "text-center",
            },
            {
              "targets": 8,
              "width": "0.1%",
              "className": "text-center",
              "visible": false
            },
            {
              "targets": 9,
              "width": "0.2%",
              "className": "text-center cell-short",
            },
            {
              "targets": 10,
              "width": "0.2%",
              "className": "text-center cell-short",
            },
            {
              "targets": 11,
              "width": "0.3%",
              "className": "text-center cell-short",
            },
            {
              "targets": 12,
              "width": "0.3%",
              "className": "text-center cell-short",
            },
            {
              "targets": 13,
              "width": "2%",
              "className": "text-center",
            },
            {
              "targets": 14,
              "visible": false,
              "searchable": false
            }
        ],
        dom: "<'row'<'col-sm-4'B><'col-sm-4'l><'col-sm-4'f>>" +
              "<'row'<'col-sm-12'tr>>" +
              "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        buttons: [
          {
            text: ' Entregar Marcados',
            titleAttr: 'Entregar marcados',
            className: '',
            init: function(api, node, config) {
              $(node).removeClass('btn-default')
            },
            action: function ( e, dt, node, config ) {
              Swal.fire({
              title: '¿Estás seguro?',
              text: "Se entregarán todos los documentos seleccionados!",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Confirmar',
              cancelButtonText: 'Cancelar'
              }).then((result) => {

                if (result.value) {
                  $('.cancel').prop('disabled', 'disabled');
                  $('.confirm').prop('disabled', 'disabled');
                  var rows_selected = $("#table_documentp").DataTable().column(0).checkboxes.selected();
                  var _token = $('input[name="_token"]').val();
                  // Iterate over all selected checkboxes
                  var valores= new Array();
                  $.each(rows_selected, function(index, rowId){
                    valores.push(rowId);
                  });
                  if ( valores.length === 0){
                    Swal.fire(
                      'Debe selecionar al menos un documento',
                      'Ningun documento afectado!',
                      'error'
                    )
                  }else{
                    $.ajax({
                      type: "POST",
                      url: "/send_item_doc_delivery",
                      data: { idents: JSON.stringify(valores), _token : _token },
                      success: function (data){
                        if (data === 'true') {
                          Swal.fire(
                            'Estatus actualizado!',
                            'Los documentos han sido entregados!',
                            'success'
                          )
                          table_permission_zero();
                        }
                        if (data === 'false') {
                          Swal.fire(
                            'Ocurrio un error!',
                            'Ningun documento afectado!',
                            'error'
                          )
                        }
                      },
                      error: function (data) {
                        Swal.fire({
                           type: 'error',
                           title: 'Oops...',
                           text: err.statusText,
                         });
                      }
                    });
                  }
                }//value
              })

            },
           },
          {
            extend: 'excelHtml5',
            text: '<i class="fas fa-file-excel"></i> Excel',
            titleAttr: 'Excel',
            title: function ( e, dt, node, config ) {
              var ax = '';
              if($('input[name="date_to_search"]').val() != ''){
                ax= '- Periodo: ' + $('input[name="date_to_search"]').val();
              }
              else {
                txx='- Periodo: ';
                var fecha = new Date();
                var ano = fecha.getFullYear();
                var mes = fecha.getMonth()+1;
                var fechita = ano+'-'+mes;
                ax = txx+fechita;
              }
              return 'Documento P '+ax;
            },
            init: function(api, node, config) {
               $(node).removeClass('btn-default')
            },
            exportOptions: {
                columns: [ 1,2,3,4,5,6,7,8,9,10,11,12 ],
                modifier: {
                    page: 'all',
                }
            },
            className: 'btn btn-success',
          },
          {
            extend: 'csvHtml5',
            text: '<i class="fas fa-file-csv"></i> CSV',
            titleAttr: 'CSV',
            title: function ( e, dt, node, config ) {
              var ax = '';
              if($('input[name="date_to_search"]').val() != ''){
                ax= '- Periodo: ' + $('input[name="date_to_search"]').val();
              }
              else {
                txx='- Periodo: ';
                var fecha = new Date();
                var ano = fecha.getFullYear();
                var mes = fecha.getMonth()+1;
                var fechita = ano+'-'+mes;
                ax = txx+fechita;
              }
              return 'Documento P '+ax;
            },
            init: function(api, node, config) {
               $(node).removeClass('btn-default')
            },
            exportOptions: {
                columns: [ 1,2,3,4,5,6,7,8,9,10,11,12 ],
                modifier: {
                    page: 'all',
                }
            },
            className: 'btn btn-info',
          },
          {
            extend: 'pdf',
            orientation: 'landscape',
            text: '<i class="fas fa-file-pdf"></i>  PDF',
            title: function ( e, dt, node, config ) {
              var ax = '';
              if($('input[name="date_to_search"]').val() != ''){
                ax= '- Periodo: ' + $('input[name="date_to_search"]').val();
              }
              else {
                txx='- Periodo: ';
                var fecha = new Date();
                var ano = fecha.getFullYear();
                var mes = fecha.getMonth()+1;
                var fechita = ano+'-'+mes;
                ax = txx+fechita;
              }
              return 'Documento P '+ ax;
            },
            init: function(api, node, config) {
               $(node).removeClass('btn-default')
            },
            exportOptions: {
                columns: [ 1,2,3,4,5,6,7,8,9,10,11,12 ],
                modifier: {
                    page: 'all',
                }
            },
            className: 'btn btn-danger',
          }
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
            },
            'select': {
                'rows': {
                    _: "%d Filas seleccionadas",
                    0: "Haga clic en una fila para seleccionarla",
                    1: "Fila seleccionada 1"
                }
            }
        },
    };
