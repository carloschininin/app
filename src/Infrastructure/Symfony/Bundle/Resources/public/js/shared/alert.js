import Swal from "sweetalert2/dist/sweetalert2.min"
import "sweetalert2/dist/sweetalert2.min.css"

const Alert = function () {

    let e = Swal.mixin({
        buttonsStyling: false,
        confirmButtonText: "Aceptar",
        customClass: {
            confirmButton: "btn btn-success m-1",
            cancelButton: "btn btn-danger m-1",
            input: "form-control"
        }
    });

    //== Private functions
    let basic = function (message, state, title) {
        e.fire(title || '', message, state)
    }

    return {
        info: function(message, title=undefined) {
            basic(message, 'info',title);
        },
        success: function(message, title=false) {
            basic(message, 'success',title);
        },
        warning: function(message, title=false) {
            basic(message, 'warning',title);
        },
        danger: function(message, title=false) {
            basic(message, 'error',title);
        },
    };
}();

window.Alert = Alert;
export default Alert;