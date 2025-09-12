<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MeetingController extends Controller
{
    use AuthorizesRequests;

    /**
     * Register new Meeting 
     */
    public function registerMeeting(Request $request)
    {
       
        $validated = $this->validateMeeting($request);
        if(is_object($validated ) && $validated->fails()) {
            return response()->json($validated->errors(), 400);
        }
        $meeting = Meeting::create($request->all());

        return response()->json([
            'message' => 'Meeting created successfully',
            'prospect' => $meeting
        ], 201);
    }

    /**
     * List all Meeting
     */
    public function listMeeting(Request $request)
    {
        $user = $request->user();

        $meeting = match((int)$user->role_id) {
            1 => Meeting::get(),
            2, 3, 4 => Meeting::with('franchise')
                    ->where('franchise_id', $user->franchise_id)
                    ->get(),
            default => null
        };

        if (!$meeting) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'message' => 'Meeting retrieved successfully',
            'meetings' => $meeting
        ], 200);
    }

    /**
     * Get meeting details
     */
    public function meetingDetails(Request $request, $id)
    {
        $meeting = Meeting::where('id' , $id)->first();
       if(empty($meeting)) {
            return response()->json([
            'message' => 'Prospect no exist'
        ], 404);
        }
        return response()->json([
            'message' => 'Meeing details retrieved successfully',
            'meeting' => $meeting
        ], 200);

    }


    /**
     * Update a meeting
     */
    public function updateProspect(Request $request, $id)
    { 
        $meeting = Meeting::where('id' , $id)->first();
       
        if(empty($prospect)) {
            return response()->json([
            'message' => 'Meeting no exist'
        ], 404);
        }

        $validated = $this->validateMeeting($request, $meeting);
        
        if(is_object($validated ) && $validated->fails()) {
            return response()->json($validated->errors(), 422);
        };

        $validated = Meeting::where(['id' => $id])->update(request()->all());

        return response()->json([
            'message' => 'Meeting update successfully',
            'meeting' => $validated
        ], 201);
     }
    

    /**
     * Delete a meeeting
     */
    public function deleteProspect(Request $request, $id)
    {
        $user = $request->user();

        $meeting = match((int)$user->role_id) {
            1 => Meeting::where('id' , $id)->first()->delete(),
            2 => Meeting::where('id' , $id)->where('franchise_id', $user->franchise_id)->first()->delete(),
            default => null
        };

               
        if(empty($meeting)) {
            return response()->json([
            'message' => 'Meeting no exist'
        ], 404);
    }

        return response()->json([
            'message' => 'Meeting deleted successfully'
        ], 200);
    }

    /**
     * Validate prospect data
     */
    private function validateProspect(Request $request, $prospect = null)
    {
        $rules = [
        'prospect_aradial_id' => 'required|exists:prospect_aradial, id',
        'user_id' => 'nullable|exists:users, id',
        'date_time1'=> 'requiered|datetime',
        'date_time2' => 'required|datetime|after:date_time1' ,
        'latitude' => 'required|numeric|beetween:90,-90', 
        'longitude' => 'requiered|numeric|beetween:180,-180',
        ];


        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $validator;
        }
Log::info(print_r($validator->validate(),true));
        return $validator->validate();
    }
}
