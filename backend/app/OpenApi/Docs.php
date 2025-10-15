<?php

namespace App\OpenApi;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Business Review System API",
 *     version="1.0.0",
 *     description="OpenAPI documentation for the Business Review System."
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="Token"
 * )
 */
class Docs
{
    /**
     * Basic system endpoint.
     *
     * @OA\Get(
     *   path="/api/v1/health",
     *   summary="Health check",
     *   tags={"System"},
     *   @OA\Response(response=200, description="OK",
     *     @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="ok"),
     *       @OA\Property(property="app", type="string", example="Business Review System"),
     *       @OA\Property(property="time", type="string", format="date-time")
     *     )
     *   )
     * )
     */
    public function system(): void {}

    /**
     * Auth endpoints
     *
     * @OA\Post(
     *   path="/api/v1/auth/register",
     *   summary="Register a new user",
     *   tags={"Auth"},
     *   @OA\RequestBody(required=true,
     *     @OA\JsonContent(required={"name","email","password","password_confirmation"},
     *       @OA\Property(property="name", type="string", example="Jane Doe"),
     *       @OA\Property(property="email", type="string", example="jane@example.com"),
     *       @OA\Property(property="password", type="string", example="secretPass123"),
     *       @OA\Property(property="password_confirmation", type="string", example="secretPass123")
     *     )
     *   ),
     *   @OA\Response(response=201, description="Registered",
     *     @OA\JsonContent(ref="#/components/schemas/AuthTokenResponse")
     *   )
     * )
     *
     * @OA\Post(
     *   path="/api/v1/auth/login",
     *   summary="Login and get token",
     *   tags={"Auth"},
     *   @OA\RequestBody(required=true,
     *     @OA\JsonContent(required={"email","password"},
     *       @OA\Property(property="email", type="string", example="jane@example.com"),
     *       @OA\Property(property="password", type="string", example="secretPass123")
     *     )
     *   ),
     *   @OA\Response(response=200, description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/AuthTokenResponse")
     *   )
     * )
     *
     * @OA\Get(
     *   path="/api/v1/auth/me",
     *   summary="Get current user",
     *   security={{"bearerAuth":{}}},
     *   tags={"Auth"},
     *   @OA\Response(response=200, description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/User")
     *   )
     * )
     *
     * @OA\Post(
     *   path="/api/v1/auth/logout",
     *   summary="Logout",
     *   security={{"bearerAuth":{}}},
     *   tags={"Auth"},
     *   @OA\Response(response=200, description="OK")
     * )
     */
    public function auth(): void {}

    /**
     * Business endpoints
     *
     * @OA\Get(
     *   path="/api/v1/businesses",
     *   summary="List businesses",
     *   tags={"Businesses"},
     *   @OA\Parameter(name="q", in="query", @OA\Schema(type="string")),
     *   @OA\Parameter(name="min_rating", in="query", @OA\Schema(type="number")),
     *   @OA\Parameter(name="max_rating", in="query", @OA\Schema(type="number")),
     *   @OA\Parameter(name="from", in="query", @OA\Schema(type="string", format="date")),
     *   @OA\Parameter(name="to", in="query", @OA\Schema(type="string", format="date")),
     *   @OA\Parameter(name="min_reviews", in="query", @OA\Schema(type="integer")),
     *   @OA\Parameter(name="sort", in="query", @OA\Schema(type="string", example="-id")),
     *   @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", example=15)),
     *   @OA\Response(response=200, description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/PaginatedBusinessResponse")
     *   )
     * )
     *
     * @OA\Get(
     *   path="/api/v1/businesses/{id}",
     *   summary="Get a business",
     *   tags={"Businesses"},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/Business")
     *   )
     * )
     *
     * @OA\Post(
     *   path="/api/v1/businesses",
     *   summary="Create business",
     *   security={{"bearerAuth":{}}},
     *   tags={"Businesses"},
     *   @OA\RequestBody(required=true,
     *     @OA\JsonContent(ref="#/components/schemas/StoreBusinessRequest")
     *   ),
     *   @OA\Response(response=201, description="Created",
     *     @OA\JsonContent(ref="#/components/schemas/Business")
     *   )
     * )
     *
     * @OA\Put(
     *   path="/api/v1/businesses/{id}",
     *   summary="Update business",
     *   security={{"bearerAuth":{}}},
     *   tags={"Businesses"},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\RequestBody(required=true,
     *     @OA\JsonContent(ref="#/components/schemas/UpdateBusinessRequest")
     *   ),
     *   @OA\Response(response=200, description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/Business")
     *   )
     * )
     *
     * @OA\Delete(
     *   path="/api/v1/businesses/{id}",
     *   summary="Delete business",
     *   security={{"bearerAuth":{}}},
     *   tags={"Businesses"},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=204, description="No Content")
     * )
     */
    public function businesses(): void {}

