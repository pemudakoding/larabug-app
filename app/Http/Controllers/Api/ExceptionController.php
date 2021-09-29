<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\ExceptionResource;
use App\Models\Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExceptionController extends Controller
{
    public function index(Request $request)
    {
        return ExceptionResource::collection(
            Exception::whereIn('project_id', $request->user()->projects()->pluck('id')->toArray())
                ->with('project:id,title')
                ->latest('created_at')
                ->limit(8)
                ->get([
                    'id',
                    'status',
                    'class',
                    'fullUrl',
                    'method',
                    'file',
                    'file_type',
                    'line',
                    'project_id',
                    'exception',
                    'created_at',
                ])
        );
    }

    public function show(Request $request, $exceptionId)
    {
        $exception = Exception::whereIn('project_id', $request->user()->projects()->pluck('id')->toArray())
            ->findOrFail($exceptionId, [
                'class',
                'created_at',
                'error',
                'exception',
                'id',
                'project_id',
                'line',
                'status'
            ]);

        if ($exception->status == Exception::OPEN) {
            $exception->markAsRead();
            $exception->markAsMailed();
        }

        return new ExceptionResource($exception);
    }

    public function markAs(Request $request, $exceptionId)
    {
        $projects = $request->user()->projects()->get(['id'])->pluck('id')->toArray();

        $exception = Exception::whereIn('project_id', $projects)
            ->findOrFail($exceptionId);

        $exception->markAs($request->input('status'));

        /*
         * Also mark as mailed because the user would already know about this exception
         */
        $exception->markAsMailed();

        return $request->input('status');
    }
}
