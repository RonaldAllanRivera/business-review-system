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

    public function test_can_sort_reviews_by_rating_desc(): void
    {
        $business = Business::factory()->create();
        Review::factory()->create(['business_id' => $business->id, 'rating' => 2]);
        Review::factory()->create(['business_id' => $business->id, 'rating' => 5]);
        Review::factory()->create(['business_id' => $business->id, 'rating' => 4]);

        $res = $this->getJson('/api/v1/reviews?business_id='.$business->id.'&sort=-rating&per_page=10')
            ->assertOk()
            ->json();

        $ratings = array_map(fn($i) => (int)$i['rating'], $res['data']);
        $this->assertSame([5, 4, 2], $ratings);
    }

    public function test_non_owner_cannot_update_or_delete_review(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $ownerToken = $owner->createToken('api')->plainTextToken;
        $otherToken = $other->createToken('api')->plainTextToken;
        $business = Business::factory()->create();

        // Owner creates review
        $created = $this->withHeader('Authorization', 'Bearer '.$ownerToken)
            ->postJson('/api/v1/reviews', [
                'business_id' => $business->id,
                'rating' => 5,
                'title' => 'Great',
                'body' => 'Nice'
            ])->assertCreated()->json();

        $id = $created['id'];

        // Other user attempts update -> 403
        $this->withHeader('Authorization', 'Bearer '.$otherToken)
            ->putJson('/api/v1/reviews/'.$id, ['rating' => 1])
            ->assertForbidden();

        // Other user attempts delete -> 403
        $this->withHeader('Authorization', 'Bearer '.$otherToken)
            ->deleteJson('/api/v1/reviews/'.$id)
            ->assertForbidden();
    }
}
