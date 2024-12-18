<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as Controller;
use App\Http\Resources\AttendeeResource;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Attendee;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AttendeeController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     */

    use AuthorizesRequests;
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show', 'update']);
        $this->authorizeResource(Attendee::class, 'attendee');
    }


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
    public function destroy(Event $event, Attendee $attendee)
    {
        // $this->authorize('delete-attendee', [$event, $attendee]);

        // Vérifiez les autorisations

        //$attendee = $event->attendees()->findOrFail($id);
        $this->authorize('delete-attendee', [$event, $attendee]);

        try {
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
