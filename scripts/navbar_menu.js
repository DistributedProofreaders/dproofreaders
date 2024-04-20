/* exported toggleMenu showSiteSearch hideSiteSearch */

// Toggle a menu contents to visible and rotate the menu icon
function toggleMenu(menuId) {
    let menu = document.getElementById(menuId);
    for (const element of menu.getElementsByClassName('menu-contents')) {
        element.classList.toggle("invisible");
        element.classList.toggle("transparent");
    }
    for (const element of menu.getElementsByClassName('menu-icon')) {
        element.classList.toggle("rotate");
    }
}

// Close all dropdown menus if the user clicks outside of one
document.addEventListener("click", function (event) {
    let inMenu = false;
    document.querySelectorAll(".menu").forEach(element => {
        if (element.contains(event.target) || element == event.target) {
            inMenu = true;
        }
    });

    if (!inMenu) {
        document.querySelectorAll(".menu-contents").forEach(element => {
            element.classList.add('invisible');
            element.classList.add('transparent');
        });
        document.querySelectorAll(".menu-icon").forEach(element => {
            element.classList.remove('rotate');
        });
    }
});

// Show / hide site search input
function showSiteSearch() {
    document.getElementById('search-menu').classList.remove('nodisp');
    document.getElementById('icon-menu').classList.add('nodisp');
    document.getElementById('search-menu-input').value = '';
    document.getElementById('search-menu-input').focus();
}

function hideSiteSearch() {
    document.getElementById('search-menu').classList.add('nodisp');
    document.getElementById('icon-menu').classList.remove('nodisp');
}

window.addEventListener('DOMContentLoaded', function() {
    // if the page has the search menu, add some listeners for it
    if (document.getElementById('search-menu')) {
        document.getElementById('search-menu').addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                hideSiteSearch();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === '/' && document.activeElement.tagName == "BODY") {
                event.preventDefault();
                showSiteSearch();
            }
        });
    }
});
