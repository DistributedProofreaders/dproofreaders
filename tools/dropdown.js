/* exported toggleList */
/* When the user clicks on a button,
toggle between hiding and showing the dropdown content
if off close all the others before turning on */
function closeAll() {
    "use strict";
    var dropdowns = document.getElementsByClassName('dropdown-content');
    var i, openDropdown;
    for (i = 0; i < dropdowns.length; i++) {
        openDropdown = dropdowns[i];
        if (openDropdown.classList.contains('dropdown-show')) {
            openDropdown.classList.remove('dropdown-show');
        }
    }
}

function toggleList(id) {
    "use strict";
    var thisDropdown = document.getElementById(id);
    var turnOn = !thisDropdown.classList.contains('dropdown-show');
    closeAll();
    if (turnOn) {
        thisDropdown.classList.add('dropdown-show');
    }
}

// Close the dropdown if the user clicks outside of it
window.onclick = function (event) {
    "use strict";
    if (!event.target.matches('.dropdown-button')) {
        closeAll();
    }
};

window.onkeydown = function (event) {
    "use strict";
    if (event.code === 'Escape') {
        closeAll();
    }
};
