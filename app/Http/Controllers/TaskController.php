<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('is_completed', 0)->orderBy('id','desc')->get();
        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'task' => 'required|max:255|unique:tasks',
        ]);

        // Create a new task
        $task = new Task;
        $task->task = $validatedData['task'];
        $task->save();

        // Return a JSON response with the newly created task
        return response()->json([
            'message' => 'Task created successfully',
            'task' => $task,
        ]);
    }
    
    public function updateStatus(Request $request, Task $task)
    {
        $validatedData = $request->validate([
            'is_completed' => 'required|in:true,false',
        ]);
        // dd($validatedData['is_completed']);
        try {
            $task->update(['is_completed' => ($validatedData['is_completed'] == 'true') ? 1 : 0]);

            return response()->json(['message' => 'Task status updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update Task: ' . $e->getMessage()], 400);
        }
    }

    public function deleteTask(Request $request, Task $task)
    {
        try {
            $task->delete();
            return response()->json(['message' => 'Task deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete Task: ' . $e->getMessage()], 400);
        }
    }
}
