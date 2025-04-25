/*global ajax codeUrl viewSplitter makeProofImageWidget makeProofTextWidget makeUrl constructToolBox hide show */
/* eslint no-use-before-define: "warn" */
/* eslint camelcase: "off" */

window.addEventListener("DOMContentLoaded", async () => {
    const url = new URL(window.location.href);
    const params = new URLSearchParams(url.search);
    const projectId = params.get("projectid");
    const projectState = params.get("proj_state");
    let pageName = params.get("imagefile");
    let pageState = params.get("page_state");
    const topDiv = document.getElementById("proofreading_interface");
    const proofText = JSON.parse(topDiv.dataset.proof_text);
    const widgetText = JSON.parse(topDiv.dataset.widget_text);

    function setUrl() {
        url.search = params;
        window.history.replaceState(null, "", url.href);
    }

    const imageTextDiv = document.getElementById("image_text");
    const imageContainer = document.getElementById("image_container");

    try {
        const proofSettings = await ajax("GET", `v1/storage/newpi`);

        const projectInfo = await ajax("GET", `v1/projects/${projectId}`, { field: ["title", "languages", "round_type"] });
        const roundType = projectInfo.round_type;
        document.getElementById("project_title").textContent = projectInfo.title;

        const pickerData = await ajax("GET", `v1/projects/${projectId}/pickersets`);

        const docMap = {
            proofreading: "proofreading_guidelines.php",
            formatting: "formatting_guidelines.php",
        };

        const docURL = await ajax("GET", `v1/documents/${docMap[roundType]}`);
        document.getElementById("editing_guidelines").href = docURL;

        const dictionaries = await ajax("GET", "v1/dictionaries");

        const container = document.getElementById("text_div");
        const imageWidget = makeProofImageWidget(imageContainer, proofSettings, widgetText, proofText);

        function syncScroll(s) {
            imageWidget.setScroll(s);
        }

        const textWidget = makeProofTextWidget(container, projectId, proofSettings, dictionaries, projectInfo.languages, proofText, widgetText);
        const theSplitter = viewSplitter(imageTextDiv, proofSettings, widgetText);
        theSplitter.mainSplit.onResize.add(textWidget.reLayout);
        theSplitter.mainSplit.onResize.add(imageWidget.reSize);
        theSplitter.setSplitDirCallback.push(textWidget.setup, imageWidget.reset);
        textWidget.scrollListeners.add(syncScroll);
        theSplitter.fireSetSplitDir();
        constructToolBox(textWidget, pickerData, roundType, proofSettings, projectId);
        document.getElementById("action_buttons").append(...theSplitter.buttons);

        let dataSaved = false;
        function setPageState(data) {
            // remove \r
            const nlText = data.text.replace(/\r/g, "");
            textWidget.setText(nlText);
            pageState = data.pagestate;
            dataSaved = data.saved;
            params.set("page_state", pageState);
            setUrl();
        }

        function setPageData(data) {
            setPageState(data);
            imageWidget.setImage(data.image_url);
            let infoText = `Page: ${data.pagenum}`;
            let roundInfoArray = data.round_info;
            if (roundInfoArray.length > 0) {
                const infoMap = roundInfoArray.map(function (roundInfo) {
                    const user = roundInfo.username;
                    let userString;
                    if (user == "" || roundInfo.forum_user_id == null) {
                        userString = user == "" ? proofText.noUser : user;
                    } else {
                        const link = makeUrl(`${proofText.forumURL}/ucp.php`, { i: "pm", mode: "compose", u: roundInfo.forum_user_id }, "comments");
                        userString = `<a href='${link}'>${user}</a>`;
                    }
                    return `${roundInfo.round_id}: ${userString}`;
                });
                infoText += ` &mdash; ${infoMap.join(", ")}`;
            }
            document.getElementById("page_number").innerHTML = infoText;
        }

        async function ajaxPage(method, action, data = {}) {
            return await ajax(method, `v1/projects/${projectId}/pages/${pageName}`, { state: projectState, pagestate: pageState, pageaction: action }, data);
        }

        async function toProjectPage() {
            await ajax("PUT", `v1/storage/newpi`, {}, proofSettings);
            const projectUrl = new URL(`${codeUrl}/project.php`);
            projectUrl.search = new URLSearchParams({ id: projectId });
            window.location.href = projectUrl.href;
        }

        const saveButton = document.getElementById("save_button");
        const exitButton = document.getElementById("exit_button");
        const doneAndExitButton = document.getElementById("done_and_exit_button");
        const doneAndNextButton = document.getElementById("done_and_next_button");
        const revertToOrigButton = document.getElementById("revert_to_original_button");
        const revertToSavedButton = document.getElementById("revert_to_saved_button");
        const abandonButton = document.getElementById("abandon_button");
        const reportBadButton = document.getElementById("report_bad_button");

        const actionButtons = [
            saveButton,
            exitButton,
            doneAndExitButton,
            doneAndNextButton,
            revertToOrigButton,
            revertToSavedButton,
            abandonButton,
            reportBadButton,
        ];

        function disableAction() {
            for (const button of actionButtons) {
                button.disabled = true;
            }
            textWidget.toTextMode();
        }

        function enableAction() {
            for (const button of actionButtons) {
                button.disabled = false;
            }
            revertToSavedButton.disabled = !dataSaved;
        }

        async function resumePage() {
            try {
                const data = await ajaxPage("PUT", "resume");
                setPageData(data);
                enableAction();
            } catch (error) {
                alert(error.message);
                toProjectPage();
            }
        }

        async function nextPage() {
            try {
                textWidget.initWordCheck();
                const data = await ajax("PUT", `v1/projects/${projectId}/checkout`, { state: projectState });
                pageName = data.pagename;
                params.set("imagefile", pageName);
                setPageData(data);
                enableAction();
            } catch (error) {
                alert(error.message);
                toProjectPage();
            }
        }

        disableAction();
        if (pageName) {
            textWidget.initWordCheck();
            // page has been given, change to temp if saved
            resumePage();
        } else {
            // get next page
            nextPage();
        }

        document.getElementById("view_other_pages").href = makeUrl(`${codeUrl}/tools/page_browser.php`, { project: projectId, imagefile: pageName });
        document.getElementById("project_page").href = makeUrl(`${codeUrl}/project.php`, { id: projectId, expected_state: projectState }, "project-comments");

        function checkValidateText(error) {
            alert(error.message);
            enableAction();
            if (error.code == 125) {
                textWidget.showValidate();
            }
        }

        // we need to report wordcheck results just before leaving the page by
        // Exit, Done & exit, Done & next, probably not before Abandon
        async function maybeReportWC() {
            const [wordChecked, acceptedWords] = textWidget.getWCState();
            if (wordChecked) {
                await ajax("PUT", `v1/projects/${projectId}/pages/${pageName}/wordcheck`, {}, { accepted_words: acceptedWords });
            }
        }

        saveButton.addEventListener("click", async () => {
            disableAction();
            const pageText = textWidget.getText();
            try {
                const data = await ajaxPage("PUT", "save", { text: pageText });
                setPageState(data);
                enableAction();
            } catch (error) {
                checkValidateText(error);
            }
        });

        exitButton.addEventListener("click", async () => {
            if (confirm(proofText.qStopProof)) {
                disableAction();
                try {
                    await maybeReportWC();
                } catch (error) {
                    alert(error.messsage);
                }
                toProjectPage();
            }
        });

        doneAndExitButton.addEventListener("click", async () => {
            disableAction();
            const pageText = textWidget.getText();
            try {
                await maybeReportWC();
                await ajaxPage("PUT", "checkin", { text: pageText });
                toProjectPage();
            } catch (error) {
                checkValidateText(error);
            }
        });

        doneAndNextButton.addEventListener("click", async () => {
            disableAction();
            const pageText = textWidget.getText();
            try {
                await maybeReportWC();
                await ajaxPage("PUT", "checkin", { text: pageText });
                await nextPage();
                enableAction();
            } catch (error) {
                checkValidateText(error);
            }
        });

        revertToOrigButton.addEventListener("click", async () => {
            disableAction();
            const pageText = textWidget.getText();
            try {
                const data = await ajaxPage("PUT", "revert", { text: pageText });
                setPageState(data);
                enableAction();
            } catch (error) {
                checkValidateText(error);
            }
        });

        revertToSavedButton.addEventListener("click", async () => {
            if (confirm(proofText.qRevert)) {
                disableAction();
                await resumePage();
                enableAction();
            }
        });

        abandonButton.addEventListener("click", async () => {
            if (confirm(proofText.qReturn)) {
                disableAction();
                try {
                    await ajaxPage("PUT", "abandon");
                } catch (error) {
                    alert(error.message);
                }
                toProjectPage();
            }
        });

        const actionDiv = document.getElementById("action_buttons");
        const badPageReport = document.getElementById("bad_page_report");
        reportBadButton.addEventListener("click", () => {
            hide(actionDiv);
            imageTextDiv.style.visibility = "hidden";
            show(badPageReport);
        });

        document.getElementById("cancel_bad_report").addEventListener("click", () => {
            show(actionDiv);
            imageTextDiv.style.visibility = "visible";
            hide(badPageReport);
        });

        document.getElementById("submit_bad_report").addEventListener("click", async () => {
            let reasonName = document.getElementById("reason_selector").value;
            if (reasonName == "") {
                alert(proofText.selectAReason);
                return;
            }
            disableAction();
            try {
                await ajax("PUT", `v1/projects/${projectId}/pages/${pageName}/reportbad`, {}, { reason: reasonName });
            } catch (error) {
                alert(error.message);
            }
            toProjectPage();
        });
    } catch (error) {
        alert(error.message);
    }
});
