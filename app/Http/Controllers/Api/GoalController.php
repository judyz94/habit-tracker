<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Goals\StoreAffirmationRequest;
use App\Http\Requests\Goals\UpdateAffirmationRequest;
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

        return $this->success(GoalResource::collection($goals), 'Metas recuperadas con éxito');
    }

    public function store(StoreAffirmationRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        $goal = $this->goalRepository->create($data);

        return $this->success(
            new GoalResource($goal->load('habits')),
            'Meta creada satisfactoriamente',
            201
        );
    }

    public function show(int $id): JsonResponse
    {
        $goal = $this->goalRepository->findOrFail($id);

        return $this->success(new GoalResource($goal->load('habits')), 'Meta obtenida con éxito');
    }

    public function update(UpdateAffirmationRequest $request, int $id): JsonResponse
    {
        try {
            $goal = $this->goalRepository->update($id, $request->validated());

            return $this->success(
                new GoalResource($goal->load('habits')),
                'Meta actualizada satisfactoriamente'
            );
        } catch (ModelNotFoundException $e) {
            return $this->error('Meta no encontrada', 404);
        } catch (\Throwable $e) {
            return $this->error('Error al actualizar la meta', 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $this->goalRepository->delete($id);

        return $this->success(null, 'Meta eliminada satisfactoriamente');
    }
}
