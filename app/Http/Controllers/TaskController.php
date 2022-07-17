<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrUpdateTaskRequest;
use App\Http\Requests\UpdateStatusTaskRequest;
use App\Models\Task;


class TaskController extends Controller
{


    public function __construct()
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $loggedUser = auth()->user();
        $tasks = Task::query()->where('user_id', $loggedUser->id)
            ->orderByDesc('id')
            ->get();


        return response()->json([
            'status' => 'success',
            'tasks' => $tasks,
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
    public function store(StoreOrUpdateTaskRequest $request)
    {
        $loggedUser = auth()->user();

        $task = Task::create([
            'user_id' => $loggedUser->id,
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message'=> 'Tarefa criada com sucesso!',
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
            'task' => $task,
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


    public function update(StoreOrUpdateTaskRequest $request, $id)
    {
        $data = $request->all();

        $task = Task::query()->find($id);

        $loggedUser = auth()->user();

        if ($task->user_id == $loggedUser->id) {
            $task->update($data);

            return response()->json([
                'status' => 'success',
                'message'=> 'Tarefa alterada com sucesso!',
                'data' => $task,
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Não é possível alterar a tarefa'
        ], 400);
    }


    public function updateStatus(UpdateStatusTaskRequest $request, $id)
    {
        $data = $request->only('status');

        $task = Task::query()->find($id);

        $loggedUser = auth()->user();

        if ($task->user_id == $loggedUser->id) {
            $task->update($data);

            return response()->json([
                'status' => 'success',
                'message'=> 'Situação da tarefa alterada com sucesso!',
                'data' => $task,
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Não é possível alterar a tarefa'
        ], 400);
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
            return response()->json(['error' => 'Não encontrado.'], 400);
        }

        if ($task->user_id == $loggedUser->id) {
            $task->delete();
            return response()->json([
                'status' => 'success',
                'message'=> 'Tarefa removida com sucesso!',
                'data' => $task
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Não é possível apagar  a tarefa'
        ], 400);
    }
}
