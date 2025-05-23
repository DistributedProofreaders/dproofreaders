/* global process require */
const { Builder, Browser, By, until } = require("selenium-webdriver");
const firefox = require("selenium-webdriver/firefox");

(async function example() {
    var options = new firefox.Options();
    options.addArguments("-headless");
    let driver = new Builder().forBrowser(Browser.FIREFOX).setFirefoxOptions(options).build();
    let succeeded = false;
    try {
        await driver.get(`file:///${process.cwd()}/SETUP/tests/jsTests/qunit.html`);
        await driver.wait(until.elementLocated(By.className("passed")), 10000);
        const passed = parseInt(await (await driver.findElement(By.className("passed"))).getText(), 10);
        const total = parseInt(await (await driver.findElement(By.className("total"))).getText(), 10);
        const failed = parseInt(await (await driver.findElement(By.className("failed"))).getText(), 10);
        console.log(`${passed} assertions of ${total} passed, ${failed} failed.`);
        if (failed !== 0) {
            console.error("Failures:");
            const failures = await driver.findElements(By.css(".fail .fail"));
            for (const failure of failures) {
                console.error("------");
                console.error(await failure.getText());
            }
        }
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
