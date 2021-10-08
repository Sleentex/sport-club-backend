<?php

namespace App\Models;

use App\Enums\TrainingStatuses;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_at',
        'finish_at',
        'trainer_id',
        'status',
    ];

    public function records()
    {
        return $this->hasMany(ClientTraining::class)->whereNotIn('status', [TrainingStatuses::CANCELLED]);
    }
}
