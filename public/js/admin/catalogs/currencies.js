$(function () {
  get_info_currencies();
  $("#select_one").select2();
  $("#editposition").select2();

  $('#creatcurrencies').formValidation({
   framework: 'bootstrap',
   excluded: ':disabled',
   fields: {
     inputCreatName: {
       validators: {
         notEmpty: {
           message: 'The field is required'
         }
       }
     },
     inputCreatCode: {
       validators: {
         notEmpty: {
           message: 'The field is required'
         }
       }
     },
     inputCreatRate: {
       validators: {
         notEmpty: {
           message: 'The field is required'
         }
       }
     },
     inputCreatDecimal: {
       validators: {
         notEmpty: {
           message: 'The field is required'
         }
       }
     },
     inputCreatSymbol: {
       validators: {
         notEmpty: {
           message: 'The field is required'
         }
       }
     },
     select_one: {
       validators: {
         notEmpty: {
           message: 'The field is required'
         }
       }
     },
     inputCreatMark: {
       validators: {
         notEmpty: {
           message: 'The field is required'
         }
       }
     },
     inputCreatThousands: {
       validators: {
         notEmpty: {
           message: 'The field is required'
         }
       }
     },
     inputCreatOrden: {
       validators: {
         notEmpty: {
           message: 'The field is required'
         }
       }
     },
   }
  })
  .on('success.form.fv', function(e) {
        e.preventDefault();
        var form = $('#creatcurrencies')[0];
        var formData = new FormData(form);
        $.ajax({
          type: "POST",
          url: "/catalogs/currencies-create",
          data: formData,
          contentType: false,
          processData: false,
          success: function (data){
            if (data == 'abort') {
              Swal.fire({
                 type: 'error',
                 title: 'Error encontrado..',
                 text: 'Realice la operacion nuevamente!',
               });
            }
            else if (data == 'false') {
              Swal.fire({
                 type: 'error',
                 title: 'Error encontrado..',
                 text: 'Ya existe!',
               });
            }
            else {
                let timerInterval;
                Swal.fire({
                  type: 'success',
                  title: 'Operación Completada!',
                  html: 'Aplicando los cambios.',
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
                    window.location.href = "/catalogs/currencies";
                  }
                });
            }
          },
          error: function (err) {
            Swal.fire({
               type: 'error',
               title: 'Oops...',
               text: err.statusText,
             });
          }
        });
  });

  $('#editcurrencies').formValidation({
    framework: 'bootstrap',
    excluded: ':disabled',
    fields: {
      inputEditCode: {
        validators: {
          notEmpty: {
            message: 'The field is required'
          }
        }
      },
      inputEditName: {
        validators: {
          notEmpty: {
            message: 'The field is required'
          }
        }
      },
      inputEditRate: {
        validators: {
          notEmpty: {
            message: 'The field is required'
          }
        }
      },
      inputEditDecimal: {
        validators: {
          notEmpty: {
            message: 'The field is required'
          }
        }
      },
      inputEditSymbol: {
        validators: {
          notEmpty: {
            message: 'The field is required'
          }
        }
      },
      edit_select_one: {
        validators: {
          notEmpty: {
            message: 'The field is required'
          }
        }
      },
      inputEditMark: {
        validators: {
          notEmpty: {
            message: 'The field is required'
          }
        }
      },
      inputEditThousands: {
        validators: {
          notEmpty: {
            message: 'The field is required'
          }
        }
      },
      inputEditOrden: {
        validators: {
          notEmpty: {
            message: 'The field is required'
          }
        }
      },
    }
   })
  .on('success.form.fv', function(e) {
      e.preventDefault();
      var form = $('#editcurrencies')[0];
      var formData = new FormData(form);
      $.ajax({
        type: "POST",
        url: "/catalogs/currencies-store",
        data: formData,
        contentType: false,
        processData: false,
        success: function (data){
           console.log(data);
           if (data == 'false') {
             Swal.fire({
                type: 'error',
                title: 'Error encontrado..',
                text: 'La clave ya existe!',
              });
           }
          else if (data == 'abort') {
            Swal.fire({
               type: 'error',
               title: 'Error encontrado..',
               text: 'Realice la operacion nuevamente!',
             });
          }
          else {
              let timerInterval;
              Swal.fire({
                type: 'success',
                title: 'Operación Completada!',
                html: 'Aplicando los cambios.',
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
                  window.location.href = "/catalogs/currencies";
                }
              });
          }
        },
        error: function (err) {
          Swal.fire({
             type: 'error',
             title: 'Oops...',
             text: err.statusText,
           });
        }
      });
  });
});

