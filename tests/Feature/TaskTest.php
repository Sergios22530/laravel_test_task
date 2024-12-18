<?php

namespace Tests\Feature;

use App\Enums\PriorityEnum;
use App\Enums\StatusEnum;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Task;

class TaskTest extends TestCase
{
    protected array $createdTaskIds = [];
    protected array $createdUserIds = [];
    protected string $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and generate a token
        $user = User::factory()->create();
        $this->trackCreatedUser($user);

        // Generate a Bearer Token
        $this->token = $user->createToken('TestToken')->plainTextToken;
    }

    protected function tearDown(): void
    {
        // Clean up only the created tasks and users
        Task::whereIn('id', $this->createdTaskIds)->delete();
        User::whereIn('id', $this->createdUserIds)->delete();

        parent::tearDown();
    }

    protected function trackCreatedTask($task)
    {
        $this->createdTaskIds[] = $task->id;
    }

    protected function trackCreatedUser($user)
    {
        $this->createdUserIds[] = $user->id;
    }

    protected function withBearerToken($method, $url, $data = [])
    {
        return $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->$method($url, $data);
    }

    public function test_can_get_all_tasks()
    {
        // Create tasks associated with the user and track them
        $tasks = Task::factory()->count(3)->create(['user_id' => $this->createdUserIds[0]]);
        foreach ($tasks as $task) {
            $this->trackCreatedTask($task);
        }

        // Send GET request with Bearer Token
        $response = $this->withBearerToken('getJson', '/api/tasks');

        // Assert response status
        $response->assertStatus(200);

        // Assert JSON structure
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'status',
                    'priority',
                    'completed_at',

                ],
            ],
        ]);
    }

    public function test_can_create_a_task()
    {
        // Sample task data
        $data = [
            'title' => 'New Task',
            'description' => 'Task Description',
            'status' => StatusEnum::PENDING,
            'priority' => PriorityEnum::HIGH,
            'user_id' => $this->createdUserIds[0],
        ];

        // Send POST request with Bearer Token
        $response = $this->withBearerToken('postJson', '/api/tasks', $data);

        // Assert response
        $response->assertStatus(200)
            ->assertJson(['status' => 200, 'data' => ['status' => true]]);

        // Track the created task
        $task = Task::where('title', 'New Task')->first();
        $this->trackCreatedTask($task);

        // Assert database
        $this->assertDatabaseHas('tasks', $data);
    }

    public function test_can_update_a_task()
    {
        // Create a task and track it
        $task = Task::factory()->create(['user_id' => $this->createdUserIds[0]]);
        $this->trackCreatedTask($task);

        // Updated data
        $updatedData = [
            'title' => 'New Task 2',
            'description' => 'Task Description 2',
            'status' => StatusEnum::COMPLETED,
            'priority' => PriorityEnum::LOW,
            'user_id' => $this->createdUserIds[0],
        ];

        // Send PUT request with Bearer Token
        $response = $this->withBearerToken('putJson', "/api/tasks/{$task->id}", $updatedData);

        // Assert response
        $response->assertStatus(200)
            ->assertJson(['status' => 200, 'data' => ['status' => true]]);;

        // Assert database
        $this->assertDatabaseHas('tasks', array_merge($updatedData, ['user_id' => $this->createdUserIds[0]]));
    }

    public function test_can_delete_a_task()
    {
        // Create a task and track it
        $task = Task::factory()->create(['user_id' => $this->createdUserIds[0]]);

        $this->trackCreatedTask($task);

        // Send DELETE request with Bearer Token
        $response = $this->withBearerToken('deleteJson', "/api/tasks/{$task->id}");

        // Assert response
        $response->assertStatus(200)->assertJson(['status' => 200, 'data' => ['status' => true]]);
    }

    public function test_can_get_a_single_task()
    {
        // Create a task and track it
        $task = Task::factory()->create([
            'user_id' => $this->createdUserIds[0],
            'status' => StatusEnum::PENDING->value,
            'priority' => PriorityEnum::HIGH->value,
        ]);
        $this->trackCreatedTask($task);

        // Send GET request with Bearer Token
        $response = $this->withBearerToken('getJson', "/api/tasks/{$task->id}");

        // Assert response
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'status' => StatusEnum::PENDING->value,
                    'priority' => PriorityEnum::HIGH->value,
                    'completed_at' => Carbon::parse($task->completed_at)->format('Y-m-d'),
                    'created_at' => $task->created_at->toDateTimeString(),
                ],
            ]);
    }
}
