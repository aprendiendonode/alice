<?php

namespace App\Http\Controllers\Projects;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Projects\Documentp_status_user;
use App\Models\Projects\{Documentp, Documentp_cart, In_Documentp_cart, Cotizador};
use App\Models\Projects\{Kickoff_approvals, Kickoff_compras, Kickoff_contrato, Kickoff_instalaciones, Kickoff_lineabase, Kickoff_perfil_cliente, Kickoff_project, Kickoff_soporte};
use App\Mail\SolicitudCompra;
use App\Models\Base\Message;
use App\Notifications\MessageDocumentp;
use App\User;
use View;
use PDF;
use Mail;
use Auth;
use DB;

class KickoffController extends Controller
{
    public function index(Request $request)
    {
      $id = $request->id_doc_3;
      $document = DB::select('CALL px_documentop_data(?)', array($id));

      $documentP = Documentp::find($id);
      $id_document = $documentP->id;

      $in_document_cart = In_Documentp_cart::where('documentp_cart_id', $document[0]->documentp_cart_id)->first();
      $tipo_cambio = $in_document_cart->tipo_cambio;
      $installation = DB::table('documentp_installation')->select('id', 'name')->get();

      $vtc = "Proyecto sin cotizador";
      $cotizador = DB::table('cotizador')->select('id', 'id_doc')->where('id_doc', $document[0]->id)->get();

      if(count($cotizador) == 1) {
        $objetivos = DB::table('cotizador_objetivos')->select()->where('cotizador_id', $cotizador[0]->id)->get();
        $vtc = $objetivos[0]->vtc;
      }
      //KICKOFF DATA
      $kickoff = Kickoff_project::firstOrCreate(['id_doc' => $id_document]);
      $kickoff_approvals = Kickoff_approvals::firstOrCreate(['kickoff_id' => $kickoff->id]);
      $kickoff_compras = Kickoff_compras::firstOrCreate(['kickoff_id' => $kickoff->id]);
      $kickoff_contrato = Kickoff_contrato::firstOrCreate(['kickoff_id' => $kickoff->id]);
      $kickoff_instalaciones = Kickoff_instalaciones::firstOrCreate(['kickoff_id' => $kickoff->id]);
      $kickoff_lineabase = Kickoff_lineabase::firstOrCreate(['kickoff_id' => $kickoff->id]);
      $kickoff_perfil_cliente = Kickoff_perfil_cliente::firstOrCreate(['kickoff_id' => $kickoff->id]);
      $kickoff_soporte = Kickoff_soporte::firstOrCreate(['kickoff_id' => $kickoff->id]);
      //dd($kickoff_contrato);
      return view('permitted.planning.kick_off_edit', compact('document','installation' ,'tipo_cambio', 'vtc', 'kickoff_approvals',
                  'kickoff_contrato', 'kickoff_instalaciones','kickoff_compras' ,'kickoff_lineabase', 'kickoff_perfil_cliente', 'kickoff_soporte' ));
    }

