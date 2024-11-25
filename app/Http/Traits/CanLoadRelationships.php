<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

trait CanLoadRelationships
{
    /**
     * Charge les relations spécifiées sur le modèle ou le QueryBuilder.
     *
     * @param Model|QueryBuilder|EloquentBuilder $for
     * @param array|null $relations
     * @return Model|QueryBuilder|EloquentBuilder
     */
    public function loadRelationships(Model|QueryBuilder|EloquentBuilder|HasMany $for, ?array $relations = null): Model|QueryBuilder|EloquentBuilder|HasMany
    {
        // Utilise les relations par défaut si aucune n'est spécifiée
        $relations = $relations ?? $this->relations ?? [];

        foreach ($relations as $relation) {
            // Charge les relations en fonction de la condition
            if ($this->shouldIncludeRelation($relation)) {
                if ($for instanceof Model) {
                    $for->load($relation);
                } elseif ($for instanceof EloquentBuilder || $for instanceof QueryBuilder) {
                    $for->with($relation);
                }
            }
        }

        return $for;
    }

    /**
     * Détermine si une relation doit être incluse.
     *
     * @param string $relation
     * @return bool
     */
    protected function shouldIncludeRelation(string $relation): bool
    {
        $include = request()->query('include');

        // Si aucune relation à inclure n'est spécifiée, retourne false
        if (!$include) {
            return false;
        }

        // Vérifie si la relation est dans la liste des relations incluses
        $relations = array_map('trim', explode(',', $include));
        return in_array($relation, $relations);
    }
}
