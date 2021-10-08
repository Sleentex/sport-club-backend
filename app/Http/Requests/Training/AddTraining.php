<?php

namespace App\Http\Requests\Training;

use App\Enums\TrainingStatuses;
use App\Models\Training;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class AddTraining extends FormRequest
{
    private $trainer;
    private $training;

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
            'start_at' => 'required|date|after:now',
            'finish_at' => 'required|date|after:start_at',
        ];
    }

    public function perform()
    {
        try {
            $this->init()->check()->addTraining();

            return [
                'status_code' => 200,
                'training'       => $this->training,
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
        $this->trainer = auth()->user();


        return $this;
    }

    public function check()
    {
        $trainer_have_training =  Training::query()
            ->where('trainer_id', $this->trainer->id)
            ->whereNotIn('status',  [TrainingStatuses::CANCELLED, TrainingStatuses::FINISHED])
            ->where(function ($query) {
                $query
                    ->where('start_at', '>=', $this->get('start_at'))
                    ->where('start_at', '<', $this->get('finish_at'))

                    ->orWhere('start_at', '<=', $this->get('start_at'))
                    ->where('finish_at', '>=', $this->get('start_at'));
            })
            ->exists();

        if ($trainer_have_training) {
            throw new \Exception("You already have a training session at this time!");
        }

        return $this;
    }

    public function addTraining()
    {
        $attributes                 = $this->validated();
        $attributes['status']       = TrainingStatuses::PENDING;
        $attributes['trainer_id']   = $this->trainer->id;

        $this->training = Training::query()->create($attributes);

        return $this;
    }
}
