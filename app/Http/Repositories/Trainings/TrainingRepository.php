<?php


namespace App\Http\Repositories\Trainings;


use App\Http\Repositories\Trainings\Actions\GetAll;

class TrainingRepository
{
    public static function getAll($options)
    {
        return GetAll::perform($options);
    }
}
