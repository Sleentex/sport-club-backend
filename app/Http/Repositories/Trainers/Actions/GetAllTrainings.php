<?php


namespace App\Http\Repositories\Trainers\Actions;


use App\Enums\TrainingStatuses;
use App\Models\Training;

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
            $this->init()->filter()->retrieve();

            return [
                'status_code'   => 200,
                'total'         => $this->total,
                'page'          => $this->page,
                'total_page'    => $this->total_page,
                'limit'         => $this->limit,
                'trainings'     => $this->trainings,
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

        $this->trainings_q = Training::query();


        return $this;
    }

    public function filter()
    {
        $trainer     = auth()->user();

        if ($trainer && $this->options->trainer_id == $trainer->id) {
            $this->trainings_q->with('records');
        }

        $this->trainings_q->whereNotIn('trainings.status',  [TrainingStatuses::CANCELLED])
            ->where('trainings.trainer_id', $this->options->trainer_id);

        if (!empty($this->options->from_datetime)) {
            $this->trainings_q->where('trainings.start_at', '>=', $this->options->from_datetime);
        }

        if (!empty($this->options->to_datetime)) {
            $this->trainings_q->where('trainings.finish_at', '<=', $this->options->to_datetime);
        }

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
