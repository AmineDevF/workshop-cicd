<?php
namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller {

    // GET /api/tasks
    public function index(): JsonResponse {
        return response()->json(Task::all());
    }

    // POST /api/tasks
    public function store(Request $request): JsonResponse {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'completed'   => 'boolean',
        ]);
        $task = Task::create($validated);
        return response()->json($task, 201);
    }

    // GET /api/tasks/{id}
    public function show(Task $task): JsonResponse {
        return response()->json($task);
    }

    // PUT /api/tasks/{id}
    public function update(Request $request, Task $task): JsonResponse {
        $validated = $request->validate([
            'title'       => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'completed'   => 'boolean',
        ]);
        $task->update($validated);
        return response()->json($task);
    }

    // DELETE /api/tasks/{id}
    public function destroy(Task $task): JsonResponse {
        $task->delete();
        return response()->json(null, 204);
    }
}