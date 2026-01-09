<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Models\Project;
use App\Application\DTOs\ProjectDTO;
use App\Application\UseCases\CreateProjectUseCase;
use App\Application\UseCases\ListProjectsUseCase;
use App\Application\UseCases\UpdateProjectUseCase;
use App\Application\UseCases\DeleteProjectUseCase;
use App\Domain\Repositories\ProjectRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function __construct(
        private ProjectRepositoryInterface $repo,
        private CreateProjectUseCase $createProjectUseCase,
        private ListProjectsUseCase $listProjectsUseCase,
        private UpdateProjectUseCase $updateProjectUseCase,
        private DeleteProjectUseCase $deleteProjectUseCase
    ) {
    }

    public function index(Request $request): View
    {
        $projects = $this->listProjectsUseCase->execute(10);
        return view('projects.index', compact('projects'));
    }

    public function create(): View
    {
        $project = new Project();
        return view('projects.create', compact('project'));
    }

    public function store(ProjectStoreRequest $request): RedirectResponse
    {
        $dto = ProjectDTO::fromArray($request->validated());
        $this->createProjectUseCase->execute($dto);
        return redirect()->route('projects.index')->with('success', 'Project created.');
    }

    public function edit(Project $project): View
    {
        return view('projects.edit', compact('project'));
    }

    public function update(ProjectUpdateRequest $request, Project $project): RedirectResponse
    {
        $data = $request->validated();
        $data['id'] = $project->id;
        $dto = ProjectDTO::fromArray($data);
        $this->updateProjectUseCase->execute($dto);
        return redirect()->route('projects.index')->with('success', 'Project updated.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->deleteProjectUseCase->execute($project->id);
        return redirect()->route('projects.index')->with('success', 'Project deleted.');
    }
}
