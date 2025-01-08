/*global codeUrl */
/* exported ajax */
/* exported AJAX_ERROR_CODES */

const AJAX_ERROR_CODES = {
    UNKNOWN_ERROR: 999,
    INCORRECT_RESPONSE_TYPE: 998,
    NETWORK_ERROR: 997,
};

class AjaxError extends Error {
    constructor(message, code) {
        super(message);
        this.code = code;
        this.name = "AjaxError";
    }
}

async function ajax(method, apiUrl, queryParams = {}, data = {}, fetchPromise = fetch) {
    let url = new URL(codeUrl + "/api/index.php");
    let searchParams = new URLSearchParams();
    searchParams.append("url", apiUrl);
    for (const key in queryParams) {
        const value = queryParams[key];
        if (Array.isArray(value)) {
            for (const item of value) {
                searchParams.append(`${key}[]`, item);
            }
        } else {
            searchParams.append(key, value);
        }
    }
    url.search = searchParams;
    let upperCaseMethod = method.toUpperCase();
    let options = {
        headers: { "X-API-KEY": "SESSION", Accept: "application/json" },
        credentials: "same-origin",
        method: upperCaseMethod,
    };
    if (upperCaseMethod !== "GET") {
        // POST or PUT
        options.headers["Content-Type"] = "application/json";
        options.body = JSON.stringify(data);
    }
    let response;
    try {
        response = await fetchPromise(url, options);
    } catch (error) {
        throw new AjaxError(error, AJAX_ERROR_CODES.NETWORK_ERROR);
    }
    const contentType = response.headers.get("content-type");
    if (!contentType || !contentType.includes("application/json")) {
        throw new AjaxError("Incorrect response type", AJAX_ERROR_CODES.INCORRECT_RESPONSE_TYPE);
    }
    const responseData = await response.json();
    if (!response.ok) {
        if (!responseData) {
            throw new AjaxError("Unknown error", AJAX_ERROR_CODES.UNKNOWN_ERROR);
        }
        throw new AjaxError(responseData.error, response.status);
    }
    return responseData;
}
