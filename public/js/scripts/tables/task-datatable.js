$(document).ready(function () {
    initTaskDatatable();
});

var _ti = null;

function initTaskDatatable() {
    'use strict';

    $.fn.dataTable.ext.errMode = 'none';

    var table = $('#task-table');
    if (table.length) {
        _ti =  table.DataTable({
            pageLength: 10,
            order: [[0, 'desc']], // Sort by the first column (index 0) in ascending order
            columns: [

                {data: 'id'},
                {data: 'title'},
                {data: 'description'},
                {data: 'status'},
                {data: 'priority'},
                {data: 'completed_at'},
                {data: 'actions'},
                {data: ''},

            ],
            columnDefs: [
                {
                    targets: [6],
                    visible: false
                },
                {
                    // targets: "_all",
                    targets: [7],
                    orderable: true
                },
                {
                    // Actions
                    targets: -1,
                    // title: tableConfig.action.label,
                    render: function (data, type, full, meta) {

                        return (
                            '<div class="processingBlock hidden" data-id="' + full[1] + '"></div>' +
                            '<div class="row-control ps-1" data-id="' + full[1] + '">' +
                            '<a class="control edit text-secondary" data-bs-original-title="Manage task" data-bs-delay="50" data-bs-toggle="tooltip" href="javascript:void(0);" data-id="' + full[1] + '" onclick="showTask(this)">' +
                            '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3 font-medium-1"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>' +
                            '</a>' +
                            '<a class="control delete ms-1 text-secondary"  data-bs-original-title="Delete task" data-bs-delay="50" data-bs-toggle="tooltip" href="javascript:void(0);" data-id="' + full[1] + '" onclick="deleteTask(this)" data-name="' + full[2] + '" class="ps-1">' +
                            '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 font-medium-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>' +
                            '</a>' +
                            '</div>');
                    }
                }
            ],

            dom: '<"d-flex justify-content-between align-items-center mx-0 row table-header"' +
                '<"col-6 col-md-6 table-left-header ps-0"l><"col-6 col-md-6 d-flex align-items-center justify-content-end"f' +
                '<"dt-action-buttons text-end ms-1"B>>>t<"d-flex justify-content-between mx-0 row"' +
                '<"col-6 col-md-6"i><"col-6 col-md-6"p>>',
            displayLength: 10,
            lengthChange: false,
            searchable: true,
            bFilter: true,
            info: false,
            buttons: [
                {
                    text: tableConfig.createTaskLink.label,
                    className: 'btn btn-primary mt-1 mb-1',
                    action: function () {
                        showCreateTaskModal();
                    }
                },

            ],
            fnDrawCallback: function (oSettings) {
                if (oSettings._iDisplayLength > oSettings.fnRecordsDisplay()) {
                    $(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
                } else {
                    $(oSettings.nTableWrapper).find('.dataTables_paginate').show();
                }

            },
            language: {
                paginate: {
                    // remove previous & next text from pagination
                    previous: '&nbsp;',
                    next: '&nbsp;'
                }
            },
            searchDelay: 350,
            processing: false,
            serverSide: true,
            ajax: {
                url: formConfig.create_url,
                // dataSrc: "data",
                type: "GET",
                pages: 5,
                headers: {
                    'X-Auth': 'default'
                },
                data: function (data) {

                },
            },
            drawCallback: function () {
                $('.user-show').on('click', function () {
                    let userId = $(this).closest('tr').attr('data-id');
                    location.href = "/admin/users/" + userId;
                });
            },
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-id', data['id']);
                $(row).attr('data-title', data['title']);
                $(row).attr('data-description', data['description']);
                $(row).attr('data-status', data['status']);
                $(row).attr('data-priority', data['priority']);
                $(row).attr('data-completed_at', data['completed_at']);

                $(row).find('td:eq(0)').addClass('text-center');
                $(row).find('td:eq(1)').addClass('text-center');
                $(row).find('td:eq(2)').addClass('text-center');
                $(row).find('td:eq(3)').addClass('text-center');
                $(row).find('td:eq(4)').addClass('text-center');
                $(row).find('td:eq(5)').addClass('text-center');
                $(row).find('td:eq(6)').addClass('text-center');

                $(row).find('td:eq(6) a').attr('data-id',data['id']);

                console.log( );
            },
        });
    }
};

function showCreateTaskModal() {
    removeAllFormErrors();
    $('#createTaskModal').modal('show');
}


function deleteTask(target) {
    let name = target.getAttribute('data-name');
    let id = target.getAttribute('data-id');

    Swal.fire({
        title: '<span>' + 'Delete task id:' + id + '?</span>',
        text: 'Are you sure you want to delete this task?',
        width: '40%',
        type: 'warning',
        icon: 'warning',
        showCloseButton: true,
        showCancelButton: true,
        reverseButtons: true,
        customClass: {
            confirmButton: 'btn btn-primary me-1',
            cancelButton: 'btn btn-outline-secondary me-1'
        },
        confirmButtonText: 'Confirm',
        cancelButtonText: 'Cancel',
        buttonsStyling: !1
    }).then((result) => {
        if (result.isConfirmed) {
            getAccessToken(true).then((reloaded) => {
                request.delete(formConfig.create_url + '/' + id).then((response) => {
                        response = JSON.parse(response).data;

                        document.dispatchEvent(new CustomEvent('taskDeletedEvent', {detail: {completed: response?.status}}));

                    }
                ).catch((response) => {
                    let errors = JSON.parse(response)?.errors;
                    document.dispatchEvent(new CustomEvent('taskDeletedEvent', {detail: {completed: false}}));
                });
            });

        }
    })

}

function refreshTaskList() {
    _ti.draw(false);
}


document.addEventListener("taskCreatedEvent", function (e) {
    let isCompleted = e.detail?.completed;


    $('#createTaskModal').modal('hide');
    document.getElementById('createTaskForm').reset();

    refreshTaskList();
    if (isCompleted) {
        toastr['success']('Created successfully', 'Task', {
            closeButton: false,
            tapToDismiss: true,
        });
        return;
    }

    toastr['error']('Failed to create', 'Task', {
        closeButton: false,
        tapToDismiss: true,
    });

});

document.addEventListener("taskUpdatedEvent", function (e) {
    let isCompleted = e.detail?.completed;

    $('#editTaskModal').modal('hide');
    document.getElementById('updateTaskForm').reset();

    refreshTaskList();
    if (isCompleted) {
        toastr['success']('Updated successfully', 'Task', {
            closeButton: false,
            tapToDismiss: true,
        });
        return;
    }

    toastr['error']('Failed to update', 'Task', {
        closeButton: false,
        tapToDismiss: true,
    });

});

document.addEventListener("taskDeletedEvent", function (e) {
    let isCompleted = e.detail?.completed;

    refreshTaskList();
    if (isCompleted) {
        toastr['success']('Task deleted successfully', 'Task', {
            closeButton: false,
            tapToDismiss: true,
        });
        return;
    }

    toastr['error']('Failed to Delete', 'Task', {
        closeButton: false,
        tapToDismiss: true,
    });
});


