<?php

namespace App\Http\Controllers\Api;

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
        $goals = $this->goalRepository->getAll();

        return $this->success(GoalResource::collection($goals), 'Goals retrieved successfully');
    }

    public function store(StoreGoalRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        $goal = $this->goalRepository->create($data);

        return $this->success(
            new GoalResource($goal->load('habits')),
            'Goal created successfully',
            201
        );
    }

    public function show(int $id): JsonResponse
    {
        $goal = $this->goalRepository->findOrFail($id);

        return $this->success(new GoalResource($goal->load('habits')), 'Goal retrieved successfully');
    }

    public function update(UpdateGoalRequest $request, int $id): JsonResponse
    {
        try {
            $goal = $this->goalRepository->update($id, $request->validated());

            return $this->success(
                new GoalResource($goal->load('habits')),
                'Goal updated successfully'
            );
        } catch (ModelNotFoundException $e) {
            return $this->error('Goal not found', 404);
        } catch (\Throwable $e) {
            return $this->error('An error occurred while updating the goal', 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $this->goalRepository->delete($id);

        return $this->success(null, 'Goal deleted successfully');
    }
}
