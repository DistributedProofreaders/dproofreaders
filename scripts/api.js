/*global codeUrl */
/* exported ajax */

function ajax(method, apiUrl, queryParams = {}, data = {}) {
    let url = new URL(`${codeUrl}/api/index.php`);
    queryParams.url = apiUrl;
    url.search = new URLSearchParams(queryParams);
    let options = {
        headers: {"X-API-KEY": "SESSION"},
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
                if(response.ok) {
                    resolve(response.json());
                } else {
                    response.json()
                        .then(function(data) {
                            reject(data.error);
                        });
                }
            });
    });
}
