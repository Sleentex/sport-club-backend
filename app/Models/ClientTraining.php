<?php

namespace App\Models;

use App\Enums\TrainingStatuses;
use App\Enums\UserRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientTraining extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'training_id',
        'status',
    ];

    public function training()
    {
        return $this->belongsTo(Training::class, 'training_id')->where('status', '!=', TrainingStatuses::CANCELLED);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id')->where('role', UserRoles::CLIENT);
    }
}
