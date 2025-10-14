<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ReviewModerationController extends Controller
{
    public function indexPending(Request $request): JsonResponse
    {
        $this->authorizeModeration($request);
        $query = Review::query()
            ->with(['business', 'user'])
            ->pending()
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

    public function indexAll(Request $request): JsonResponse
    {
        $this->authorizeModeration($request);
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
    public function approve(Request $request, Review $review): JsonResponse
    {
        $this->authorizeModeration($request);

        $review->update([
            'status' => Review::STATUS_APPROVED,
            'moderated_by' => $request->user()->id,
            'moderated_at' => now(),
            'rejection_reason' => null,
        ]);

        $this->bustBusinessCaches($review->business_id);

        return response()->json(new ReviewResource($review->load(['business', 'user'])));
    }

    public function reject(Request $request, Review $review): JsonResponse
    {
        $this->authorizeModeration($request);

        $data = $request->validate([
            'reason' => ['required', 'string', 'max:255'],
        ]);

        $review->update([
            'status' => Review::STATUS_REJECTED,
            'moderated_by' => $request->user()->id,
            'moderated_at' => now(),
            'rejection_reason' => $data['reason'],
        ]);

        $this->bustBusinessCaches($review->business_id);

        return response()->json(new ReviewResource($review->load(['business', 'user'])));
    }

    protected function authorizeModeration(Request $request): void
    {
        abort_unless($request->user() && $request->user()->can('review.moderate'), 403);
    }

    protected function bustBusinessCaches(int $businessId): void
    {
        Cache::forget('businesses:show:' . $businessId);
        $ver = (int) Cache::get('businesses:index:version', 1);
        Cache::forever('businesses:index:version', $ver + 1);
    }
}
