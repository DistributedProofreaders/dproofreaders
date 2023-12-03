/* global QUnit ajax unknownError incorrectResponseType networkError */
/* exported codeUrl */

let codeUrl = "https://www.dummy.org";

QUnit.module("Ajax test", function() {

    QUnit.test("Return correct data", function (assert) {
        function fetchPromise() {
            const blob = new Blob([JSON.stringify({key: "value"})], {type : 'application/json'});
            const init = {status: 200, headers: {'content-type': 'application/json'}};
            return Promise.resolve(new Response(blob, init));
        }

        return ajax("GET", "myUrl", {}, {}, fetchPromise)
            .then(function(data) {
                assert.deepEqual(data, {key: "value"});
            });
    });

    QUnit.test("Wrong type of data", function (assert) {
        function fetchPromise() {
            const blob = new Blob([JSON.stringify("mydata")], {type : 'application/json'});
            const init = {status: 200, headers: {'content-type': 'text/html'}};
            return Promise.resolve(new Response(blob, init));
        }

        return ajax("GET", "myUrl", {}, {}, fetchPromise)
            .then(function() {}, function(data) {
                assert.deepEqual(data, {error: "Incorrect response type", code: incorrectResponseType});
            });
    });

    QUnit.test("Status 404", function (assert) {
        function fetchPromise() {
            const blob = new Blob([JSON.stringify({error: "not found"})], {type : 'application/json'});
            const init = {status: 404, headers: {'content-type': 'application/json'}};
            return Promise.resolve(new Response(blob, init));
        }

        return ajax("GET", "myUrl", {}, {}, fetchPromise)
            .then(function() {}, function(data) {
                assert.strictEqual(data.error, "not found");
            });
    });

    QUnit.test("Unknown error", function (assert) {
        function fetchPromise() {
            const blob = new Blob([JSON.stringify("")], {type : 'application/json'});
            const init = {status: 404, headers: {'content-type': 'application/json'}};
            return Promise.resolve(new Response(blob, init));
        }

        return ajax("GET", "myUrl", {}, {}, fetchPromise)
            .then(function() {}, function(data) {
                assert.deepEqual(data, {error: "Unknown error", code: unknownError});
            });
    });

    QUnit.test("Network error", function (assert) {
        function fetchPromise() {
            return Promise.reject();
        }

        return ajax("GET", "myUrl", {}, {}, fetchPromise)
            .then(function() {}, function(data) {
                assert.deepEqual(data, {error: "Network error", code: networkError});
            });
    });

    QUnit.test("Check sent data", function (assert) {
        function fetchPromise(url, options) {
            assert.strictEqual(url.href, "https://www.dummy.org/api/index.php?url=myUrl&querykey=queryvalue");
            assert.strictEqual(options.method, "POST");
            assert.strictEqual(options.headers['Content-Type'], 'application/json');
            assert.strictEqual(options.headers["X-API-KEY"], "SESSION");
            assert.strictEqual(options.headers.Accept, 'application/json');
            assert.strictEqual(options.body, "{\"datakey\":\"datavalue\"}");
            const blob = new Blob([JSON.stringify("mydata")], {type : 'application/json'});
            const init = {status: 200, headers: {'content-type': 'application/json'}};
            return Promise.resolve(new Response(blob, init));
        }

        return ajax("POST", "myUrl", {querykey: "queryvalue"}, {datakey: "datavalue"}, fetchPromise)
            .then();
    });
});
