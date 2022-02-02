/*global codeUrl */
/* exported ajax */

function ajax(method, apiUrl, queryParams = {}, data = {}) {
    let url = new URL(`${codeUrl}/api/index.php`);
    url.search = new URLSearchParams(Object.assign({url: apiUrl}, queryParams));
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
        fetch(url, options)
            .then(function(response) {
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    reject("Incorrect response type");
                } else if(response.ok) {
                    resolve(response.json());
                } else {
                    return response.json();
                }
            })
            .then(function(data) {
                let message = data.error;
                if(!message) {
                    message = "Unknown error";
                }
                reject(message);
            })
            .catch(reject);
    });
}
