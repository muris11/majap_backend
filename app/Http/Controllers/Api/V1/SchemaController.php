<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\ActivityStatus;
use App\Enums\AlbumStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Api\BaseApiController;
use App\Services\SchemaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SchemaController extends BaseApiController
{
    public function __construct(
        protected SchemaService $schemaService
    ) {}

    public function enums(string $name): JsonResponse
    {
        $enumMap = [
            'activity-status' => ActivityStatus::class,
            'album-status' => AlbumStatus::class,
            'user-role' => UserRole::class,
        ];

        if (!isset($enumMap[$name])) {
            return $this->notFoundResponse("Enum '{$name}' not found");
        }

        $enumClass = $enumMap[$name];

        if (!method_exists($enumClass, 'toArray')) {
            $options = array_map(fn ($case) => [
                'value' => $case->value,
                'label' => $case->name,
            ], $enumClass::cases());
        } else {
            $options = $enumClass::toArray();
        }

        return $this->successResponse([
            'name' => $name,
            'options' => $options,
        ]);
    }

    public function forms(string $name): JsonResponse
    {
        $schema = $this->schemaService->getFormSchema($name);

        if (!$schema) {
            return $this->notFoundResponse("Form schema '{$name}' not found");
        }

        return $this->successResponse($schema);
    }

    public function tables(string $name): JsonResponse
    {
        $schema = $this->schemaService->getTableSchema($name);

        if (!$schema) {
            return $this->notFoundResponse("Table schema '{$name}' not found");
        }

        return $this->successResponse($schema);
    }

    public function navigation(Request $request): JsonResponse
    {
        $user = $request->user();
        $navigation = $this->schemaService->getNavigation($user);

        return $this->successResponse($navigation);
    }

    public function permissions(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return $this->successResponse([
                'permissions' => [],
                'roles' => [],
            ]);
        }

        return $this->successResponse([
            'permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
            'roles' => $user->getRoleNames()->toArray(),
        ]);
    }
}
