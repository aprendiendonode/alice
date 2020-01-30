<?php

namespace App\Http\Controllers\Purchases;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use Storage;
use App\Models\Base\DocumentType;
use App\Helpers\Helper;
use App\Models\Catalogs\PaymentTerm;
use App\Models\Purchases\Purchase;
use App\Models\Purchases\PurchaseLine;
use App\Models\Purchases\PurchaseTax;
use App\Models\Catalogs\Tax;
use Carbon\Carbon;

class PurchasesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $providers = DB::table('customers')->select('id', 'name')->where('provider', 1)->get();
        // $customer = DB::select('CALL px_only_customer_data ()', array());
        $sucursal = DB::select('CALL GetSucursalsActivev2 ()', array());
        $currency = DB::select('CALL GetAllCurrencyActivev2 ()', array());
        // $salespersons = DB::select('CALL GetAllSalespersonv2 ()', array());
        $payment_way = DB::select('CALL GetAllPaymentWayv2 ()', array());
        $payment_term = DB::select('CALL GetAllPaymentTermsv2 ()', array());
        $payment_methods = DB::select('CALL GetAllPaymentMethodsv2 ()', array());
        $cfdi_uses = DB::select('CALL GetAllCfdiUsev2 ()', array());
        $cfdi_relations = DB::select('CALL GetAllCfdiRelationsv2 ()', array());

        $product =  DB::select('CALL GetAllProductActivev2 ()', array());
        $unitmeasures = DB::select('CALL GetUnitMeasuresActivev2 ()', array());
        $satproduct = DB::select('CALL GetSatProductActivev2 ()', array());
        $impuestos =  DB::select('CALL GetAllTaxesActivev2 ()', array());

        $document_type = DocumentType::where('cfdi_type_id', 2)->get();// Solo documentos de ingresos

        $cxclassifications = DB::table('cxclassifications')->select('id', 'name')->get();

        return view('permitted.purchases.purchase_view', compact('providers','sucursal','currency','payment_way','payment_term', 'payment_methods', 'cfdi_uses', 'cfdi_relations', 'product', 'unitmeasures', 'satproduct', 'impuestos', 'cxclassifications', 'document_type'));
    }

    public function get_currency(Request $request)
    {

        $currency = $request->id_currency;
        $date = Carbon::parse($request->date)->format('Y-m-d');
        // $date_a = Carbon::parse($request->filter_date_from)->format('Y-m-d');
        // Carbon::now()

        if ($request->id_currency == 2) {
            $current_rate = DB::table('exchange_rates')->select('current_rate')->where('current_date', $date)->first();
            // $current_rate = DB::table('exchange_rates')->select('current_rate')->latest()->first();
            if (empty($current_rate)) {
              return response()->json(['error' => __('general.error_general')], 422);
            }
            else{
              return $current_rate->current_rate;
            }
        }
        else {
            $item_currency_code = DB::table('currencies')->select('rate')
            ->where('id', $currency)->value('rate');
            return $item_currency_code;
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Variables del controller
            /*$provider = $request->customer_id;
            $currency = $request->currency_id;
            $currency_value = $request->currency_value;
            $date_actual = $request->date;
            $date_fact = $request->date_fact;
            $payment_term = $request->payment_term_id;
            $date_end = $request->date_due;
            $payment_way = $request->payment_way_id;
            $payment_method = $request->payment_method_id;
            $cfdi_use = $request->cfdi_use_id;
            $document_type = $request->document_type;
            $iva = $request->iva_general;
            $iva_ret = $request->iva_retencion;
            $pdf_fact = $request->file('file_pdf');
            $xml_fact = $request->file('file_xml');*/
        //
        // return (string)filesize($request->file('file_pdf'));
        
        // Begin a transaction
        \DB::beginTransaction();

        // Open a try/catch block
        try {
            //Logica            
            $request->merge(['created_uid' => \Auth::user()->id]);
            $request->merge(['updated_uid' => \Auth::user()->id]);
            $request->merge(['status' => 1]);
            //Ajusta fecha y genera fecha de vencimiento
            $date = Helper::createDateTime($request->date);
            $request->merge(['date' => Helper::dateTimeToSql($date)]);

            $date_fact = Helper::createDateTime($request->date_fact);
            $request->merge(['date_fact' => Helper::dateTimeToSql($date_fact)]);


            $date_due = $date; //La fecha de vencimiento por default
            $date_due_fix = $request->date_due;//Fix valida si la fecha de vencimiento esta vacia en caso de error
            
            if (!empty($request->date_due)) {
              $date_due = Helper::createDate($request->date_due);
            } else {
              $payment_term = PaymentTerm::findOrFail($request->payment_term_id);
              $date_due = $payment_term->days > 0 ? $date->copy()->addDays($payment_term->days) : $date->copy();
            }
            $request->merge(['date_due' => Helper::dateToSql($date_due)]);
            //Obtiene folio
            $document_type = Helper::getNextDocumentTypeByCode($request->document_type);
            $request->merge(['document_type_id' => $document_type['id']]);
            $request->merge(['name' => $document_type['name']]);
            $request->merge(['serie' => $document_type['serie']]);
            $request->merge(['folio' => $document_type['folio']]);
            // 
            $request->merge(['payment_term_id' => $request->payment_term_id]);
            $request->merge(['payment_way_id' => $request->payment_way_id]);
            $request->merge(['payment_method_id' => $request->payment_method_id]);
            $request->merge(['cfdi_use_id' => $request->cfdi_use_id]);

            $file_pdf = $request->file('file_pdf');
            $file_xml = $request->file('file_xml');

            $purchase_store = Purchase::create($request->input());

            //Registro de lineas
            $amount_subtotal = 0;
            $amount_discount = 0;  //Descuento de cantidad
            $amount_untaxed = 0;   //Cantidad sin impuestos
            $amount_tax = 0;       //Importe impuesto
            $amount_tax_ret = 0;   //Importe impuesto ret
            $amount_total = 0;     //Cantidad total
            $balance = 0;          //Balance
            $taxes = array();      //Impuestos

            $currency_pral_id = $request->currency_id;      //Moneda principal
            $currency_pral_value = $request->currency_value;//Valor o TC de la moneda principal

            //Lineas
            $iva = explode(',', $request->iva);

            if($request->item){
                foreach ($request->item as $key => $item) {
                    //Logica
                    $item_quantity = (double)$item['quantity'];  //cantidad de artículo
                    $item_price_unit = (double)$item['price_unit']; //unidad de precio del artículo
                    $item_discount = (double)$item['discount']; //descuento del artículo

                    $item_subtotal_quantity = round($item_price_unit * $item_quantity, 2);

                    $item_price_reduce = ($item_price_unit * (100 - $item_discount) / 100); //Precio reducido
                    $item_amount_untaxed = round($item_quantity * $item_price_reduce, 2); //libre de impuestos
                    $item_amount_discount = round($item_quantity * $item_price_unit, 2) - $item_amount_untaxed;//descuento
                    $item_amount_tax = 0;//impuesto a la cantidad del artículo
                    $item_amount_tax_ret = 0;// cantidad de artículo retiro de impuestos

                    //Impuestos por cada producto
                    if ($iva[0] != 0) {
                        foreach ($iva as $tax_id) {
                            
                            $tax = Tax::findOrFail($tax_id);
                            $tmp = 0;
                            if ($tax->factor == 'Tasa') {
                               $tmp = $item_amount_untaxed * $tax->rate / 100;
                            } elseif ($tax->factor == 'Cuota') {
                               $tmp = $tax->rate;
                            }
                            $tmp = round($tmp, 2);
                            if ($tmp < 0) { //Retenciones
                               $item_amount_tax_ret += $tmp;
                            } else { //Traslados
                               $item_amount_tax += $tmp;
                            }
                        }
                    }
                    $item_subtotal_clean = $item_subtotal_quantity;
                    $item_discount_clean = $item_amount_discount;
                    $item_amount_total = $item_amount_untaxed + $item_amount_tax + $item_amount_tax_ret;
                    $item_subtotal = $item_amount_untaxed; //libre de impuestos

                    //Tipo de cambio
                    $item_currency_id = $item['current'];
                    $item_currency_code = DB::table('currencies')->select('code_banxico')->where('id', $item_currency_id)->value('code_banxico');
                    $item_currency_value = $currency_pral_value;

                     //--------------------------------------------------------------------------------------------------------------------------//
                      //Moneda principal es dolar
                      if ($currency_pral_id == '2') {
                        if ($item_currency_id == '2') {
                          $item_currency_value = $currency_pral_value; //Tipo de cambio a usar
                        }
                        else {
                          $item_currency_value = $currency_pral_value; //Tipo de cambio a usar
                          //Tranformamos a dolar
                          $item_amount_tax = $item_amount_tax / $item_currency_value;
                          $item_amount_tax_ret = $item_amount_tax_ret / $item_currency_value;
                          $item_amount_total = $item_amount_total / $item_currency_value;
                          $item_subtotal = $item_subtotal / $item_currency_value;
                          $item_subtotal_clean = $item_subtotal_clean / $item_currency_value;
                          $item_discount_clean = $item_discount_clean / $item_currency_value;
                        }
                      }
                      //Moneda distinta
                      else {
                        if ($item_currency_id == '2') { //bien
                          $exchange_rates = DB::table('exchange_rates')->select('current_rate')->latest()->first();
                          $item_currency_value = $exchange_rates->current_rate; //Tipo de cambio a usar
                          //Tranformamos a dolar
                          $item_amount_tax = $item_amount_tax * $item_currency_value;
                          $item_amount_tax_ret = $item_amount_tax_ret * $item_currency_value;
                          $item_amount_total = $item_amount_total * $item_currency_value;
                          $item_subtotal = $item_subtotal * $item_currency_value;
                          $item_subtotal_clean = $item_subtotal_clean * $item_currency_value;
                          $item_discount_clean = $item_discount_clean * $item_currency_value;
                        }
                        else {
                          $item_currency_value = DB::table('currencies')->select('rate')->where('id', $item_currency_id)->value('rate');
                          //Tranformamos al valor de la moneda seleccionada
                          $item_amount_tax = $item_amount_tax * $item_currency_value;
                          $item_amount_tax_ret = $item_amount_tax_ret * $item_currency_value;
                          $item_amount_total = $item_amount_total * $item_currency_value;
                          $item_subtotal = $item_subtotal * $item_currency_value;
                          $item_subtotal_clean = $item_subtotal_clean * $item_currency_value;
                          $item_discount_clean = $item_discount_clean * $item_currency_value;
                        }
                      }
                      //--------------------------------------------------------------------------------------------------------------------------//
                      //Sumatoria totales
                      $amount_subtotal += $item_subtotal_clean;
                      $amount_discount += $item_discount_clean;
                      $amount_untaxed += $item_subtotal; //Original -> $amount_untaxed += $item_amount_untaxed;
                      $amount_tax += $item_amount_tax;
                      $amount_tax_ret += $item_amount_tax_ret;
                      $amount_total += $item_amount_total;

                      //Guardar Linea (Purchases Line)
                      $purchase_lines = PurchaseLine::create([
                          'created_uid' => \Auth::user()->id,
                          'updated_uid' => \Auth::user()->id,
                          'purchase_id' => $purchase_store->id,
                          'name' => $item['name'],
                          'product_id' => $item['product_id'],
                          'sat_product_id' => $item['sat_product_id'],
                          'unit_measure_id' => $item['unit_measure_id'],
                          'quantity' => $item_quantity,
                          'price_unit' => $item_price_unit,
                          'discount' => $item_discount,
                          'price_reduce' => $item_price_reduce,
                          'amount_discount' => $item_discount_clean,
                          'amount_untaxed' => $item_subtotal_clean,
                          'amount_tax' => $item_amount_tax,
                          'amount_tax_ret' => $item_amount_tax_ret,
                          'amount_total' => $item_amount_total,
                          'sort_order' => $key,
                          'status' => 1,
                          'currency_id' => $item['current'],
                          'currency_value' => $item_currency_value,
                      ]);
                      // Guardar impuestos por linea (Purchases Line Taxes)
                      if ($iva[0] != 0) {
                        $purchase_lines->taxes()->sync($iva);
                      } else {
                        $purchase_lines->taxes()->sync([]);
                      }
                }
            }

            // return $request->item;
            
            //Guardar resumen de impuestos (Purchases Tax)
            if ($iva[0] != 0) {
                $i = 0;
                $amount_tax_formula = $amount_tax - $amount_tax_ret;
                foreach ($iva as $tax_id) {
                    $tax = Tax::findOrFail($tax_id);
                    $customer_invoice_tax = PurchaseTax::create([
                        'created_uid' => \Auth::user()->id,
                        'updated_uid' => \Auth::user()->id,
                        'purchase_id' => $purchase_store->id,
                        'name' => $tax->name,
                        'tax_id' => $tax_id,
                        'amount_base' => $amount_subtotal,
                        'amount_tax' => $amount_tax_formula,
                        'sort_order' => $i,
                        'status' => 1,
                    ]);
                    $i++;
                }
            }
            // Factura PDF y XML
            
            if($file_pdf != null )
            {
                $file_extension = $file_pdf->getClientOriginalExtension(); //** get filename extension
                $fileName = $request->name_fact.'_'.date("Y-m-d H:i:s").'.'.$file_extension;
                $pdf= $file_pdf->storeAs('filestore/storage/compras/'.date('Y-m'), $fileName);
                // DB::table('pay_facturas')->insert([
                //     'name' => $pdf,
                //     'payment_id' => $id_payment
                // ]);
            }
            if($file_xml != null )
            {
                $file_extension = $file_xml->getClientOriginalExtension(); //** get filename extension
                $fileName = $request->name_fact.'_'.date("Y-m-d H:i:s").'.'.$file_extension;
                $pdf= $file_xml->storeAs('filestore/storage/compras/'.date('Y-m'), $fileName);
                // DB::table('pay_facturas')->insert([
                //     'name' => $pdf,
                //     'payment_id' => $id_payment
                // ]);
            }
            //Actualiza registro principal con totales
            $purchase_store->amount_discount = $amount_discount;
            $purchase_store->amount_untaxed = $amount_subtotal;
            $purchase_store->amount_tax = $amount_tax;
            $purchase_store->amount_tax_ret = $amount_tax_ret;
            $purchase_store->amount_total = $amount_total;
            $purchase_store->balance = $amount_total;
            $purchase_store->update();

            // Factura END

            // END with Commit
            DB::commit();
            return 'success';
        }catch (\Exception $e) {
            $request->merge([
                'date' => Helper::convertSqlToDateTime($request->date),
            ]);
            if (!empty($date_due_fix)) {
                $request->merge([
                    'date_due' => Helper::convertSqlToDate($request->date_due),
                ]);
            }else{
                $request->merge([
                    'date_due' => '',
                ]);
            }
            // An error occured; cancel the transaction...
            DB::rollback();

            // and throw the error again.
            // throw $e;
            return $e;
            // return __('general.error_cfdi_pac');
        }
    }

    public function get_consecutivo(Request $request)
    {
        $value = $request->document_type;
        $increment_number = "";
        try {
            $data = [];
            $document_type = DocumentType::where('code', '=', $value)->first();
            if (!empty($document_type)) {
               $document_type->current_number += $document_type->increment_number;
               $increment_number = $document_type->current_number;
            } else {
               throw new \Exception(__('document_type.error_next_document_type'));
            }
            return $increment_number;
        } catch (\Exception $e) {
            throw $e;
        }
    }
    public function totalLines(Request $request)
    {
       //Variables
       $json = new \stdClass;
       $input_items = $request->item;
       $currency_id = $request->currency_id; //Guardo la moneda seleccionada
       $currency_value = $request->currency_value;
       $resp_currency_value = $request->currency_value;
       $iva = explode(',', $request->iva);

       // $texto = "";
       if (empty($currency_id)) {
         $currency_id = 1;
       }
       if ($currency_id === 1) {
         $currency_value = 1;
       }
       if (empty($currency_value)) {
         $current_select_rate = DB::table('currencies')->select('rate')->where('id', $currency_id)->first();
         $currency_value = $current_rate->rate;
       }

       $currency_code = 'MXN'; //En caso que no haya moneda le digo por defecto es pesos mexicanos

       if ($request->ajax()) {
           //Variables de totales
           $amount_subtotal = 0;
           $amount_discount = 0;
           $amount_untaxed = 0;
           $amount_tax = 0;
           $amount_tax_ret = 0;
           $amount_total = 0;
           $balance = 0;
           $items = [];
           $items_tc = [];

           if (!empty($input_items)) {
             foreach ($input_items as $key => $item) {
               //Logica
               $item_quantity = (double)$item['quantity'];
               $item_price_unit = (double)$item['price_unit'];
               $item_discount = (double)$item['discount'];
               if(isset($item['exchange'])){
                 $input_currency_value=(double)$item['exchange'];
               }

               $item_subtotal_quantity = round($item_price_unit * $item_quantity, 2);

               $item_price_reduce = ($item_price_unit * (100 - $item_discount) / 100); //precio del artículo reducido
               $item_amount_untaxed = round($item_quantity * $item_price_reduce, 2); //cantidad del artículo sin impuestos
               $item_amount_discount = round($item_quantity * $item_price_unit, 2) - $item_amount_untaxed; //descuento del importe del artículo
               $item_amount_tax = 0; //cantidad de impuestos
               $item_amount_tax_ret = 0; //importe del artículo reducción de impuestos
                
                if ($iva[0] != 0) {
                    foreach ($iva as $tax_id) {
                        
                        $tax = Tax::findOrFail($tax_id);
                        $tmp = 0;
                        if ($tax->factor == 'Tasa') {
                           $tmp = $item_amount_untaxed * $tax->rate / 100;
                        } elseif ($tax->factor == 'Cuota') {
                           $tmp = $tax->rate;
                        }
                        $tmp = round($tmp, 2);
                        if ($tmp < 0) { //Retenciones
                           $item_amount_tax_ret += $tmp;
                        } else { //Traslados
                           $item_amount_tax += $tmp;
                        }
                    }
                }

               $item_amount_total = $item_amount_untaxed + $item_amount_tax + $item_amount_tax_ret;
               $item_subtotal_clean = $item_subtotal_quantity;
               $item_discount_clean = $item_amount_discount;
               $item_subtotal = $item_amount_untaxed ;
               //Tipo cambio
               if ($item['current'] === $currency_id) {
                   // $item_amount_total = $item_amount_total * $currency_value;
                   $items_tc [$key] =$resp_currency_value;//ALMACENO TIPO CAMBIO
               }
               elseif ( $item['current'] != $currency_id) {
                 if ( $item['current'] === '2') { //ES DOLAR
                   if(empty($input_currency_value) || $input_currency_value==1 ){
                   $current_select_rate = DB::table('exchange_rates')->select('current_rate')->latest()->first();
                   $currency_value = $current_select_rate->current_rate;}
                   else{
                     $currency_value=$input_currency_value;
                   }
                   $currency_code = DB::table('currencies')->select('code_banxico')->where('id', $currency_id)->value('code_banxico');
                   $item_amount_tax = $item_amount_tax * $currency_value;
                   $item_amount_tax_ret = $item_amount_tax_ret * $currency_value;
                   $item_amount_total = $item_amount_total * $currency_value;
                   $item_subtotal = $item_subtotal * $currency_value;

                   $item_subtotal_clean = $item_subtotal_clean * $currency_value;
                   $item_discount_clean = $item_discount_clean * $currency_value;

                   $items_tc [$key] =$currency_value;//ALMACENO TIPO CAMBIO
                 }
                 else { //moneda distinta
                   if(empty($input_currency_value)){
                  $currency_value = DB::table('currencies')->select('rate')->where('id', $item['current'])->value('rate');
                  }else{
                  $currency_value=$input_currency_value;
                  }
                   $currency_code = DB::table('currencies')->select('code_banxico')->where('id', $item['current'])->value('code_banxico');
                   if ($currency_id === '2') { //SI LA MONEDA SELECCIONADA ES DOLAR
                      $item_amount_total = $item_amount_total/$resp_currency_value;
                      $item_amount_tax = $item_amount_tax / $resp_currency_value;
                      $item_amount_tax_ret = $item_amount_tax_ret / $resp_currency_value;
                      $item_subtotal = $item_subtotal / $resp_currency_value;

                      $item_subtotal_clean = $item_subtotal_clean / $resp_currency_value;
                      $item_discount_clean = $item_discount_clean / $resp_currency_value;


                      $items_tc [$key] =$resp_currency_value;//ALMACENO TIPO CAMBIO
                   }
                   else {
                     $item_amount_total = $item_amount_total*$currency_value;
                     $items_tc [$key] =$currency_value;//ALMACENO TIPO CAMBIO
                   }
                 }
               }

               $item_amount_untaxed = round($item_quantity * $item_amount_total, 2); //cantidad del artículo sin impuestos

               //Sumatoria totales
               $amount_subtotal += $item_subtotal_clean;

               $amount_discount += $item_discount_clean;
               $amount_untaxed += $item_subtotal;
               $amount_tax += $item_amount_tax;
               $amount_tax_ret += $item_amount_tax_ret;
               $amount_total += $item_amount_total;

               //Subtotales por cada item
               // $items[$key] = $currency_id;
               // $items[$key] = $item_amount_total;
               // $items[$key] = moneyFormat($item_amount_total, $currency_code);
               $items[$key] = $item_amount_total;
             }
           }
           //Respuesta
           $json->text = $currency_value;
           $json->items = $items;
           $json->amount_subtotal = $amount_subtotal;
           $json->amount_discount = $amount_discount;
           $json->amount_untaxed = $amount_untaxed;
           $json->amount_tax = $amount_tax + $amount_tax_ret;
           $json->amount_total = $amount_total;
           $json->amount_total_tmp = $amount_total;
           $json->tc_used = $items_tc;
           return response()->json($json);
       }
       return response()->json(['error' => __('general.error_general')], 422);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
