<?php


namespace App\Http\Repositories\Trainers\Actions;


use App\Enums\UserRoles;
use App\Models\User;

class GetById
{
    private $trainer;
    private $trainer_id;

    public function __construct($id)
    {
        $this->trainer_id = $id;
    }

    public static function perform($id)
    {
        return (new static($id))->handle();
    }

    public function handle()
    {
        try {
            $this->init();

            return [
                'status_code'   => 200,
                'trainer'       => $this->trainer,
            ];
        } catch (\Throwable $exception){
            return [
                'status_code' => 422,
                'error'       => $exception->getMessage(),
            ];
        }
    }

    public function init()
    {
        $this->trainer = User::query()
            ->where('id', $this->trainer_id)
            ->where('role', UserRoles::TRAINER)
            ->first();

        if (!$this->trainer) {
            throw new \Exception("The trainer doesn't exist");
        }

        return $this;
    }

}
