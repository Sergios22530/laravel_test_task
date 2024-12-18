const DONE = 200;
const ERROR = 500;
const VALIDATION_ERROR = 422;
const ACCESS_DENIED_ERROR = 403;
const UNACCEPTABLE_ERROR = 406;
const INVALID_ACCESS_TOKEN_ERROR = 498;

var xhrResponseType = null;
var requireAuthorizationHeader = true;

var request = {
    post: function (url, params) {
        // let token = {"_token": csrfToken};
        //
        // params = {
        //     ...params,
        //     ...token
        // }

        return this.send(url, 'post', params);
    },
    put: function (url, params) {
        // let token = {"_token": csrfToken};
        //
        // params = {
        //     ...params,
        //     ...token
        // }

        return this.send(url, 'put', params);
    },

    get: function (url, params) {
        return this.send(url, 'get', params);
    },

    delete: function (url, params) {
        // let token = {"_token": csrfToken};
        //
        // params = {
        //     ...params,
        //     ...token
        // }

        return this.send(url, 'delete', params);
    },

    send: function (url, method, params) {


        return new Promise(function (resolve, reject) {


            var xhr = new XMLHttpRequest();
            xhr.open(method, url, true);

            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            // xhr.setRequestHeader('X-XSRF-TOKEN', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'));
            if (requireAuthorizationHeader) xhr.setRequestHeader('Authorization', 'Bearer ' + localStorage.getItem('token'));
            xhr.setRequestHeader('Content-Type', "application/json");
            xhr.setRequestHeader('Accept', "application/json");

            if (xhrResponseType) xhr.responseType = xhrResponseType; // Устанавливаем тип ответа как бинарный

            // xhr.setRequestHeader('X-Compress', null);
            xhr.onload = function () {

                if (this.status === DONE) {
                    resolve(this.response);
                }
                if (this.status === ERROR) {
                    reject(this.response);
                }
                if (this.status === VALIDATION_ERROR) {
                    reject(this.response);
                }
                if (this.status === ACCESS_DENIED_ERROR) {
                    reject(this.response);
                }

                if (this.status === UNACCEPTABLE_ERROR) {
                    reject(this.response);
                }

                if (this.status === INVALID_ACCESS_TOKEN_ERROR) {
                    reject(this.response);
                }
            };

            xhr.onerror = function () {
                reject(new Error("Network Error"));
            };

            xhr.send(JSON.stringify(params));

            requireAuthorizationHeader = true;

        })
    },
};


function getAccessToken() {
    let tokenFromLocalStorage = getTokenFromLocalStorage();

    if (tokenFromLocalStorage) return new Promise((resolve) => resolve());

    requireAuthorizationHeader = false;

    return request.post(
        '/api/login',
        {
            email: atob(config.api_credentials.login),
            password: atob(config.api_credentials.password)
        }
    ).then((response) => {
        response = JSON.parse(response).data;


        console.log(response);

        localStorage.removeItem('token');
        localStorage.removeItem('expires_at');

        // Save the token and expiration date in localStorage
        if(response?.token) localStorage.setItem('token', response?.token);
        if(response?.expires_at)  localStorage.setItem('expires_at', response?.expires_at);


        return response?.token;
    }).catch((response) => {


        // response = JSON.parse(response).data;
        return null;
    });
}

function getTokenFromLocalStorage() {
    const now = new Date();
    const token = localStorage.getItem('token');
    const expiresAt = localStorage.getItem('expires_at');

    if (!token || !expiresAt) return null;

    if (new Date(expiresAt) < now) {
        localStorage.removeItem('token');
        localStorage.removeItem('expires_at');

        return null;
    }

    return token;
}

function serializeFormToObject(form, defaultData = {}) {
    const formData = new FormData(form); // Extract form data
    const data = {...defaultData}; // Start with default parameters

    formData.forEach((value, key) => {
        // Overwrite default data with form data
        data[key] = value;
    });

    return data;
}
