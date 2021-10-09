<?php

namespace App\Http\Controllers;

use App\Http\Repositories\Clients\ClientRepository;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api')->only(['getAllTrainings']);
        $this->middleware('verify.client')->only(['getAllTrainings']);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/clients/trainings",
     *     tags={"Client"},
     *     operationId="getAllClientTrainings",
     *     summary="Returns list of client trainings",
     *     description="Returns list of client trainings",
     *     security={ {"bearer": {} }},
     *     @OA\Parameter(description="Number of page", in="query", name="page", example="1", @OA\Schema(type="integer")),
     *     @OA\Parameter(description="Items per page", in="query", name="limit", example="10", @OA\Schema(type="integer")),
     *     @OA\Response(
     *          response=200,
     *          description="Succefully found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status_code", type="integer", example=200),
     *              @OA\Property(property="total", type="integer", example=2),
     *              @OA\Property(property="page", type="integer", example=1),
     *              @OA\Property(property="total_page", type="integer", example=1),
     *              @OA\Property(property="limit", type="integer", example=10),
     *              @OA\Property(property="records", type="array", @OA\Items(type="object",
     *              	@OA\Property(property="id", type="integer", example=5),
     *              	@OA\Property(property="trainer_id", type="integer", example=1),
     *              	@OA\Property(property="start_at", type="datetime", example="2021-10-23 13:00:00"),
     *              	@OA\Property(property="finish_at", type="datetime", example="2021-10-23 15:00:00"),
     *              	@OA\Property(property="status", type="string", example="pending"),
     *              	@OA\Property(property="created_at", type="datetime", example="2021-10-08T15:32:51.000000Z"),
     *              	@OA\Property(property="updated_at", type="datetime", example="2021-10-08T18:43:44.000000Z"),
     *              	@OA\Property(property="client_id", type="integer", example=2),
     *              	@OA\Property(property="training_id", type="integer", example=3),
     *              )),
     *          )
     *      )
     * )
     */
    function getAllTrainings(Request $request)
    {
        $response = ClientRepository::getAllTrainings($request);
        return response()->json($response, $response['status_code']);
    }
}
