/*global codeUrl */
/* exported ajax ajaxAlert unknownError incorrectResponseType networkError */

const unknownError = 999;
const incorrectResponseType = 998;
const networkError = 997;

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
                    reject({error: "Incorrect response type", code: incorrectResponseType});
                } else if(response.ok) {
                    resolve(response.json());
                } else {
                    response.json()
                        .then(function(data) {
                            if(!data) {
                                data = {error: "Unknown error", code: unknownError};
                            }
                            reject(data);
                        });
                }
            })
            .catch(function() {
                reject({error: "Network error", code: networkError});
            });
    });
}

function ajaxAlert(data) {
    alert(data.error);
}
