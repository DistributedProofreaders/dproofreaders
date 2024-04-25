/*global $ ajax ajaxAlert codeUrl viewSplitter makeImageWidget makeTextWidget */

window.addEventListener("DOMContentLoaded", () => {
    let url = new URL(window.location.href);
    let params = new URLSearchParams(url.search);
    let projectId = params.get("projectid");
    let projectState = params.get("proj_state");
    let pageName = params.get("imagefile");
    let pageState = params.get("page_state");
    let topDiv = document.getElementById("page-browser");

    // the non-scrolling area which will contain the controls
    let fixHead = document.createElement('div');
    fixHead.classList.add('fixed-box', 'control-pane', 'top-pane');
    const proofControl = document.createElement("span");

    // the area for the image and text
    let stretchDiv = document.createElement('div');
    stretchDiv.classList.add('stretch-box');

    let imageDiv = document.createElement('div');
    let textDiv = document.createElement('div');

    const imageTextDiv = document.createElement('div');
    imageTextDiv.classList.add("full-height");
    imageTextDiv.append(imageDiv, textDiv);
    stretchDiv.append(imageTextDiv);

    let imageWidget = makeImageWidget($(imageDiv));

    const storageKey = "proof";
    const theSplitter = viewSplitter(imageTextDiv, storageKey);

    const proofTextPane = document.createElement('div');
    let textWidget = makeTextWidget($(proofTextPane), true);
    theSplitter.mainSplit.onResize.add(textWidget.reLayout);
    theSplitter.setSplitDirCallback.push(imageWidget.setup, textWidget.setup);

    const infoSpan = document.createElement("span");

    topDiv.append(fixHead, stretchDiv);

    function makeButton(label, title) {
        const button = document.createElement("input");
        button.type = 'button';
        button.value = label;
        button.title = title;
        return button;
    }

    let returnToRoundButton = makeButton("Return to Round", "");
    let stopProofButton = makeButton("Stop Proofreading", "");
    let saveAsInProgButton = makeButton("Save as in Progress", "");
    let saveAsDoneButton = makeButton("Save as Done", "");
    let saveAsDoneAndNextButton = makeButton("Save as Done and proofread next page", "");
    let revertToOrigButton = makeButton("Save and revert to original", "");
    let restoreToSaveButton = makeButton("Restore to last Save", "");

    function showProof() {
        proofControl.innerHTML = "";
        proofControl.append(returnToRoundButton, stopProofButton, saveAsInProgButton, saveAsDoneButton, saveAsDoneAndNextButton, revertToOrigButton, restoreToSaveButton);
        textDiv.innerHTML = "";
        textDiv.append(proofTextPane);
        theSplitter.fireSetSplitDir();
    }

    function toProjectPage() {
        let params = new URLSearchParams({id: projectId});
        let projectUrl = new URL(`${codeUrl}/project.php`);
        projectUrl.search = params;
        window.location.href = projectUrl.href;
    }

    function setUrl() {
        url.search = params;
        window.history.replaceState(null, '', url.href);
    }

    function setInfo(data) {
        let infoText = `Page: ${data.pagenum}`;
        let roundInfoArray = data.round_info;
        if (roundInfoArray.length > 0) {
            const infoMap = roundInfoArray.map(function(roundInfo) {
                let user = roundInfo.username;
                if(user == '') {
                    user = 'none';
                }
                return `${roundInfo.round_id}: ${user}`;
            });
            infoText += ` &mdash; ${infoMap.join(", ")}`;
        }
        infoSpan.innerHTML = infoText;
    }

    function setPageState(data) {
        textWidget.setText(data.text);
        pageState = data.pagestate;
        restoreToSaveButton.disabled = !data.saved;
        params.set("page_state", pageState);
        setUrl();
    }

    function setPageData(data) {
        setPageState(data);
        imageWidget.setImage(data.image_url);
        setInfo(data);
    }

    function ajaxPage(method, action, data = {}) {
        return ajax(method, `v1/projects/${projectId}/pages/${pageName}`, {state: projectState, pagestate: pageState, pageaction: action}, data);
    }

    function resumePage() {
        ajaxPage("PUT", "resume")
            .then(setPageData)
            .catch(function (data) {
                ajaxAlert(data);
                toProjectPage();
            });
    }

    function nextPage() {
        // get next checked out page
        ajax("PUT", `v1/projects/${projectId}/checkout`, {state: projectState})
            .then(function (data) {
                pageName = data.pagename;
                params.set("imagefile", pageName);
                setPageData(data);
            })
            .catch(function(data) {
                ajaxAlert(data);
                toProjectPage();
            });
    }

    function returnToRound() {
        if(confirm("This will discard all changes you have made on this page. Are you sure you want to return this page to the current round?")) {
            ajaxPage("PUT", "abandon")
                .catch(ajaxAlert)
                .finally(toProjectPage);
        }
    }

    function stopProof() {
        if(confirm("Are you sure you want to stop proofreading?")) {
            toProjectPage();
        }
    }

    function showValidate(markArray) {
        const GOOD_TEXT = 0;
        const BAD_TEXT = 1;

        let valDiv = document.createElement("pre");
        let quitValidButton = makeButton("Quit", "");
        let removeInvalidButton = makeButton("Remove invalid characters and Quit", "");

        proofControl.innerHTML = "";
        proofControl.append(quitValidButton, removeInvalidButton);

        quitValidButton.addEventListener("click", showProof);

        removeInvalidButton.addEventListener("click", function() {
            let goodText = "";
            markArray.forEach(function([textPiece, type]) {
                if(type == GOOD_TEXT) {
                    goodText += textPiece;
                }
            });
            textWidget.setText(goodText);
            showProof();
        });

        markArray.forEach(function([textPiece, type]) {
            let textElement;
            if(type == GOOD_TEXT) {
                textElement = textPiece;
            } else if(type == BAD_TEXT) {
                textElement = document.createElement("span");
                textElement.classList.add('bad-char');
                textElement.append(textPiece);
            }
            valDiv.append(textElement);
        });
        textDiv.innerHTML = "";
        textDiv.append(valDiv);
    }

    function checkValidateText(data, pageText) {
        ajaxAlert(data);
        if(data.code == 125) {
            ajax('PUT', `v1/projects/${projectId}/validatetext`, {}, {text: pageText})
                .then( function (data) {
                    showValidate(data.mark_array);
                })
                .catch(ajaxAlert);
        }
    }

    function _saveAsInProgress(action) {
        let pageText = textWidget.getText();
        ajaxPage("PUT", action, {text: pageText})
            .then(setPageState, function(data) {
                checkValidateText(data, pageText);
            });
    }

    function saveAsDone() {
        let pageText = textWidget.getText();
        ajaxPage("PUT", "checkin", {text: pageText})
            .then(toProjectPage, function (data) {
                checkValidateText(data, pageText);
            });
    }

    function saveAsDoneAndNext() {
        let pageText = textWidget.getText();
        ajaxPage("PUT", "checkin", {text: pageText})
            .then(nextPage, function (data) {
                checkValidateText(data, pageText);
            });
    }

    function restoreToSave() {
        if(confirm("Are you sure you want to restore to your last save?")) {
            resumePage();
        }
    }

    returnToRoundButton.addEventListener("click", returnToRound);
    stopProofButton.addEventListener("click", stopProof);
    saveAsInProgButton.addEventListener("click", () => {
        _saveAsInProgress("save");
    });
    saveAsDoneButton.addEventListener("click", saveAsDone);
    saveAsDoneAndNextButton.addEventListener("click", saveAsDoneAndNext);
    revertToOrigButton.addEventListener("click", () => {
        _saveAsInProgress("revert");
    });
    restoreToSaveButton.addEventListener("click", restoreToSave);

    fixHead.append(proofControl, theSplitter.button, infoSpan);

    showProof();

    if(pageName) {
        // page has been given, change to temp if saved
        resumePage();
    } else {
        // get next page
        nextPage();
    }
});
