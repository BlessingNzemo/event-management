<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use App\Models\Attendee;
use App\Http\Resources\EventResource;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        //   return response()->json([
        //        'data' => Event::with('attendees')->get()
        //      ]);

        // return Event::all();
        // }

        $events = Event::with('user')->get();
        return EventResource::collection(Event::with('user')->get());
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Valider les données
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time'
            ]);

            // Ajouter user_id aux données validées
            $validated['user_id'] = 1;

            // Créer l'événement
            $event = Event::create($validated);

            return new EventResource($event);


            // response()->json(
            //     [
            //         'message'=> 'Element crée avec succès!',
            //         'data'=>$event
            //     ],201
            // );

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating event',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $event = Event::with(['user', 'attendees'])->findOrFail($id);
        return new EventResource($event);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        //
        // Valider les données
        // return $event->update($request->validate([
        //     'name' => 'sometimes|string|max:255',
        //     'description' => 'nullable|string',
        //     'start_time' => 'sometimes|date',
        //     'end_time' => 'sometimes|date|after:start_time'
        // ]));

        // ou

        $event->update(

            $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'sometimes|date',
                'end_time' => 'sometimes|date|after:start_time'
            ])
        );

        return new EventResource($event);

        // return ($event);


        // Quand on fait un update de l'API au lieu de required il faut utiliser un sometimes


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        //
        $event->delete();
        // return response()->json ([
        //     'message'=>"Event deleted successfully"
        // ]);

        return response(status: 204);
    }
}
