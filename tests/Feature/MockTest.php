<?php

namespace Tests\Feature;

use App\Enums\PriorityEnum;
use App\Enums\StatusEnum;
use App\Http\Controllers\Api\ApiTaskController;
use App\Http\Controllers\Api\Requests\CreateTaskRequest;
use App\Http\Controllers\Api\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Repositories\TaskRepository;
use App\Services\Tasks\CreateTaskActionService;
use App\Services\Tasks\DeleteTaskActionService;
use App\Services\Tasks\UpdateTaskActionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Facades\DataTables;

class MockTest extends TestCase
{
    /**
     * A basic feature test list.
     */
    public function testListReturnsFilteredResults(): void
    {
        // Create a mock for EloquentDataTable
        $eloquentDataTableMock = Mockery::mock(EloquentDataTable::class);

        // Mock the behavior of the EloquentDataTable instance
        $eloquentDataTableMock->shouldReceive('filter')
            ->once()
            ->andReturnSelf(); // Allow chaining

        $eloquentDataTableMock->shouldReceive('setTransformer->toJson')
            ->once()
            ->andReturn('mocked_json_result');

        // Mock the DataTables facade
        DataTables::shouldReceive('eloquent')
            ->once()
            ->andReturn($eloquentDataTableMock); // Return the mocked EloquentDataTable

        // Create a mock request
        $request = Request::create('/api/tasks', 'GET', ['search' => ['value' => 'test']]);

        // Instantiate the controller
        $controller = new ApiTaskController();

        // Call the list method
        $result = $controller->list($request);

        // Assert the expected result
        $this->assertEquals('mocked_json_result', $result);
    }

    public function testCreateTaskReturnsSuccessResponse()
    {
        // Mock the CreateTaskActionService
        $mockCreateService = Mockery::mock(CreateTaskActionService::class);
        $mockCreateService->shouldReceive('setAttributes->handle->hasErrors')->andReturn(false);

        // Mock the CreateTaskRequest
        $mockRequest = Mockery::mock(CreateTaskRequest::class);

        // Mock the `validated()` method to handle key-specific behavior
        $mockRequest->shouldReceive('validated')->andReturnUsing(function ($key = null) {
            $validatedData = [
                'title' => 'Test Task',
                'description' => 'description',
                'status' => 'pending',
                'priority' => 'low',
                'completed_at' => '2025-12-12',
                'user_id' => 1,
            ];

            // Return the whole array if no key is specified, otherwise return the specific value
            return $key ? $validatedData[$key] : $validatedData;
        });

        // Mock the `has()` method
        $mockRequest->shouldReceive('has')->andReturnUsing(function ($key) {
            $validKeys = ['title', 'description', 'status', 'priority', 'completed_at', 'user_id'];
            return in_array($key, $validKeys);
        });

        // Inject the mock into the controller
        $controller = new ApiTaskController();

        // Call the create method with the mocked request
        $response = $controller->create($mockRequest);

        // Assert the response status
        $this->assertEquals(200, $response->status());

    }

    /**
     * Test the show method and its output resource.
     */
    public function testShowReturnsTaskResourceWithExpectedData(): void
    {
        // Create a fake task instance
        $task = Task::factory()->make([
            'id' => 1,
            'title' => 'Test Task',
            'description' => 'Task description',
            'status' => StatusEnum::PENDING,
            'priority' => PriorityEnum::LOW,
            'completed_at' => '2025-12-12',
            'created_at' => now(),
        ]);

        // Mock the controller
        $controller = new ApiTaskController();

        // Call the show method with the fake task
        $response = $controller->show($task);

        // Assert that the response is an instance of TaskResource
        $this->assertInstanceOf(TaskResource::class, $response);

        // Convert the resource to an array and assert its structure
        $responseArray = $response->toArray(new Request());

        $this->assertEquals([
            'id' => 1,
            'title' => 'Test Task',
            'description' => 'Task description',
            'status' => StatusEnum::PENDING,
            'priority' => PriorityEnum::LOW,
            'completed_at' => '2025-12-12',
            'created_at' => now()->toDateTimeString(),
        ], $responseArray);
    }


    /**
     * Test the update method with valid data.
     */
    public function testUpdateTaskReturnsSuccessResponse(): void
    {
        // Mock the UpdateTaskActionService
        $mockUpdateService = Mockery::mock(UpdateTaskActionService::class);
        $mockUpdateService->shouldReceive('setAttributes->setModel->handle->hasErrors')->andReturn(false);

        // Mock the UpdateTaskRequest
        $mockRequest = Mockery::mock(UpdateTaskRequest::class);

        // Mock `validated()` to handle key-specific behavior
        $mockRequest->shouldReceive('validated')->andReturnUsing(function ($key = null) {
            $validatedData = [
                'title' => 'Updated Task',
                'description' => 'Updated description',
                'status' => 'completed',
                'priority' => 'high',
                'completed_at' => '2025-12-12',
            ];

            // Return the entire array if no key is provided
            return $key ? $validatedData[$key] : $validatedData;
        });

        // Mock `has()` method
        $mockRequest->shouldReceive('has')->andReturnUsing(function ($key) {
            $validKeys = ['title', 'description', 'status', 'priority', 'completed_at'];
            return in_array($key, $validKeys);
        });

        // Create a fake task instance
        $task = Task::factory()->make([
            'id' => 1,
            'title' => 'Original Task',
            'description' => 'Original description',
            'status' => 'pending',
            'priority' => 'low',
            'completed_at' => null,
        ]);

        // Instantiate the controller
        $controller = new ApiTaskController();

        // Call the update method
        $response = $controller->update($mockRequest, $task);

        // Assert the response is a JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Assert the response structure
        $responseArray = $response->getData(true);
        $this->assertArrayHasKey('status', $responseArray);
    }

    /**
     * Test the delete method with successful deletion.
     */
    public function testDeleteTaskReturnsSuccessResponse(): void
    {
        // Mock the DeleteTaskActionService
        $mockDeleteService = Mockery::mock(DeleteTaskActionService::class);
        $mockDeleteService->shouldReceive('setModel->handle->hasErrors')->andReturn(false);

        // Create a fake task instance
        $task = Task::factory()->make([
            'id' => 1,
            'title' => 'Task to be deleted',
            'description' => 'Task description',
            'status' => 'pending',
            'priority' => 'low',
            'completed_at' => null,
        ]);

        // Instantiate the controller
        $controller = new ApiTaskController();

        // Call the delete method
        $response = $controller->delete($task);

        // Assert the response is a JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Assert the response structure
        $responseArray = $response->getData(true);


        $this->assertArrayHasKey('status', $responseArray);
        $this->assertFalse($responseArray['data']['status']);
    }

}
