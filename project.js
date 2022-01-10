/*global $ ajax codeUrl */

$(function () {
    $(".start-proof").click(function () {
        let projectData = JSON.parse(this.dataset.proj);
        let projectId = projectData.projectId;
        let roundId = projectData.roundId;
        // get a new page
        ajax("POST", `v1/projects/${projectId}/rounds/${roundId}`)
            .then(function (pageData) {
                let proofParams = new URLSearchParams({"projectid": projectId, "roundid": roundId, "imagefile": pageData.pagename, "page_state": pageData.pagestate});
                let proofUrl = new URL(`${codeUrl}/tools/proofers/proof.php`);
                proofUrl.search = proofParams.toString();
                window.location.href = proofUrl.href;
            }, alert);
    });
});
