<?php

namespace App\Http\Helpers;

class ApiResponse
{
    public static function format($data = [], $code = 200, $message = 'success')
    {
        $response = [
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($response);
    }
}
