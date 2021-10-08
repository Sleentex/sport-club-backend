<?php


namespace App\Http\Repositories\Trainers\Actions;


use App\Enums\UserRoles;
use App\Models\User;

class GetAll
{
    const ITEMS_PER_PAGE = 10;

    private $options;
    private $trainers_q;
    private $trainers;
    private $page;
    private $limit;
    private $total;
    private $total_page;

    public function __construct($options)
    {
        $this->options = $options;
    }

    public static function perform($options)
    {
        return (new static($options))->handle();
    }

    public function handle()
    {
        try {
            $this->init()->retrieve();

            return [
                'status_code'   => 200,
                'total'         => $this->total,
                'page'          => $this->page,
                'total_page'    => $this->total_page,
                'limit'         => $this->limit,
                'trainers'      => $this->trainers,
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
        $this->trainers_q = User::query()->where('role', UserRoles::TRAINER);

        $this->page  = $this->options->page ?? 1;
        $this->limit = $this->options->limit ?? self::ITEMS_PER_PAGE;

        return $this;
    }

    public function retrieve()
    {
        $paginator          = $this->trainers_q->orderByDesc('id')->paginate($this->limit, ['*'], 'page', $this->page);

        $this->trainers     = $paginator->items();
        $this->total        = $paginator->total();
        $this->total_page   = $paginator->lastPage();

        return $this;
    }
}
