<?php

namespace Tests\Feature\Api;

use App\Models\Business;
use App\Models\User;
use Tests\TestCase;

class BusinessTest extends TestCase
{
    public function test_can_list_businesses_with_pagination(): void
    {
        Business::factory(25)->create();

        $res = $this->getJson('/api/v1/businesses?per_page=10')
            ->assertOk()
            ->json();

        $this->assertArrayHasKey('data', $res);
        $this->assertArrayHasKey('meta', $res);
        $this->assertCount(10, $res['data']);
    }

    public function test_can_create_update_delete_business_with_auth(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('api')->plainTextToken;

        // Create
        $create = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/businesses', [
                'name' => 'My Coffee Shop',
                'description' => 'Best coffee in town',
            ])->assertCreated()->json();

        $this->assertArrayHasKey('id', $create);

        $id = $create['id'];

        // Update
        $update = $this->withHeader('Authorization', 'Bearer '.$token)
            ->putJson('/api/v1/businesses/'.$id, [
                'name' => 'My Coffee Shop Updated',
            ])->assertOk()->json();

        $this->assertEquals('My Coffee Shop Updated', $update['name']);

        // Delete
        $this->withHeader('Authorization', 'Bearer '.$token)
            ->deleteJson('/api/v1/businesses/'.$id)
            ->assertNoContent();
    }

    public function test_can_filter_businesses_by_search_and_rating(): void
    {
        Business::factory()->create(['name' => 'Coffee Shop', 'rating' => 4.5]);
        Business::factory()->create(['name' => 'Tea House', 'rating' => 3.0]);
        Business::factory()->create(['name' => 'Coffee Beans', 'rating' => 4.9]);

        $res = $this->getJson('/api/v1/businesses?q=Coffee&min_rating=4&sort=name&per_page=10')
            ->assertOk()
            ->json();

        $this->assertCount(2, $res['data']);
        $names = array_map(fn($i) => $i['name'], $res['data']);
        $this->assertSame(['Coffee Beans', 'Coffee Shop'], $names);
    }

    public function test_sorts_businesses_by_allowed_columns_and_direction(): void
    {
        Business::factory()->create(['name' => 'A', 'rating' => 3.0]);
        Business::factory()->create(['name' => 'B', 'rating' => 5.0]);
        Business::factory()->create(['name' => 'C', 'rating' => 4.0]);

        $res = $this->getJson('/api/v1/businesses?sort=-rating&per_page=10')
            ->assertOk()
            ->json();

        $ratings = array_map(fn($i) => (float)$i['rating'], $res['data']);
        $this->assertSame([5.0, 4.0, 3.0], $ratings);
    }

    public function test_invalid_sort_falls_back_to_latest(): void
    {
        $first = Business::factory()->create(['name' => 'First']);
        $second = Business::factory()->create(['name' => 'Second']);

        $res = $this->getJson('/api/v1/businesses?sort=hacker&per_page=10')
            ->assertOk()
            ->json();

        $this->assertSame($second->id, $res['data'][0]['id']);
    }
}
