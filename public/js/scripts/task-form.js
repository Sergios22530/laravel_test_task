$('.datepicker').datepicker({
    format: 'yyyy-mm-dd', // Set date format to yyyy-mm-dd
});

document.querySelector('.create-task-btn').addEventListener('click', function (target) {

    const form = document.querySelector('#createTaskForm');
    let requestData = serializeFormToObject(form, {
        title: null,
        description: null,
        completed_at: null
    });

    requestData = Object.assign(requestData, {
        user_id: config.user_id,
    });

    getAccessToken(true).then(() => {
        request.post(formConfig.create_url, requestData).then((response) => {
                response = JSON.parse(response).data;

                document.dispatchEvent(new CustomEvent('taskCreatedEvent', {detail: {completed: response?.status}}));
            }
        ).catch((response) => {
            let errors = JSON.parse(response).errors;

            let form = document.getElementById('createTaskForm');
            if (errors) Object.keys(errors).forEach((key) => addError(form, key, errors[key]));

            return false;
        });
    });
});

document.querySelector('.update-task-btn').addEventListener('click', function (target) {

    const form = document.querySelector('#updateTaskForm');
    let requestData = serializeFormToObject(form, {
        title: null,
        description: null,
        completed_at: null
    });

    requestData = Object.assign(requestData, {
        user_id: config.user_id,
    });

    getAccessToken(true).then(() => {
        request.put(formConfig.create_url + '/' + this.getAttribute('data-id'), requestData).then((response) => {
                response = JSON.parse(response).data;
                document.dispatchEvent(new CustomEvent('taskUpdatedEvent', {detail: {completed: response?.status}}));
            }
        ).catch((response) => {
            let errors = JSON.parse(response).errors;

            let form = document.getElementById('updateTaskForm');
            if (errors) Object.keys(errors).forEach((key) => addError(form, key, errors[key]));

            return false;
        });
    });
});

function showTask(target) {
    let id = target.getAttribute('data-id');
    if (!id) return;

    getAccessToken(true).then(() => {
        request.get(formConfig.create_url + '/' + id).then((response) => {
                response = JSON.parse(response).data;

                populateForm(response);
                removeAllFormErrors();
                $('#editTaskModal').modal('show');
                document.querySelector('.update-task-btn')?.setAttribute('data-id', id);
                document.getElementById('updateTaskModalLabel').innerHTML = 'Update task id:' + id;
            }
        ).catch((response) => {
            toastr['error']('Resource not found', 'Task ' + id, {
                closeButton: false,
                tapToDismiss: true,
            });
            return false;
        });
    });
}

// Function to populate the form
function populateForm(response) {
    // Set the values of the form inputs based on the response
    const formElements = ['title', 'description', 'status', 'priority', 'completed_at'];

    formElements.forEach(field => {
        // Find the form element by its name attribute
        const inputElement = document.querySelector(`#editTaskModal [name="${field}"]`);

        if (inputElement) {
            // Set the value of the form element based on the response, fallback to empty string
            inputElement.value = response[field] || '';
        }
    });
}


function addError(form, key, message) {
    let element = form.querySelector('[name="' + key + '"]');

    form.querySelector('[name="' + key + '"]')?.classList.add('is-invalid');
    let errorBlock = getNextSiblingBySelector(element, '.invalid-feedback');

    if (errorBlock) errorBlock.textContent = message;


    toastr['error'](message, 'Form Error', {
        closeButton: false,
        tapToDismiss: true,
    });
}


function getNextSiblingBySelector(element, selector) {

    if (!element) return null;

    let sibling = element.nextElementSibling;

    while (sibling) {
        if (sibling.matches(selector)) {
            return sibling; // Return the first matching sibling
        }
        sibling = sibling.nextElementSibling; // Move to the next sibling
    }

    return null; // Return null if no matching sibling is found
}

function removeAllFormErrors() {
    document.querySelectorAll('form .form-element').forEach((element) => {
        element?.classList?.remove('is-invalid');
        let errorBlock = getNextSiblingBySelector(element, '.invalid-feedback');
        if (errorBlock) errorBlock.textContent = '';
    });
}

