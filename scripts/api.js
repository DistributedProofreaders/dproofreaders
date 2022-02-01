/*global codeUrl */
/* exported ajax */

function ajax(method, apiUrl, queryParams = {}, data = {}) {
    let url = new URL(`${codeUrl}/api/index.php`);
    queryParams.url = apiUrl;
    url.search = new URLSearchParams(queryParams);
    method = method.toUpperCase();
    let options = {
        headers: {"X-API-KEY": "SESSION", 'Accept': 'application/json'},
        credentials: "same-origin",
        method: method,
    };
    if(method !== "GET") {
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