    public function update(Request $request)
    {
      $flag  = "false";
      $id = $request->id;

      DB::beginTransaction();
      try {
        //DOCUMENTO P
        $documentp = Documentp::find($id);
        $documentp->num_oportunidad = $request->num_oportunidad;
        $documentp->num_sitios = $request->sitios;
        $documentp->lugar_instalacion_id = $request->lugar_instalacion;
        $documentp->updated_at = \Carbon\Carbon::now();
        $documentp->save();

        $kickoff = Kickoff_project::where('id_doc', $documentp->id)->first();
        //PERFIL CLIENTE
        DB::table('kickoff_perfil_cliente')->where('kickoff_id', $kickoff->id)->update([
           'rfc' => $request->rfc,
           'razon_social' => $request->razon_social,
           'edo_municipio' => $request->edo_municipio,
           'contacto' => $request->contacto,
           'puesto' => $request->puesto,
           'telefono' => $request->telefono,
           'email' => $request->email,
           'direccion' => $request->direccion,
           'updated_at' => \Carbon\Carbon::now()
        ]);
        //CONTRATO
        DB::table('kickoff_contrato')->where('kickoff_id', $kickoff->id)->update([
           'num_contrato' => $request->num_contrato,
           'fecha_inicio' => $request->fecha_inicio,
           'fecha_termino' => $request->fecha_termino,
           'fecha_entrega' => $request->fecha_entrega,
           'servicio' => $request->servicio,
           'autorizacion_sitwifi' => $request->autorizacion_sitwifi,
           'autorizacion_cliente' => $request->autorizacion_cliente,
           'mantenimiento_vigencia' => $request->mantenimiento_vigencia,
           'tipo_adquisicion' => $request->tipo_adquisicion,
           'tipo_pago' => $request->tipo_pago,
           'vendedor' => $request->vendedor,
           'inside_sales' => $request->inside_sales,
           'cierre' => $request->cierre,
           'contacto' => $request->contacto_comercial,
           'updated_at' => \Carbon\Carbon::now()
        ]);
        //INSTALACIONES
        DB::table('kickoff_instalaciones')->where('kickoff_id', $kickoff->id)->update([
           'fecha_inicio' => $request->fecha_inicio_instalacion,
           'fecha_termino' => $request->fecha_termino_instalacion,
           'viaticos_proveedor' => $request->viaticos_proveedor,
           'calidad_contratista' => $request->calidad_contratista,
           'fecha_mantenimiento' => $request->fecha_mantenimiento,
           'fecha_acta_entrega' => $request->fecha_acta_entrega,
           'fecha_entrega_memoria_tecnica' => $request->fecha_entrega_memoria_tecnica,
           'observaciones' => $request->observaciones,
           'updated_at' => \Carbon\Carbon::now()
        ]);
        //SOPORTE
        DB::table('kickoff_soporte')->where('kickoff_id', $kickoff->id)->update([
           'licencias' => $request->licencias,
           'proveedor_enlace' => $request->proveedor_enlace,
           'plazo_enlace' => $request->plazo_enlace,
           'fecha_mantenimiento' => $request->fecha_mantenimiento_soporte,
           'cantidad_equipos_monitoriados' => $request->cantidad_equipos_monitoriados,
           'nombre_ti_cliente' => $request->nombre_ti_cliente,
           'updated_at' => \Carbon\Carbon::now()
        ]);
        //COMPRAS
        DB::table('kickoff_compras')->where('kickoff_id', $kickoff->id)->update([
           'fecha_entrega_ea' => $request->fecha_entrega_ea,
           'fecha_entrega_ena' => $request->fecha_entrega_ena,
           'fecha_operacion_enlace' => $request->fecha_entrega_operacion_enlace,
           'fecha_contratacion_enlace' => $request->fecha_contratacion_enlace,
           'proveedor1' => $request->proveedor1,
           'proveedor2' => $request->proveedor2,
           'proveedor3' => $request->proveedor3,
           'proveedor4' => $request->proveedor4,
           'proveedor5' => $request->proveedor5,
           'updated_at' => \Carbon\Carbon::now()
        ]);

        DB::commit();
        $flag  = "true";

      } catch(\Exception $e){
        $e->getMessage();
        DB::rollback();
        //dd($e);
        return $e;
      }

      return $flag;
    }

    public function approval_administracion($id)
    {
      $flag = "0";
      $documentp = Documentp::find($id);
      $kickoff = Kickoff_project::where('id_doc', $documentp->id)->first();

        DB::beginTransaction();
        try {
          DB::table('kickoff_approvals')->where('kickoff_id', $kickoff->id)->update([
             'administracion' => 1,
             'updated_at' => \Carbon\Carbon::now()
          ]);
          //Cambiando status de documento a "EN REVISIÓN"
          $documentp->status_id = 2;
          $documentp->save();

          $user = Auth::user()->id;
          $new_doc_state = new Documentp_status_user;
          $new_doc_state->documentp_id = $documentp->id;
          $new_doc_state->user_id = $user;
          $new_doc_state->status_id = '2';
          $new_doc_state->save();

          if(!$this->check_approvals($documentp->id)){
            $flag = "1";
          }else{
            $flag = "2";
          }
          DB::commit();

      } catch(\Exception $e){
        $e->getMessage();
        DB::rollback();
        return $e;
      }

      return $flag;
    }

    public function approval_comercial($id)
    {
      $flag = "0";
      $documentp = Documentp::find($id);
      $kickoff = Kickoff_project::where('id_doc', $documentp->id)->first();

      DB::beginTransaction();
      try {
        DB::table('kickoff_approvals')->where('kickoff_id', $kickoff->id)->update([
           'comercial' => 1,
           'updated_at' => \Carbon\Carbon::now()
        ]);
        //Cambiando status de documento a "EN REVISIÓN"
        $documentp->status_id = 2;
        $documentp->save();

        $user = Auth::user()->id;
        $new_doc_state = new Documentp_status_user;
        $new_doc_state->documentp_id = $documentp->id;
        $new_doc_state->user_id = $user;
        $new_doc_state->status_id = '2';
        $new_doc_state->save();

        if(!$this->check_approvals($documentp->id)){
          $flag = "1";
        }else{
          $flag = "2";
        }
        DB::commit();

    } catch(\Exception $e){
      $e->getMessage();
      DB::rollback();
      return $e;
    }

      return $flag;
    }

    public function approval_proyectos($id)
    {
      $flag = "0";
      $documentp = Documentp::find($id);
      $kickoff = Kickoff_project::where('id_doc', $documentp->id)->first();

      DB::beginTransaction();
      try {
        DB::table('kickoff_approvals')->where('kickoff_id', $kickoff->id)->update([
           'proyectos' => 1,
           'updated_at' => \Carbon\Carbon::now()
        ]);
        //Cambiando status de documento a "EN REVISIÓN"
        $documentp->status_id = 2;
        $documentp->save();

        $user = Auth::user()->id;
        $new_doc_state = new Documentp_status_user;
        $new_doc_state->documentp_id = $documentp->id;
        $new_doc_state->user_id = $user;
        $new_doc_state->status_id = '2';
        $new_doc_state->save();

        if(!$this->check_approvals($documentp->id)){
          $flag = "1";
        }else{
          $flag = "2";
        }
        DB::commit();

    } catch(\Exception $e){
      $e->getMessage();
      DB::rollback();
      return $e;
    }

      return $flag;
    }

