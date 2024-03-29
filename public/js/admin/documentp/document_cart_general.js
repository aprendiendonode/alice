$(function () {
  var _token = $('input[name="_token"]').val();
  const headers = new Headers({
    "Accept": "application/json",
    "X-Requested-With": "XMLHttpRequest",
    "X-CSRF-TOKEN": _token
  })

  var miInit = { method: 'get',
                    headers: headers,
                    credentials: "same-origin",
                    cache: 'default' };

  $('#date').datepicker({
    format: 'dd/mm/yyyy'
  })

  $('#grupo_id').select2();
  $('#anexo_id').select2();
  $('#itc').select2();
  //Oculto los campos para dicumento M por default
  $(".fields_docm").css('display', 'none');

  $('#grupo_id').on('change', function(){
    var id_cadena = $(this).val();
    var datax;

    $.ajax({
      type: "POST",
      url: "/get_hotel_cadena_doc",
      data: { data_one : id_cadena, _token : _token },
      success: function (data){
        datax = JSON.parse(data);
        if ($.trim(data)){
          $('#anexo_id').empty();
          $.each(datax, function(i, item) {
              $('#anexo_id').append("<option value="+item.id+">"+item.Nombre_hotel+"</option>");
          });
          get_vertical_anexo();
          get_table_estimation();
        }
        else{
          $("#anexo_id").text('');
        }
      },
      error: function (data) {
        console.log('Error:', data);
      }
    });
  })

  $('#delete_cart').on('click', function(){
    Swal.fire({
      title: '¿Estas seguro?',
      text: "Se borraran todos los productos del carrito.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Continuar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.value) {
        localStorage.clear();
        Swal.fire(
          'Productos eliminados!',
          '',
          'success'
        )
      }
    })
  });

  function getTypeMaterial(material){
    fetch(`/getTypeMaterial/material/${material}`,  miInit)
      .then(function(response){
        return response.json();
      })
      .then(function(data){
        $('#tipo_material').empty();
        $.each(data, function(i, key) {
          $('#tipo_material').append("<option value="+key.id+">"+key.name+"</option>");
      });
      })
      .catch(function(error){
        console.log(error);
      })
  } 

  $('#anexo_id').on('change', function(){
    get_table_estimation();
  });

  $('#lugar_instalacion').on('change', function(){
    check_installation_site();
  });

  function check_installation_site()
  {
    let instalacion = document.getElementById('lugar_instalacion').value;
    if(instalacion == 2){
      $("#div_button_viatic").removeClass('d-none');
    }else{
      $("#div_button_viatic").addClass('d-none');
    }
  }

  function get_table_estimation(){
    var id_anexo = $('#anexo_id').val();
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

  function get_vertical_anexo(){
    let id_anexo = $('#anexo_id').val();

    fetch(`/get_vertical_anexo/anexo/${id_anexo}`, miInit)
      .then(function(response){
        return response.json();
      })
      .then(function(data){
        if(data[0].vertical_id != null){
          $("#vertical").val(data[0].vertical_id);
        }else{
          $("#vertical").val(0);
        }
      })

  }

  $("#doc_type").on("change", function(){
    let type_doc = $(this).val();
    
    if(type_doc == 1){
      $(".fields_docp").css('display', 'block');
      $(".fields_docm").css('display', 'none');
      $('#type_service').val(1);
    }else if(type_doc == 2){
      $(".fields_docp").find('input').val('');
      $(".fields_docp").css('display', 'none');
      $(".fields_docm").css('display', 'block');
      $('#type_service').val(4);
    }else{
      console.log("Cotizacion nueva");
      $(".fields_docp").css('display', 'block');
      $(".fields_docm").css('display', 'none');
      $('#type_service').val(1);
    }

  });

  $('#type_service').on('change',function(){
    let type = $(this).val();
    if(type == 2 || type == 3){
      $(".fields_docm").css('display', 'block');
    }else{
      $(".fields_docm").css('display', 'none');
    }
  })

  $('#categoria').on('change', function(){
    let categoria = $(this).val();
    if(categoria == 12){
      $('#div_tuberia').removeClass("d-none");
    }else{
      $('#div_tuberia').addClass("d-none");
    }
    
  })

  function get_aps_sites(id_cad){
    fetch(`/get_aps_sites/id/${id_cad}`,  miInit)
    .then(function(response){
      return response.json();
    })
    .then(function(data){
      console.log(data);
    })
    .catch(function(error){
      console.log(error);
    })
  }

  $("input[name='optionsMaterial']").on("change",function(e){
    let material = $("input[name='optionsMaterial']:checked").val();
    getTypeMaterial(material);
    // var categoria = document.getElementById('categoria').value;
    // var description = document.getElementById('description').value;
    // var type = document.getElementById('tipo_material').value;
    // var material = $("input[name='optionsMaterial']:checked").val();
    // var medida = $("input[name='optionsMedida']:checked").val();
    
    // let url = ``;

    // if(description.length >=3){
    //   url = `/items/ajax/third/${categoria}/${description}/${material}/${type}/${medida}`;
    //   getArticlesCategorias(url);
    // }
  })

  $("input[name='optionsMedida']").on("change",function(e){
    var categoria = document.getElementById('categoria').value;
    var description = document.getElementById('description').value;
    var type = document.getElementById('tipo_material').value;
    var material = $("input[name='optionsMaterial']:checked").val();
    var medida = $("input[name='optionsMedida']:checked").val();
    
    let url = ``;
    
      if(description.lenght != '' && description.lenght != undefined && description.length >=3){
        url = `/items/ajax/third/${categoria}/${description}/${material}/${type}/${medida}`;
      }else{
        url = `/items/ajax/third/${categoria}/${material}/${type}/${medida}`;
      }

      getArticlesCategorias(url);
    
  })

  $('#add_shopping_cart').on('click', function(){
    var data_gabinete = get_gabinetes();
    var data_switches = get_switches();
    var data_gabinete = get_gabinetes();
    var data_aps = get_aps();
    var aps = JSON.stringify(data_aps[0]);
    var firewalls = JSON.stringify(get_firewalls());
    var switches = JSON.stringify(data_switches[0]);
    var gabinetes = JSON.stringify(data_gabinete[0]);
 
    fetch(`/getProductsCart/${aps}/${firewalls}/${switches}/${gabinetes}`, miInit)
         .then(function(response){
           return response.json();
         })
         .then(function(products){
           products.forEach(product => {
              var productosLS = obtenerProductosLocalStorage();
              var id_product = product.id;
              if(productosLS == '[]'){
                //Primer producto del carrito
                leerDatosProductMO(product);
                menssage_toast('Mensaje', '3', 'Producto agregado' , '2000');
              }else {
                let count =  productosLS.filter(producto => producto.id == id_product);
    
                if(count.length == 1){
                  //El producto existe
                  menssage_toast('Error', '2', 'Este producto ya fue agregado al pedido' , '3000');
                }else{
                  leerDatosProductMO(product);
                  menssage_toast('Mensaje', '3', 'Producto agregado' , '2000');
                }
              }
           });
         })
         .catch(function(error){
                 console.log(error);
         });
    
  });


  /**
**** scripts del funcionamiento de la paginacion de Equip. activo, materiales,
**** filtro por categoria y aps, firewalls y switches dinamicos
  */


    function get_gabinetes(){
      var data = [];
      var select_gabinete = document.getElementsByClassName("gabinetes_select");
      var cant_gabinete = document.getElementsByClassName("gabinetes_cant");
      var element = {}
      var gabinetes = [];
      var total = 0;

      for(var i = 0;i < select_gabinete.length - 1; i++)
      {
        element = {"id" : $(select_gabinete[i]).val(),
                    "cant" : parseFloat(cant_gabinete[i].value)}

        total+= element.cant;
        gabinetes.push(element);
      }

      return data = [gabinetes, total];
    }

    function get_aps(){
      var select_aps = document.getElementsByClassName("aps_modelo");
      var cant_aps = document.getElementsByClassName("aps_cant");
      var element = {};
      var aps = [];
      var api = 0;
      var ape = 0;
      var data = [];

      for(var i = 0;i < select_aps.length - 1; i++)
      {
        element = {"id" : $(select_aps[i]).val(),
                    "modelo" : $(select_aps[i]).children("option").filter(":selected").text(),
                    "clave" : $(select_aps[i]).children("option").filter(":selected").data('key'),
                    "cant" : cant_aps[i].value}

        aps.push(element);

        if(element.clave == "API"){
          api = api + parseInt(element.cant);
        }else if(element.clave == "APE"){
          ape = ape + parseInt(element.cant);
        }
      }

      return data = [aps,api,ape];

    }

    function get_firewalls(){
      var select_firewall = document.getElementsByClassName("firewall_modelo");
      var cant_firewall = document.getElementsByClassName("firewall_cant");
      var element = {}
      var firewall = [];

      for(var i = 0;i < select_firewall.length - 1; i++)
      {
        element = {"id" : $(select_firewall[i]).val(),
                    "modelo" : $(select_firewall[i]).children("option").filter(":selected").text(),
                    "cant" : cant_firewall[i].value}

        firewall.push(element);
      }

      return firewall;

    }

    function get_switches(){
      var select_switches = document.getElementsByClassName("switch_modelo");
      var cant_switches = document.getElementsByClassName("switch_cant");
      var element = {}
      var switches = [];
      var data = [];
      var switch_cant = 0;

      for(var i = 0;i < select_switches.length - 1; i++)
      {
        element = {"id" : $(select_switches[i]).val(),
                    "modelo" : $(select_switches[i]).children("option").filter(":selected").text(),
                    "cant" : cant_switches[i].value}

        switches.push(element);
        if(element.cant != "" && element.cant != 0){
          switch_cant += parseInt(element.cant);
        }

      }

      return data = [switches, switch_cant];

    }

    function getPaginationSelectedPage(url) {
          var chunks = url.split('?');
          var baseUrl = chunks[0];
          var querystr = chunks[1].split('&');
          var pg = 1;
          for (i in querystr) {
              var qs = querystr[i].split('=');
              if (qs[0] == 'page') {
                  pg = qs[1];
                  break;
              }
          }
          return pg;
      }

    $('#description').on('keyup',function(){
      var categoria = document.getElementById('categoria').value;
      var description = document.getElementById('description').value;
      var type = document.getElementById('tipo_material').value;
      var material = $("input[name='optionsMaterial']:checked").val();
      var medida = $("input[name='optionsMedida']:checked").val();
      
      let url = ``;

      if(description.length >=3){
        url = `/items/ajax/third/${categoria}/${description}/${material}/${type}/${medida}`;
        getArticlesCategorias(url);
      }

    });

    $('#products-grid').on('click', '.pagination a', function(e) {
        e.preventDefault();
        var pg = getPaginationSelectedPage($(this).attr('href'));
        var data_aps = get_aps();
        var data_switches = get_switches();
        var aps = JSON.stringify(data_aps[0]);
        var api = data_aps[1];
        var ape = data_aps[2];
        var firewalls = JSON.stringify(get_firewalls());
        var switches = JSON.stringify(data_switches[0]);
        var switch_cant = data_switches[1];
        $.ajax({
            url: `/items/ajax/first/${aps}/${api}/${ape}/${firewalls}/${switches}/${switch_cant}`,
            data: { page: pg },
            success: function(data) {
                $('#products-grid').html(data);
            }
        });
    });

    $('#products-grid-materiales').on('click', '.pagination a', function(e) {
        e.preventDefault();
        var pg = getPaginationSelectedPage($(this).attr('href'));
        var data_aps = get_aps();
        var data_switches = get_switches();
        var aps = JSON.stringify(data_aps[0]);
        var api = parseInt(data_aps[1]);
        var ape = parseInt(data_aps[2]);
        var firewalls = JSON.stringify(get_firewalls());
        var switches = JSON.stringify(data_switches[0]);
        var switch_cant = data_switches[1];
        var data_gabinete = get_gabinetes();
        var gabinetes = data_gabinete[1];
        var switches = data_switches[1];
        var material = $('.material_select').val();
        var medida = $('.medida_select').val();

        $.ajax({
            url : `/items/ajax/second/${api}/${ape}/${switch_cant}/${gabinetes}/${material}/${medida}`,
            data: { page: pg },
            success: function(data) {
                $('#products-grid-materiales').html(data);
            }
        });
    });

    $('#products-grid-mo').on('click', '.pagination a', function(e) {
        e.preventDefault();
        var pg = getPaginationSelectedPage($(this).attr('href'));
        var data_aps= get_aps();
        var aps = JSON.stringify(data_aps[0]);

        $.ajax({
            url: `/items/ajax/four/${aps}`,
            data: { page: pg },
            success: function(data) {
                $('#products-grid-mo').html(data);
            }
        });

    });

    $('#products-grid-categorias').on('click', '.pagination a', function(e) {
        e.preventDefault();
        var pg = getPaginationSelectedPage($(this).attr('href'));
        var categoria = document.getElementById('categoria').value;
        var description = document.getElementById('description').value;
        var type = document.getElementById('tipo_material').value;
        var material = $("input[name='optionsMaterial']:checked").val();
        var medida = $("input[name='optionsMedida']:checked").val();
        
        if(type != undefined && material != undefined){
          description == undefined || description == '' ? description = ' ' : description;
          url = `/items/ajax/third/${categoria}/${description}/${material}/${type}/${medida}`;
        }else if(description == undefined || description == ''){
          url = `/items/ajax/third/${categoria}/${material}/${type}/${medida}`;
        }else{
          url = `/items/ajax/third/${categoria}/${description}/0/0/0`;
        } 

        $.ajax({
            url: url,
            data: { page: pg },
            success: function(data) {
                $('#products-grid-categorias').html(data);
            }
        });
    });

  $("#get_equipo_button").on("click",function(e){
    e.preventDefault();

    var data_aps= get_aps();
    var data_switches = get_switches();
    
    var aps = JSON.stringify(data_aps[0]);
    var api = data_aps[1];
    var ape = data_aps[2];
    var firewalls = JSON.stringify(get_firewalls());
    var switches = JSON.stringify(data_switches[0]);
    var switch_cant = data_switches[1];

    getArticles(`/items/ajax/first/${aps}/${api}/${ape}/${firewalls}/${switches}/${switch_cant}`);
  })

  $("#get_materiales_button").on("click",function(e){
    e.preventDefault();
    var data_aps = get_aps();
    var data_switches = get_switches();
    var aps = JSON.stringify(data_aps[0]);
    var api = parseInt(data_aps[1]);
    var ape = parseInt(data_aps[2]);
    var firewalls = JSON.stringify(get_firewalls());
    var switches = JSON.stringify(data_switches[0]);
    var switch_cant = data_switches[1];
    var data_gabinete = get_gabinetes();
    var gabinetes = data_gabinete[1];
    var switches = data_switches[1];
    var material = $('.material_select').val();
    var medida = $('.medida_select').val();
  
    url = `/items/ajax/second/${api}/${ape}/${switch_cant}/${gabinetes}/${material}/${medida}`;
    getArticlesMateriales(url);
  })

  $("#get_mo_button").on("click",function(e){
    e.preventDefault();
    var data_aps = get_aps();
    var data_switches = get_switches();
    var aps = JSON.stringify(data_aps[0]);
    var api = data_aps[1];
    var ape = data_aps[2];
    var firewalls = JSON.stringify(get_firewalls());
    var switches = JSON.stringify(data_switches[0]);
    var switch_cant = data_switches[1];
    url = `/items/ajax/four/${api}/${ape}`;
    getArticlesManoObra(url);

  })

  $("#get_viatics_button").on("click",function(e){
    update_viaticos();
  })

  $("#get_categorias_button").on("click",function(e){
    e.preventDefault();
    var categoria = document.getElementById('categoria').value;
    var description = document.getElementById('description').value;
    var type = document.getElementById('tipo_material').value;
    var material = $("input[name='optionsMaterial']:checked").val();
    var medida = $("input[name='optionsMedida']:checked").val();
    
    if(description.lenght != '' && description.lenght != undefined){
      url = `/items/ajax/third/${categoria}/${description}/${material}/${type}/${medida}`;
    }else{
      url = `/items/ajax/third/${categoria}/${material}/${type}/${medida}`;
    }

    getArticlesCategorias(url);
  })

});

