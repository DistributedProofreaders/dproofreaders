/*global $ makeApiAjaxSettings codeUrl */

$(function () {
    // show error if ajax fails
    function showError(jqxhr) {
        alert(jqxhr.responseJSON.error);
    }

    function doProof(projectId, projState, pagename, pageState) {
        let proofParams = new URLSearchParams({"projectid": projectId, "proj_state": projState, "imagefile": pagename, "page_state": pageState});
        let proofUrl = new URL(`${codeUrl}/tools/proofers/proof.php`);
        proofUrl.search = proofParams.toString();
        window.location.href = proofUrl.href;
    }

    $(".start-proof").click(function () {
        let projectData = JSON.parse(this.dataset.proj);
        let projectId = projectData.projectId;
        let projState = projectData.projState;
        let ajaxSettings = makeApiAjaxSettings(`v1/projects/${projectId}/projectstates/${projState}/checkout`);
        ajaxSettings.type = "POST";
        $.ajax(ajaxSettings).done(function (pageData) {
            doProof(projectId, projState, pageData.pagename, pageData.pageState);
        })
            .fail(showError);
    });

    $(".resume-proof").click(function () {
        let projectData = JSON.parse(this.dataset.page);
        let projectId = projectData.projectId;
        let projState = projectData.projState;
        let imagefile = projectData.imagefile;
        let ajaxSettings = makeApiAjaxSettings(`v1/projects/${projectId}/projectstates/${projState}/pages/${imagefile}/pagestates/${projectData.pageState}/reopen`);
        ajaxSettings.type = "PUT";
        $.ajax(ajaxSettings).done(function (pageData) {
            doProof(projectId, projState, imagefile, pageData.pageState);
        })
            .fail(showError);
    });
});
