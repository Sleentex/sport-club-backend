<?php

namespace App\Http\Controllers;

use App\Http\Repositories\Trainings\TrainingRepository;
use App\Http\Requests\Training\AddTraining;
use App\Http\Requests\Training\CancelRecordTraining;
use App\Http\Requests\Training\CancelTraining;
use App\Http\Requests\Training\JoinTraining;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api')->only(['joinTraining', 'addTraining', 'getAll', 'cancelRecordTraining', 'cancelTraining']);
        $this->middleware('verify.client')->only(['joinTraining', 'cancelRecordTraining']);
        $this->middleware('verify.trainer')->only(['addTraining', 'cancelTraining']);

    }

    // need to change
    /**
     * @OA\Get(
     *     path="/api/v1/trainings",
     *     tags={"Training"},
     *     operationId="getAllTrainings",
     *     summary="Returns list of trainings",
     *     description="Returns list of trainings",
     *     security={ {"bearer": {} }},
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
        $response = TrainingRepository::getAll($request);
        return response()->json($response, $response['status_code']);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/trainings/{id}/join",
     *     operationId="trainingJoin",
     *     description="Join client to training",
     *     summary="Join client to training",
     *     tags={"Training"},
     *     security={ {"bearer": {} }},
     *     @OA\Parameter(
     *          description="Training id",
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
     *          description="Successfully logged out",
     *          @OA\JsonContent(
     *               @OA\Property(property="status_code", type="integer", example=200),
     *               @OA\Property(property="message", type="string", example="Joined successfully")
     *          )
     *      )
     * )
     */
    function joinTraining(JoinTraining $request)
    {
        $response = $request->perform();
        return response()->json($response, $response['status_code']);
    }


    /**
     * @OA\Post(
     *     path="/api/v1/trainings",
     *     operationId="trainingAdd",
     *     description="Trainer add new training",
     *     summary="Trainer add new training",
     *     tags={"Training"},
     *     security={ {"bearer": {} }},
     *     @OA\RequestBody(
     *          required=true,
     *          description="Pass new customer data",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(property="start_at", type="string", example="2021-10-23 13:00:00", description="Date format: Y-m-d H:i:s"),
     *                  @OA\Property(property="finish_at", type="string", example="2021-10-23 15:00:00", description="Date format: Y-m-d H:i:s"),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully logged out",
     *          @OA\JsonContent(
     *              @OA\Property(property="status_code", type="integer", example=200),
     *              @OA\Property(property="training", type="object",
     *              	@OA\Property(property="start_at", type="datetime", example="2021-10-23 13:00:00"),
     *              	@OA\Property(property="finish_at", type="datetime", example="2021-10-23 15:00:00"),
     *              	@OA\Property(property="status", type="string", example="pending"),
     *              	@OA\Property(property="trainer_id", type="integer", example=1),
     *              	@OA\Property(property="updated_at", type="datetime", example="2021-10-08T19:26:06"),
     *              	@OA\Property(property="created_at", type="datetime", example="2021-10-08T19:26:06"),
     *              	@OA\Property(property="id", type="integer", example=3),
     *              ),
     *          )
     *      )
     * )
     */
    function addTraining(AddTraining $request)
    {
        $response = $request->perform();
        return response()->json($response, $response['status_code']);
    }


    /**
     * @OA\Post(
     *     path="/api/v1/trainings/{id}/cancel-record",
     *     operationId="trainingCancelRecord",
     *     description="Cancel record training",
     *     summary="Cancel record training",
     *     tags={"Training"},
     *     security={ {"bearer": {} }},
     *     @OA\Parameter(
     *          description="Training id",
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
     *          description="Successfully logged out",
     *          @OA\JsonContent(
     *               @OA\Property(property="status_code", type="integer", example=200),
     *               @OA\Property(property="message", type="string", example="Cancel record training successfully")
     *          )
     *      )
     * )
     */
    function cancelRecordTraining(CancelRecordTraining $request)
    {
        $response = $request->perform();
        return response()->json($response, $response['status_code']);
    }


    /**
     * @OA\Post(
     *     path="/api/v1/trainings/{id}/cancel",
     *     operationId="trainingCancel",
     *     description="Cancel training",
     *     summary="Cancel training",
     *     tags={"Training"},
     *     security={ {"bearer": {} }},
     *     @OA\Parameter(
     *          description="Training id",
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
     *          description="Successfully logged out",
     *          @OA\JsonContent(
     *               @OA\Property(property="status_code", type="integer", example=200),
     *               @OA\Property(property="message", type="string", example="Cancel training successfully")
     *          )
     *      )
     * )
     */
    function cancelTraining(CancelTraining $request)
    {
        $response = $request->perform();
        return response()->json($response, $response['status_code']);
    }

}
