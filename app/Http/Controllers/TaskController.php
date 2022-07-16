<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;


class TaskController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $tasks = $this->user->tasks()->get();

        return response()->json([
            'status' => 'success',
            'data' => $tasks,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreTaskRequest $request)
    {
        $loggedUser = auth()->user();

        $task = Task::create([
            'user_id' => $loggedUser->id,
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Task created successfully',
            'data' => $task,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $loggedUser = auth()->user();
        $task = Task::query()->where('id', $id)
            ->where('user_id', $loggedUser->id)->first();

        if (empty($task)) {
            return response()->json(['error' => 'Não encontrado.'], 200);
        }

        return response()->json([
            'status' => 'success',
            'data' => $task,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $data = $request->only('name', 'status');

        $loggedUser = auth()->user();

        if ($task->user_id == $loggedUser->id) {
            $task = $task->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Task updated successfully',
                'data' => $task,
            ]);
        }
        return response()->json(['error' => 'Não é possível alterar  a task'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Task $task)
    {
        $loggedUser = auth()->user();

        $findTask = Task::query()->where('id', $task->id)
            ->where('user_id', $loggedUser->id)->first();
        if (empty($findTask)) {
            return response()->json(['error' => 'Não encontrado.'], 200);
        }

        if ($task->user_id == $loggedUser->id) {
            $task->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Task deleted successfully',
                'data' => $task
            ]);
        }

        return response()->json(['error' => 'Não é possível deletar a task'], 200);
    }
}
