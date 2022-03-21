<?php

namespace App\Http\Controllers\API;

use App\Models\Todos;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ApiTodoController extends ApiBaseController
{

    /**
     * @OA\Get(
     * path="/api/v1/todo/lists",
     * summary="Get all todo lists.",
     * description="Search for cars for hire and for sale.",
     * operationId="v1TodoLists",
     * tags={"To do List"},
     * @OA\Response(
     *    response=200,
     *    description="Lists of tasks",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="boolean", example="false"),
     *       @OA\Property(property="status_code", type="integer", example="200"),
     *       @OA\Property(property="status", type="string", example="OK"),
     *       @OA\Property(property="message", type="string", example="Lists of tasks"),
     *       @OA\Property(
     *          property="data",
     *          type="object",
     *          @OA\Property(property="total", type="integer", example="2"),
     *          @OA\Property(
     *              property="query",
     *              type="object",
     *              @OA\Property(property="name", type="string", example="Task name"),
     *              @OA\Property(property="activity", type="string", example="Task activity"),
     *              @OA\Property(property="data", type="string", example="2022/03/05"),
     *              @OA\Property(property="status", type="string", example="failed"),
     *          ),
     *       )
     *    )
     * ),
     * )
     */
    public function lists()
    {

        $lists = Todos::where('deleted_at', null)->orderBy('created_at', 'DESC')->get();
        return $this->success("Liste of task", $lists->toArray());
    }

    /**
     * @OA\Post(
     * path="/api/v1/todo/create",
     * summary="Create a new Task",
     * description="Create a new Task",
     * operationId="v1TodoCreate",
     * tags={"To do List"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Task informations",
     *    @OA\JsonContent(
     *       required={"name", "activity", "date"},
     *       @OA\Property(property="name", type="string", example="task name"),
     *       @OA\Property(property="activity", type="string", example="Description"),
     *       @OA\Property(property="date", type="date", example="Activity date"),
     * ),
     * ),
     * @OA\Response(
     *    response=422,
     *    description="An unknown error has occurred !!! Please try again later",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="boolean", example="true"),
     *       @OA\Property(property="status_code", type="string", example="422"),
     *       @OA\Property(property="status", type="string", example="Unprocessable Entity"),
     *       @OA\Property(property="message", type="string", example="An unknown error has occurred !!! Please try again later"),
     *    )
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Task added",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="boolean", example="false"),
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="status_code", type="integer", example="200"),
     *       @OA\Property(property="message", type="string", example="Task added."),
     *       @OA\Property(
     *          property="data",
     *          type="object",
     *          @OA\Property(property="id", type="integer", example="2"),
     *          @OA\Property(property="name", type="string", example="Name task"),
     *          @OA\Property(property="activity", type="string", example="Activity description"),
     *          @OA\Property(property="status", type="string", example="completed"),
     *          @OA\Property(property="created_at", type="string", example="2022/02/12"),
     *       )
     *    )
     * ),
     * )
     */
    public function create(Request $request)
    {

        // header('Access-Control-Allow-Origin: *');
        try {

            $this->validate($request, [
                'name' => 'required|string|min:3|max:60',
                'activity' => 'required|string|min:5|max:240',
                'date' => 'required|string|min:5|max:240',
            ]);
    
            $datas = $request->only('name', 'activity', 'date');
            $datas['slug'] = Str::slug($datas['name']);
            $task = Todos::insert($datas);
            return $this->success("Task added");
        } catch (\Throwable $th) {

            return $this->unprocessable($th);
        }
    }

    /**
     * @OA\Post(
     * path="/api/v1/todo/completed",
     * summary="Completed Task",
     * description="Completed Task",
     * operationId="v1TodoCompleted",
     * tags={"To do List"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Task informations",
     *    @OA\JsonContent(
     *       required={"id"},
     *       @OA\Property(property="id", type="integer", example="1"),
     * ),
     * ),
     * @OA\Response(
     *    response=422,
     *    description="An unknown error has occurred !!! Please try again later",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="boolean", example="true"),
     *       @OA\Property(property="status_code", type="string", example="422"),
     *       @OA\Property(property="status", type="string", example="Unprocessable Entity"),
     *       @OA\Property(property="message", type="string", example="An unknown error has occurred !!! Please try again later"),
     *    )
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Completed task",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="boolean", example="false"),
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="status_code", type="integer", example="200"),
     *       @OA\Property(property="message", type="string", example="Task completed."),
     *    )
     * ),
     * )
     */
    public function completed(Request $request)
    {

        $this->validate($request, [
            'id' => 'required'
        ]);

        $task = Todos::find($request->id);
        if ($task) {

            $task->status = 'completed';
            $task->save();
            return $this->success("Task completed");
        }

        return $this->unprocessable("Specific task not found");
    }

    /**
     * @OA\Post(
     * path="/api/v1/todo/delete",
     * summary="Completed Task",
     * description="Delete Task",
     * operationId="v1TodoDelete",
     * tags={"To do List"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Task informations",
     *    @OA\JsonContent(
     *       required={"id"},
     *       @OA\Property(property="id", type="integer", example="1"),
     *    ),
     * ),
     * @OA\Response(
     *    response=422,
     *    description="An unknown error has occurred !!! Please try again later",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="boolean", example="true"),
     *       @OA\Property(property="status_code", type="string", example="422"),
     *       @OA\Property(property="status", type="string", example="Unprocessable Entity"),
     *       @OA\Property(property="message", type="string", example="An unknown error has occurred !!! Please try again later"),
     *    )
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Completed task",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="boolean", example="false"),
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="status_code", type="integer", example="200"),
     *       @OA\Property(property="message", type="string", example="Task delete."),
     *    )
     * ),
     * )
     */
    public function delete(Request $request)
    {

        $this->validate($request, [
            'id' => 'required'
        ]);

        $task = Todos::find($request->id);
        if ($task) {

            $task->deleted_at = time();
            $task->save();
            return $this->success("Task delete");
        }

        return $this->unprocessable("Specific task not found");
    }
}

// u537328530_exam
// u537328530_exam_api
// hQqgR!:SCD:2

// RewriteEngine on
// RewriteCond %{HTTP_HOST} ^examapi.jifitech.com$ [NC,OR]
// RewriteCond %{HTTP_HOST} ^www.examapi.jifitech.com/$
// RewriteCond %{REQUEST_URI} !public/
// RewriteRule (.*) /public/$1 [L]
