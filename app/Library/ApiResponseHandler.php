<?php

namespace App\Library;

class ApiResponseHandler
{
    private $responseFormat = [
        "statuscode" => "",
        "message" => "",
        "data" => "",
        "error" => ""
    ];

    private $defaultResponses = [
        200 => "Succesful!",
        202 => "Created!",
        400 => "Invalid information!",
        401 => "Unauthorized!",
        500 => "Internal server error!"
    ];
    public function __getResponse($code, $data = null, $custom = null, $error = null)
    {
        $this->responseFormat["statuscode"] = $code;
        $this->responseFormat["message"] = $this->defaultResponses[$code];
        $this->responseFormat["data"] = $data;
        $this->responseFormat["error"] = $error;
        return response()->json($this->responseFormat, $code);
    }
}
