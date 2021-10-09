<?php


namespace App\Http\Repositories\Clients\Actions;


use App\Enums\TrainingStatuses;
use App\Models\Training;
use App\Models\User;

class GetAllTrainings
{
    const ITEMS_PER_PAGE = 10;

    private $options;
    private $trainings_q;
    private $trainings;
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
                'records'       => $this->trainings,
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
        $this->page  = $this->options->page ?? 1;
        $this->limit = $this->options->limit ?? self::ITEMS_PER_PAGE;
        $client      = auth()->user();

        $this->trainings_q = Training::query()
            ->join('client_trainings', 'client_trainings.training_id', '=', 'trainings.id')
            ->where('client_trainings.client_id', $client->id)
            ->whereNotIn('client_trainings.status', [TrainingStatuses::CANCELLED])
            ->whereNotIn('trainings.status',  [TrainingStatuses::CANCELLED]);

        return $this;
    }

    public function retrieve()
    {
        $paginator          = $this->trainings_q->orderByDesc('trainings.id')->paginate($this->limit, ['*'], 'page', $this->page);
        $this->trainings    = $paginator->items();
        $this->total        = $paginator->total();
        $this->total_page   = $paginator->lastPage();

        return $this;
    }
}
