<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Affirmations\StoreAffirmationRequest;
use App\Http\Requests\Affirmations\UpdateAffirmationRequest;
use App\Http\Resources\AffirmationResource;
use App\Repositories\AffirmationRepository;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class AffirmationController extends Controller
{
    use ApiResponse;

    protected AffirmationRepository $affirmationRepository;

    public function __construct(AffirmationRepository $affirmationRepository)
    {
        $this->affirmationRepository = $affirmationRepository;
    }

    public function index(): JsonResponse
    {
        $affirmations = $this->affirmationRepository->getAll();

        return $this->success(AffirmationResource::collection($affirmations), 'Affirmations retrieved successfully');
    }

    public function store(StoreAffirmationRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $affirmation = $this->affirmationRepository->create($data);

        return $this->success(
            new AffirmationResource($affirmation),
            'Affirmation created successfully',
            201
        );
    }

    public function show(int $id): JsonResponse
    {
        $affirmation = $this->affirmationRepository->findOrFail($id);

        return $this->success(new AffirmationResource($affirmation), 'Affirmation retrieved successfully');
    }

    public function update(UpdateAffirmationRequest $request, int $id): JsonResponse
    {
        try {
            $affirmation = $this->affirmationRepository->update($id, $request->validated());

            return $this->success(
                new AffirmationResource($affirmation),
                'Affirmation updated successfully'
            );
        } catch (ModelNotFoundException $e) {
            return $this->error('Affirmation not found', 404);
        } catch (\Throwable $e) {
            return $this->error('An error occurred while updating the affirmation', 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $this->affirmationRepository->delete($id);

        return $this->success(null, 'Affirmation deleted successfully');
    }
}