    /**
     * Review endpoints
     *
     * @OA\Get(
     *   path="/api/v1/reviews",
     *   summary="List reviews",
     *   tags={"Reviews"},
     *   @OA\Parameter(name="business_id", in="query", @OA\Schema(type="integer")),
     *   @OA\Parameter(name="user_id", in="query", @OA\Schema(type="integer")),
     *   @OA\Parameter(name="sort", in="query", @OA\Schema(type="string", example="-rating")),
     *   @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", example=15)),
     *   @OA\Response(response=200, description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/PaginatedReviewResponse")
     *   )
     * )
     *
     * @OA\Get(
     *   path="/api/v1/reviews/{id}",
     *   summary="Get a review",
     *   tags={"Reviews"},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/Review")
     *   )
     * )
     *
     * @OA\Post(
     *   path="/api/v1/reviews",
     *   summary="Create review",
     *   security={{"bearerAuth":{}}},
     *   tags={"Reviews"},
     *   @OA\RequestBody(required=true,
     *     @OA\JsonContent(ref="#/components/schemas/StoreReviewRequest")
     *   ),
     *   @OA\Response(response=201, description="Created",
     *     @OA\JsonContent(ref="#/components/schemas/Review")
     *   )
     * )
     *
     * @OA\Put(
     *   path="/api/v1/reviews/{id}",
     *   summary="Update review",
     *   security={{"bearerAuth":{}}},
     *   tags={"Reviews"},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\RequestBody(required=true,
     *     @OA\JsonContent(ref="#/components/schemas/UpdateReviewRequest")
     *   ),
     *   @OA\Response(response=200, description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/Review")
     *   )
     * )
     *
     * @OA\Delete(
     *   path="/api/v1/reviews/{id}",
     *   summary="Delete review",
     *   security={{"bearerAuth":{}}},
     *   tags={"Reviews"},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=204, description="No Content")
     * )
     */
    public function reviews(): void {}

    /**
     * Profile endpoints
     *
     * @OA\Get(
     *   path="/api/v1/profile",
     *   summary="Get current user's profile",
     *   security={{"bearerAuth":{}}},
     *   tags={"Profile"},
     *   @OA\Response(response=200, description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/User")
     *   )
     * )
     *
     * @OA\Put(
     *   path="/api/v1/profile",
     *   summary="Update profile",
     *   security={{"bearerAuth":{}}},
     *   tags={"Profile"},
     *   @OA\RequestBody(required=true,
     *     @OA\JsonContent(ref="#/components/schemas/UpdateProfileRequest")
     *   ),
     *   @OA\Response(response=200, description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/User")
     *   )
     * )
     *
     * @OA\Post(
     *   path="/api/v1/profile/avatar",
     *   summary="Upload avatar",
     *   security={{"bearerAuth":{}}},
     *   tags={"Profile"},
     *   @OA\RequestBody(required=true,
     *     @OA\MediaType(mediaType="multipart/form-data",
     *       @OA\Schema(
     *         type="object",
     *         required={"avatar"},
     *         @OA\Property(property="avatar", type="string", format="binary")
     *       )
     *     )
     *   ),
     *   @OA\Response(response=200, description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/User")
     *   )
     * )
     */
    public function profile(): void {}

    /**
     * Review moderation endpoints
     *
     * @OA\Get(
     *   path="/api/v1/reviews/moderation/pending",
     *   summary="List pending reviews",
     *   security={{"bearerAuth":{}}},
     *   tags={"Review Moderation"},
     *   @OA\Response(response=200, description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/PaginatedReviewResponse")
     *   )
     * )
     *
     * @OA\Get(
     *   path="/api/v1/reviews/moderation/all",
     *   summary="List all reviews (moderation)",
     *   security={{"bearerAuth":{}}},
     *   tags={"Review Moderation"},
     *   @OA\Response(response=200, description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/PaginatedReviewResponse")
     *   )
     * )
     *
     * @OA\Post(
     *   path="/api/v1/reviews/{id}/approve",
     *   summary="Approve a review",
     *   security={{"bearerAuth":{}}},
     *   tags={"Review Moderation"},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/Review")
     *   )
     * )
     *
     * @OA\Post(
     *   path="/api/v1/reviews/{id}/reject",
     *   summary="Reject a review",
     *   security={{"bearerAuth":{}}},
     *   tags={"Review Moderation"},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\RequestBody(required=true,
     *     @OA\JsonContent(
     *       required={"reason"},
     *       @OA\Property(property="reason", type="string", example="Spam")
     *     )
     *   ),
     *   @OA\Response(response=200, description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/Review")
     *   )
     * )
     */
    public function moderation(): void {}

