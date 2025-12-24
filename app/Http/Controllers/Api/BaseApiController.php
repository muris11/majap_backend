<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
 use Illuminate\Http\JsonResponse;
 use Illuminate\Http\Resources\Json\JsonResource;
 use Illuminate\Http\Resources\Json\ResourceCollection;
 use Illuminate\Pagination\LengthAwarePaginator;
 
 abstract class BaseApiController extends Controller
 {
     protected function successResponse(
         mixed $data = null,
         string $message = null,
         int $statusCode = 200,
         array $meta = []
     ): JsonResponse {
         $response = [
             'success' => true,
         ];
 
         if ($message) {
             $response['message'] = $message;
         }
 
         if ($data !== null) {
             $response['data'] = $data;
         }
 
         if (!empty($meta)) {
             $response['meta'] = $meta;
         }
 
         return response()->json($response, $statusCode);
     }
 
     protected function paginatedResponse(
         LengthAwarePaginator $paginator,
         string $resourceClass = null
     ): JsonResponse {
         $items = $paginator->items();
         
         if ($resourceClass && class_exists($resourceClass)) {
             $items = $resourceClass::collection(collect($items))->resolve();
         }
 
         return $this->successResponse(
             data: $items,
             meta: [
                 'pagination' => [
                     'current_page' => $paginator->currentPage(),
                     'last_page' => $paginator->lastPage(),
                     'per_page' => $paginator->perPage(),
                     'total' => $paginator->total(),
                     'from' => $paginator->firstItem(),
                     'to' => $paginator->lastItem(),
                 ],
             ]
         );
     }
 
     protected function resourceResponse(
         JsonResource|ResourceCollection $resource,
         string $message = null,
         int $statusCode = 200
     ): JsonResponse {
         return $this->successResponse(
             data: $resource->resolve(),
             message: $message,
             statusCode: $statusCode
         );
     }
 
     protected function errorResponse(
         string $message,
         int $statusCode = 400,
         array $errors = [],
         string $errorCode = null
     ): JsonResponse {
         $response = [
             'success' => false,
             'message' => $message,
         ];
 
         if ($errorCode) {
             $response['error_code'] = $errorCode;
         }
 
         if (!empty($errors)) {
             $response['errors'] = $errors;
         }
 
         return response()->json($response, $statusCode);
     }
 
     protected function notFoundResponse(string $message = 'Resource not found'): JsonResponse
     {
         return $this->errorResponse($message, 404, [], 'NOT_FOUND');
     }
 
     protected function validationErrorResponse(array $errors, string $message = 'Validation failed'): JsonResponse
     {
         return $this->errorResponse($message, 422, $errors, 'VALIDATION_ERROR');
     }
 
     protected function unauthorizedResponse(string $message = 'Unauthorized'): JsonResponse
     {
         return $this->errorResponse($message, 401, [], 'UNAUTHORIZED');
     }
 
     protected function forbiddenResponse(string $message = 'Forbidden'): JsonResponse
     {
         return $this->errorResponse($message, 403, [], 'FORBIDDEN');
     }
 
     protected function createdResponse(mixed $data = null, string $message = 'Created successfully'): JsonResponse
     {
         return $this->successResponse($data, $message, 201);
     }
 
     protected function noContentResponse(): JsonResponse
     {
         return response()->json(null, 204);
     }
 }
