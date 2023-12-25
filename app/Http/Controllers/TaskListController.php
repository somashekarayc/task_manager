<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskList;
use App\Enums\TaskStatus;
use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TaskListResource;

class TaskListController extends Controller
{

    public function index()
    {
        return $this->success(['task_lists' => TaskListResource::collection(TaskList::where('user_id', Auth::id())->get())]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string'],
        ]);

        $taskList = new TaskList();
        $taskList->user_id = Auth::id();
        $taskList->title = $request->title;
        $taskList->save();

        return $this->success(['task_list' => new TaskListResource($taskList),]);
    }


    public function show(TaskList $taskList)
    {
        return $this->success(['task_list', new TaskListResource($taskList),]);
    }
    public function tasks(TaskList $taskList)
    {
        return $this->success(['tasks', TaskResource::collection($taskList->tasks),]);
    }

    public function addTask(Request $request, TaskList $taskList)
    {
        $request->validate(['title' => ['required', 'string'], 'details' => ['string', 'required']]);

        $task = new Task();
        $task->user_id = Auth::id();
        $task->task_list_id = $taskList->id;
        $task->status = TaskStatus::ToDo->value;
        $task->title = $request->title;
        $task->details = $request->details;
        $task->save();

        return $this->success(['task' => new TaskResource($task)]);
    }

    public function update(Request $request, TaskList $taskList)
    {
        $request->validate([
            'title' => ['required', 'string'],
        ]);

        $taskList->title = $request->title;
        $taskList->save();

        return $this->success(['task_list' => new TaskListResource($taskList),]);
    }

    public function destroy(TaskList $taskList)
    {
        $taskList->delete();

        return $this->success();
    }
}
