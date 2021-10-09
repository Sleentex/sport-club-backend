<?php


namespace App\Http\Repositories\Clients;


use App\Http\Repositories\Clients\Actions\GetAllTrainings;

class ClientRepository
{
    public static function getAllTrainings($options)
    {
        return GetAllTrainings::perform($options);
    }
}
