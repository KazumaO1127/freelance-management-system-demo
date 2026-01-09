<?php

namespace App\Infrastructure\Repositories;

use App\Application\DTOs\ProjectDTO;
use App\Domain\Repositories\ProjectRepositoryInterface;
use App\Models\Project;
use Illuminate\Contracts\Pagination\Paginator;

class EloquentProjectRepository implements ProjectRepositoryInterface
{
    public function save(ProjectDTO $project): void
    {
        $model = $project->id ? Project::find($project->id) : new Project();
        $model->title = $project->title;
        $model->client_name = $project->client_name;
        $model->unit_price = $project->unit_price;
        $model->start_date = $project->start_date;
        $model->end_date = $project->end_date;
        $model->status = $project->status->value;
        $model->memo = $project->memo;
        $model->user_id = $project->user_id;
        $model->save();
    }

    public function findById(int $id): ?ProjectDTO
    {
        $m = Project::find($id);
        if (! $m) return null;

        return ProjectDTO::fromArray([
            'id' => $m->id,
            'title' => $m->title,
            'client_name' => $m->client_name,
            'unit_price' => $m->unit_price,
            'start_date' => $m->start_date?->format('Y-m-d'),
            'end_date' => $m->end_date?->format('Y-m-d'),
            'status' => $m->status,
            'memo' => $m->memo,
            'user_id' => $m->user_id,
        ]);
    }

    public function paginate(int $perPage = 10): Paginator
    {
        return Project::orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function delete(int $id): void
    {
        Project::destroy($id);
    }
}
