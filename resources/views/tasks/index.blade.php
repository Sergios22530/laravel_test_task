@php use App\Models\Task;use Illuminate\Support\Collection;use Illuminate\Support\Js; @endphp
@php
    /** @var Task|Collection $tasks */
@endphp


@extends('layouts.app')

@section('vendor-style')
    {{-- vendor css files --}}
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- Include SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.10/dist/sweetalert2.min.css" rel="stylesheet">

@endsection

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">

                <div id="task-list" class="card">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-title mb-0"><h1
                                            class="text-primary">Tasks</h1>
                                    </div>

                                    <div class="taskOuterTableWrapper">
                                        @include('tasks/task-table', ['tasks' => $tasks])
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        @include('tasks/_partials/create-modal')

        @include('tasks/_partials/edit-modal')

        @endsection

        @section('page-script')
            <script type="application/javascript">

                var config = {
                    user_id: {{Js::from(auth()->user()?->id)}},
                    api_credentials: {
                        login: btoa({{Js::from(config('api.user'))}}),
                        password: btoa({{Js::from(config('api.password'))}}),
                    }
                };

                var formConfig = {
                    create_url: {{Js::from(route('create-task'))}}
                };

                var tableConfig = {
                    createTaskLink: {
                        label: "Create Task",
                        labelDisabled: "Delete",
                    }
                };
            </script>

            <!-- Toastr JS -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
            <!-- Include SweetAlert2 JS -->
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.10/dist/sweetalert2.min.js"></script>

            <script src="{{ asset(mix('js/scripts/tables/task-datatable.js')) }}"></script>
            <script src="{{ asset(mix('js/scripts/task-index.js')) }}"></script>
            <script src="{{ asset(mix('js/scripts/task-form.js')) }}"></script>
@endsection



