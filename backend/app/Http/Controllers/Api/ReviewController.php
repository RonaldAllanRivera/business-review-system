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
        $query = Review::query()
            ->with(['business', 'user'])
            ->byBusiness($request->query('business_id'))
            ->byUser($request->query('user_id'))
            ->sorted($request->query('sort'));

        $perPage = (int) ($request->query('per_page', 15));
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
        // Ensure the review is loaded with the user relationship
        $review->load('user');
        
        // Check if the authenticated user is the owner of the review
        if ($request->user()->id !== $review->user_id) {
            abort(403, 'You are not allowed to update this review.');
        }
        
        $review->update($request->validated());
        return response()->json(new ReviewResource($review->load(['business', 'user'])));
    }

    public function destroy(Request $request, Review $review): JsonResponse
    {
        // Ensure the review is loaded with the user relationship
        $review->load('user');
        
        // Check if the authenticated user is the owner of the review
        if ($request->user()->id !== $review->user_id) {
            abort(403, 'You are not allowed to delete this review.');
        }
        
        $review->delete();
        return response()->json(status: 204);
    }
}
