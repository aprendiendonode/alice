<?php

namespace App\Http\Controllers\Catalogs;
use DB;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $currency = DB::select('CALL GetAllCurrencyActivev2 ()', array());
      $unitmeasures = DB::select('CALL GetUnitMeasuresActivev2 ()', array());
      $satproduct = DB::select('CALL GetSatProductActivev2 ()', array());
      $satproduct = DB::select('CALL GetSatProductActivev2 ()', array());
      $customer = DB::select('CALL GetCustomersActivev2 ()', array());
      $category = DB::select('CALL GetAllCategoriesActivev2 ()', array());
      $brands = DB::select('CALL GetAllBrandsActivev2 ()', array());
      $models = DB::select('CALL GetAllModelsActivev2 ()', array());
      $estatus = DB::select('CALL GetAllStatusProductsActivev2 ()', array());
      $marcas = DB::select('CALL GetAllBrandsActivev2 ()', array());
      $especificacion = DB::select('CALL GetAllEspecificacionActivev2 ()', array());

      return view('permitted.catalogs.products',compact('currency','unitmeasures','satproduct', 'customer', 'category','brands','models', 'estatus', 'marcas', 'especificacion'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
          $user_id= Auth::user()->id;
          $key = $request->inputCreatkey;

          $result = DB::table('products')
                    ->select('code')
                    ->where([
                        ['code', '=', $key],
                      ])->count();
          if($result == 0)
          {
            $nkey = $request->inputCreatpart;
            $name = $request->inputCreatname;
            $precio_comas = $request->inputCreatcoindefault;
            $precio_sincomas = str_replace(',','',$precio_comas);

            $nDescription= !empty($request->description) ? $request->description : '';
            $nComment= !empty($request->comment) ? $request->comment : '';

            $id_moneda = $request->sel_modal_coin;
            $id_proveedor = $request->sel_modal_proveedor;
            $id_categoria = $request->sel_categoria;
            $id_modelo = $request->sel_modelo;

            $id_unit = $request->sel_unit;
            $id_satserv = $request->sel_satserv;
            $id_estatus = $request->sel_estatus;

            $orden= $request->inputCreatOrden;
            $fabricante= !empty($request->inputCreatManufacter) ? $request->inputCreatManufacter : '';
            $status= !empty($request->status) ? 1 : 0;

            $id_name_category = DB::select('CALL px_products_categories_name (?)', array($id_categoria));
            $name_category = $id_name_category[0]->categoria;

            $id_name_modelos = DB::select('CALL px_products_modelos_namev2 (?)', array($id_modelo));
            $name_modelos = $id_name_modelos[0]->modelos;

            $rest_id_marca = DB::select('CALL px_products_id_modelosv2 (?)', array($id_modelo));
            $id_marca = $rest_id_marca[0]->marca_id;

            $rest_id_especification = DB::select('CALL px_products_especification_idv2 (?)', array($id_modelo));
            $id_especification = $rest_id_especification[0]->especification_id;

            $file_img = $request->file('fileInput');
            $file_extension = $file_img->getClientOriginalExtension(); //** get filename extension
            $fileName = uniqid().'.'.$file_extension;
            $img= $request->file('fileInput')->storeAs('product',$fileName);

            $newId = DB::table('products')
            ->insertGetId([
              'name' => $name,
              'code' => $key,
              'num_parte' => $nkey,
              'description' => $nDescription,
              'image' => $img,
              'model' => $name_modelos,
              'manufacturer' => $fabricante,
              'price' => $precio_sincomas,
              'categoria_id' => $id_categoria,
              'currency_id' => $id_moneda,
              'modelo_id' => $id_modelo,
              'marca_id' => $id_marca,
              'proveedor_id' => $id_proveedor,
              'status_id' => $id_estatus,
              'unit_measure_id' => $id_unit,
              'sat_product_id' => $id_satserv,
              'especifications_id' => $id_especification,
              'comment' => $nComment,
              'sort_order' => $orden,
              'status' => $status,
              'created_uid' => $user_id,
              'created_at' => \Carbon\Carbon::now()
            ]);

            if(empty($newId)) {
              return 'abort'; // returns 0
            }
            else {
              return $newId; // returns id
            }
          }
          else
          {
            return 'false';//Ya esta asociado
          }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
      $resultados = DB::select('CALL GetAllProductsv2 ()', array());
      return json_encode($resultados);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
      $identificador= $request->value;
      $resultados = DB::select('CALL GetAllProductsByIdv2 (?)', array($identificador));
      foreach ($resultados as $key) {
        $key->id = Crypt::encryptString($key->id);
      }
      return $resultados;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
