<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class JokeControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_requires_authentication()
    {
        $response = $this->getJson('/api/jokes');

        $response->assertStatus(401);
    }

    /** @test */
    public function authenticated_user_can_fetch_three_jokes()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        // Mock the external API
        $fakeJokes = [
            ['id'=>1, 'type'=>'programming', 'setup'=>'Setup 1', 'punchline'=>'Punchline 1'],
            ['id'=>2, 'type'=>'programming', 'setup'=>'Setup 2', 'punchline'=>'Punchline 2'],
            ['id'=>3, 'type'=>'programming', 'setup'=>'Setup 3', 'punchline'=>'Punchline 3'],
            ['id'=>4, 'type'=>'programming', 'setup'=>'Setup 4', 'punchline'=>'Punchline 4'],
        ];

        Http::fake([
            getenv('JOKE_API') . '/jokes/programming/ten' => Http::response($fakeJokes, 200)
        ]);

        $response = $this->getJson('/api/jokes');

        $response->assertStatus(200)
                 ->assertJsonCount(3)
                 ->assertJsonFragment(['setup' => 'Setup 1'])
                 ->assertJsonFragment(['setup' => 'Setup 2'])
                 ->assertJsonFragment(['setup' => 'Setup 3']);
    }

    /** @test */
    public function it_returns_empty_array_when_api_fails()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        Http::fake([
            getenv('JOKE_API') . '/jokes/programming/ten' => Http::response([], 500)
        ]);

        $response = $this->getJson('/api/jokes');

        $response->assertStatus(200)
                 ->assertExactJson([]);
    }
}
