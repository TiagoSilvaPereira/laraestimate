window.toastr = require('toastr');

toastr.options.closeButton = true;

window.toast = {

    success(message, title = null) {
        toastr.success(message, title);
    },

    warning(message, title = null) {
        toastr.warning(message, title);
    },

    error(message, title = null) {
        toastr.error(message, title);
    },

    info(message, title = null) {
        toastr.info(message, title);
    },

}