    /**
     * COMPONENT SCHEMAS
     * These reflect your API resources and request bodies.
     *
     * @OA\Schema(schema="User",
     *   type="object",
     *   @OA\Property(property="id", type="integer"),
     *   @OA\Property(property="name", type="string"),
     *   @OA\Property(property="email", type="string"),
     *   @OA\Property(property="avatar_url", type="string", nullable=true)
     * )
     *
     * @OA\Schema(schema="Business",
     *   type="object",
     *   @OA\Property(property="id", type="integer"),
     *   @OA\Property(property="name", type="string", nullable=true),
     *   @OA\Property(property="slug", type="string", nullable=true),
     *   @OA\Property(property="description", type="string", nullable=true),
     *   @OA\Property(property="rating", type="number", format="float", nullable=true),
     *   @OA\Property(property="reviews_count", type="integer", nullable=true),
     *   @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
     *   @OA\Property(property="updated_at", type="string", format="date-time", nullable=true)
     * )
     *
     * @OA\Schema(schema="Review",
     *   type="object",
     *   @OA\Property(property="id", type="integer"),
     *   @OA\Property(property="business_id", type="integer"),
     *   @OA\Property(property="user_id", type="integer"),
     *   @OA\Property(property="rating", type="integer"),
     *   @OA\Property(property="title", type="string", nullable=true),
     *   @OA\Property(property="body", type="string", nullable=true),
     *   @OA\Property(property="status", type="string", example="approved"),
     *   @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
     *   @OA\Property(property="updated_at", type="string", format="date-time", nullable=true)
     * )
     *
     * @OA\Schema(schema="PaginatedMeta",
     *   type="object",
     *   @OA\Property(property="current_page", type="integer"),
     *   @OA\Property(property="last_page", type="integer"),
     *   @OA\Property(property="per_page", type="integer"),
     *   @OA\Property(property="total", type="integer")
     * )
     *
     * @OA\Schema(schema="PaginatedBusinessResponse",
     *   type="object",
     *   @OA\Property(property="data", type="array",
     *     @OA\Items(ref="#/components/schemas/Business")
     *   ),
     *   @OA\Property(property="meta", ref="#/components/schemas/PaginatedMeta")
     * )
     *
     * @OA\Schema(schema="PaginatedReviewResponse",
     *   type="object",
     *   @OA\Property(property="data", type="array",
     *     @OA\Items(ref="#/components/schemas/Review")
     *   ),
     *   @OA\Property(property="meta", ref="#/components/schemas/PaginatedMeta")
     * )
     *
     * @OA\Schema(schema="AuthTokenResponse",
     *   type="object",
     *   @OA\Property(property="token", type="string"),
     *   @OA\Property(property="user", ref="#/components/schemas/User")
     * )
     *
     * @OA\Schema(schema="StoreBusinessRequest",
     *   type="object",
     *   required={"name"},
     *   @OA\Property(property="name", type="string"),
     *   @OA\Property(property="slug", type="string", nullable=true),
     *   @OA\Property(property="description", type="string", nullable=true)
     * )
     *
     * @OA\Schema(schema="UpdateBusinessRequest",
     *   type="object",
     *   @OA\Property(property="name", type="string", nullable=true),
     *   @OA\Property(property="slug", type="string", nullable=true),
     *   @OA\Property(property="description", type="string", nullable=true)
     * )
     *
     * @OA\Schema(schema="StoreReviewRequest",
     *   type="object",
     *   required={"business_id","rating"},
     *   @OA\Property(property="business_id", type="integer"),
     *   @OA\Property(property="rating", type="integer", minimum=1, maximum=5),
     *   @OA\Property(property="title", type="string", nullable=true),
     *   @OA\Property(property="body", type="string", nullable=true)
     * )
     *
     * @OA\Schema(schema="UpdateReviewRequest",
     *   type="object",
     *   @OA\Property(property="rating", type="integer", minimum=1, maximum=5, nullable=true),
     *   @OA\Property(property="title", type="string", nullable=true),
     *   @OA\Property(property="body", type="string", nullable=true)
     * )
     *
     * @OA\Schema(schema="UpdateProfileRequest",
     *   type="object",
     *   @OA\Property(property="name", type="string", nullable=true)
     * )
     */
    public function schemas(): void {}
}
