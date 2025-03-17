<?php

namespace App\Http\Controllers\Admin\Crm\Board;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AssignTaskFormRequest;
use App\Http\Requests\TaskFormRequest;
use App\Models\Event;
use App\Models\Task;
use App\Repositories\Board\TaskRepository;
use Illuminate\Http\Request;

//CMS

class TaskController extends Controller
{
    private $repository;

    public function __construct(TaskRepository $repository)
    {
//        $this->middleware('permission:box-list|box-create|box-edit|box-delete', [
//            'only' => ['index','store']
//        ]);
//        $this->middleware('permission:box-create', [
//            'only' => ['create','store']
//        ]);
//        $this->middleware('permission:box-edit', [
//            'only' => ['edit','update']
//        ]);
//        $this->middleware('permission:box-delete', [
//            'only' => ['destroy']
//        ]);

        $this->repository = $repository;
    }

    public function form(Request $request)
    {
        if (request()->ajax()) {
            if($request->input('id')){
                $task = $this->repository->find($request->input('id'));
                return view('admin.crm.modal.board-task', [
                    'task' => $task->load('client'),
                    'stage_id' => $task->stage_id,
                    'board_id' => $request->input('board_id')
                ])->render();
            } else {
                return view('admin.crm.modal.board-task', [
                    'stage_id' => $request->input('stage_id'),
                    'board_id' => $request->input('board_id')
                ])->with('task', Task::make())->render();
            }
        }
    }

    public function assign(Request $request)
    {
        if (request()->ajax()) {
            if($request->input('id')){
                $task = $this->repository->find($request->input('id'));
                return view('admin.crm.modal.board-assign', [
                    'task' => $task,
                    'stage_id' => $task->stage_id,
                    'board_id' => $request->input('board_id')
                ])->render();
            }
        }
    }

    public function saveAssign(AssignTaskFormRequest $request)
    {
        $task = Task::find($request->validated('task_id'));

        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        $eventData = [
            'start' => $request->validated('start'),
            'time' => $request->validated('time'),
            'type' => 8,
            'allday' => $request->validated('time') ? 0 : 1,
            'active' => 0,
            'name' => $task->name,
            'user_id' => $request->validated('user_id'),
            'client_id' => $task->client_id,
        ];

        $event = Event::create($eventData);

        return response()->json([
            'message' => 'Event assigned successfully',
            'event' => $event,
        ], 201);
    }

    public function store(TaskFormRequest $request)
    {
        if (request()->ajax()) {
            if($request->input('id')) {
                $task = $this->repository->find($request->input('id'));
                return $this->repository->updateTask($request->validated(), $task);
            } else {
                return $this->repository->createTask($request->validated());
            }
        }
    }

    public function destroy(Task $task)
    {
        if (request()->ajax()) {
            $task->delete();
            return ['success' => true];
        }
    }

    public function sort(Request $request)
    {
        if (request()->ajax() && $request->input('items') && $request->input('stage_id')) {
            $this->repository->updateOrderWithStage($request->input('items'), $request->input('stage_id'));
            return ['success' => true];
        }
    }
}
