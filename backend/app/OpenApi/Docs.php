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
     * Basic examples to help generate the spec. Expand as needed.
     *
     * @OA\Get(
     *   path="/api/v1/health",
     *   summary="Health check",
     *   tags={"System"},
     *   @OA\Response(response=200, description="OK")
     * )
     *
     * @OA\Post(
     *   path="/api/v1/auth/login",
     *   summary="Login and get token",
     *   tags={"Auth"},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", example="test@example.com"),
     *       @OA\Property(property="password", type="string", example="password")
     *     )
     *   ),
     *   @OA\Response(response=200, description="Token issued")
     * )
     *
     * @OA\Get(
     *   path="/api/v1/businesses",
     *   summary="List businesses",
     *   tags={"Businesses"},
     *   @OA\Parameter(name="q", in="query", required=false, @OA\Schema(type="string")),
     *   @OA\Parameter(name="min_rating", in="query", required=false, @OA\Schema(type="number")),
     *   @OA\Parameter(name="max_rating", in="query", required=false, @OA\Schema(type="number")),
     *   @OA\Parameter(name="sort", in="query", required=false, @OA\Schema(type="string", example="-id")),
     *   @OA\Parameter(name="per_page", in="query", required=false, @OA\Schema(type="integer", example=15)),
     *   @OA\Response(response=200, description="OK")
     * )
     */
    public function examples(): void
    {
        // This class only holds annotations.
    }
}
