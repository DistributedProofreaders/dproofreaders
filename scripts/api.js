/*global codeUrl */
/* exported ajax ajaxAlert AJAX_ERROR_CODES */

const AJAX_ERROR_CODES = {
    UNKNOWN_ERROR: 999,
    INCORRECT_RESPONSE_TYPE: 998,
    NETWORK_ERROR: 997,
};

function ajax(method, apiUrl, queryParams = {}, data = {}, fetchPromise = fetch) {
    let url = new URL(codeUrl + "/api/index.php");
    let searchParams = new URLSearchParams();
    searchParams.append("url", apiUrl);
    for (const key in queryParams) {
        searchParams.append(key, queryParams[key]);
    }
    url.search = searchParams;
    let upperCaseMethod = method.toUpperCase();
    let options = {
        headers: {"X-API-KEY": "SESSION", 'Accept': 'application/json'},
        credentials: "same-origin",
        method: upperCaseMethod,
    };
    if(upperCaseMethod !== "GET") {
        // POST or PUT
        options.headers['Content-Type'] = 'application/json';
        options.body = JSON.stringify(data);
    }
    return new Promise(function(resolve, reject) {
        fetchPromise(url, options)
            .then(function(response) {
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    reject({error: "Incorrect response type", code: AJAX_ERROR_CODES.INCORRECT_RESPONSE_TYPE});
                } else if(response.ok) {
                    resolve(response.json());
                } else {
                    response.json()
                        .then(function(data) {
                            if(!data) {
                                data = {error: "Unknown error", code: AJAX_ERROR_CODES.UNKNOWN_ERROR};
                            }
                            reject(data);
                        });
                }
            })
            .catch(function() {
                reject({error: "Network error", code: AJAX_ERROR_CODES.NETWORK_ERROR});
            });
    });
}

function ajaxAlert(data) {
    alert(data.error);
}
