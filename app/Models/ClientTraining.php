<?php

namespace App\Models;

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

    public function trainer()
    {
        return $this->belongsTo(Training::class);
    }
}
