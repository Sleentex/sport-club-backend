<?php

namespace App\Http\Controllers;

use App\Enums\UserRoles;
use App\Http\Repositories\Trainers\TrainerRepository;
use App\Models\User;
use Illuminate\Http\Request;

class TrainerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/trainers",
     *     tags={"Trainer"},
     *     operationId="getAllTrainer",
     *     summary="Returns list of trainers",
     *     description="Returns list of trainers",
     *     @OA\Parameter(description="Number of page", in="query", name="page", example="1", @OA\Schema(type="integer")),
     *     @OA\Parameter(description="Items per page", in="query", name="limit", example="10", @OA\Schema(type="integer")),
     *     @OA\Response(
     *          response=200,
     *          description="Succefully found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status_code", type="integer", example=200),
     *              @OA\Property(property="total", type="integer", example=3),
     *              @OA\Property(property="page", type="integer", example=1),
     *              @OA\Property(property="total_page", type="integer", example=1),
     *              @OA\Property(property="limit", type="integer", example=10),
     *              @OA\Property(property="trainers", type="array", @OA\Items(type="object", ref="#/components/schemas/User")),
     *          )
     *      )
     * )
     */
    function getAll(Request $request)
    {
        $response = TrainerRepository::getAll($request);
        return response()->json($response, $response['status_code']);
    }


    /**
     * @OA\Get(
     *      path="/api/v1/trainers/{id}",
     *      tags={"Trainer"},
     *      operationId="getTrainer",
     *      summary="Returns trainer by id",
     *      description="Returns trainer by id",
     *      @OA\Parameter(
     *          description="Trainer id",
     *          in="path",
     *          name="id",
     *          required=true,
     *          example="1",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Succefully found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status_code", type="integer", example=200),
     *              @OA\Property(property="trainer", type="object", ref="#/components/schemas/User"),
     *          )
     *      )
     *     )
     */
    function getById($id, Request $request)
    {
        $response = TrainerRepository::getById($id);
        return response()->json($response, $response['status_code']);
    }

}
