@php
    use App\Enums\PriorityEnum;
    use App\Models\Task;
    use Illuminate\Support\Carbon;use Illuminate\Support\Collection;
    use App\Enums\StatusEnum;
    use Illuminate\Support\Str;

   /** @var Task|Collection $tasks */
   /** @var Task|Collection $task */
@endphp

<table class="datatables-basic table table-striped" id="task-table">
    <thead>
    <tr>
        <th class="text-center">ID</th>
        <th class="text-center">Title</th>
        <th class="text-center">Description</th>
        <th class="text-center">Status</th>
        <th class="text-center">Priority</th>
        <th class="text-center" >Completed At</th>
        <th class="text-center hidden" ></th>
        <th class="text-center" >Actions</th>
    </tr>
    </thead>
    <tbody>
    @foreach($tasks as $task)
        <tr data-id="{{$task?->id}}">
            <td></td>

            <td class="text-center">{{$task?->id}}</td>

            <td class="py-1 ps-3 text-center">
                {{Str::limit($task?->title,50)}}
            </td>

            <td @class(['text-center'])>{{Str::limit($task?->description,70)}}</td>

            <td @class(['py-1 text-center'])>{{StatusEnum::from($task->status?->value)?->label()}}</td>

            <td @class(['py-1 text-center'])>{{PriorityEnum::from($task->priority?->value)?->label()}} </td>

            <td @class(['py-1 text-center'])>{{Carbon::parse($task->completed_at)->format('Y-m-d')}}</td>

            <td class="py-1 text-center toggle" data-id="{{$task?->id}}"></td>
        </tr>
    @endforeach
    </tbody>
</table>
