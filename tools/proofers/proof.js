/*global $ codeUrl ajax makeImageWidget makeTextWidget viewSplitter makeValidator*/


$(function () {
    let url = new URL(window.location.href);
    let params = new URLSearchParams(url.search);

    let projectId = params.get("projectid");
    let roundId = params.get("roundid");
    let pageName = params.get("imagefile");
    let pageState = params.get("page_state");

    let topDiv = $("#page-browser");

    // links and controls always available
    let titlePane = $("<div>", {class: 'fixed-box control-pane'});
    let pageNumber = $("<label>");
    titlePane.append(pageNumber);

    // the non-scrolling area which will contain the controls
    let fixHead = $("<div>", {class: 'fixed-box control-pane'});
    let stretchDiv = $("<div>", {class: 'imtext stretch-box'});
    topDiv.append(titlePane, fixHead, stretchDiv);

    let imageDiv = $("<div>");
    let textDiv = $("<div>");

    let imageWidget = makeImageWidget(imageDiv);


    stretchDiv.append(imageDiv, textDiv);

    const storageKey = "proof";
    const theSplitter = viewSplitter(stretchDiv, storageKey);

    const proofTextPane = $("<div>", {class: "proof-control"});
    textDiv.append(proofTextPane);
    let textWidget = makeTextWidget(proofTextPane, true, theSplitter.mainSplit.reSize);

    theSplitter.setSplitDirCallback.add(imageWidget.setup, textWidget.setup);
    theSplitter.fireSetSplitDir();
    titlePane.append(theSplitter.buttons);

    let saveAsInProgButton = $("<input>", {type: 'button', class: 'proof-control', value: "Save as in Progress"});
    let saveAsDoneButton = $("<input>", {type: 'button', class: 'proof-control', value: "Save as Done"});
    let saveAsDoneAndNextButton = $("<input>", {type: 'button', class: 'proof-control', value: "Save as Done and proofread next page"});
    let returnToRoundButton = $("<input>", {type: 'button', class: 'proof-control', value: "Return to Round"});
    let stopProofButton = $("<input>", {type: 'button', class: 'proof-control', value: "Stop Proofreading"});
    let revertToOrigButton = $("<input>", {type: 'button', class: 'proof-control', value: "Revert to original"});
    let restoreToSaveButton = $("<input>", {type: 'button', class: 'proof-control', value: "Restore to last Save"});

    fixHead.append(returnToRoundButton, saveAsInProgButton, saveAsDoneButton, stopProofButton, revertToOrigButton, restoreToSaveButton, saveAsDoneAndNextButton);

    function replaceUrl() {
        url.search = params.toString();
        window.history.replaceState(null, '', url.href);
    }

    function doNothing() {}

    function toProjectPage() {
        let params = new URLSearchParams({"id": projectId});
        let url = new URL(`${codeUrl}/project.php`);
        url.search = params.toString();
        window.location.href = url.href;
    }

    function setPageData(data) {
        textWidget.setText(data.text);
        restoreToSaveButton.prop("disabled", !data.canRestore);
        pageState = data.pagestate;
        params.set("page_state", pageState);
        replaceUrl();
    }

    function loadTextImage(loadImage) {
        ajax("PUT", `v1/projects/${projectId}/pages/${pageName}/rounds/${roundId}`, {pagestate: pageState, action: "open"})
            .then(function (data) {
                setPageData(data);
                if(loadImage) {
                    pageNumber.text(pageName);
                    imageWidget.setImage(data.imageUrl);
                }
            }, function(data) {
                alert(data);
                toProjectPage();
            });
    }

    function saveAsInProgress(text, revert = false) {
        return new Promise(function(resolve, reject) {
            let action = revert ? "revert" : "save_temp";
            ajax("PUT", `v1/projects/${projectId}/pages/${pageName}/rounds/${roundId}`, {pagestate: pageState, action: action}, text)
                .then(function (data) {
                    setPageData(data);
                    resolve();
                }, function (data) {
                    alert(data);
                    reject();
                });
        });
    }

    function showNewPage(data) {
        pageName = data.pagename;
        pageState = data.pagestate;
        params.set("imagefile", pageName);
        loadTextImage(true);
    }

    function andNext() {
        // get a new page
        ajax("POST", `v1/projects/${projectId}/rounds/${roundId}`)
            .then(showNewPage, function(data) {
                alert(data);
                toProjectPage();
            });
    }

    function saveAsDone(text) {
        return new Promise(function(resolve, reject) {
            ajax("PUT", `v1/projects/${projectId}/pages/${pageName}/rounds/${roundId}`, {pagestate: pageState, action: "save_done"}, text)
                .then(function (data) {
                    if(data.limitReached) {
                        if(data.pageSaved) {
                            alert("Your page has been saved as 'Done'. However, you have now reached the daily page limit for this round.");
                            resolve();
                        } else {
                            saveAsInProgress(text).then(function () {
                                alert("Your page was saved as 'In Progress' rather than 'Done', because you have already reached the daily page limit for this round.");
                                reject();
                            });
                        }
                    } else {
                        // limit not reached
                        resolve();
                    }
                }, function (data) {
                    alert(data);
                    reject();
                });
        });
    }

    function hideProof() {
        $(".proof-control").hide();
    }

    function showProof() {
        $(".proof-control").show();
        theSplitter.fireSetSplitDir();
    }

    let validate = makeValidator({projectId, textWidget, fixHead, textDiv, hideProof, showProof});

    function returnToRound() {
        ajax("PUT", `v1/projects/${projectId}/pages/${pageName}/rounds/${roundId}`, {pagestate: pageState, action: "return"})
            .then(toProjectPage, alert);
    }

    saveAsInProgButton.click( function() {
        validate()
            .then(saveAsInProgress)
            .catch(doNothing);
    });

    saveAsDoneButton.click( function () {
        validate()
            .then(saveAsDone)
            .then(toProjectPage)
            .catch(doNothing);
    });

    saveAsDoneAndNextButton.click(function () {
        validate()
            .then(saveAsDone)
            .then(andNext)
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
        validate()
            .then(function(text) {
                saveAsInProgress(text, true)
                    .catch(doNothing);
            })
            .catch(doNothing);
    });

    restoreToSaveButton.click( function() {
        if(confirm("Are you sure you want to restore to your last save?")) {
            loadTextImage(false);
        }
    });

    loadTextImage(true);
});
