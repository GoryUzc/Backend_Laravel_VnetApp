<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\ProspectAradial;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MeetingController extends Controller {
    use AuthorizesRequests;

    /**
     * Register new Meeting 
     */
    public function registerMeeting(Request $request){
    // Validar los datos de la cita
    $validator = $this->validateMeeting($request);
    
    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }
    
    try {
        // Obtener datos validados
        $validatedData = $validator->validated();
        
        // Crear la cita en la base de datos
        $meeting = Meeting::create($validatedData);
        
        if ($meeting) {
            // Obtener información del prospecto/cliente
            $prospect = ProspectAradial::find($validatedData['prospect_aradial_id']);
            
            if (!$prospect) {
                return response()->json([
                    'error' => 'Prospecto no encontrado'
                ], 404);
            }
            
            // Preparar datos para el correo
            $emailData = [
                'fechaHora' => $validatedData['date_time1'],
                'plan' => $prospect['plan'] ?? 'No especificado',
                'direccion' => $prospect['address'] ?? 'No especificada',
                'clienteNombre' => $prospect->name . ' ' . $prospect->last_name,
                'clienteEmail' => $prospect->email,
                'clienteTelefono' => $prospect->phone,
            ];
            
            // Enviar correo de confirmación
            Mail::send('email.meetingCreated', $emailData, function ($message) use ($prospect) {
                $message->from(env('MAIL_FROM_ADDRESS'), 'VNET');
                $message->to($prospect->email);
                $message->subject('Cita Agendada con exito - VNET');
            });
            
            return response()->json([
                'message' => 'Appointment created successfully and email sent',
                'meeting' => $meeting
            ], 201);
        } else {
            return response()->json([
                'error' => 'Error creating appointment'
            ], 400);
        }
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Internal Server Error: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * List all Meeting
     */
    public function listMeeting(Request $request)
    {
        $user = $request->user();

        $meetings = match((int)$user->role_id) {
            1 => Meeting::get(),
            2, 3, 4 => Meeting::where('franchise_id', $user->franchise_id)->get(), // Usuarios con roles 2,3,4: Solo reuniones de su franquicia. 
        
            default => null
        };

        if (!$meetings) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'message' => 'Meeting retrieved successfully',
            'meetings' => $meetings
        ], 200);
    }


    /**
     * Get all meeting no assigned 
     */
    public function listMeetingUnassigned (Request $request){
        $user = $request->user();
        $meetings = match ((int)$user->role_id) {
            1 => Meeting::whereNull('user_id')->get(),
            2, 3, 4 => Meeting::whereNull('user_id')->where('franchise_id', $user->franchise_id)
            ->get(), 
            default => null
        };
         if (!$meetings) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'message' => 'Meeting retrieved successfully',
            'meetings' => $meetings
        ], 200);
    }

    /**
     * Get all meeting assigned 
     */
    public function listAllMeetingAssigned(Request $request){
        $user = $request->user();
        $meetings = match((int)$user->role_id){
            1 => Meeting::whereNotNull('user_id')->get(),
            2, 3, 4 => Meeting::with('franchise')
            ->where('franchise_id', $user->franchise_id)
            ->whereNotNull('user_id')->get(),
            default => null
        };
        if (!$meetings) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'message' => 'Meeting retrieved successfully',
            'meetings' => $meetings
        ], 200);
    }

    public function listMeetingUserAssigned(Request $request) {
        $user = $request->user();
        $meetings = Meeting::where('user_id', $user->id)
        ->where('status', 'asignada')
        ->get();
        if(!$meetings){ 
            return response()->json([ 'message' => 'User without installations'], 401);
        } else {
            return response()->json([
            'message' => 'Meeting retrieved successfully',
            'meetings' => $meetings
        ], 200);
        }
    }

    public function listMeetingUserProcess(Request $request) {
        $user = $request->user();
        $meetings = Meeting::where('user_id', $user->id)
        ->where('status', 'en_proceso')
        ->get();
        if(!$meetings){
            return response()->json([ 'message' => 'User without installations'], 401);
        }else {
            return response()->json([
            'message' => 'Meeting retrieved successfully',
            'meetings' => $meetings
        ], 200);
        }
    }


    /*
    * Init Meeting Installation
    */

    public function initMeetingUpdatedStatus(Request $request, $id) {
        $meeting = Meeting::findOrFail($id, 'id'); 
        if (!$meeting) {
            return response()->json([
                'message' => 'Meeting does not exist'
            ], 404);
        }
        $meeting->update([
            'status' => 'en_proceso'
        ]);

        return response()->json([
        'message' => 'Status updated successfully',
        'meeting' => $meeting // ← Devolver el modelo actualizado, no un número
    ], 201);
    }


    public function endMeetingUpdatedStatus(Request $request, $id) {
        $meeting = Meeting::findOrFail($id, 'id'); 
        if (!$meeting) {
            return response()->json([
                'message' => 'Meeting does not exist'
            ], 404);
        }
        $meeting->update([
            'status' => 'finalizada'
        ]);

        return response()->json([
        'message' => 'Status updated successfully',
        'meeting' => $meeting // ← Devolver el modelo actualizado, no un número
    ], 201);
    }

    /**
     * Get meeting details
     */
    public function detailsMeeting(Request $request, $id)
    {
        $meeting = Meeting::where('id' , $id)->first();
        if(empty($meeting)) {
            return response()->json([
            'message' => 'Prospect no exist'
        ], 404);
        }
        return response()->json([
            'message' => 'Meeting details retrieved successfully',
            'meeting' => $meeting
        ], 200);

    }


    /**
     * Update a meeting
     */
 public function updateMeeting(Request $request, $id)
{
    // Buscar la reunión
    $meeting = Meeting::find($id);
    if (!$meeting) {
        return response()->json([
            'message' => 'Meeting does not exist'
        ], 404);
    }

    $validator = $this->validateMeeting($request);
    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    $validatedData = $validator->validated();

    $meeting->update($validatedData);

    return response()->json([
        'message' => 'Meeting updated successfully',
        'meeting' => $meeting // ← Devolver el modelo actualizado, no un número
    ], 200);
}
    

    /**
     * Delete a meeeting
     */
    public function deleteMeeting(Request $request, $id){
        $user = $request->user();
        // Verificar rol 

        if(!in_array($user->role_id,[1, 2])){
            return response()->json([
             'error' => 'You do not have permission to delete meetings'   
            ], 403);}

            $meeting = Meeting::find('id', $id);
            if (!$meeting){
            return response()->json([
            'message' => 'Meeting does not exist'
        ], 404); 
    }
        // Eliminar
        $deleted = $meeting->delete();
    
        if ($deleted) {
            return response()->json([
                'message' => 'Meeting deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'error' => 'Failed to delete meeting'
            ], 500);
        }     
    }

