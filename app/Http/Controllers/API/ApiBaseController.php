<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Http\Controllers\Controller;

class ApiBaseController extends Controller
{

    /**
     * Send response.
     *
     * @param  bool $error
     * @param  string $message
     * @param  string $status
     * @param  int $code
     * @param  array $datas
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function response(bool $error, string $message, string $status, int $code,  array $data = [])
    {

        $default = [
            'error' => $error,
            'status' => $status,
            'status_code' => $code,
            'message' => $message
        ];

        if (!empty($data)) {

            $default['data'] = $data;
        }

        return response()->json($default, $code);
    }


    /**
     * unauthorized
     *
     * @param  string $message
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    protected function unauthorized(string $message = 'Protected page, log in', array $data = [])
    {

        return $this->response(true, $message, 'Unauthorized', 401, $data);
    }


    /**
     * unauthorized
     *
     * @param  string $message
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    protected function unprocessable(string $message = 'Unprocessable Entity', array $data = [])
    {

        return $this->response(true, $message, 'Unprocessable Entity', 422, $data);
    }

    /**
     * Send success response.
     *
     * @param  array $datas
     * @param  string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function success(string $message = "", array $data = [])
    {

        return $this->response(false, $message, 'success', 200, $data);
    }

    /**
     * Get authentify user.
     *
     * @return null|User
     */
    protected function user(): ?User
    {

        $token = request()->bearerToken();
        if (!$token) {

            return null;
        }
        return User::where('api_token', $token)->first();
    }
}
