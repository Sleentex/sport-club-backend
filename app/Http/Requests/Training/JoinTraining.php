<?php

namespace App\Http\Requests\Training;

use App\Enums\TrainingStatuses;
use App\Enums\UserRoles;
use App\Models\ClientTraining;
use App\Models\Training;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class JoinTraining extends FormRequest
{
    private $client;
    private $training;
    private $trainer;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

        ];
    }

    public function perform()
    {
        try {
            $this->init()->check()->joinToTraining();

            return [
                'status_code' => 200,
                'message'       => 'Joined successfully',
            ];
        } catch (\Throwable $exception){
            return [
                'status_code' => 422,
                'error'       => $exception->getMessage()
            ];
        }
    }

    public function init()
    {
        $this->client = auth()->user();

        $this->training = Training::query()
            ->where('id', $this->route('id'))
            ->whereNotIn('status',  [TrainingStatuses::CANCELLED, TrainingStatuses::FINISHED])
            ->first();
        if (!$this->training) {
            throw new \Exception("The training does not exist or finish!");
        }

        return $this;
    }

    public function check()
    {
        $client_have_training = DB::table('trainings')
            ->join('client_trainings', 'client_trainings.training_id', '=', 'trainings.id')
            ->where('client_trainings.client_id', $this->client->id)
            ->whereNotIn('trainings.status',  [TrainingStatuses::CANCELLED, TrainingStatuses::FINISHED])
            ->whereNotIn('client_trainings.status', [TrainingStatuses::CANCELLED, TrainingStatuses::FINISHED])
            ->where(function ($query) {
                $query
                    ->where('trainings.start_at', '>=', $this->training->start_at)
                    ->where('trainings.start_at', '<', $this->training->finish_at)

                    ->orWhere('trainings.start_at', '<=', $this->training->start_at)
                    ->where('trainings.finish_at', '>=', $this->training->start_at);
            })
            ->exists();
        if ($client_have_training) {
            throw new \Exception("The client already has a training session at this time!");
        }

        return $this;
    }

    public function joinToTraining()
    {
        $client_training = new ClientTraining();
        $client_training->client_id   = $this->client->id;
        $client_training->training_id = $this->training->id;
        $client_training->status      = TrainingStatuses::PENDING;
        $client_training->save();

        return $this;
    }

}
