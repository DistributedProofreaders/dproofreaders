/* exported toggleMenu */

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
