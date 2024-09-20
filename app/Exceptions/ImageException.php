<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\MessageBag;
use Throwable;

class ImageException extends Exception
{
    public function __construct($message = "", $code = 422, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }


    /**
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->message,
        ], $this->getCode());
    }
}