public function takeMeeting(Request $request, $id){

    // Validar entrada
   $user = $request->user();

    // Obtener la orden
    $meeting = Meeting::findOrFail($id);

    // Verificar que no esté ya tomada
    if ($meeting->user_id !== null) {
        return response()->json([
            'error' => 'Esta orden ya fue tomada por otro usuario.'
        ], 409);
    }

    // Obtener el rol;
    $roleId = $user->role_id; 

    // Definir rango de conflicto (±1 hora)
    $targetDateTime = $meeting->date_time1;
    $startTime = $targetDateTime->copy()->subHour();
    $endTime = $targetDateTime->copy()->addHour();

    if ($roleId == 4) {
        $hasConflict = Meeting::where('user_id', $user->id)
            ->where('id', '!=', $meeting->id)
            ->whereBetween('date_time1', [$startTime, $endTime])
            ->exists();

        if ($hasConflict) {
            return response()->json([
                'error' => 'You cant take this order because you already have another one scheduled at this time.'

            ], 403);
        }

        $meeting->update([
            'user_id' => $user->id,
            'status' => 'asignada',
        ]);

        //Enviar correo al cliente 
        $this->SendEmailTakeMeeting($meeting, $user);

        return response()->json([
            'message' => 'Orden tomada con éxito.',
            'meeting' => $meeting
        ], 201);
    }

    if ($roleId == 3) {

        $availableWorkers = User::where(function ($query) use ($user) {
                $query->where('role_id', 4) // Trabajadores
                      ->orWhere('id', $user->id); // Incluir al contratista mismo
            })
            ->whereDoesntHave('meetings', function ($query) use ($startTime, $endTime) {
                $query->whereBetween('date_time1', [$startTime, $endTime]);
            })
            ->exists();

        if (!$availableWorkers) {
            return response()->json([
                'error' => 'You cannot take this order because there are no workers available (including you) to cover this schedule.'
            ], 403);
        }

        $meeting->update([
            'user_id' => $user->id,
            'status' => 'asignada',
        ]);

        $this->SendEmailTakeMeeting($meeting, $user);

        return response()->json([
            'message' => 'Order taken successfully.',
            'meeting' => $meeting
        ], 201);
    }

    return response()->json([
        'error' => 'Your role does not have permission to take installation orders.'
    ], 403);
}

private function SendEmailTakeMeeting($meeting, $assignedUser){
     //Obtener al prospecto a traves del aradial_prospect_id 
    $prospect = ProspectAradial::find($meeting->prospect_aradial_id);
    if (!$prospect || !$prospect->email){
        return response()->json([
            'error' => 'Cannot send email: client without email. Prospect ID: {$meeting->prospect_aradial_id}'
        ], 403); 
    }
    //Datos para la vista del correo
    $emailData = [
        'clienteNombre' => $prospect->name . ' ' . ($prospect->last_name ?? ''),
        'fechaHora' => $meeting->date_time1->format('d/m/Y \a \l\a\s H:i'),
        'tecnicoNombre' => $assignedUser->name . ' ' . ($assignedUser->last_name ?? ''),
        'telefonoTecnico' => $assignedUser->phone ?? 'No disponible',
        'direccion' => $prospect->address ?? 'Dirección no especificada',
        ]; 
    try {
        
        Mail::send('email.meetingAssigned', $emailData, function ($message) use ($prospect) {
        $message->from(env('MAIL_FROM_ADDRESS'), 'VNET');
        $message->to($prospect->email);
        $message->subject('Cita Asignada con exito - VNET');
        });
        Log::info("Correo enviado al ciente:" . $prospect->email . "para la orden ID:" . $meeting->id);
    } catch(Exception $e){
        Log::error("Error enviar el correo al cliente " . $e->getMessage());
    }
}




    /**
     * Validate prospect data
     */
    private function validateMeeting(Request $request)
    {
        return Validator::make($request->all(), [
        'prospect_aradial_id' => 'required|exists:prospect_aradial,id',
        'user_id' => 'nullable|exists:users,id',
        'date_time1'=> 'required|date_format:Y-m-d H:i:s',
        'franchise_id'=> 'required|exists:franchises,id',
        'latitude' => 'required|numeric', 
        'longitude' => 'required|numeric',
        'nro_contract' => 'required|string',
        ]);
    }
}
