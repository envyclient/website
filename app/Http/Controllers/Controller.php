<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function bad(): JsonResponse
    {
        return response()->json([
            'message' => '400 Bad Request',
        ], 400);
    }

    public static function ok(): JsonResponse
    {
        return response()->json([
            'message' => '200 OK',
        ]);
    }
}
