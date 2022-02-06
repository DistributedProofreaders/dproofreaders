/*global codeUrl */
/* exported ajax */

function ajax(method, apiUrl, queryParams = {}, data = {}, fetchPromise = fetch) {
    let url = new URL("/api/index.php", codeUrl);
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
                    reject("Incorrect response type");
                } else if(response.ok) {
                    resolve(response.json());
                } else {
                    response.json()
                        .then(function(data) {
                            let message = data.error;
                            if(!message) {
                                message = "Unknown error";
                            }
                            reject(message);
                        });
                }
            })
            .catch(reject);
    });
}