function get_info_currencies(){
  var _token = $('meta[name="csrf-token"]').attr('content');
  $.ajax({
      type: "POST",
      url: "/catalogs/currencies-show",
      data: { _token : _token },
      success: function (data){
        table_currencies(data, $("#table_currencies"));
      },
      error: function (data) {
        console.log('Error:', data.statusText);
      }
  });
}

function table_currencies(datajson, table){
  table.DataTable().destroy();
  var vartable = table.dataTable(Configuration_table_responsive_currencies);
  vartable.fnClearTable();
  $.each(JSON.parse(datajson), function(index, information){
    var badge = '<span class="badge badge-success badge-pill text-uppercase text-white">Habilitado</span>';
    if (information.status == '0') {
      badge= '<span class="badge badge-danger badge-pill text-uppercase text-white">Inhabilitado</span>';
    }
    vartable.fnAddData([
      information.name,
      information.code,
      information.rate,
      information.decimal_place,
      information.sort_order,
      badge,
      '<a href="javascript:void(0);" onclick="edit_currency(this)" class="btn btn-primary  btn-sm" value="'+information.id+'"><i class="fas fa-pencil-alt btn-icon-prepend fastable"></i></a>'
    ]);
  });
}
//Mostrar - Edit country
function edit_currency(e){
  var valor= e.getAttribute('value');
  var _token = $('meta[name="csrf-token"]').attr('content');
  $.ajax({
       type: "POST",
       url: '/catalogs/currencies-edit',
       data: {value : valor, _token : _token},
       success: function (data) {
         $("#editcurrencies")[0].reset();
         $('#editcurrencies').data('formValidation').resetForm($('#editcurrencies'));

         if (data != []) {
            $('#token_b').val(data[0].id);
            $('#inputEditCode').val(data[0].code);
            $('#inputEditName').val(data[0].name);
            $('#inputEditRate').val(data[0].rate);
            $('#inputEditDecimal').val(data[0].decimal_place);
            $('#inputEditSymbol').val(data[0].symbol);
            $('#inputEditMark').val(data[0].decimal_mark);
            $('#inputEditThousands').val(data[0].thousands_separator);
            $('#inputEditOrden').val(data[0].sort_order);
            $("#editposition").val(data[0].symbol_position).trigger('change');
            if (data[0].status == '0')
            {
              $("#editstatus").prop('checked', false).change();
            }
            else {
              $('#editstatus').prop('checked', true).change();
            }
            $('#modal-Edit').modal('show');
         }
         else {
           Swal.fire({
             type: 'error',
             title: 'Error encontrado..',
             text: 'Realice la operacion nuevamente!',
           });
         }
           //$('#modal-Edit').modal('show');
       },
       error: function (data) {
         alert('Error:', data);
       }
   })
}


var Configuration_table_responsive_currencies = {
  dom: "<'row'<'col-sm-5'B><'col-sm-3'l><'col-sm-4'f>>" +
          "<'row'<'col-sm-12'tr>>" +
          "<'row'<'col-sm-5'i><'col-sm-7'p>>",
    buttons: [
      {
        text: '<i class="fas fa-plus-circle fastable mt-2"></i> Crear nuevo',
        titleAttr: 'Crear nuevo',
        className: 'btn btn-danger btn-sm',
        init: function(api, node, config) {
           $(node).removeClass('btn-secondary')
        },
        action: function ( e, dt, node, config ) {
          $('#modal-CreatNew').modal('show');
          if (document.getElementById("creatcurrencies")) {
            $('#creatcurrencies')[0].reset();
            $('#creatcurrencies').data('formValidation').resetForm($('#creatcurrencies'));
            $('#inputCreatOrden').val(0);
          }
        }
      },
      {
        extend: 'excelHtml5',
        title: 'Moneda',
        init: function(api, node, config) {
           $(node).removeClass('btn-secondary')
        },
        text: '<i class="fas fa-file-excel fastable mt-2"></i> Extraer a Excel',
        titleAttr: 'Excel',
        className: 'btn btn-success btn-sm',
        exportOptions: {
            columns: [ 0, 1, 2, 3]
        },
      },
      {
        extend: 'csvHtml5',
        title: 'Moneda',
        init: function(api, node, config) {
           $(node).removeClass('btn-secondary')
        },
        text: '<i class="fas fa-file-csv fastable mt-2"></i> Extraer a CSV',
        titleAttr: 'CSV',
        className: 'btn btn-primary btn-sm',
        exportOptions: {
            columns: [ 0, 1, 2, 3]
        },
      }
  ],
  "processing": true,
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
};

$(".onlynumber").keypress(function (e) {
  //if the letter is not digit then display error and don't type anything
  if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
    //display error message
    // $("#errmsg").html("Digits Only").show().fadeOut("slow");
    return false;
  }
});
