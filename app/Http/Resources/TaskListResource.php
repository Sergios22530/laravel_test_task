<?php

namespace App\Http\Resources;

use App\Enums\PriorityEnum;
use App\Enums\StatusEnum;
use App\Models\Task;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use League\Fractal\TransformerAbstract;

class TaskListResource extends TransformerAbstract
{
    /**
     * Transform the resource into an array.
     *
     * @param Task $model
     * @return array
     */
    public function transform(Task $model)
    {

        return [
            'id' => $model->id,
            'title' => Str::limit($model?->title, 50),
            'description' => Str::limit($model?->description, 70),
            'status' => StatusEnum::from($model->status?->value)?->label(),
            'priority' => PriorityEnum::from($model->priority?->value)?->label(),
            'completed_at' => Carbon::parse($model->completed_at)->format('Y-m-d'),
        ];
    }
}
