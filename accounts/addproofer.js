/*global $*/

function AddProofer() {
  "use strict";

  var that = this;
  this.initForm = function() {
    "use strict";

    that.referrerOption = $('select[name=referrer]');
    that.referrerExplanation = $('#referrer_details');
    that.setOtherExplanation();
    that.referrerOption.change(that.setOtherExplanation);
  };

  this.setOtherExplanation = function() {
    "use string";

    if (that.referrerOption.val() !== 'other') {
      that.referrerExplanation.hide();
    } else {
      that.referrerExplanation.show();
    }
  }
};

var addProofer = new AddProofer();

$(addProofer.initForm);
