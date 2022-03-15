<?php

namespace App\Http\Controllers\API;

use App\Models\Todos;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ApiTodoController extends ApiBaseController
{

    /**
     * @OA\Get(
     * path="/api/v1/todo'/lists",
     * summary="Get all todo lists.",
     * description="Search for cars for hire and for sale.",
     * operationId="v1TodoLists",
     * tags={"To do List"},
     * @OA\Response(
     *    response=422,
     *    description="The type is wrong",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="boolean", example="true"),
     *       @OA\Property(property="status_code", type="integer", example="422"),
     *       @OA\Property(property="status", type="string", example="Unprocessable Entity"),
     *       @OA\Property(property="message", type="string", example="The type is wrong"),
     *    )
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Research results",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="boolean", example="false"),
     *       @OA\Property(property="status_code", type="integer", example="200"),
     *       @OA\Property(property="status", type="string", example="OK"),
     *       @OA\Property(property="message", type="string", example="Research results"),
     *       @OA\Property(
     *          property="data",
     *          type="object",
     *          @OA\Property(property="total", type="integer", example="2"),
     *          @OA\Property(
     *              property="query",
     *              type="object",
     *              @OA\Property(property="price", type="string", example="Research results"),
     *              @OA\Property(property="yes_out", type="string", example="no"),
     *              @OA\Property(property="pickup-location", type="string", example="Research results"),
     *              @OA\Property(property="with-driver", type="string", example="Research results"),
     *          ),
     *       )
     *    )
     * ),
     * )
     */
    public function lists()
    {

        $lists = Todos::where('deleted_at', null)->orderBy('created_at', 'DESC')->get();
        return $this->success("Liste of task", $lists);
    }

    public function create(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|string|min:3|max:60',
            'activity' => 'required|string|min:5|max:240'
        ]);

        $datas = $request->only('name', 'activity');
        $datas['slug'] = Str::slug($datas['name']);
        $task = Todos::insert($datas);
        return $this->success("Liste of task", $task);
    }

    public function update(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|string|min:3|max:60',
            'activity' => 'required|string|min:5|max:240'
        ]);

        $task = Todos::find(1);
        if ($task) {
            
            $task->name = 'Paris to London';
            $task->activity = 'Paris to London';
            $task->save();
            return $this->success("Liste of task", $task);
        }

        return $this->unprocessable("Specific task not found");
    }
}
