<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ApiException extends Exception
{
    /**
     * Report or log an exception.
     *
     * @return bool
     */
    public function shouldReport()
    {
        // Report the exception only if the status code is 500
        return $this->getCode() === JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request)
    {
        return response()->json([
            'message' => 'Internal Server Error',
        ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
}
