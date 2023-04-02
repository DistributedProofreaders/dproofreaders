/*global*/

function AddProofer() {
    "use strict";

    var that = this;
    this.initForm = function() {
        "use strict";

        that.referrerOption = document.querySelector('select[name=referrer]');
        that.referrerDetails = document.getElementById('referrer_details');
        if (that.referrerOption) {
            that.setOtherDetails();
            that.referrerOption.addEventListener('change', that.setOtherDetails);
        }
    };

    this.setOtherDetails = function() {
        "use strict";

        if (that.referrerOption.value !== 'other') {
            that.referrerDetails.style.display = 'none';
        } else {
            that.referrerDetails.style.display = '';
        }
    };
}

var addProofer = new AddProofer();

window.addEventListener('DOMContentLoaded', addProofer.initForm);
