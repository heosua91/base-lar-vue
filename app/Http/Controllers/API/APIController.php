<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class APIController extends Controller
{
    const VERSION_API = '1.0.0';

    // Code for API
    const CODE_SUCCESS = '000';
    const CODE_LOGIN_FAIL = '013';

    const CODE_EXCEPTION_FROM_SERVER = '900';
    const CODE_EXCEPTION_OTHER = '901';

    public function __construct()
    {
    }

    /**
     * Parse json_format
     * 
     * @param  array  $data
     * @param  int  $statusCode
     * @param  string  $code
     * @param  array  $message (default: '')
     * @return JsonResponse
     */
    public function parseJson(array $data, int $statusCode, string $code = self::CODE_SUCCESS, array $messages = []): JsonResponse
    {
        $data_format = [
            'version' => self::VERSION_API,
            'status' => [
                'code' => $code,
                'message' => $messages,
                'api' => request()->path()
            ],
            'result' => empty($data) ? (object)[] : $data
        ];

        return response()->json($data_format, $statusCode);
    }
}
