<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $projects = Project::orderBy('created_at', 'desc')->paginate(10);
        return view('projects.index', compact('projects'));
    }

    public function create(): View
    {
        $project = new Project();
        return view('projects.create', compact('project'));
    }

    public function store(ProjectStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        Project::create($data);
        return redirect()->route('projects.index')->with('success', 'Project created.');
    }

    public function edit(Project $project): View
    {
        return view('projects.edit', compact('project'));
    }

    public function update(ProjectUpdateRequest $request, Project $project): RedirectResponse
    {
        $project->update($request->validated());
        return redirect()->route('projects.index')->with('success', 'Project updated.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted.');
    }
}
