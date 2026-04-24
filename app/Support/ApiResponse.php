<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ApiResponse
{
    public static function success(mixed $data, int $code = 200): JsonResponse
    {
        // If it's a resource collection wrapping a paginator
        $message = 'Request successful.';
        if ($data instanceof AnonymousResourceCollection
            && $data->resource instanceof LengthAwarePaginator) {
            $paginator = $data->resource;

            return response()->json([
                'status'  => 'success',
                'message' => $message,
                'data'    => $data->collection, // transformed items
                'meta'    => [
                    'current_page' => $paginator->currentPage(),
                    'last_page'    => $paginator->lastPage(),
                    'per_page'     => $paginator->perPage(),
                    'total'        => $paginator->total(),
                    'from'         => $paginator->firstItem(),
                    'to'           => $paginator->lastItem(),
                ],
                'links' => [
                    'first' => $paginator->url(1),
                    'last'  => $paginator->url($paginator->lastPage()),
                    'prev'  => $paginator->previousPageUrl(),
                    'next'  => $paginator->nextPageUrl(),
                ],
            ], $code);
        }

        // If it's a raw paginator
        if ($data instanceof LengthAwarePaginator) {
            return response()->json([
                'status'  => 'success',
                'message' => $message,
                'data'    => $data->items(),
                'meta'    => [
                    'current_page' => $data->currentPage(),
                    'last_page'    => $data->lastPage(),
                    'per_page'     => $data->perPage(),
                    'total'        => $data->total(),
                    'from'         => $data->firstItem(),
                    'to'           => $data->lastItem(),
                ],
                'links' => [
                    'first' => $data->url(1),
                    'last'  => $data->url($data->lastPage()),
                    'prev'  => $data->previousPageUrl(),
                    'next'  => $data->nextPageUrl(),
                ],
            ], $code);
        }

        return response()->json([
            'status'  => 'success',
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    public static function created(mixed $data): JsonResponse
    {
        return self::success($data, 201);
    }

    public static function error(mixed $data, string $message, int $code = 400): JsonResponse
    {
        return response()->json(['status' => 'error', 'message' => $message, 'errors' => $data], $code);
    }
}