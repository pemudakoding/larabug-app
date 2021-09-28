<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ExceptionResource;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        return ProjectResource::collection(
            $request->user()->projects()->withCount('unreadExceptions')->paginate()
        );
    }

    public function show(Request $request, $id)
    {
        return new ProjectResource(
            $request->user()->projects()->findOrFail($id)
        );
    }

    public function exceptions(Request $request, $id)
    {
        $project = $request->user()
            ->projects()
            ->findOrFail($id);

        return ExceptionResource::collection(
            $project->exceptions()->latest()->paginate()
        );
    }

    public function exception(Request $request, $id, $exceptionId)
    {
        $project = $request->user()
            ->projects()
            ->findOrFail($id);

        return new ExceptionResource(
            $project->exceptions()->findOrFail($exceptionId)
        );
    }

    public function store(Request $request)
    {
        $project = Project::create($request->only([
            'title',
            'description',
            'url',
            'receive_email'
        ]));

        $request->user()->projects()->save($project, ['owner' => true]);

        return $project;
    }
}
