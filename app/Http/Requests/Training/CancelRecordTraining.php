<?php

namespace App\Http\Requests\Training;

use App\Enums\TrainingStatuses;
use App\Models\ClientTraining;
use App\Models\Training;
use Illuminate\Foundation\Http\FormRequest;

class CancelRecordTraining extends FormRequest
{
    private $client;
    private $record;

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
            $this->init()->checkDate()->cancel();

            return [
                'status_code' => 200,
                'message'       => 'Cancel record training successfully',
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

        $this->record = ClientTraining::query()
            ->where('client_id', $this->client->id)
            ->where('training_id', $this->route('id'))
            ->where('status', '!=', TrainingStatuses::CANCELLED)
            ->first();

        if (!$this->record) {
            throw new \Exception('Not found record!');
        }

        return $this;
    }

    public function checkDate()
    {
        $is_start_training = Training::query()
            ->where('id', $this->route('id'))
            ->where('start_at', '<=', now()->toDateTimeString())
            ->exists();

        if ($is_start_training) {
            throw new \Exception('It is too late to cancel record!');
        }

        return $this;
    }

    public function cancel()
    {
        $this->record->status = TrainingStatuses::CANCELLED;
        $this->record->save();

        return $this;
    }
}
