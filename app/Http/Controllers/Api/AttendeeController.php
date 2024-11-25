<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Attendee;

class AttendeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Event $event)
    {
        //
        $attendees = $event->attendees()->latest();

        return AttendeeResource::collection(
            $attendees->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        //
        $attendee = $event->attendees()->create([
            'user_id' => 1,
        ]);

        return new AttendeeResource($attendee);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, $id)
    {
        try {
            $attendee = $event->attendees()->findOrFail($id);
            return new AttendeeResource($attendee);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la récupération du participant',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, $id)
{
    try {
        $attendee = $event->attendees()->findOrFail($id);
        $attendee->delete();
        return response(status: 204);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Erreur lors de la suppression du participant',
            'error' => $e->getMessage()
        ], 500);
    }
}
}
