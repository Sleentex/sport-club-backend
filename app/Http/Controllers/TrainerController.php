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


    /**
     * @OA\Get(
     *     path="/api/v1/trainers/{id}/trainings",
     *     tags={"Trainer"},
     *     operationId="getAllTrainerTrainings",
     *     summary="Returns list of trainer trainings",
     *     description="Returns list of trainer trainings",
     *     security={ {"bearer": {} }},
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
     *     @OA\Parameter(description="From datetime", in="query", name="from_datetime", example="2021-10-23 13:00:00", description="Datetime format: Y-m-d H:i:s", @OA\Schema(type="string")),
     *     @OA\Parameter(description="To datetime", in="query", name="to_datetime", example="2021-10-23 15:00:00", description="Datetime format: Y-m-d H:i:s", @OA\Schema(type="string")),
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
     *              @OA\Property(property="trainings", type="array", @OA\Items(type="object",
     *              	@OA\Property(property="id", type="integer", example=1),
     *              	@OA\Property(property="trainer_id", type="integer", example=1),
     *              	@OA\Property(property="start_at", type="datetime", example="2021-10-09 21:44:43"),
     *              	@OA\Property(property="finish_at", type="datetime", example="2021-10-10 13:50:35"),
     *              	@OA\Property(property="status", type="string", example="pending"),
     *              	@OA\Property(property="created_at", type="datetime", example="2021-10-08T14:47:13.000000Z"),
     *              	@OA\Property(property="updated_at", type="datetime", example="2021-10-08T18:44:52.000000Z"),
     *              	@OA\Property(property="records", type="array", @OA\Items(type="object",
     *              		@OA\Property(property="id", type="integer", example=1),
     *              		@OA\Property(property="client_id", type="integer", example=2),
     *              		@OA\Property(property="training_id", type="integer", example=1),
     *              		@OA\Property(property="status", type="string", example="pending"),
     *              		@OA\Property(property="created_at", type="datetime", example="2021-10-08T15:30:48.000000Z"),
     *              		@OA\Property(property="updated_at", type="datetime", example="2021-10-08T18:44:52.000000Z"),
     *              	),
     *              ),
     *              )),
     *          )
     *      )
     * )
     */
    function getAllTrainings($id, Request $request)
    {
        $request->trainer_id = $id;
        $response = TrainerRepository::getAllTrainings($request);
        return response()->json($response, $response['status_code']);
    }

}
