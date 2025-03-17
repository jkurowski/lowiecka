<?php

namespace App\Http\Controllers\Admin\Crm\Property;

use App\Http\Controllers\Controller;
use App\Http\Requests\PropertyTodoFormRequest;
use App\Models\Property;
use App\Models\PropertyTodo;

class HandoverController extends Controller
{
    public function index(Property $property)
    {
        $property->load('todos');

        return view('admin.crm.property.index', [
            'property' => $property
        ]);
    }

    public function modal(?int $taskId = null)
    {
        if ($taskId) {
            $task = PropertyTodo::find($taskId);
            if (!$task) {
                return response()->json(['success' => false, 'message' => 'Task not found'], 404);
            }
            return view('admin.crm.modal.task', compact('task'));
        }

        return view('admin.crm.modal.task');
    }

    public function store(PropertyTodoFormRequest $request, Property $property)
    {
        $validated = $request->validated();
        $task = PropertyTodo::create([
            'property_id' => $property->id,
            'user_id' => auth()->id(),
            'due_date' => $validated['due_date'],
            'completed' => $validated['completed'],
            'text' => $validated['text'],
            'x' => $validated['x'] ?: null,
            'y' => $validated['y'] ?: null,
        ]);

        $task->load(['user:id,name,surname']);
        return response()->json(['success' => true, 'task' => $task]);
    }

    public function destroy($taskId)
    {
        $task = PropertyTodo::find($taskId);

        if ($task) {
            $task->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }
}
