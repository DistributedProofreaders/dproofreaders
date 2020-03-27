/*global $*/

function AddProofer() {
  "use strict";

  var that = this;
  this.initForm = function() {
    "use strict";

    that.referrerOption = $('select[name=referrer]');
    that.referrerDetails = $('#referrer_details');
    that.setOtherDetails();
    that.referrerOption.change(that.setOtherDetails);
  };

  this.setOtherDetails = function() {
    "use string";

    if (that.referrerOption.val() !== 'other') {
      that.referrerDetails.hide();
    } else {
      that.referrerDetails.show();
    }
  }
};

var addProofer = new AddProofer();

$(addProofer.initForm);
