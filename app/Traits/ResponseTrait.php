<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseTrait
{
    public function success($message,$code = 200,$data = []) : JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ],$code);
    }

    public function failure($message,$code = 422,$data = []) : JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => $data
        ],$code);
    }
}
