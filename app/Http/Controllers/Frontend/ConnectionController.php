<?php

namespace App\Http\Controllers\Frontend;

use \Firebase\JWT\JWT;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Repositories\Backend\Flight\FlightContract;
//Exceptions
use App\Exceptions\ApiException;
use App\Exceptions\NotFoundException;
//Requests
use App\Http\Requests\Api\Frontend\Test\ConnectionRequest;

class ConnectionController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function connectionTest(ConnectionRequest $request)
    {
        $key = config('flight.jwt_key');
        $response = [
            'user_name' => $request['user_name'],
            'user_email' => $request['user_email'],
            'id' => $request['id'],
            'browser' => 'chrome',
            'ip_address' => '49.106.192.3',
            'up_load' => '12345',
            'down_load' => '12345',
            'connection_method' => 'tcp',
        ];
        $response['jwt'] = JWT::encode($response, $key);
        sleep(2);
        return \Response::json($response, 200);
    }
}
