<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

/**
 * Standardizes all API/AJAX JSON responses across Dashboard controllers.
 * Ensures a uniform { success, message, data } envelope for all responses.
 */
trait ApiResponse
{
    /**
     * Return a successful JSON response.
     */
    protected function successResponse(string $message, mixed $data = null, int $code = 200): JsonResponse
    {
        $payload = ['success' => true, 'message' => $message];

        if (!is_null($data)) {
            $payload['data'] = $data;
        }

        return response()->json($payload, $code);
    }

    /**
     * Return an error JSON response.
     */
    protected function errorResponse(string $message, mixed $errors = null, int $code = 400): JsonResponse
    {
        $payload = ['success' => false, 'message' => $message];

        if (!is_null($errors)) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $code);
    }
}
