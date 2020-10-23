<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Library\ApiResponseHandler;
use App\Oauth_access_token;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiAuthController extends Controller
{
    private $response;
    private $validated;

    public function __construct()
    {
        $this->response = new ApiResponseHandler;
    }

    public function userValidator(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required'
        ]);
        $this->validated = $validator;
        return $validator;
    }

    /**
     * Function to login to the api and get a token as response
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // var_dump($request);
        // exit;
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            $user = Auth::user();
            $getToken = $user->createToken('User');
            $success['user'] = $user;
            $success['token'] =  $getToken->accessToken;
            $success['id'] = $getToken->token->id;

            return $this->response->__getResponse(200, $success);
        } else {
            return $this->response->__getResponse(401, "Sorry, your email and password do not match. Try Again!");
        }
    }

    /**
     * Function to register as a user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        if ($request->has("name") || $request->has("email") || $request->has("password")) {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8'],
            ]);
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            return response()->json(["message" => "Completed"]);
        } else {
            return response()->json(['error' => 'Not all fields are filled in'], 401);
        }
    }

    /**
     * Function to check if the token is expired or not
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function tokenExpired(Request $request)
    {
        if (!($request->id)) {
            return response()->json('Values do not match');
        }
        $user = User::find(auth('api')->user()->id);
        return $user->tokenExpired($request->id) ?
            response()->json(['messages' => 'expired'], 401) : response()->json(['messages' => 'valid'], 200);
    }
}
