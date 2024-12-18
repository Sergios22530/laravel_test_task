@php
    use App\Enums\PriorityEnum;use App\Enums\StatusEnum;
@endphp

    <!-- Modal -->
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="updateTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateTaskModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateTaskForm">
                    @csrf

                    <div class="col-12 mb-1 ">
                        <label class="form-label" for="enterLabel">Title</label>
                        <input type="text" name="title" class="form-control form-element">

                        <span class="invalid-feedback" role="alert"><strong></strong></span>
                    </div>

                    <div class="col-12 mb-1 ">
                        <label class="form-label" for="enterLabel">Description</label>
                        <textarea class="form-control form-element" rows="3" name="description"
                                  placeholder="Enter the description..."></textarea>
                        <span class="invalid-feedback" role="alert"><strong></strong></span>
                    </div>

                    <div class="col-12 mb-1 ">
                        <label class="form-label" for="enterLabel">Completed date</label>
                        <input type="text" class="form-control form-element datepicker" name="completed_at" placeholder="Select date">
                        <span class="invalid-feedback" role="alert"><strong></strong></span>
                    </div>

                    <div class="col-12 mb-1">
                        <label class="form-label" for="enterLabel">Status</label>
                        <select class="form-select form-element" name="status">
                            @foreach (StatusEnum::cases() as $status)
                                <option
                                    value="{{ $status->value }}" {{ StatusEnum::IN_PROGRESS == $status->value ? 'selected' : '' }} >

                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                        <span class="invalid-feedback" role="alert"><strong></strong></span>
                    </div>

                    <div class="col-12 mb-1">
                        <label class="form-label" for="enterLabel">Priority</label>
                        <select class="form-select form-element" name="priority">
                            @foreach (PriorityEnum::cases() as $priority)
                                <option
                                    value="{{ $priority->value }}" {{ PriorityEnum::LOW == $priority->value ? 'selected' : '' }} >
                                    {{ $priority->label() }}
                                </option>
                            @endforeach
                        </select>
                        <span class="invalid-feedback" role="alert"><strong></strong></span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary update-task-btn">Update</button>
            </div>
        </div>
    </div>
</div>
