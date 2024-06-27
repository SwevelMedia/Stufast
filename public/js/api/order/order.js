async function generateListOrder(filter) {
    let filteredList = await $.ajax({
        type: "GET",

        url: "/api/order/get-order-by-member/" + filter,

        contentType: "application/json",

        headers: {
            Authorization: "Bearer " + Cookies.get("access_token"),

            "Content-Type": "application/json",
        },
    });

    let res_order_course;

    if (filteredList.length == 0) {
        res_order_course =
            '<div class="d-flex justify-content-center"><img src="/image/cart/not-found.png" class="img-fluid" style="max-height: 200px; margin-top: 50px"></div><div class="text-center mt-3">Transaksi yang kamu cari tidak ditemukan</div>';
    } else {
        res_order_course = filteredList.map((data) => {
            let statusPembayaran = data.transaction_status;

            let button = "";

            if (statusPembayaran === "paid") {
                statusPembayaran = "Pembayaran Berhasil";

                button = `<div class=" mb-3 mt-2 d-flex justify-content-center justify-content-md-end">
    
                                        <a target="_blank" href="/invoice?order_id=${data.order_id}">
    
                                            <button class="btn btn-outline-success btn-sm" style="width: 150px; height: 30px;">Detail Invoice <br> </button>
    
    
                                        </a>
    
                                    </div>
    
                                    <div class="d-flex justify-content-center justify-content-md-end">
    
                                        <a href="/course">
    
                                            <button class="btn btn-outline-success btn-sm" style="width: 150px; height: 30px;">Lihat Course <br> </button>
    
                                        </a>
    
                                    </div>`;
            } else if (statusPembayaran === "pending") {
                statusPembayaran =
                    '<div class="icon-jam" style="color: orange;"><i class="bi bi-clock-history">&nbsp</i> Menunggu Pembayaran</div>';

                button = `<div class="mb-3 mt-2 d-flex justify-content-center justify-content-md-end ">
    
                                        <button data-token="${data.snap_token}" class="checkout btn btn-outline-success btn-sm" style="width: 150px; height: 30px;">Pembayaran </button>
    
                                    </div>
    
                                    <div class="mb-3 mt-2 d-flex justify-content-center justify-content-md-end ">
    
                                        <button data-order-id="${data.order_id}" class="cancel btn btn-outline-success btn-sm" style="width: 150px; height: 30px;">Batalkan</button>
    
                                    </div>
    
                                    <div class="d-flex justify-content-center justify-content-md-end">
    
                                        <a target="_blank" href="/invoice?order_id=${data.order_id}">
    
                                            <button class="btn btn-outline-success btn-sm" style="width: 150px; height: 30px;">Detail Invoice <br> </button>
    
                                        </a>
    
                                    </div>`;
            } else if (statusPembayaran === "cancel") {
                statusPembayaran =
                    '<div style="color: red;">Pesanan Dibatalkan</div>';

                button = `<div class="d-flex justify-content-center justify-content-md-end">
    
                                        <a href="/courses">
    
                                            <button class="btn btn-outline-success btn-sm" style="width: 150px; height: 30px;">Beli Lagi <br> </button>
    
                                        </a>
    
                                    </div>`;
            }

            let total =
                data.total_item > 1
                    ? '<span class="ml-1 mt-2 mb-1" style="font-size: 14px; font-weight: normal;"> + ' +
                      (data.total_item - 1) +
                      " Lainnya</span>"
                    : "";

            const itemsHtml = [];

            data.item.forEach((item, i) => {
                let title = data.total_item > 0 ? item.title : "";

                let thumbnail =
                    data.total_item > 0
                        ? item.thumbnail
                        : "https://dev.stufast.id/upload/course/thumbnail/1.jpg";

                let type = data.total_item > 0 ? item.type : "";

                if (data.item.length == 1) {
                    itemsHtml.push(`
        
                            <div class="row mb-3">
                
                                <div class="col">
                
                                    <div class="card">
                
                                        <div class="row">
                
                                            <div class="col-12 col-md-8">
                
                                                <div class="row">
                
                                                        <div class="col-12 col-md-5 ">
                
                                                            <div class="foto-course thumbnail me-2 mt-2">
                
                                                                <img src="${thumbnail}" alt="" width="100%">
                
                                                            </div>
                
                                                        </div>
                
                                                    <div class="col-12 col-md-7">
                
                                                        <div class="d-flex">
                
                                                            <div class="course-title mt-2 mb-1" style="line-height: 14px; d-flex justify-content-end">
                
                                                                <p style="font-size: 17px; line-height: 20px; font-weight: bold;">${title}</p>
                
                                                                <button type="button" class="${type}-badge mb-3" style="font-size: 14px">${type}</button>
                
                                                            </div>
                
                                                        </div>
                
                                                    </div>
                                                
                                                </div>
                                    
                
                                            </div>
                
                                            <div class="col-12 col-md-4">
    
                                                <div class="">
    
                                                    ${button}
                                        
                                                </div>
    
                                            </div>
                
                                        </div>

                                        <hr>
                
                                        <div class="card-footer">
                                                
                                            <div class="row">
                
                                                    <div class="col-12 col-md-8 mb-2">
                
                                                        ${statusPembayaran}
                
                                                    </div>
                
                                                <div class="col-12 col-md-4 d-flex justify-content-start justify-content-md-end">
                
                                                        ${getRupiah(
                                                            data.gross_amount
                                                        )}
                
                                                </div>
                                                
                                            </div>
                
                                        </div>
                
                                    </div>
                
                                </div>
                
                            </div>
                
                
                        `);
                } else {
                    if (i == 0) {
                        itemsHtml.push(`
        
                            <div class="row mb-3">
                
                                <div class="col">
                
                                    <div class="card">
                
                                        <div class="row">
                
                                            <div class="col-12 col-md-8">
                
                                                <div class="row">
                
                                                        <div class="col-12 col-md-5 ">
                
                                                            <div class="foto-course thumbnail me-2 mt-2">
                
                                                                <img src="${thumbnail}" alt="" width="100%">
                
                                                            </div>
                
                                                        </div>
                
                                                    <div class="col-12 col-md-7">
                
                                                        <div class="d-flex">
                
                                                            <div class="course-title mt-2 mb-1" style="line-height: 14px; d-flex justify-content-end">
                
                                                                <p style="font-size: 17px; line-height: 20px; font-weight: bold;">${title} ${total}</p>
                
                                                                <button type="button" class="${type}-badge mb-3" style="font-size: 14px">${type}</button><br>
    
                                                                <a href="#" data-toggle="collapse" data-target="#collapse-${data.order_id}"><img class="me-1" src="/image/profile/arrow-down.svg" style="width: 20px;"><span style="color: #525252; font-size: 14px; ">Lihat semua</span></a>
                
                                                            </div>
                
                                                        </div>
                
                                                    </div>
                                                
                                                </div>
                                    
                
                                            </div>
                
                                            <div class="col-12 col-md-4">
    
                                                <div class="">
    
                                                    ${button}
                                        
                                                </div>
    
                                            </div>
                
                                        </div>

                                        <div class="collapse" id="collapse-${data.order_id}">
                
                
                        `);
                    } else if (i == data.item.length - 1) {
                        itemsHtml.push(`
                
                                        <div class="row">
                
                                            <div class="col-12 col-md-8">
                
                                                <div class="row">
                
                                                        <div class="col-12 col-md-5 ">
                
                                                            <div class="foto-course thumbnail me-2 mt-2">
                
                                                                <img src="${thumbnail}" alt="" width="100%">
                
                                                            </div>
                
                                                        </div>
                
                                                    <div class="col-12 col-md-7">
                
                                                        <div class="d-flex">
                
                                                            <div class="course-title mt-2 mb-1" style="line-height: 14px; d-flex justify-content-end">
                
                                                                <p style="font-size: 17px; line-height: 20px; font-weight: bold;">${title}</p>
                
                                                                <button type="button" class="${type}-badge mb-3" style="font-size: 14px">${type}</button>
                
                                                            </div>
                
                                                        </div>
                
                                                    </div>
                                                
                                                </div>
                                    
                
                                            </div>
                
                                        </div>

                                    </div>
    
                                        
                
                                    <hr>
                
                                        <div class="card-footer">
                                                
                                            <div class="row">
                
                                                    <div class="col-12 col-md-8 mb-2">
                
                                                        ${statusPembayaran}
                
                                                    </div>
                
                                                <div class="col-12 col-md-4 d-flex justify-content-start justify-content-md-end">
                
                                                        ${getRupiah(
                                                            data.gross_amount
                                                        )}
                
                                                </div>
                                                
                                            </div>
                
                                        </div>
                
                                    </div>
                
                                </div>
                
                            </div>
                
                
                        `);
                    } else {
                        itemsHtml.push(`<div class="row">
                
                        <div class="col-12 col-md-8">
    
                            <div class="row">
    
                                    <div class="col-12 col-md-5 ">
    
                                        <div class="foto-course thumbnail me-2 mt-2">
    
                                            <img src="${thumbnail}" alt="" width="100%">
    
                                        </div>
    
                                    </div>
    
                                <div class="col-12 col-md-7">
    
                                    <div class="d-flex">
    
                                        <div class="course-title mt-2 mb-1" style="line-height: 14px; d-flex justify-content-end">
    
                                            <p style="font-size: 17px; line-height: 20px; font-weight: bold;">${title}</p>
    
                                            <button type="button" class="${type}-badge mb-3" style="font-size: 14px">${type}</button>
    
                                        </div>
    
                                    </div>
    
                                </div>
                            
                            </div>
                
    
                        </div>
    
                    </div>`);
                    }
                }
            });
            return itemsHtml.join("");
        });
    }

    $("#loader").hide();

    $(".loop-order").html(res_order_course);
}