    public function approval_soporte($id)
    {
      $flag = "0";
      $documentp = Documentp::find($id);
      $kickoff = Kickoff_project::where('id_doc', $documentp->id)->first();

      DB::beginTransaction();
      try {
        DB::table('kickoff_approvals')->where('kickoff_id', $kickoff->id)->update([
           'soporte' => 1,
           'updated_at' => \Carbon\Carbon::now()
        ]);
        //Cambiando status de documento a "EN REVISIÓN"
        $documentp->status_id = 2;
        $documentp->save();

        $user = Auth::user()->id;
        $new_doc_state = new Documentp_status_user;
        $new_doc_state->documentp_id = $documentp->id;
        $new_doc_state->user_id = $user;
        $new_doc_state->status_id = '2';
        $new_doc_state->save();

        if(!$this->check_approvals($documentp->id)){
          $flag = "1";
        }else{
          $flag = "2";
        }
        DB::commit();

    } catch(\Exception $e){
      $e->getMessage();
      DB::rollback();
      return $e;
    }

      return $flag;
    }

    public function approval_planeacion($id)
    {
      $flag = "0";
      $documentp = Documentp::find($id);
      $kickoff = Kickoff_project::where('id_doc', $documentp->id)->first();

      DB::beginTransaction();
      try {
        DB::table('kickoff_approvals')->where('kickoff_id', $kickoff->id)->update([
           'planeacion' => 1,
           'updated_at' => \Carbon\Carbon::now()
        ]);
        //Cambiando status de documento a "EN REVISIÓN"
        $documentp->status_id = 2;
        $documentp->save();

        $user = Auth::user()->id;
        $new_doc_state = new Documentp_status_user;
        $new_doc_state->documentp_id = $documentp->id;
        $new_doc_state->user_id = $user;
        $new_doc_state->status_id = '2';
        $new_doc_state->save();

        if(!$this->check_approvals($documentp->id)){
          $flag = "1";
        }else{
          $flag = "2";
        }
        DB::commit();

    } catch(\Exception $e){
      $e->getMessage();
      DB::rollback();
      return $e;
    }

      return $flag;
    }

    public function check_approvals($id_doc)
    {
      $documentp = Documentp::findOrFail($id_doc);
      $kickoff = Kickoff_project::where('id_doc', $documentp->id)->first();
      $kickoff_approvals = Kickoff_approvals::where('kickoff_id', $kickoff->id)->first();
      //Revisando si todos los departamentos ya revisaron el documento
      if($kickoff_approvals->administracion == 1 && $kickoff_approvals->comercial == 1 && $kickoff_approvals->proyectos == 1 &&
          $kickoff_approvals->soporte == 1 && $kickoff_approvals->planeacion == 1){
          $kickoff_approvals->fecha_aprobacion_all = \Carbon\Carbon::now();
          $kickoff_approvals->save();
          //Cambiando status de documento  a "AUTORIZADO"
          $documentp->status_id = 3;
          $documentp->doc_type = 1;//Cambiando a "DOCUMENTO P"
          $documentp->fecha_aprobacion = \Carbon\Carbon::now();
          $documentp->save();
          $this->update_linea_base($documentp->id);
          $this->sendNotifications($documentp->id);
          return true;
      }else{
        return false;
      }

    }

    public function update_linea_base($id_doc)
    {
      $documentp = Documentp::findOrFail($id_doc);
      $kickoff = Kickoff_project::where('id_doc', $documentp->id)->first();
      $lineaBase = Kickoff_lineabase::where('kickoff_id', $kickoff->id)->first();
      $lineaBase->total_ea = $documentp->total_ea;
      $lineaBase->total_ena = $documentp->total_ena;
      $lineaBase->total_mo = $documentp->total_mo;
      $lineaBase->total_usd = $documentp->total_usd;
      $lineaBase->save();
    }

    public function sendNotifications($id_doc)
    {
      $documentp = Documentp::findOrFail($id_doc);
      //NOTIFICACIONES
      $recipient_users = json_decode(User::find([258, 6, 7])); //Todos los usuarios nivel 1
      array_push($recipient_users, (object) array('id' => Auth::user()->id)); //Creador de la notificación

      $recipients = [];

      foreach($recipient_users as $user) { array_push($recipients, $user->id); }

      $recipients = array_unique($recipients);

      foreach($recipients as $recipient_id) {

        $message = Message::create([
          'sender_id' => auth()->id(),
          'recipient_id' => $recipient_id,
          'body' =>  'Proyecto aprobado',
          'folio' => $documentp->folio,
          'proyecto' => $documentp->nombre_proyecto,
          'status' => 'Autorizado',
          'date' => \Carbon\Carbon::now(),
          'link' => route('view_history_documentp')
        ]);

        $recipient = User::find($recipient_id);
        $recipient->notify(new MessageDocumentp($message));
      }

    }


}