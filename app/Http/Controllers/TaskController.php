<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        if ($request->query('all') == 'true') {
            return Task::all();
        } else {
            return Task::where('completed', 0)->get();
        }
    }

    public function store(Request $request)
    {
        $request->validate(['task' => 'required|unique:tasks']);
        return Task::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->update($request->all());
        return $task;
    }

    public function destroy($id)
    {
        Task::destroy($id);
        return response()->json(['success' => true]);
    }
}
