<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::query()
            ->with('user')
            ->get();

        return view('task-index', compact('tasks'));
    }

    public function create()
    {
        $employees = User::select(['id', 'name'])
            ->whereRole(User::ROLE_EMPLOYEE)
            ->get();

        return view('task-create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:300',
            'user_id' => 'required|exists:users,id'
        ]);

        Task::create($validated);

        return redirect(route('tasks.index'));
    }

    public function show(Task $task)
    {
        $task->load('user');

        return view('task-show', compact('task'));
    }

    public function edit(Task $task)
    {
    }

    public function update(Request $request, Task $task)
    {
    }

    public function destroy(Task $task)
    {
    }
}
