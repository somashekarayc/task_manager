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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->success(['tasks' => TaskResource::collection(Task::where('user_id', Auth::id())->get()),]);
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return $this->success(['task' => new TaskResource($task)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $request->validate(['title' => ['required', 'string'], 'details' => ['nullable', 'string']]);

        $task->title = $request->title;
        $task->details = $request->details;
        $task->save();

        return $this->success(['task' => new TaskResource($task)]);

    }

    public function updateStatus(Request $request, Task $task)
    {
        $request->validate(['status' => ['required', 'string', new Enum(TaskStatus::class)]]);

        $task->status = $request->status;
        $task->save();

        return $this->success(['task' => new TaskResource($task)]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return $this->success();
    }
}
