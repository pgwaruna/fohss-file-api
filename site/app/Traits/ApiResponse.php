<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponse
{

    public function successResponse($message, $data, $statusCode = Response::HTTP_OK)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data], $statusCode);
    }

    public function errorResponse($errorMessage, $statusCode)
    {
        return response()->json([
            'status' => 'error',
            'message' => $errorMessage], $statusCode);
    }
}
