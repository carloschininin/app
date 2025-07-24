export let Notify = function () {
    function send(message, type)
    {
        Codebase.helpers('jq-notify', {
            align: 'right',             // 'right', 'left', 'center'
            from: 'top',                // 'top', 'bottom'
            type: type,               // 'info', 'success', 'warning', 'danger'
            icon: 'fa fa-info me-3',    // Icon class
            message: message
        });
    }
    return {
        info: function(message) {
            send(message, 'info')
        },
        success: function(message) {
            send(message, 'success')
        },
        warning: function(message) {
            send(message, 'warning')
        },
        danger: function(message) {
            send(message, 'danger')
        },
        error: function(message) {
            send(message, 'danger')
        },
    };
}();