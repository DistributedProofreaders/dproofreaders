/*global $ makeApiAjaxSettings codeUrl splitControl */

// show error if ajax fails
function showError(jqxhr) {
    alert(jqxhr.responseJSON.error);
}

$(function () {
    let url = new URL(window.location.href);
    let params = new URLSearchParams(url.search);

    let projectId = params.get("projectid");
    let projState = params.get("proj_state");
    let imageUrl = params.get("imageurl");
    let pageName = params.get("imagefile");
    let pageState = params.get("page_state");

    let topDiv = $("#page-browser");
    // the non-scrolling area which will contain the controls
    let fixHead = $("<div>", {class: 'fixed-box control-pane'});
    let stretchDiv = $("<div>", {class: 'imtext stretch-box'});
    topDiv.append(fixHead, stretchDiv);

    let imageElement = $("<img>", {src: imageUrl});
    let textArea = $("<textarea>", {class: "text-pane proof-control"});
    let imageDiv = $("<div>", {class: 'overflow-auto'}).append(imageElement);
    let textDiv = $("<div>", {class: 'overflow-auto display-flex'});

    stretchDiv.append(imageDiv, textDiv);
    let mainSplit = splitControl(stretchDiv, {splitVertical: true, splitPercent: 50});
    mainSplit.reLayout();

    let saveAsInProgButton = $("<input>", {type: 'button', class: 'proof-control', value: "Save as in Progress"});
    let saveAsDoneButton = $("<input>", {type: 'button', class: 'proof-control', value: "Save as Done"});
    let saveAsDoneAndNextButton = $("<input>", {type: 'button', class: 'proof-control', value: "Save as Done and proofread next page"});
    let returnToRoundButton = $("<input>", {type: 'button', class: 'proof-control', value: "Return to Round"});
    let stopProofButton = $("<input>", {type: 'button', class: 'proof-control', value: "Stop Proofreading"});
    let revertToOrigButton = $("<input>", {type: 'button', class: 'proof-control', value: "Revert to original"});
    let revertToSaveButton = $("<input>", {type: 'button', class: 'proof-control', value: "Revert to last Save"});

    function isPageOutState() {
        return /page_out/.test(pageState);
    }

    function enableRestoreButton() {
        revertToSaveButton.prop("disabled", isPageOutState());
    }

    function replaceUrl() {
        url.search = params.toString();
        window.history.replaceState(null, '', url.href);
        enableRestoreButton();
    }

    function doNothing() {}

    function toProjectPage() {
        let params = new URLSearchParams({"id": projectId, "expected_state": projState});
        let url = new URL(`${codeUrl}/project.php`);
        url.search = params.toString();
        window.location.href = url.href;
    }

    function loadTextImage(version, loadImage) {
        $.ajax(makeApiAjaxSettings(`v1/projects/${projectId}/projectstates/${projState}/pages/${pageName}/pagestates/${pageState}/versions/${version}`))
            .done(function (data) {
                textArea.val(data.text);
                if(loadImage) {
                    imageElement.attr("src", data.imageUrl);
                }
            })
            .fail(showError);
    }

    function savedInProg(data) {
        pageState = data.pageState;
        params.set("page_state", pageState);
        replaceUrl();
        // reload data incase invalid characters removed or changed
        loadTextImage("current", false);
    }

    function savedDone(data) {
        let limitWarn = data.message;
        if(limitWarn) {
            alert(limitWarn);
        }
        toProjectPage();
    }

    function showNewPage(data) {
        pageName = data.pagename;
        pageState = data.pageState;
        params.set("imagefile", pageName);
        params.set("page_state", pageState);
        replaceUrl();
        loadTextImage("previous", true);
    }

    function andNext(data) {
        let limitWarn = data.message;
        if(limitWarn) {
            alert(limitWarn);
            toProjectPage();
        } else {
            // get a new page
            let ajaxSettings = makeApiAjaxSettings(`v1/projects/${projectId}/projectstates/${projState}/checkout`);
            ajaxSettings.type = "POST";
            $.ajax(ajaxSettings).done(showNewPage)
                .fail( function (jqxhr) {
                    showError(jqxhr);
                    toProjectPage();
                });
        }
    }

    function saveAsInProgress(text) {
        let ajaxSettings = makeApiAjaxSettings(`v1/projects/${projectId}/projectstates/${projState}/pages/${pageName}/pagestates/${pageState}/saveinprogress`);
        ajaxSettings.type = "POST";
        ajaxSettings.data = {text: text};
        $.ajax(ajaxSettings)
            .done(savedInProg)
            .fail(showError);
    }

    function saveAsDone(text) {
        return new Promise(function(resolve, reject) {
            let ajaxSettings = makeApiAjaxSettings(`v1/projects/${projectId}/projectstates/${projState}/pages/${pageName}/pagestates/${pageState}/saveasdone`);
            ajaxSettings.type = "POST";
            ajaxSettings.data = {text};
            $.ajax(ajaxSettings)
                .done(resolve)
                .fail( function errorAndReject(data) {
                    showError(data);
                    reject();
                });
        });
    }

    function returnToRound() {
        let ajaxSettings = makeApiAjaxSettings(`v1/projects/${projectId}/projectstates/${projState}/pages/${pageName}/pagestates/${pageState}/return`);
        ajaxSettings.type = "PUT";
        $.ajax(ajaxSettings)
            .done(toProjectPage)
            .fail(showError);
    }

    saveAsInProgButton.click( function() {
        saveAsInProgress(textArea.val());
    });

    saveAsDoneButton.click( function () {
        saveAsDone(textArea.val()).then(savedDone)
            .catch(doNothing);
    });

    saveAsDoneAndNextButton.click(function () {
        saveAsDone(textArea.val()).then(andNext)
            .catch(doNothing);
    });

    returnToRoundButton.click( function() {
        if(confirm("This will discard all changes you have made on this page. Are you sure you want to return this page to the current round?")) {
            returnToRound();
        }
    });

    stopProofButton.click( function() {
        if(confirm("Are you sure you want to stop proofreading?")) {
            toProjectPage();
        }
    });

    revertToOrigButton.click( function() {
        if(confirm("Are you sure you want to revert to the original text for this round?")) {
            loadTextImage("previous", false);
        }
    });

    revertToSaveButton.click( function() {
        if(confirm("Are you sure you want to revert to your last save?")) {
            loadTextImage("current", false);
        }
    });

    fixHead.append(returnToRoundButton, saveAsInProgButton, revertToOrigButton, revertToSaveButton, saveAsDoneButton, saveAsDoneAndNextButton, stopProofButton);
    textDiv.append(textArea);

    enableRestoreButton();
    let round = isPageOutState() ? "previous" : "current";
    loadTextImage(round, true);
});
