<?php


namespace App\Http\Repositories\Trainers;


use App\Http\Repositories\Trainers\Actions\GetAll;
use App\Http\Repositories\Trainers\Actions\GetById;

class TrainerRepository
{
    public static function getAll($options)
    {
        return GetAll::perform($options);
    }

    public static function getById($id)
    {
        return GetById::perform($id);
    }
}
