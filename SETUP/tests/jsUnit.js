/* global process require */
const {Builder, Browser, By, until} = require('selenium-webdriver');
const firefox = require('selenium-webdriver/firefox');

(async function example() {
    var options = new firefox.Options();
    options.addArguments("-headless");
    let driver = new Builder()
        .forBrowser(Browser.FIREFOX)
        .setFirefoxOptions(options)
        .build();
    let succeeded = false;
    try {
        await driver.get(`file:///${process.cwd()}/SETUP/tests/qunit.html`);
        await driver.wait(until.elementLocated(By.className('passed')), 10000);
        var passed = parseInt(await (await driver.findElement(By.className('passed'))).getText(), 10);
        var total = parseInt(await (await driver.findElement(By.className('total'))).getText(), 10);
        var failed = parseInt(await (await driver.findElement(By.className('failed'))).getText(), 10);
        console.log(`${passed} assertions of ${total} passed, ${failed} failed.`);
        succeeded = passed === total && failed === 0;
    } catch (e) {
        console.error(e);
    } finally {
        await driver.quit();
    }
    if (!succeeded) {
        process.exit(1);
    }
})();
