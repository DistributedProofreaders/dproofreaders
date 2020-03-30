/*global $ */

$(function () {
    $("#page-select").change(function() {
        this.form.submit();
    });
});
