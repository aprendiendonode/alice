<html lang="en">
<head>
<meta charset="UTF-8">
<title>
  
</title>

<style type="text/css">
    * {
        font-family: Verdana, Arial, sans-serif;
    }
    table{
        font-size: x-small;
    }

    tfoot tr td{
        font-weight: bold;
        font-size: x-small;
    }

    .header_address{
      line-height: .3rem;
    }

    .header_address h4{
      font-size: 1.5rem;
    }

    .header_address h5{
      font-size: 1.2rem;
      line-height: .1rem !important;
    }

    .text-center{
      text-align: center;
    }

    .text-address{
      font-size: 14px;
      line-height: .7rem;
    }

    .row{
      width: 100%;
    }

    .customer_info{
      height: 120px;
      border: 1px solid #000;
      border-radius: 5px;
      margin-top: 5px;
      font-size: 12px;
    }

    .customer_info div{
      padding: 0rem 2px;
    }

    .customer_info div p{
      line-height: .2rem;
      font-weight: bold;
    }

    .customer_info div span{
      font-weight: normal;
    }

    .header{
      border: 1px solid #000;
      border-radius: 5px;
      margin-top: 20px;
      font-size: 9px;
      height: 80px;
    }

    .header div{
      display: inline-block;
      width: 32%;
      padding: 1.2rem 1px;
      font-weight: bold;
      text-align: left;
    }

    .header div span{
      font-weight: normal !important;
    }

    .header div p{
      line-height: .6rem;
      display: block;
    }

    .product_description{
      height: 100px;
      border: 1px solid #000;
      border-radius: 5px;
      margin-top: 5px;
      font-size: 12px;
    }

    .transparent{
      color: white;
    }

    .text-bold{
      font-weight: bold;
    }

    #table_products{
      margin-top: 10px;
      border: 2px solid;
      border-radius: 2px;
    }

    #table_products {
      border-collapse: collapse;
    }

    #table_products tbody {
      border: 1px solid black;
    }

    #table_products tbody tr td{
      border-right: 1px solid black;
    }

    #table_totales{
      position: absolute;
      bottom: 10px;
      margin-bottom: 10px;
      border: 2px solid black;
    }

</style>

</head>
<body>

  <table width="100%">
    <tr>
        <td style="width:20%;" valign="top"><img width="130" src="{{ public_path('images/storage/SIT070918IXA/files/companies/logo.png') }}"/></td>
        <td class="header_address" style="width:60%;" class="" align="center">
            <h4>SITWIFI, S.A DE C.V.</h4>
            <p class="text-address">HAMBURGO No. Ext. 159 No. Int. PISO 1 </p>
            <p>Col. JUAREZ CP 06600</p>
            <p>Delg. CUAUHTÉMOC CIUDAD DE MÉXICO </p>
            <h5>R.F.C. SIT070918IXA</h5>
        </td>
        <td style="width:20%;" valign="top"></td>
    </tr>
  </table>

  <table width="100%">
    <tr>
      <td style="width:60%;">
        <h4>PROVEEDOR:</h4>
        <h4></h4>
        <h4>TEL: </h4>
      </td>
      <td style="width:40%;">
        <h4>ORDEN No.:</h4>
        <h4>FECHA</h4>
      </td>
    </tr>
  </table>

  <table id="table_products" width="100%">
    <thead>
      <tr>       
        <th align="center">CANTIDAD</th>
        <th colspan="2" align="center">PRODUCTO</th>
        <th>COSTO UNITARIO</th>
        <th>DESCUENTO</th>
        <th>TOTAL</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($products as $product)
          <tr>
            <td>{{$product->cantidad}}</td>
            <td colspan="2">{{$product->name}}</td>
            <td>{{$product->price}}</td>
            <td>{{$product->discount}}</td>
            <td>{{$product->total}}</td>
          </tr>
      @endforeach
    </tbody>
  </table>

  <table id="table_totales" width="100%">
    <tr>
      <td style="width:70%;">
        <h4 class="text-bold">({{$ammount_letter}} M.N.)</h4>
      </td>
      <td style="width:20%;">
        <p class="text-bold">SUBTOTAL:</p>
        <p class="text-bold">DESCUENTO:</p>
        <p class="text-bold">I.V.A.</p>
        <p class="text-bold">TOTAL</p>
      </td>
      <td style="width:20%;">
        
      </td>
    </tr>
  </table>

</body>
</html>
