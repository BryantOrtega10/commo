$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
$(document).ready(function () {
    function getNotifications() {
        $.ajax({
            type: "GET",
            url: "/leads/activity/notifications",
            success: function (notifications) {
                notifications.forEach(notification => {
                    if($(`.notification-${notification.data.activity_id}`).length == 0){
                        $(document).Toasts("create", {
                            title: notification.data.message,
                            body: `${notification.data.message} <br> <a href="${notification.data.url}" class="btn btn-light">Go to the task</a>`,
                            icon: 'fas fa-exclamation-triangle',
                            class: `bg-danger notification-${notification.data.activity_id}`
                        });
                    }
                });
                
            },
            error: function (data) {
                console.log("error");
                console.log(data);
            },
        });
    }
    getNotifications();
    setInterval(getNotifications, 10000);
});
