<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Response;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('is_completed', 0)->orderBy('id','desc')->get();
        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'task' => 'required|max:255|unique:tasks',
        ]);

        Task::create($validatedData);

        return response()->json([
            'message' => 'Task created succesfully',
            'task' => Task::where('is_completed', 0)->orderBy('id','desc')->get(),
        ]);
    }
}
