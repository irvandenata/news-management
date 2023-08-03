<?php

namespace App\Helpers;

/**
 * Format response.
 */

class ResponseFormatter
{

    /**
     * API Response
     *
     * @var array
     */
    protected static $response = [
        'meta' => [
            'code' => 200,
            'status' => 'success',
            'message' => null,
        ],
        'data' => [],
    ];

    /**
     * Give success response.
     */
    public static function success($data = null, $message = null, $code = 200, $type = 'collection')
    {
        self::$response['meta']['message'] = $message;
        if ($type == 'single') {
            self::$response['data'] = $data;
        } else if ($type == 'paginate') {
            self::$response['data'] = $data['data'];
            self::$response['meta']['current_page'] = $data['meta']['current_page'];
            self::$response['meta']['last_page'] = $data['meta']['last_page'];
            self::$response['meta']['per_page'] = $data['meta']['per_page'];
            self::$response['meta']['total'] = $data['meta']['total'];
            self::$response['meta']['to'] = $data['meta']['to'];
            self::$response['meta']['from'] = $data['meta']['from'];
            self::$response['meta']['query'] = [
               'page'=> '?page=',
               'search'=>'?search='
            ];
        }else {
            self::$response['data'] = [...$data];
        }

        return response()->json(self::$response, $code);
    }

    /**
     * Give error response.
     */
    public static function error($data = null, $message = null, $code = 400)
    {
        self::$response['meta']['status'] = 'error';
        self::$response['meta']['code'] = $code;
        self::$response['meta']['message'] = $message;
        self::$response['data'] = $data;

        return response()->json(self::$response, self::$response['meta']['code']);
    }
}