function getArticles(url) {
    $.ajax({
        url : url
    }).done(function (data) {
      if(data != ''){
        $('#products-grid').html(data);
      }else{
        swal("No se cargaron los equipos","Favor de llenar las cantidades de los modelos seleccionados","warning");
      }
    }).fail(function (error) {
        console.log(error);
        swal("No se cargaron articulos","Ocurrio un error inesperado","error");
    });
}

function getArticlesMateriales(url) {
    $.ajax({
        url : url
    }).done(function (data) {
      if(data != ''){
        $('#products-grid-materiales').html(data);
      }else{
        swal("Debe llenar las cantidades de los equipos","","warning");
      }

    }).fail(function () {
        swal("Debe llenar las cantidades de los equipos","","warning");
    });
}

function getArticlesManoObra(url) {
    $.ajax({
        url : url
    }).done(function (data) {
      var productosLS = obtenerProductosLocalStorage();

        data.forEach(element => {
          var productosLS = obtenerProductosLocalStorage();
          var id_product = element.id;
          if(productosLS == '[]'){
            //Primer producto del carrito
            leerDatosProductMO(element);
            menssage_toast('Mensaje', '3', 'Producto agregado' , '2000');
          }else {
            let count =  productosLS.filter(producto => producto.id == id_product);

            if(count.length == 1){
              //El producto existe
              menssage_toast('Error', '2', 'Este producto ya fue agregado al pedido' , '3000');
            }else{
              leerDatosProductMO(element);
              menssage_toast('Mensaje', '3', 'Producto agregado' , '2000');
            }

          }

        })

    }).fail(function () {
        swal("Debe llenar las cantidades de los equipos","","warning");
    });
}

