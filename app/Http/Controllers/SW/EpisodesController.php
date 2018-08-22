<?php

namespace App\Http\Controllers\SW;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Repositories\SWRepository;

class EpisodesController extends Controller
{
    /**
     * Episodes Controller constructor
     *
     * @param SWRepository $repository
     */
    public function __construct(
        SWRepository $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function index(): JsonResponse
    {
        $resource = $this->repository->get();
        return response()->json(
            $resource
        );
    }
}
