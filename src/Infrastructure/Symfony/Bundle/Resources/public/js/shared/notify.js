export let Notify = function () {
    function send(message, type, delay)
    {
        const colorProgressBar = type === "error" ? "danger" : "success";
        let icon= "info";
        switch (type) {
            case "success":
                icon = "check";
                break;
            case "warning":
                icon = "exclamation-triangle";
                break;
            case "danger":
                icon = "times";
                break;
            case "error":
                icon = "skull-crossbones";
                break;
        }

        jQuery.notify(
            {
                icon: `fa fa-${icon} me-3`,
                message: message,
                url: "",
            },
            {
                element: "body",
                type: type !== "error" ? type : "danger",
                placement: {
                    from: "top",
                    align: "right",
                },
                allow_dismiss: true,
                newest_on_top: true,
                showProgressbar: false,
                offset: 20,
                spacing: 10,
                z_index: 1033,
                delay: delay || 5000,
                timer: 1000,
                animate: {
                    enter: "animated fadeIn",
                    exit: "animated fadeOutDown",
                },
                template: `<div data-notify="container" class="col-11 col-sm-4 alert alert-{0} alert-dismissible" role="alert">
                  <p class="mb-0">
                    <span data-notify="icon"></span>
                    <span data-notify="title">{1}</span>
                    <span data-notify="message">{2}</span>
                  </p>
                  <div class="progress push" data-notify="progressbar" style="height: 8px;" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar progress-bar-{0} bg-${colorProgressBar}" style="width: 0%;"></div>
                  </div>
                  <a href="{3}" target="{4}" data-notify="url"></a>
                  <a class="p-2 m-1 text-dark" href="javascript:void(0)" aria-label="Close" data-notify="dismiss">
                    <i class="fa fa-times"></i>
                  </a>
                </div>`,
            }
        );
    }
    return {
        info: function(message) {
            send(message, 'info')
        },
        success: function(message) {
            send(message, 'success')
        },
        warning: function(message) {
            send(message, 'warning', 8000)
        },
        danger: function(message) {
            send(message, 'danger', 12000)
        },
        error: function(message) {
            send(message, 'error', 24000)
        },
    };
}();