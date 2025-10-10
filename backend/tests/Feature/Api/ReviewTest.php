<?php

namespace Tests\Feature\Api;

use App\Models\Business;
use App\Models\Review;
use App\Models\User;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    public function test_can_list_reviews_with_filters(): void
    {
        $business = Business::factory()->create();
        Review::factory(5)->create(['business_id' => $business->id]);

        $res = $this->getJson('/api/v1/reviews?business_id='.$business->id.'&per_page=3')
            ->assertOk()
            ->json();

        $this->assertArrayHasKey('data', $res);
        $this->assertArrayHasKey('meta', $res);
        $this->assertCount(3, $res['data']);
    }

    public function test_can_create_update_delete_review_with_auth(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('api')->plainTextToken;
        $business = Business::factory()->create();

        // Create
        $create = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/reviews', [
                'business_id' => $business->id,
                'rating' => 5,
                'title' => 'Great place',
                'body' => 'Loved it',
            ])->assertCreated()->json();

        $id = $create['id'];

        // Update (owner)
        $update = $this->withHeader('Authorization', 'Bearer '.$token)
            ->putJson('/api/v1/reviews/'.$id, [
                'rating' => 4,
            ])->assertOk()->json();

        $this->assertEquals(4, $update['rating']);

        // Delete (owner)
        $this->withHeader('Authorization', 'Bearer '.$token)
            ->deleteJson('/api/v1/reviews/'.$id)
            ->assertNoContent();
    }
}
