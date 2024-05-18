<?php

namespace Tests\Unit;

use Tests\TestCase;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Log;

class TodoTest extends TestCase
{
    //use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Run migrations
        Artisan::call('migrate');

        // Seed the database with a user
        $this->seed();
    }
    /**
     * A basic unit test example.
     */

     /** @test */
    public function it_can_fetch_all_todos()
    {
        Log::info("Ruinning it_can_fetch_all_todos");
        User::factory()->count(1)->create();
        $user = User::first();
        $this->actingAs($user);

        // Create some todos for the user
        Todo::factory()->count(5)->create(['userId' => $user->id]);

        // Hit the endpoint
        $response = $this->get('/api/todos',['Content-Type'=>'application/json','Authorization'=>'Bearer '.$user->api_token]);

        // Assert response status
        $response->assertStatus(200);

        // Assert response structure
        if (env('APP_MODE') == 'live') {
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => ['id', 'userId', 'title', 'completed', 'created_at', 'updated_at']
            ],
            'message'
        ]);
        } else {
            $response->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'userId', 'title', 'completed']
                ],
                'message'
            ]);
        }
    }

    /** @test */
    public function it_can_fetch_todo()
    {
        Log::info("Running it_can_fetch_todo");
        User::factory()->count(1)->create();
        $user = User::first();
        $this->actingAs($user);

        // Create some todos for the user
        Todo::factory()->count(5)->create(['userId' => $user->id]);
        $id = rand(1,5);
        // Hit the endpoint
        $response = $this->get("/api/todos/$id",['Content-Type'=>'application/json','Authorization'=>'Bearer '.$user->api_token]);
        
        // Assert response status
        $response->assertStatus(200);

        // Assert response structure
        if (env('APP_MODE') == 'live') {
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => ['id', 'userId', 'title', 'completed', 'created_at', 'updated_at']
            ],
            'message'
        ]);
        } else {
            $response->assertJsonStructure([
                'success',
                'data' => ['id', 'userId', 'title', 'completed'],
                'message'
            ]);
        }
    }

    /** @test */
    public function it_can_create_todo()
    {
        Log::info("Running it_can_create_todo");
        User::factory()->count(1)->create();
        $user = User::first();
        $this->actingAs($user);

        $data = ['title'=>"Banku has come"];
        // Hit the endpoint
        $response = $this->postJson("/api/todos",['title'=>"Banku has come"],['Content-Type'=>'application/json','Authorization'=>'Bearer '.$user->api_token]);
        
        // Assert response status
        $response->assertStatus(201);

        // Assert response structure
        if (env('APP_MODE') == 'live') {
        $response->assertJsonStructure([
            'success',
            'data' => ['id', 'title', 'completed'],
            'message'
        ]);
        } else {
            $response->assertJsonStructure([
                'success',
                'data' =>['id', 'title', 'completed'],
                'message'
            ]);
        }
    }

    /** @test */
    public function it_can_update_todo()
    {
        Log::info("Running it_can_update_todo");
        User::factory()->count(1)->create();
        $user = User::first();
        $this->actingAs($user);

        // Create some todos for the user
        Todo::factory()->count(5)->create(['userId' => $user->id]);
        $id = rand(12,16);
        $bool = rand(0,1);
        // Hit the endpoint
        $response = $this->putJson("/api/todos/$id",['title'=>"Banku has come updated",'completed'=>$bool],['Content-Type'=>'application/json','Authorization'=>'Bearer '.$user->api_token]);
        
        // Assert response status
        $response->assertStatus(200);

        // Assert response structure
        if (env('APP_MODE') == 'live') {
        $response->assertJsonStructure([
            'success',
            'data' => ['id', 'title', 'completed'],
            'message'
        ]);
        } else {
            $response->assertJsonStructure([
                'success',
                'data' =>['id', 'title', 'completed'],
                'message'
            ]);
        }
    }

    /** @test */
    public function it_can_delete_todo()
    {
        Log::info("Running it_can_delete_todo");
        User::factory()->count(1)->create();
        $user = User::first();
        $this->actingAs($user);

        // Create some todos for the user
        Todo::factory()->count(5)->create(['userId' => $user->id]);
        $id = rand(17,22);

        // Hit the endpoint
        $response = $this->delete("/api/todos/$id",['Content-Type'=>'application/json','Authorization'=>'Bearer '.$user->api_token]);
        
        // Assert response status
        $response->assertStatus(200);

        // Assert response structure
        if (env('APP_MODE') == 'live') {
        $response->assertJsonStructure([
            'success',
            'data' => [],
            'message'
        ]);
        } else {
            $response->assertJsonStructure([
                'success',
                'data' =>[],
                'message'
            ]);
        }
    }
}
