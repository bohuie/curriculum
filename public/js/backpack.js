// prevent use of enter key on all admin panel pages except when using a textarea input
// this will prevent form submission on enter
$(document).on("keydown", ":input:not(textarea)", function(event) {
    return event.key != "Enter";
});