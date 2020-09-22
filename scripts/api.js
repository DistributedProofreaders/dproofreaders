/*global $ codeUrl */
/* exported makeApiAjaxSettings */

function makeApiAjaxSettings(apiUrl, queryParams) {
    let queryParamString = "";
    if(queryParams) {
        queryParamString = "&" + $.param(queryParams);
    }
    let settings = {
        "url": codeUrl + "/api/index.php?url=" + apiUrl + queryParamString,
        "dataType": "json",
        "headers": {
            "X-API-KEY": "SESSION"
        }
    };
    return settings;
}
