<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\HabitLogs\StoreHabitLogRequest;
use App\Http\Requests\HabitLogs\UpdateHabitLogRequest;
use App\Http\Resources\HabitLogResource;
use App\Repositories\HabitLogRepository;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HabitLogController extends Controller
{
    use ApiResponse;

    protected HabitLogRepository $habitLogRepository;

    public function __construct(HabitLogRepository $habitLogRepository)
    {
        $this->habitLogRepository = $habitLogRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $habitId = $request->query('habit_id');

        $logs = $this->habitLogRepository->getAll($habitId);

        return $this->success(
            HabitLogResource::collection($logs),
            $habitId
                ? 'Habit logs retrieved successfully for the given habit'
                : 'All habit logs retrieved successfully'
        );
    }

    public function store(StoreHabitLogRequest $request): JsonResponse
    {
        try {
            $habitLog = $this->habitLogRepository->create($request->validated());

            return $this->success(
                new HabitLogResource($habitLog),
                'Habit log created successfully',
                201
            );
        } catch (\Throwable $e) {
            return $this->error('Failed to create habit log' . $e, 500);
        }
    }

    public function update(UpdateHabitLogRequest $request, int $id): JsonResponse
    {
        try {
            $habit = $this->habitLogRepository->update($id, auth()->id(), $request->validated());

            return $this->success(
                new HabitLogResource($habit),
                'Habit log updated successfully'
            );
        } catch (ModelNotFoundException $e) {
            return $this->error('Habit log not found or not accessible', 404);
        } catch (\Throwable $e) {
            return $this->error('An error occurred while updating the habit log', 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $this->habitLogRepository->delete($id, auth()->id());

        return $this->success(null, 'Habit log deleted successfully');
    }
}