function update_viaticos(){

  var num_aps = 0;
  var productos = obtenerProductosLocalStorage();
    //Filtro de antenas
  var products_aps = productos.filter(producto =>
                     producto.codigo.substring(0, 3) == 'API' || producto.codigo.substring(0, 3) == 'APE');
    
    var viaticos_products = productos.filter(producto => producto.categoria_id == 15);
    /* Si es mayor a 0 significa que hay aps
       parte del usuario y se procede a recalcular las cantidades */
    if(products_aps.length > 0){
      $.each(products_aps, function( i, key ) {
        num_aps += key.cant_req;
      })

      new_products =  productos.filter(producto => producto.categoria_id != 15);
      localStorage.setItem('productos', JSON.stringify(new_products));

      $.ajax({
        url :  `/items/ajax/five/0/${num_aps}`
      }).done(function (data) {
        var productosLS = obtenerProductosLocalStorage();
          data.forEach(element => {
            var productosLS = obtenerProductosLocalStorage();
            var id_product = element.id;
            if(productosLS == '[]'){
              //Primer producto del carrito
              leerDatosProductMO(element);
            }else {
              let count =  productosLS.filter(producto => producto.id == id_product);
              (count.length == 1) ? console.log("producto existe") : leerDatosProductMO(element);
            }
          })
            menssage_toast('Mensaje', '4', 'Viaticos actualizados' , '2000');
          }).fail(function () {
              Swal.fire("Ocurrio un error al actualizar mano de obra","","error");
          });//Fin funcion ajax

      }else{
        Swal.fire("El proyecto no tiene antenas","Agrega AP'S y vuelve a intentarlo","warning");
      }
}

  function getArticlesViatics(url) {
    $.ajax({
        url : url
    }).done(function (data) {
      var productosLS = obtenerProductosLocalStorage();

        data.forEach(element => {
          var productosLS = obtenerProductosLocalStorage();
          var id_product = element.id;
          if(productosLS == '[]'){
            //Primer producto del carrito
            leerDatosProductMO(element);
            menssage_toast('Mensaje', '3', 'Producto agregado' , '2000');
          }else {
            let count =  productosLS.filter(producto => producto.id == id_product);

            if(count.length == 1){
              //El producto existe
              menssage_toast('Error', '2', 'Este producto ya fue agregado al pedido' , '3000');
            }else{
              leerDatosProductMO(element);
              menssage_toast('Mensaje', '3', 'Producto agregado' , '2000');
            }

          }

        })

    }).fail(function () {
        Swal.fire("Debe llenar las cantidades de los equipos","","warning");
    });
  }

