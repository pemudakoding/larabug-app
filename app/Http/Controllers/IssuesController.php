<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Project;

class IssuesController extends Controller
{
    public function index()
    {
        $projectIds = auth()->user()->projects()->pluck('id');

        $issues = Issue::query()
            ->addSelect([
                'project_name' => Project::query()
                    ->select('title')
                    ->whereColumn('project_id', 'projects.id')
                    ->latest()
                    ->take(1)
            ])
            ->whereIn('project_id', $projectIds)
            ->filter(request()->only('search'))
            ->orderBy('last_occurred_at', 'desc')
            ->paginate();

        return inertia('Issues/Index', [
            'filters' => request()->only('search'),
            'issues' => $issues,
        ]);
    }

    public function show($id)
    {
        $projectIds = auth()->user()->projects()->pluck('id');

        $issue = Issue::query()
            ->withCount('unreadExceptions')
            ->firstWhere('id', $id);

        abort_unless($projectIds->contains($issue->project_id), 403);

        $exceptions = $issue
            ->exceptions()
            ->filter(request()->only('search', 'status', 'has_feedback'))
            ->withCount('feedback')
            ->latest()
            ->paginate(10);

        $affectedVersions = $issue->exceptions()
            ->pluck('project_version')
            ->unique()
            ->filter()
            ->sort()
            ->values()
            ->toArray();

        return inertia('Issues/Show', [
            'issue' => $issue,
            'exceptions' => $exceptions,
            'project' => $issue->project,
            'filters' => request()->only('search'),
            'affected_versions' => implode(', ', $affectedVersions) ?: '-',
            'last_occurrence_human' => $issue->last_occurred_at->diffForHumans(),
            'total_occurrences' => $issue->exceptions()->count(),
        ]);
    }
}