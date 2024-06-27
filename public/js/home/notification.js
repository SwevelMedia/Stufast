$(document).ready(function () {
    // call function setNotification every 15 seconds

    if (Cookies.get("access_token") != null) {

        const role = JSON.parse(atob(Cookies.get("access_token").split('.')[1], 'base64')).role;

        if (role == "company") {

            handleHireNotification();

        } else {

            handleCartNotification();

        }

        setNotification();

        setInterval(async () => {
            setNotification();
        }, 15000);
    }

    $(".notifications-baca").on("click", function (e) {
        e.preventDefault();
        e.stopPropagation();

        $(".notifications-list").html(`
        <div class="spinner-container text-center" style="height: 50vh; display: flex; align-items: center; justify-content: center;">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>`);

        $.ajax({
            url: `/api/notification/read/all`,
            method: "PUT",
            headers: {
                Authorization: "Bearer " + Cookies.get("access_token"),
            },
            dataType: "json",
        }).then((res) => {
            $("nav #dropdown-notification").html(
                `<img src="/image/home/notification-icon.png" alt="icon"></img>`
            );
            setNotification();
        });
    });
});

// handle cart notification

function handleCartNotification() {
    $.ajax({
        url: "/api/cart",

        method: "GET",

        headers: {
            Authorization: "Bearer " + Cookies.get("access_token"),
        },

        dataType: "json",
    })
        .then((res) => {
            let cartCount = res.item.length;

            if (cartCount > 0) {
                $("#cart-count").append(
                    `<div class="nav-btn-icon-amount">${cartCount}</div>`
                );
            }
        })
        .catch((err) => {
            // console.log(err)
        });
}


function handleHireNotification() {
    $.ajax({
        url: "/api/hire-batch",

        method: "GET",

        headers: {
            Authorization: "Bearer " + Cookies.get("access_token"),
        },

        dataType: "json",
    })
        .then((res) => {
            let hireCount = res.length;

            if (hireCount > 0) {
                $("#hire-count").append(
                    `<div class="nav-btn-icon-amount">${hireCount}</div>`
                );
            }
        })
        .catch((err) => {
            // console.log(err)
        });
}

// handle notification

function getNotification() {
    try {
        return $.ajax({
            url: "/api/notification",

            method: "GET",

            headers: {
                Authorization: "Bearer " + Cookies.get("access_token"),
            },

            dataType: "json",
        });
    } catch (error) {
        // console.log(error);
    }
}

async function setNotification() {
    const data = await getNotification();

    if (data.unread > 0) {
        $("nav #dropdown-notification").append(`

            <div class="nav-btn-icon-amount">${data.unread}</div>

        `);
    }

    let content = "";

    content += ``;

    if (data.notification.length > 0) {
        data.notification.forEach((notification) => {
            const { message, created_at } = notification;

            created_at_human = moment(created_at, "YYYY-MM-DD hh:mm:ss")
                .locale("id")
                .fromNow();

            content += `
    
                    <div class="notif ${
                        notification.read == 0 ? "unread" : ""
                    }">
    
                        <a href="" class="user-notif" data-id="${
                            notification.user_notification_id
                        }" data-link="${notification.link}">
    
                            <div class="icon">
    
                                <img src="${notification.thumbnail}" alt="icon">
    
                            </div>
    
                            <div class="item">

                                <p class="text-notif" style="font-size: 12px;">
    
                                    ${message}
    
                                </p>
    
                                <span>${created_at_human}</span>
    
                            </div>
    
                        </a>
    
                    </div>
    
                    `;
        });
    } else {
        content += `
            <div class="content">

                <img src="/image/notif/empty.png" height=150>

                <p class="mt-3">Tidak ada notifikasi yang masuk</p>

            </div>`;
    }

    $(".notifications-list").html(content);

    $(".user-notif").click(function (e) {
        e.preventDefault();
        e.stopPropagation();

        $(".notifications-list").html(`
        <div class="spinner-container text-center" style="height: 50vh; display: flex; align-items: center; justify-content: center;">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>`);

        $.ajax({
            url: `/api/notification/read/${$(this).data("id")}`,
            method: "PUT",
            headers: {
                Authorization: "Bearer " + Cookies.get("access_token"),
            },
            dataType: "json",
        }).then((res) => {
            window.location.href = $(this).data("link");
        });
    });
}
