<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBusinessRequest;
use App\Http\Requests\UpdateBusinessRequest;
use App\Http\Resources\BusinessResource;
use App\Models\Business;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BusinessController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Business::query();

        if ($search = $request->string('q')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
            });
        }

        $sort = $request->string('sort')->toString();
        if ($sort) {
            $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';
            $column = ltrim($sort, '-');
            $query->orderBy($column, $direction);
        } else {
            $query->latest('id');
        }

        $perPage = (int) $request->integer('per_page', 15);
        $paginator = $query->paginate($perPage)->appends($request->query());

        return response()->json([
            'data' => BusinessResource::collection($paginator)->resolve(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    public function store(StoreBusinessRequest $request): JsonResponse
    {
        $data = $request->validated();
        if (empty($data['slug'] ?? null)) {
            $data['slug'] = Str::slug($data['name'].'-'.Str::random(6));
        }
        $business = Business::create($data);
        return response()->json(new BusinessResource($business), 201);
    }

    public function show(Business $business): JsonResponse
    {
        return response()->json(new BusinessResource($business));
    }

    public function update(UpdateBusinessRequest $request, Business $business): JsonResponse
    {
        $data = $request->validated();
        if (!empty($data['name']) && empty($data['slug'] ?? null)) {
            $data['slug'] = Str::slug($data['name'].'-'.Str::random(6));
        }
        $business->update($data);
        return response()->json(new BusinessResource($business));
    }

    public function destroy(Business $business): JsonResponse
    {
        $business->delete();
        return response()->json(status: 204);
    }
}
