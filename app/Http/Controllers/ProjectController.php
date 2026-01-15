<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Application\DTOs\ProjectCreateDTO;
use App\Application\UseCases\CreateProjectUseCase;
use App\Application\UseCases\ListProjectsUseCase;
use App\Application\UseCases\UpdateProjectUseCase;
use App\Application\UseCases\DeleteProjectUseCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Domain\ValueObjects\ProjectStatus;

class ProjectController extends Controller
{
    public function __construct(
        private CreateProjectUseCase $createProjectUseCase,
        private ListProjectsUseCase $listProjectsUseCase,
        private UpdateProjectUseCase $updateProjectUseCase,
        private DeleteProjectUseCase $deleteProjectUseCase,
        private \App\Application\UseCases\GetProjectUseCase $getProjectUseCase
    ) {
    }

    public function index(Request $request): View
    {
        $projects = $this->listProjectsUseCase->execute(10);
        return view('projects.index', compact('projects'));
    }

    public function create(): View
    {
        $project = new \App\Application\ViewModels\ProjectViewModel();
        $statuses = ProjectStatus::options();
        return view('projects.create', compact('project', 'statuses'));
    }

    public function store(ProjectStoreRequest $request): RedirectResponse
    {
        $dto = ProjectCreateDTO::fromArray($request->validated());
        $this->createProjectUseCase->execute($dto);
        return redirect()->route('projects.index')->with('success', '案件を作成しました。');
    }

    public function edit(int $project): View
    {
        $vm = $this->getProjectUseCase->execute($project);
        if (! $vm) abort(404);
        return view('projects.edit', ['project' => $vm, 'statuses' => ProjectStatus::options()]);
    }

    public function update(ProjectUpdateRequest $request, int $project): RedirectResponse
    {
        $data = $request->validated();
        $data['id'] = $project;
        $dto = \App\Application\DTOs\ProjectUpdateDTO::fromArray($data);
        $this->updateProjectUseCase->execute($dto);
        return redirect()->route('projects.index')->with('success', '案件を更新しました。');
    }

    public function destroy(int $project): RedirectResponse
    {
        $this->deleteProjectUseCase->execute($project);
        return redirect()->route('projects.index')->with('success', '案件を削除しました。');
    }
}
