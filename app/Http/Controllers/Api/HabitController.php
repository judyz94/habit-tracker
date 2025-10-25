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
use Illuminate\Http\Request;

class HabitController extends Controller
{
    use ApiResponse;

    protected HabitRepository $habitRepository;

    public function __construct(HabitRepository $habitRepository)
    {
        $this->habitRepository = $habitRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $goalId = $request->query('goal_id');

        $habits = $this->habitRepository->getAll($goalId);

        return $this->success(
            HabitResource::collection($habits),
            $goalId
                ? 'Habits retrieved successfully for the given goal'
                : 'All Habits retrieved successfully'
        );
    }

    public function store(StoreHabitRequest $request): JsonResponse
    {
        try {
            $habit = $this->habitRepository->create($request->validated());

            return $this->success(
                new HabitResource($habit),
                'Habit created successfully',
                201
            );
        } catch (\Throwable $e) {
            return $this->error('Failed to create habit', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $habit = $this->habitRepository->findOrFail($id, auth()->id());

            return $this->success(new HabitResource($habit), 'Habit retrieved successfully');
        } catch (ModelNotFoundException $e) {
            return $this->error('Habit not found or not accessible', 404);
        }
    }

    public function update(UpdateHabitRequest $request, int $id): JsonResponse
    {
        try {
            $habit = $this->habitRepository->update($id, auth()->id(), $request->validated());

            return $this->success(
                new HabitResource($habit),
                'Habit updated successfully'
            );
        } catch (ModelNotFoundException $e) {
            return $this->error('Habit not found or not accessible', 404);
        } catch (\Throwable $e) {
            return $this->error('An error occurred while updating the habit', 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $this->habitRepository->delete($id, auth()->id());

        return $this->success(null, 'Habit deleted successfully');
    }
}
