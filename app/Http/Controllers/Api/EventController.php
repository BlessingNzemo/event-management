<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\CanLoadRelationships;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Http\Resources\EventResource;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Gate;

class EventController extends BaseController
{
    use CanLoadRelationships;

    private array $relations = ['user', 'attendees', 'attendees.user'];

    /**
     * Display a listing of the resource.
     *
     *
     */

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
        $this->authorizeResource(Event::class,'event');
    }
    public function index()
    {
        // Utilisation de la propriété $relations définie dans la classe
        $query = $this->loadRelationships(Event::query(), $this->relations);

        return EventResource::collection($query->latest()->paginate());
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
            $validated['user_id'] = $request->user()->id;

            // Créer l'événement
            $event = Event::create($validated);

            return new EventResource($this->loadRelationships($event));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur survenue lors de la création API',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Récupérer l'événement avec les relations chargées
        $event = Event::findOrFail($id);
        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {   //Avec la méthode Gate nous allons vérifier si la personne est autorisée à effectuer la tache qu'elle apprete de faire
        if (Gate::denies('update-event', $event)) {
            abort(403, "Vous n'etes pas autorisé à faire la mise à jour  de cet événément");
        }

        //$this->authorize('update-event',$event);
        //    if (!Gate::allows('update-event', $event)){
        //     abort(403, "Vous n'etes pas autorisé à faire la mise à jour  de cet événément");
        //    }
        // Valider les données
        $event->update(
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'sometimes|date',
                'end_time' => 'sometimes|date|after:start_time'
            ])
        );

        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return response(status: 204);
    }
}
