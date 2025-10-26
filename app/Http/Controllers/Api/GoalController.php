<?php

namespace App\Http\Controllers\Api;

use App\Enums\GoalStatusEnum;
use App\Enums\GoalTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Goals\StoreGoalRequest;
use App\Http\Requests\Goals\UpdateGoalRequest;
use App\Http\Resources\GoalResource;
use App\Repositories\GoalRepository;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class GoalController extends Controller
{
    use ApiResponse;

    protected GoalRepository $goalRepository;

    public function __construct(GoalRepository $goalRepository)
    {
        $this->goalRepository = $goalRepository;
    }

    public function index(): JsonResponse
    {
        $userId = auth()->id();

        $goals = $this->goalRepository->getAll($userId);

        return $this->success(GoalResource::collection($goals), 'Goals retrieved successfully');
    }

    public function weekly(): JsonResponse
    {
        try {
            $habits = $this->goalRepository->query()
                ->where('user_id', auth()->id())
                ->where('type', GoalTypeEnum::Weekly->value)
                ->where('status', GoalStatusEnum::Active->value)
                ->get();

            return $this->success(
                GoalResource::collection($habits),
                'Weekly goals retrieved successfully'
            );
        } catch (\Throwable $e) {
            return $this->error('Failed to retrieve weekly goals', 500);
        }
    }

    public function monthly(): JsonResponse
    {
        try {
            $habits = $this->goalRepository->query()
                ->where('user_id', auth()->id())
                ->where('type', GoalTypeEnum::Monthly->value)
                ->where('status', GoalStatusEnum::Active->value)
                ->get();

            return $this->success(
                GoalResource::collection($habits),
                'Monthly active goals retrieved successfully'
            );
        } catch (\Throwable $e) {
            return $this->error('Failed to retrieve monthly goals', 500);
        }
    }

    public function store(StoreGoalRequest $request): JsonResponse
    {
        try {
            $goal = $this->goalRepository->create($request->validated(), auth()->id());

            return $this->success(
                new GoalResource($goal),
                'Goal created successfully',
                201
            );
        } catch (\Throwable $e) {
            return $this->error('Failed to create goal', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $goal = $this->goalRepository->findOrFail($id, auth()->id());

            return $this->success(new GoalResource($goal), 'Goal retrieved successfully');
        } catch (ModelNotFoundException $e) {
            return $this->error('Goal not found or not accessible', 404);
        }
    }

    public function update(UpdateGoalRequest $request, int $id): JsonResponse
    {
        try {
            $goal = $this->goalRepository->update($id, auth()->id(), $request->validated());

            return $this->success(
                new GoalResource($goal),
                'Goal updated successfully'
            );
        } catch (ModelNotFoundException $e) {
            return $this->error('Goal not found or not accessible', 404);
        } catch (\Throwable $e) {
            return $this->error('An error occurred while updating the goal', 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $this->goalRepository->delete($id, auth()->id());

        return $this->success(null, 'Goal deleted successfully');
    }
}
