<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Review::query()->with(['business', 'user']);

        if ($businessId = $request->integer('business_id')) {
            $query->where('business_id', $businessId);
        }

        if ($userId = $request->integer('user_id')) {
            $query->where('user_id', $userId);
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
            'data' => ReviewResource::collection($paginator)->resolve(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    public function store(StoreReviewRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $review = Review::create($data);
        return response()->json(new ReviewResource($review->load(['business', 'user'])), 201);
    }

    public function show(Review $review): JsonResponse
    {
        return response()->json(new ReviewResource($review->load(['business', 'user'])));
    }

    public function update(UpdateReviewRequest $request, Review $review): JsonResponse
    {
        $this->authorizeOwnership($request, $review);
        $review->update($request->validated());
        return response()->json(new ReviewResource($review->load(['business', 'user'])));
    }

    public function destroy(Request $request, Review $review): JsonResponse
    {
        $this->authorizeOwnership($request, $review);
        $review->delete();
        return response()->json(status: 204);
    }

    private function authorizeOwnership(Request $request, Review $review): void
    {
        if (!$request->user() || $request->user()->id !== $review->user_id) {
            abort(403, 'You are not allowed to modify this review.');
        }
    }
}