function getArticlesCategorias(url) {
    $.ajax({
        url : url
    }).done(function (data) {
        $('#products-grid-categorias').html(data);
    }).fail(function () {
        alert('Articles could not be loaded.');
    });
}



//Localstorage

function leerDatosProductMO(producto){
  var tipo_cambio = document.getElementById('tipo_cambio').value;
  var precioTotal = 0.0;
  var precio_usd = 0.0;
  var cant_req = 0;
  var cant_sug = producto.cantidad;
  var currency_id = producto.currency_id;

  if(cant_req == 0){
    //Si  la cantidad requerida es 0 se toma en cuenta la cantidad sugerida como la cantidad de artculos solicitada
    cant_req = parseFloat(cant_sug);
  }else{
    cant_req = parseFloat(cant_req);
  }

  precioTotal = (parseFloat(cant_req) * parseFloat(producto.precio));

  if(currency_id == 1){
    precio_usd = precioTotal / parseFloat(tipo_cambio);
  }else{
    precio_usd = precioTotal;
  }

   const infoProducto = {
       id: producto.id,
       descripcion: producto.descripcion,
       img: producto.img,
       codigo: producto.codigo,
       precio: producto.precio,
       categoria: producto.categoria,
       categoria_id: producto.categoria_id,
       num_parte: producto.num_parte,
       currency: producto.currency,
       currency_id: producto.currency_id,
       proveedor: producto.proveedor,
       descuento: producto.descuento,
       cant_sug: cant_sug,
       cant_req: cant_req,
       precio_total : precioTotal.toFixed(2),
       precio_total_usd : precio_usd.toFixed(2)
   }

   console.log(infoProducto);
   insertarMO(infoProducto);
}

function guardarProductoLS(producto){
    let productos;

    productos = obtenerProductosLocalStorage();
    productos.push(producto);

    localStorage.setItem('productos', JSON.stringify(productos));
}

function obtenerProductosLS(){
    let productosLS;

    if(localStorage.getItem('productos') === null){
        productosLS = [];
    }else{
        productosLS = JSON.parse(localStorage.getItem('productos'));
    }

    return productosLS;
}

function insertarMO(producto){
    guardarProductoLS(producto);
}

