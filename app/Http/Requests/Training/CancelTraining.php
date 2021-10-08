<?php

namespace App\Http\Requests\Training;

use App\Enums\TrainingStatuses;
use App\Models\ClientTraining;
use App\Models\Training;
use Illuminate\Foundation\Http\FormRequest;

class CancelTraining extends FormRequest
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
            //
        ];
    }

    public function perform()
    {
        try {
            $this->init()->checkDate()->cancelTraining()->cancelRecords();

            return [
                'status_code'   => 200,
                'message'       => 'Cancel training successfully',
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

        $this->training = Training::query()
            ->where('id', $this->route('id'))
            ->where('trainer_id', $this->trainer->id)
            ->where('status', '!=', TrainingStatuses::CANCELLED)
            ->first();

        if (!$this->training) {
            throw new \Exception('Not found training!');
        }

        return $this;
    }

    public function checkDate()
    {
        if ($this->training->start_at <= now()->toDateTimeString()) {
            throw new \Exception('It is too late to cancel training!');
        }

        return $this;
    }

    public function cancelTraining()
    {
        $this->training->status = TrainingStatuses::CANCELLED;
        $this->training->save();

        return $this;
    }

    public function cancelRecords()
    {
        ClientTraining::query()
            ->where('training_id', $this->training->id)
            ->where('status', '!=', TrainingStatuses::CANCELLED)
            ->update([
                'status' => TrainingStatuses::CANCELLED
            ]);

        return $this;
    }
}