$(document).ready(function () {
    generateListOrder("pending");

    $("body").on("click", ".checkout", function () {
        let token = $(this).data("token");

        window.snap.pay(token, {
            onSuccess: function (result) {
                window.location.href = "/course";
            },

            onPending: function (result) {},

            onError: function (result) {
                window.location.href = "/cart";
            },

            onClose: function () {},
        });
    });

    $("body").on("click", ".cancel", function () {
        let id = $(this).data("order-id");

        Swal.fire({
            title: "Konfirmasi",

            text: "Apakah anda yakin ingin membatalkan pesanan?",

            icon: "warning",

            showCancelButton: true,

            confirmButtonText: "Yakin",

            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",

                    url: "/api/order/cancel",

                    headers: {
                        Authorization: "Bearer " + Cookies.get("access_token"),
                    },

                    data: {
                        order_id: id,
                    },

                    success: function () {
                        $(".loop-order").html("");

                        $("#loader").show();

                        generateListOrder("cancel");

                        $(`.btn-order`).removeClass("active");

                        $("#cancel-btn").addClass("active");

                        Swal.fire({
                            title: "Sukses",

                            text: "Pesanan anda berhasil dibatalkan",

                            icon: "success",
                        });
                    },

                    error: function (xhr) {
                        Swal.fire({
                            title: "Gagal",

                            text: "Terjadi kesalahan",

                            icon: "error",
                        });
                    },
                });
            }
        });
    });

    $(`.btn-order`).on("click", function (e) {
        e.preventDefault();

        $(`.btn-order`).removeClass("active");

        $(this).addClass("active");

        $(".loop-order").html("");

        $("#loader").show();

        generateListOrder($(this).data("filter"));
    });
});
