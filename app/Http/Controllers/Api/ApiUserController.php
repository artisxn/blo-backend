<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Library\ApiResponseHandler;

class ApiUserController extends Controller
{
    private $response;

    public function __construct()
    {
        $this->response = new ApiResponseHandler;
    }

    public function getLoggedInUser()
    {
        return response()->json(auth()->guard('api')->user());
    }
}
