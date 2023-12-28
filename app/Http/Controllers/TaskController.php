<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

class TaskController extends Controller
{

    public function index(Request $request)
    {
        $request->validate([
            'sort_by' => ['nullable', 'in:created_at,updated_at,title,details,status'],
            'sort_dir' => ['nullable', 'in:asc,desc'],
            'search' => ['nullable', 'string'],
        ]);

        $query = Task::where('user_id', Auth::id());

        if($request->search ?? false)
        {
            $query->where(function ($q) use ($request){
                $q->orWhere('title', 'LIKE', '%' . $request->search . '%');
                $q->orWhere('details', 'LIKE', '%' . $request->search . '%');
                $q->orWhere('status', 'LIKE', '%' . $request->search . '%');
            });
        }


        $paginated = $query
        ->orderBy($request->sort_by ?? $this->sortBy, $request->sort_dir ?? $this->sortDir)
        ->paginate($this->itemsPerPage);

        return $this->success([
            'tasks' => TaskResource::collection($paginated),
            'total' => $paginated->total(),
            'page' => $paginated->currentPage(),
            'lastPage' => $paginated->lastPage(),
        ]);
    }


    public function store(Request $request)
    {
        $request->validate(['title' => ['required', 'string'], 'details' => ['string', 'required']]);

        $task = new Task();
        $task->user_id = Auth::id();
        $task->status = TaskStatus::ToDo->value;
        $task->title = $request->title;
        $task->details = $request->details;
        $task->save();

        return $this->success(['task' => new TaskResource($task)]);
    }


    public function show(Task $task)
    {
        abort_unless($task->user_id === Auth::id(), 403);

        return $this->success(['task' => new TaskResource($task)]);
    }

    public function update(Request $request, Task $task)
    {
        abort_unless($task->user_id === Auth::id(), 403);

        $request->validate(['title' => ['required', 'string'], 'details' => ['nullable', 'string']]);

        $task->title = $request->title;
        $task->details = $request->details;
        $task->save();

        return $this->success(['task' => new TaskResource($task)]);
    }

    public function updateStatus(Request $request, Task $task)
    {
        abort_unless($task->user_id === Auth::id(), 403);

        $request->validate(['status' => ['required', 'string', new Enum(TaskStatus::class)]]);

        $task->status = $request->status;
        $task->save();

        return $this->success(['task' => new TaskResource($task)]);
    }


    public function destroy(Task $task)
    {
        abort_unless($task->user_id === Auth::id(), 403);

        $task->delete();

        return $this->success();
    }

}
