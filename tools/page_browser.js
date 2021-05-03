/*global $ proofIntData pageBrowse mentorMode makeApiAjaxSettings*/

$(function () {
    let url = new URL(window.location.href);
    let params = url.searchParams;

    function replaceUrl() {
        window.history.replaceState(null, '', url.href);
    }

    // show error if ajax fails
    function showError(jqxhr) {
        alert(jqxhr.responseJSON.error);
    }

    let projectId = params.get("project");

    let topDiv = $("#page-browser");
    // non-scrolling area for project name and button to select another
    let fixHead = $("<div>", {class: 'fixed-box control-pane'});
    // replace any previous content of topDiv
    topDiv.html(fixHead);
    let stretchDiv = $("<div>", {class: 'stretch-box'});
    topDiv.append(stretchDiv);

    // declare this here to avoid use before define warning
    let getProjectData;

    function selectAProject() {
        // just show the project input
        fixHead.empty();
        stretchDiv.empty();
        document.title = proofIntData.strings.selectProject;

        let projectSelectButton = $("<input>", {type: 'button', value: proofIntData.strings.selectProject});
        let projectInput = $("<input>", {type: 'text', required: true});

        projectSelectButton.click(function () {
            projectId = projectInput.val();
            if("" === projectId) {
                alert(proofIntData.strings.enterID);
                return;
            }
            params.set("project", projectId);
            replaceUrl();
            getProjectData();
        });

        fixHead.append($("<span>", {class: "nowrap"})
            .append(proofIntData.strings.projectid, " ", projectInput), projectSelectButton);
    }

    function showProjectInfo(projectData) {
        fixHead.empty();
        // show project name and button to select another
        let resetButton = $("<input>", {type: 'button', value: proofIntData.strings.reset});
        resetButton.click(function () {
            params.delete("project");
            params.delete("imagefile");
            // keep mode and round
            replaceUrl();
            selectAProject();
        });
        const projectRef = new URL(proofIntData.projectFile);
        projectRef.searchParams.append("id", projectId);
        fixHead.append($("<a>", {href: projectRef}).append(projectData.title), resetButton);

        pageBrowse(stretchDiv, params, "page-browse", replaceUrl, mentorMode);
    }

    getProjectData = function() {
        $.ajax(makeApiAjaxSettings("v1/projects/" + projectId)).done(showProjectInfo)
            .fail(function(jqxhr) {
                showError(jqxhr);
                selectAProject();
            });
    };

    if(projectId) {
        getProjectData();
    } else {
        selectAProject();
    }
});
