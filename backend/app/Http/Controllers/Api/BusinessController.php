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
use Illuminate\Support\Facades\Cache;

class BusinessController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $version = Cache::rememberForever('businesses:index:version', fn () => 1);
        $cacheKey = 'businesses:index:'. $version .':'. md5($request->fullUrl());
        $ttl = now()->addSeconds(60);

        $payload = Cache::remember($cacheKey, $ttl, function () use ($request) {
            $query = Business::query()
                ->search($request->query('q'))
                ->ratingBetween($request->query('min_rating'), $request->query('max_rating'))
                ->createdBetween($request->query('from'), $request->query('to'))
                ->withReviewsCountMin($request->integer('min_reviews'))
                ->sorted($request->query('sort'));

            $perPage = (int) ($request->query('per_page', 15));
            $paginator = $query->paginate($perPage)->appends($request->query());

            return [
                'data' => BusinessResource::collection($paginator)->resolve(),
                'meta' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                ],
            ];
        });

        return response()->json($payload);
    }

    public function store(StoreBusinessRequest $request): JsonResponse
    {
        $data = $request->validated();
        if (empty($data['slug'] ?? null)) {
            $data['slug'] = Str::slug($data['name'].'-'.Str::random(6));
        }
        $business = Business::create($data);
        $ver = (int) Cache::get('businesses:index:version', 1);
        Cache::forever('businesses:index:version', $ver + 1);
        return response()->json(new BusinessResource($business), 201);
    }

    public function show(Business $business): JsonResponse
    {
        $cacheKey = 'businesses:show:'.$business->id;
        $ttl = now()->addMinutes(5);
        $data = Cache::remember($cacheKey, $ttl, function () use ($business) {
            $business->loadCount(['reviews' => function ($q) { $q->where('status', \App\Models\Review::STATUS_APPROVED); }]);
            return (new BusinessResource($business))->resolve();
        });
        return response()->json($data);
    }

    public function update(UpdateBusinessRequest $request, Business $business): JsonResponse
    {
        $data = $request->validated();
        if (!empty($data['name']) && empty($data['slug'] ?? null)) {
            $data['slug'] = Str::slug($data['name'].'-'.Str::random(6));
        }
        $business->update($data);
        Cache::forget('businesses:show:'.$business->id);
        $ver = (int) Cache::get('businesses:index:version', 1);
        Cache::forever('businesses:index:version', $ver + 1);
        return response()->json(new BusinessResource($business));
    }

    public function destroy(Business $business): JsonResponse
    {
        $business->delete();
        Cache::forget('businesses:show:'.$business->id);
        $ver = (int) Cache::get('businesses:index:version', 1);
        Cache::forever('businesses:index:version', $ver + 1);
        return response()->json(status: 204);
    }
}
