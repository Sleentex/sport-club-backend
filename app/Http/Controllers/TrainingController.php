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


    function getAll(Request $request)
    {
        $response = TrainingRepository::getAll($request);
        return response()->json($response, $response['status_code']);
    }

    function joinTraining(JoinTraining $request)
    {
        $response = $request->perform();
        return response()->json($response, $response['status_code']);
    }

    function addTraining(AddTraining $request)
    {
        $response = $request->perform();
        return response()->json($response, $response['status_code']);
    }

    function cancelRecordTraining(CancelRecordTraining $request)
    {
        $response = $request->perform();
        return response()->json($response, $response['status_code']);
    }

    function cancelTraining(CancelTraining $request)
    {
        $response = $request->perform();
        return response()->json($response, $response['status_code']);
    }

}
