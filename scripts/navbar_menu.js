/* exported toggleMenu */
/* exported showSiteSearch */
/* exported hideSiteSearch */

// Toggle a menu contents to visible and rotate the menu icon
function toggleMenu(menu) {
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
    const menuSelectors = document.getElementsByClassName('menu-selector');
    for (const menuSelector of menuSelectors) {
        menuSelector.addEventListener('click', function () {
            toggleMenu(this.parentElement);
        });
    }

    // if the page has the search menu, add some listeners for it
    if (document.getElementById('search-menu')) {
        document.getElementById('search-menu').addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                hideSiteSearch();
            }
        });

        document.getElementById('site_search').addEventListener('click', showSiteSearch);
        document.getElementById('hide_site_search').addEventListener('click', hideSiteSearch);

        document.addEventListener('keydown', (event) => {
            if (event.key === '/' && document.activeElement.tagName == "BODY") {
                event.preventDefault();
                showSiteSearch();
            }
        });
    }

    // if the page has a language selector, listen for a change
    let langSelector = document.getElementById('langform-lang');
    if (langSelector) {
        langSelector.addEventListener("change", function() {
            document.getElementById('langform').submit();
        });
    }
});
