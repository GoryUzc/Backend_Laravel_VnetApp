<?php

namespace App\Http\Controllers;

use App\Models\InstallationOrder;
use Exception;
use Illuminate\Http\Request;
use Validator;


class InstallationOrderController extends Controller {

    public function registerOrderInstallation(Request $request){
        
        $validator = $this->ValidateOrderInstallation($request);
        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        } 
        try{
            $validated = $validator->validate();
            
            $order = InstallationOrder::create($validated);

                return response()->json([
                    'message'=>'Order created successfully',
                    'order'=>$order
                ], 201 );
        }catch(Exception $e){
            return response()->json([
            'Internal Server Error: '. $e->getMessage()
        ], 500);
            }
    }



    public function listOrderInstallation(Request $request){

    // Obtener el usuario autenticado (gracias a tu middleware)
    $user = $request->user();

    // Construir la consulta base con relaciones
    $query = InstallationOrder::with([
        'user',
        'meeting.prospect_aradial',
        'user.franchise'
    ]);

    // Aplicar filtros según el rol del usuario autenticado
    switch ((int) $user->role_id) {
        case 1: // Admin → todas las órdenes
            break;

        case 2: // Supervisor → órdenes de su franquicia
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('franchise_id', $user->franchise_id);
            });
            break;

        case 3:
            //Ordenes de TODO por contratista:
             $query->whereHas('user', function ($q) use ($user) {
                $q->where('contractor_id', $user->contractor_id);
             });

             break;
             
             
        case 4: // Contratista o Trabajador → órdenes asignadas a ellos o a su contratista
            $query->where('user_id', $user->id);
            
            break;

        default:
            return response()->json([
                'message' => 'Your role is not authorized to view installation orders'
            ], 403);
    }

    // Filtros opcionales
    // if ($request->filled('status')) {
    //     $query->where('status', $request->status);
    // }

    // if ($request->filled('meeting_id')) {
    //     $query->where('id_meeting', $request->meeting_id);
    // }

    // Paginación  para producción
    // $orders = $query->paginate($request->get('per_page', 20));

    $orders = $query->get(); //para debbug

    return response()->json([
        'message' => 'Installation orders retrieved successfully',
        'orders' => $orders
    ], 200);
}

    public function detailOrderInstallation(Request $request, $id){
        try {
            $order = InstallationOrder::where('id', $id)->first();
         if(empty($order)) {
            return response()->json([
            'order' => 'order no exist'
        ], 404);
        }
        return response()->json([
            'message' => 'Order details retrieved successfully',
            'order' => $order
        ], 200);
        }catch(Exception $e){
            return response()->json([
            'error' => 'Internal Server Error: ' . $e->getMessage()
        ], 500);

        }  
    }


    public function updateOrderInstallation(Request $request, $id){
        try {
            $order = InstallationOrder::find('id', $id);
            if(!$order) {
            return response()->json([
            'message' => 'Order no exist'
        ], 404);
        }
        $validator = $this->validateMeeting($request);
            if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
        }

         $validatedData = $validator->validated();

         $order->update($validatedData);
        return response()->json([
        'message' => 'Order updated successfully',
        'meeting' => $order
        ], 200);
    }catch(Exception $e){
            return response()->json([
            'error' => 'Internal Server Error: ' . $e->getMessage()
        ], 500);

        }  
    }


    public function deleteOrderInstallation(Request $request, $id){
        $user = $request->user();

        //Verificar el rol del user 
        if(!in_array($user->role_id,[1])){
            return response()->json([
             'error' => 'You do not have permission to delete order'   
            ], 403);}

            $order = InstallationOrder::find('id', $id);
            if (!$order){
            return response()->json([
            'message' => 'Order does not exist'
        ], 404); 
    }
        // Eliminar
        $deleted = $order->delete();
    
        if ($deleted) {
            return response()->json([
                'message' => 'Order deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'error' => 'Failed to delete Order'
            ], 500);
        }
    }

    public function uploadSignature(Request $request, $id){
        // Validar que el archivo sea un imagen
        $request->validate([
            'signature' => 'required|image|mimes:png,jpg,jpeg|max2048', //Max 2MB
        ]);

        try{

        $order = InstallationOrder::find($id);
        if(!$order){
            return response()->json([
                'message' => 'Order no found',
            ]);
            }
            // Delete previous signature if exist
            if ($order->signature_path) {
                Storage::delete(InstallationOrder::find($id)->signature_path);
            }

            // Save new signature 
            $paht = $request->file('signature')->Storage('signatures', 'public');
            $order->update(['signature_path' => $paht]);

            return response()-> json([
                'message' => 'Signature uploaded successfully',
                'signature_url' => $order->signature_url,
            ], 200);  
        }catch(Exception $e){
            return response()->json([
                'error' => 'Internal Server Error: ' . $e->getMessage()
            ], 500);
        }
    }

    private function ValidateOrderInstallation(Request $request){
        return Validator::make(
            $request->all(),
            [
                'user_id'=>'required|exists:users,id',
                'id_meeting' =>'required|exists:meeting,id',
                'prospect_aradial_id'=>'required|exists:prospect_aradial,id',
                'ont_puerto_1' =>'required|numeric|min:0',
                'conector_sc_pc'=>'required|numeric|min:0',
                'patch_cord_scpc-scapc'=>'required|numeric|min:0',
                'roseta'=>'required|numeric|min:0',
                'adapter_scapc'=>'required|numeric|min:0',
                'ont_4_puertos'=>'required|numeric|min:0',
                'conector_sc_upc'=>'required|numeric|min:0',
                'canaletas'=>'required|numeric|min:0',
                'ramplug'=>'required|numeric|min:0',
                'cable_drop'=>'required|numeric|min:0',
                'hilos'=>'required|numeric|min:0',
                'potencia_recibida_ont'=>'required|string',
                'mac_ont'=>'required|string',
                'serial_ont'=>'required|string',
                'puerto_nap'=>'required|string',
                'ppoe_user'=>'required|string',
                'ppoe_password'=>'required|string',
                'ubicacion_onu'=>'required|string',
                'nro_equipos_conectar'=>'required|string',
                'puerto_olt'=>'required|string',
                'etiqueta_cliente'=>'required|string',
                'router'=>'required|string',
                'detalles_instalacion'=>'required|string',
            ]);
    }
}
