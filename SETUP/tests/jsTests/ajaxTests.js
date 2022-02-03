/* global QUnit ajax */
/* exported codeUrl */

let codeUrl = "https://www.dummy.org";

QUnit.module("Ajax test", function() {

    QUnit.test("Return correct data", function (assert) {
        function fetchPromise() {
            const blob = new Blob([JSON.stringify("mydata")], {type : 'application/json'});
            const init = {status: 200, headers: {'content-type': 'application/json'}};
            return Promise.resolve(new Response(blob, init));
        }

        return ajax("GET", "myUrl", {}, {}, fetchPromise)
            .then(function(data) {
                assert.strictEqual(data, "mydata");
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
                assert.strictEqual(data, "Incorrect response type");
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
                assert.strictEqual(data, "not found");
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
                assert.strictEqual(data, "Unknown error");
            });
    });

});
