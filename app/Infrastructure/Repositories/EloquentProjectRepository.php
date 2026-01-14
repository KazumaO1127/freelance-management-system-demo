<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Models\Project as DomainProject;
use App\Domain\Repositories\ProjectRepositoryInterface;
use App\Models\Project as EloquentProject;
use Illuminate\Contracts\Pagination\Paginator;

class EloquentProjectRepository implements ProjectRepositoryInterface
{
    public function save(DomainProject $project): DomainProject
    {
        $pr = $project->toPrimitives();
        $model = $pr['id'] ? EloquentProject::find($pr['id']) : new EloquentProject();
        $model->fill($pr);
        $model->save();

        $saved = EloquentProject::find($model->id);
        $primitives = [
            'id' => $saved->id,
            'title' => $saved->title,
            'client_name' => $saved->client_name,
            'unit_price' => $saved->unit_price,
            'start_date' => $saved->start_date?->format('Y-m-d'),
            'end_date' => $saved->end_date?->format('Y-m-d'),
            'status' => $saved->status,
            'memo' => $saved->memo,
            'user_id' => $saved->user_id,
        ];

        return DomainProject::fromPrimitives($primitives);
    }

    public function findById(int $id): ?DomainProject
    {
        $m = EloquentProject::find($id);
        if (! $m) return null;

        // Map Eloquent -> primitives -> DomainProject
        $primitives = [
            'id' => $m->id,
            'title' => $m->title,
            'client_name' => $m->client_name,
            'unit_price' => $m->unit_price,
            'start_date' => $m->start_date?->format('Y-m-d'),
            'end_date' => $m->end_date?->format('Y-m-d'),
            'status' => $m->status,
            'memo' => $m->memo,
            'user_id' => $m->user_id,
        ];

        return DomainProject::fromPrimitives($primitives);
    }

    public function paginate(int $perPage = 10): Paginator
    {
        return EloquentProject::orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function delete(int $id): void
    {
        EloquentProject::destroy($id);
    }
}
