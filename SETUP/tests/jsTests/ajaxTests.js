/* global QUnit ajax AJAX_ERROR_CODES */
/* exported codeUrl */

let codeUrl = "https://www.dummy.org";

QUnit.module("Ajax test", function () {
    QUnit.test("Return correct data", async function (assert) {
        function fetchPromise() {
            const blob = new Blob([JSON.stringify({ key: "value" })], { type: "application/json" });
            const init = { status: 200, headers: { "content-type": "application/json" } };
            return Promise.resolve(new Response(blob, init));
        }
        const data = await ajax("GET", "myUrl", {}, {}, fetchPromise);
        assert.deepEqual(data, { key: "value" });
    });

    QUnit.test("Wrong type of data", async (assert) => {
        function fetchPromise() {
            const blob = new Blob([JSON.stringify("mydata")], { type: "application/json" });
            const init = { status: 200, headers: { "content-type": "text/html" } };
            return Promise.resolve(new Response(blob, init));
        }
        try {
            await ajax("GET", "myUrl", {}, {}, fetchPromise);
        } catch (error) {
            assert.strictEqual(error.message, "Incorrect response type");
            assert.strictEqual(error.code, AJAX_ERROR_CODES.INCORRECT_RESPONSE_TYPE);
            assert.strictEqual(error.name, "AjaxError");
        }
    });

    QUnit.test("Status 404", async function (assert) {
        function fetchPromise() {
            const blob = new Blob([JSON.stringify({ error: "not found" })], { type: "application/json" });
            const init = { status: 404, headers: { "content-type": "application/json" } };
            return Promise.resolve(new Response(blob, init));
        }
        try {
            await ajax("GET", "myUrl", {}, {}, fetchPromise);
        } catch (error) {
            assert.strictEqual(error.message, "not found");
            assert.strictEqual(error.code, 404);
        }
    });

    QUnit.test("Unknown error", async function (assert) {
        function fetchPromise() {
            const blob = new Blob([JSON.stringify("")], { type: "application/json" });
            const init = { status: 404, headers: { "content-type": "application/json" } };
            return Promise.resolve(new Response(blob, init));
        }
        try {
            await ajax("GET", "myUrl", {}, {}, fetchPromise);
        } catch (error) {
            assert.strictEqual(error.message, "Unknown error");
            assert.strictEqual(error.code, AJAX_ERROR_CODES.UNKNOWN_ERROR);
        }
    });

    QUnit.test("Network error", async function (assert) {
        function fetchPromise() {
            return Promise.reject("Network error");
        }
        try {
            await ajax("GET", "myUrl", {}, {}, fetchPromise);
        } catch (error) {
            assert.strictEqual(error.message, "Network error");
            assert.strictEqual(error.code, AJAX_ERROR_CODES.NETWORK_ERROR);
        }
    });

    QUnit.test("Invalid JSON error", async function (assert) {
        function fetchPromise() {
            const blob = new Blob(["/"], { type: "application/json" });
            const init = { status: 200, headers: { "content-type": "application/json" } };
            return Promise.resolve(new Response(blob, init));
        }
        try {
            await ajax("GET", "myUrl", {}, {}, fetchPromise);
        } catch (error) {
            assert.strictEqual(error.message, "SyntaxError: JSON.parse: unexpected character at line 1 column 1 of the JSON data");
            assert.strictEqual(error.code, AJAX_ERROR_CODES.JSON_ERROR);
        }
    });

    QUnit.test("Check sent data", async function (assert) {
        function fetchPromise(url, options) {
            assert.strictEqual(url.href, "https://www.dummy.org/api/index.php?url=myUrl&querykey=queryvalue");
            assert.strictEqual(options.method, "POST");
            assert.strictEqual(options.headers["Content-Type"], "application/json");
            assert.strictEqual(options.headers["X-API-KEY"], "SESSION");
            assert.strictEqual(options.headers.Accept, "application/json");
            assert.strictEqual(options.body, '{"datakey":"datavalue"}');
            const blob = new Blob([JSON.stringify("mydata")], { type: "application/json" });
            const init = { status: 200, headers: { "content-type": "application/json" } };
            return Promise.resolve(new Response(blob, init));
        }

        await ajax("POST", "myUrl", { querykey: "queryvalue" }, { datakey: "datavalue" }, fetchPromise);
    });

    QUnit.test("Check array parameter", async function (assert) {
        function fetchPromise(url) {
            const blob = new Blob([JSON.stringify({ key: "value" })], { type: "application/json" });
            assert.strictEqual(url.href, "https://www.dummy.org/api/index.php?url=myUrl&key%5B%5D=value1&key%5B%5D=value2");
            const init = { status: 200, headers: { "content-type": "application/json" } };
            return Promise.resolve(new Response(blob, init));
        }

        await ajax("GET", "myUrl", { key: ["value1", "value2"] }, {}, fetchPromise);
    });
});
