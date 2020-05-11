<?php

namespace app\controllers;

use app\base\Controller;

class HomeController extends Controller
{

    public function methodIndex()
    {
        return $this->renderJson([
            'ok' => true,
            'message' => 'Welcome to riki.uz'
        ]);
        
    }

    public function methodError($code = null)
    {
        http_response_code(404);
        if ($code !== null) {
            $codes = $this->httpCodes();
            if (isset($codes[$code])) {
                http_response_code($code);
                return $this->renderJson([
                    "ok" => false,
                    "error_code" => $code,
                    "message" => $codes[$code]
                ]);
            } else {
                return $this->renderJson([
                    "ok" => false,
                    "error_code" => 404,
                    "message" => "Requested page not found."
                ]);
            }
        } else {
            return $this->renderJson([
                "ok" => false,
                "error_code" => 404,
                "message" => "Requested page not found."
            ]);
        }
    }

    private function httpCodes()
    {
        return [
            400 => "Bad Request",
            401 => "Unauthorized",
            402 => "Payment Required",
            403 => "Forbidden",
            404 => "Not Found",
            405 => "Method Not Allowed",
            406 => "Not Acceptable",
            407 => "Proxy Authentication Required",
            408 => "Request Timeout",
            409 => "Conflict",
            410 => "Gone",
            411 => "Length Required",
            412 => "Precondition Failed",
            413 => "Payload Too Large",
            414 => "Request-URI Too Long",
            415 => "Unsupported Media Type",
            416 => "Requested Range Not Satisfiable",
            417 => "Expectation Failed",
            418 => "I'm a teapot",
            421 => "Misdirected Request",
            422 => "Unprocessable Entity",
            423 => "Locked",
            424 => "Failed Dependency",
            426 => "Upgrade Required",
            428 => "Precondition Required",
            429 => "Too Many Requests",
            431 => "Request Header Fields Too Large",
            444 => "Connection Closed Without Response",
            451 => "Unavailable For Legal Reasons",
            499 => "Client Closed Request",
            500 => "Internal Server Error",
            501 => "Not Implemented",
            502 => "Bad Gateway",
            503 => "Service Unavailable",
            504 => "Gateway Timeout",
            505 => "HTTP Version Not Supported",
            506 => "Variant Also Negotiates",
            507 => "Insufficient Storage",
            508 => "Loop Detected",
            510 => "Not Extended",
            511 => "Network Authentication Required",
            599 => "Network Connect Timeout Error"
        ];
    }
}