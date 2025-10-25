<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Habits\StoreHabitRequest;
use App\Http\Requests\Habits\UpdateHabitRequest;
use App\Http\Resources\HabitResource;
use App\Repositories\HabitRepository;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class HabitController extends Controller
{
    use ApiResponse;

    protected HabitRepository $habitRepository;

    public function __construct(HabitRepository $habitRepository)
    {
        $this->habitRepository = $habitRepository;
    }

    public function index(): JsonResponse
    {
        $habits = $this->habitRepository->getAll();

        return $this->success(HabitResource::collection($habits), 'Habits retrieved successfully');
    }

    public function store(StoreHabitRequest $request): JsonResponse
    {
        $data = $request->validated();
        $habit = $this->habitRepository->create($data);

        return $this->success(
            new HabitResource($habit->load('logs')),
            'Habit created successfully',
            201
        );
    }

    public function show(int $id): JsonResponse
    {
        $habit = $this->habitRepository->findOrFail($id);

        return $this->success(new HabitResource($habit->load('logs')), 'Habit retrieved successfully');
    }

    public function update(UpdateHabitRequest $request, int $id): JsonResponse
    {
        try {
            $habit = $this->habitRepository->update($id, $request->validated());

            return $this->success(
                new HabitResource($habit->load('logs')),
                'Habit updated successfully'
            );
        } catch (ModelNotFoundException $e) {
            return $this->error('Habit not found', 404);
        } catch (\Throwable $e) {
            return $this->error('An error occurred while updating the habit', 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $this->habitRepository->delete($id);

        return $this->success(null, 'Habit deleted successfully');
    }
}
