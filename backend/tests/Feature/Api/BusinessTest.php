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
}
