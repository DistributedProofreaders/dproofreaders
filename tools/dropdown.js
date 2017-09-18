/* When the user clicks on a button, 
toggle between hiding and showing the dropdown content
if off close all the others before turning on */
function toggleList(id) {
    console.log(id);
    var thisButton = document.getElementById(id);
    var turnOn = !thisButton.classList.contains('show');
    closeAll();
    if(turnOn) {
        thisButton.classList.add('show');
    }
}

function closeAll() {
    var dropdowns = document.getElementsByClassName('dropdown-content');
    var i;
    for (i = 0; i < dropdowns.length; i++) {
        var openDropdown = dropdowns[i];
        if (openDropdown.classList.contains('show')) {
            openDropdown.classList.remove('show');
        }
    }
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {
        closeAll();
  }
}

window.onkeydown = function(event) {
    console.log(event, event.code);
    if(event.code === 'Escape') {
        closeAll();
  }
}
