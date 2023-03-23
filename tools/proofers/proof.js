/*global $ codeUrl ajax makeImageWidget makeTextWidget viewSplitter validateView
 makeTextOps proofText makeSearchControl */

$(function () {
    let url = new URL(window.location.href);
    let params = new URLSearchParams(url.search);

    let textOp = makeTextOps();

    let projectId = params.get("projectid");
    let roundId = params.get("round");
    let pageName = params.get("imagefile");

    let topDiv = $("#proof_interface");
    let blankDiv = $("<div style='position:fixed; z-index: 2; top:0; left:0; bottom:0; right:0; background:rgba(0,0,0,.5);'>");
    topDiv.append(blankDiv);
    blankDiv.hide();
    // links and controls always available
    let titlePane = $("<div>", {class: 'fixed-box control-pane top-pane'});
    const projectTitle = $("<span>");
    const roundSpan = document.createElement("span");
    const pageNumber = $("<span>");

    const docLink = document.createElement("a");
    docLink.textContent = proofText.guidelines;
    docLink.target = "roundDoc";

    const helpLink = document.createElement("a");
    helpLink.textContent = proofText.help;
    helpLink.target = "helpNewWin";
    helpLink.href = proofText.helpUrl;

    titlePane.append(projectTitle, roundSpan, pageNumber, docLink, helpLink);

    // the non-scrolling area which will contain the controls
    let fixHead = $("<div>", {class: 'fixed-box control-pane top-pane'});
    const proofControl = $("<span>");
    fixHead.append(proofControl);

    let stretchDiv = $("<div>", {class: 'stretch-box'});
    let toolBox = $("<div>", {id: 'toolbox', class: 'fixed-box display-flex bot-pane'});
    let searchBox = $("<div>", {id: 'searchbox', class: 'fixed-box display-flex control-pane bot-pane'});

    makeSearchControl(searchBox, textOp);

    topDiv.append(titlePane, fixHead, stretchDiv, searchBox, toolBox);

    let imageDiv = $("<div>");
    let textDiv = $("<div>");

    let imageWidget = makeImageWidget(imageDiv);

    const imageTextDiv = $("<div>", {class: "full-height"});
    imageTextDiv.append(imageDiv, textDiv);
    stretchDiv.append(imageTextDiv);

    const storageKey = "proof";
    const theSplitter = viewSplitter(imageTextDiv, storageKey);

    const proofTextPane = $("<div>");
    textDiv.append(proofTextPane);
    let textWidget = makeTextWidget(proofTextPane, true, theSplitter.mainSplit.reSize);
    textOp.setTarget(textWidget.textArea);
    theSplitter.setSplitDirCallback.add(imageWidget.setup, textWidget.setup);
    titlePane.append(theSplitter.buttons);

    function doNothing() {}

    function hideProof() {
        proofControl.hide();
        proofTextPane.hide();
        searchBox.hide();
    }

    function showProof() {
        proofControl.show();
        proofTextPane.show();
        searchBox.show();
        textOp.setTarget(textWidget.textArea);
        theSplitter.fireSetSplitDir();
    }

    function validateText(pageText) {
        return new Promise( function(resolve, reject) {
            blankDiv.show();
            ajax("POST", `v1/projects/${projectId}/validatetext`, {}, {text: pageText})
                .then(function (data) {
                    if(data.good) {
                        resolve();
                    } else {
                        blankDiv.hide();
                        hideProof();
                        validateView(data, textDiv, textWidget.setText, fixHead)
                            .then(function() {
                                showProof();
                                reject();
                            });
                    }
                })
                .catch(function(data) {
                    blankDiv.hide();
                    alert(data);
                });
        });
    }

    function makePageLocator() {
        return `v1/rounds/${roundId}/projects/${projectId}/pages/${pageName}`;
    }

    function loadText(option = "current") {
        ajax("GET", makePageLocator(), {pageoption: option})
            .then(function (data) {
                textWidget.setText(data.text);
            })
            .catch(alert);
    }

    function setPageData(data) {
        pageName = data.pagename;
        params.set("imagefile", pageName);
        pageNumber.text(pageName);
        imageWidget.setImage(data.image_url);
        textWidget.setText(data.text);
        url.search = params;
        window.history.replaceState(null, '', url.href);
    }

    function toProjectPage() {
        let params = new URLSearchParams({"id": projectId});
        let url = new URL(`${codeUrl}/project.php`);
        url.search = params;
        window.location.href = url.href;
    }

    function toActivityHub() {
        let url = new URL(`${codeUrl}/activity_hub.php`);
        window.location.href = url.href;
    }

    function newPage() {
        // get a new page
        ajax("POST", `v1/rounds/${roundId}/projects/${projectId}/pages`)
            .then(setPageData)
            .catch(function(data) {
                alert(data);
                toProjectPage();
            });
    }


    function saveAs(newState) {
        return new Promise( function(resolve, reject) {
            let pageText = textWidget.getText();
            validateText(pageText)
                .then( function() {
                    ajax("PUT", makePageLocator(), {}, {"new_page_state": newState, text: pageText})
                        .then(function() {
                            blankDiv.hide();
                            resolve();
                        })
                        .catch( function(data) {
                            blankDiv.hide();
                            alert(data);
                            reject();
                        });
                })
                .catch( function() {
                    reject();
                });
        });
    }

    function _saveAsInProgress() {
        return saveAs("temp");
    }

    function saveAsInProgress() {
        _saveAsInProgress()
            .catch(doNothing);
    }

    function revertToOriginal() {
        _saveAsInProgress()
            .then(function () {
                loadText("original");
            })
            .catch(doNothing);
    }

    function _saveAsDone() {
        return saveAs("saved");
    }

    function saveAsDone() {
        _saveAsDone()
            .then(toProjectPage)
            .catch(doNothing);
    }

    function saveAsDoneAndNext() {
        _saveAsDone()
            .then(newPage)
            .catch(doNothing);
    }

    function returnToRound() {
        if(confirm("This will discard all changes you have made on this page. Are you sure you want to return this page to the current round?")) {
            ajax("PUT", makePageLocator(), {}, {"new_page_state": "avail"})
                .then(toProjectPage, alert);
        }
    }

    function stopProof() {
        if(confirm("Are you sure you want to stop proofreading?")) {
            toProjectPage();
        }
    }

    function restoreToSave() {
        if(confirm("Are you sure you want to restore to your last save?")) {
            loadText();
        }
    }

    function makeButton(label, title, func) {
        const button = document.createElement("button");
        button.innerHTML = label;
        button.title = title;
        button.addEventListener("click", func);
        return button;
    }

    let saveAsInProgButton = makeButton("Save as in Progress", "", saveAsInProgress);
    let saveAsDoneButton = makeButton("Save as Done", "", saveAsDone);
    let revertToOrigButton = makeButton("Save and revert to original", "", revertToOriginal);
    let saveAsDoneAndNextButton = makeButton("Save as Done and proofread next page", "", saveAsDoneAndNext);
    let returnToRoundButton = makeButton("Return to Round", "", returnToRound);
    let stopProofButton = makeButton("Stop Proofreading", "", stopProof);
    let restoreToSaveButton = makeButton("Restore to last Save", "", restoreToSave);

    proofControl.append(returnToRoundButton, saveAsInProgButton, saveAsDoneButton, stopProofButton, revertToOrigButton, restoreToSaveButton, saveAsDoneAndNextButton);

    function hideAll() {
        proofControl.hide();
        searchBox.hide();
        imageTextDiv.hide();
        toolBox.hide();
    }

    function oldPage() {
        // change to temp if saved
        ajax("POST", makePageLocator())
            .then(setPageData)
            .catch(function (data) {
                hideAll();
                alert(data);
                toProjectPage();
            });
    }

    function getProjectInfo() {
        ajax("GET", `v1/projects/${projectId}`)
            .then(function (projectData) {
                projectTitle.text(projectData.title);
                if(!pageName) {
                    // need to checkout a new page
                    newPage();
                } else {
                    oldPage();
                }
                theSplitter.fireSetSplitDir();
            })
            .catch( function(data) {
                hideAll();
                alert(data);
                toActivityHub();
            });
    }

    getProjectInfo();
});
