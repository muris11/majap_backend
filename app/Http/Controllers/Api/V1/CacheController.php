<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Services\CacheVersionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CacheController extends BaseApiController
{
    public function __construct(
        protected CacheVersionService $cacheVersionService
    ) {}

    /**
     * Get all cache versions - frontend polls this endpoint
     */
    public function versions(): JsonResponse
    {
        return $this->successResponse(
            $this->cacheVersionService->getAllVersions()
        );
    }

    /**
     * Get version for specific resource
     */
    public function version(string $resource): JsonResponse
    {
        return $this->successResponse([
            'resource' => $resource,
            'version' => $this->cacheVersionService->getVersion($resource),
        ]);
    }

    /**
     * Invalidate cache for specific resource (admin only)
     */
    public function invalidate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'resources' => 'required|array',
            'resources.*' => 'string|in:settings,hero_slides,did_you_know,timeline,batches,organization,activities,albums,schema,faqs',
        ]);

        $versions = $this->cacheVersionService->incrementMultiple($validated['resources']);

        return $this->successResponse([
            'message' => 'Cache invalidated successfully',
            'versions' => $versions,
        ]);
    }
}
