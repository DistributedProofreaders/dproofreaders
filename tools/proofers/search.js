/* exported makeSearchControl */

function makeSearchControl(searchBox, textOp) {
    const searchInput = document.createElement("input");

    let searchButton = document.createElement("button");
    searchButton.innerHTML = "Search";
    searchButton.addEventListener("click", function() {
        const needle = searchInput.value;
        textOp.search(needle);
    });

    searchBox.append(searchInput, searchButton);
}