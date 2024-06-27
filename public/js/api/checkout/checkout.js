if(!Cookies.get("access_token")){

  new swal({

    title: "Gagal",

    icon: "warning",

    text: "Silahkan masuk terlebih dahulu untuk melanjutkan",

    showConfirmButton: false,

  })

  setTimeout(() => {

    window.location.href = "/login";

  }, 2000);

} else {

  const role = JSON.parse(atob(Cookies.get("access_token").split('.')[1], 'base64')).role;

  if(role != 'member') {

    new swal({

      title: "Gagal",
  
      icon: "warning",
  
      text: "Anda tidak dapat mengakses halaman ini",
  
      showConfirmButton: false,
  
    })
  
    setTimeout(() => {
  
      history.back();
  
    }, 2000);

  }

}



$(document).ready(() => {

  const getCart = async () => {

    try {

      let option = {

        type: "GET",

        url: "/api/cart",

        dataType: "json",

        headers: {

          "Authorization": `Bearer ${Cookies.get("access_token")}`,

        },

        success: function (cart) {

          cart.item.forEach((item) => {

            if(item.bundling){

              item['type'] = 'Bundling'

              item['detail'] = item.bundling

              item['thumbnail'] = item.bundling.thumbnail || 'image/cart/ux-banner.png'

            } else if (item.course) {

              item['type'] = 'Course'

              item['detail'] = item.course

              item['thumbnail'] = item.course.thumbnail || 'image/cart/ux-banner.png'

            } else if (item.training) {

              item['type'] = 'Training'

              item['detail'] = item.training

              item['thumbnail'] = item.training.thumbnail || 'image/cart/ux-banner.png'

            } 
            
            else if (item.webinar) {

              item['type'] = 'Webinar'

              item['detail'] = item.webinar

              item['detail']['category'] = item.webinar.tag

              item['thumbnail'] = item.webinar.thumbnail || 'image/cart/ux-banner.png'

            }

            delete item.bundling

            delete item.course

            delete item.training

            delete item.webinar

          })

          // rename cart.item to cart.items

          cart['items'] = cart.item

          delete cart.item

          data = cart

        }

        }

        

      let data

      await $.ajax(option)

      return data

    } catch (error) {

      // console.log(error);

    }

  }



  const getCourse = async (id) => {

    try {

      let option = {

        type: "GET",

        url: `/api/course/detail/${id}`,

        dataType: "json",

        headers: {

          "Authorization": `Bearer ${Cookies.get("access_token")}`,

        },

        success: function (course) {

          let item = {

            type: "Course",

            detail: {

              title: course.title,

              new_price: course.new_price,

              old_price: course.old_price,

              category: course.category,

              thumbnail: course.thumbnail || 'image/cart/ux-banner.png',

            },

            tax: course.tax,

            sub_total: course.new_price || course.old_price,

          }

          data = item

        }

      }



      let data

      await $.ajax(option)

      return data

    } catch (error) {

      // console.log(error);

    }

  }



  const getBundle = async (id) => {

    try {

      let option = {

        type: "GET",

        url: `/api/bundling/detail/${id}`,

        dataType: "json",

        headers: {

          "Authorization": `Bearer ${Cookies.get("access_token")}`,

        },

        success: (bundle) => {

          let item = {

            type: "Bundling",

            detail: {

              title: bundle.title,

              new_price: bundle.new_price,

              old_price: bundle.old_price,

              category: bundle.category_name,

              thumbnail: bundle.thumbnail || 'image/cart/ux-banner.png',

            },

            tax: bundle.tax ,

            sub_total: bundle.new_price || bundle.old_price,

          }

          data = item

        }

      }



      let data

      await $.ajax(option)

      return data

    } catch (error) {

      // console.log(error);

    }

  }



  const getTraining = async (id) => {

    try {

      let option = {

        type: "GET",

        url: `/api/course/filter/training/detail/${id}`,

        dataType: "json",

        headers: {

          "Authorization": `Bearer ${Cookies.get("access_token")}`,

        },

        success: (training) => {

          training = training[0]

          categori = training.category[0]

          let item = {

            type: "Training",

            detail: {

              title: training.title,

              new_price: training.new_price,

              old_price: training.old_price,

              category: categori ? categori.name : "Basic",

              thumbnail: training.thumbnail || 'image/cart/ux-banner.png',

            },

            tax: training.tax ,

            sub_total: training.new_price || training.old_price,

          }

          data = item

        }

      }

      let data

      await $.ajax(option)

      return data

    } catch (error) {

      // console.log(error);

    }

  }



  const getWebinar = async (id) => {

    try {

      let option = {

        type: "GET",

        url: `/api/webinar/detail/${id}`,

        dataType: "json",

        headers: {

          "Authorization": `Bearer ${Cookies.get("access_token")}`,

        },

        success: (webinar) => {

          let item = {

            type: "Webinar",

            detail: {

              title: webinar.title,

              new_price: webinar.new_price,

              old_price: webinar.old_price,

              category: webinar.tag_name,

              thumbnail: webinar.thumbnail || 'image/cart/ux-banner.png',

            },

            tax: webinar.tax ,

            sub_total: webinar.new_price || webinar.old_price,

          }

          data = item

        }

      }

      let data

      await $.ajax(option)

      return data

    } catch (error) {

      // console.log(error);

    }

  }



  const getListVoucher = async () => {

    try {

      let option = {

        type: "GET",

        url: "/api/voucher",

        dataType: "json",

        headers: {

          "Authorization": `Bearer ${Cookies.get("access_token")}`,

        },

        success: function (voucher) {

          data = voucher

        }

      }

      let data

      await $.ajax(option)

      return data

    } catch (error) {

      // console.log(error);

    }

  }



  const getVoucher = async (code) => {

    try {

      let option = {

        type: "GET",

        url: `/api/voucher/code-detail?code=${code}`,

        dataType: "json",

        headers: {

          "Authorization": `Bearer ${Cookies.get("access_token")}`,

        },

        success: function (voucher) {

          data = voucher

        }

      }

      let data = null;

      await $.ajax(option)

      return data

    } catch (error) {

      // console.log(error);

    }

  }



  const populateVoucher = async() => {



    let voucher = await getListVoucher()



    const checkout_voucherList_content = $("#cart-voucher-list")



    const redeem_input = $("#redeem-input")



    const redeem_button = $("#redeem-btn")



    checkout_voucherList_content.empty()



    voucher.forEach((item) => {



      let voucher_item = `



      <div class="col-6 pb-3 pe-2 ps-0">



          <button class="cart-referral-modal-coucher-btn" data-code="${item.code}">



              <div class="referral-item">



                  <div class="icon">



                      <img src="/image/cart/voucher-icon.png" alt="">



                  </div>



                  <div class="disc">



                      ${item.discount_price}%



                  </div>



              </div>



          </button>



      </div>`



      checkout_voucherList_content.append(voucher_item)



    })







    $("#cart-voucher-list").children().each((index, element) => {



      $(element).on('click', () => {



        let thisPage = new URL(window.location.href);



        let code = $(element).children().data('code')



        thisPage.searchParams.set('code', code)



        window.location.href = thisPage



      })



    })

    

  }

  

  $("#redeem-btn").on('click', () => {

    let thisPage = new URL(window.location.href);

    let code = $('#redeem-input').val()

    thisPage.searchParams.set('code', code)

    window.location.href = thisPage

  })



  const checkout = async (data) => {



    try {



      const queryString = window.location.search;



      const urlParams = new URLSearchParams(queryString);



      const id = urlParams.get('id') || null;



      const type = urlParams.get('type') || null;



      const code = urlParams.get('code') || null;



      let url = "/api/order/generatesnap?"



      if(type == "course"){ 



        url += "cr=" + id



      } else if (type == "bundling"){



        url += "bd=" + id



      } else if (type == "training"){



        url += "tr=" + id



      } else if (type == "webinar"){



        url += "wb=" + id



      }



      if(code){



        url += type ? "&c=" + code : "c=" + code



      }



      let option = {



        type: "GET",



        url: url,



        dataType: "json",



        headers: {



          "Authorization": `Bearer ${Cookies.get("access_token")}`,



        },



        success: function (checkout_detail) {



          let token  = checkout_detail.token



          // console.log(checkout_detail.data_order)

          // console.log(checkout_detail.data_order_course)

          // console.log('token')

          // console.log(checkout_detail.token)

          // console.log('||')

          // console.log(checkout_detail.type)

          window.snap.pay(token, {

            onSuccess: function(result){

              // alert("payment success!"); console.log(result);

              // console.log(checkout_detail);

              simpanPembayaran(result, checkout_detail)

              window.location.href = "/course";

            },



            onPending: function(result){

              // alert("wating your payment!"); console.log(result);

              // console.log(checkout_detail);

              simpanPembayaran(result ,checkout_detail )

              window.location.href = "/order";

            },

// 

            onError: function(result){

              // alert("payment failed!"); console.log(result);

              // console.log(checkout_detail);

              simpanPembayaran(result,checkout_detail)

              window.location.href = "/cart";

            },



            onClose: function(){

              window.location.href = "/order";

              // console.log(checkout_detail);

            }



          }); 



        }



      }



      await $.ajax(option)



    } catch (error) {



      // console.log(error);



    }



  }



//   function simpanPembayaran(result, checkout_detail){

//       console.log(result);

//       console.log(checkout_detail);

//       alert('tes');



//     }

    

    const simpanPembayaran = async (result, checkout_detail) => {

        // alert('tes simpan pembayaran');

        // result.map((element) => {

        //     const elementnya = element;

        // });



        try {

            let option = {

                type: 'POST',

                url: `/api/order/create`,

                dataType: 'json',

                data: {

                    'result' : result,

                    'checkout_detail' : checkout_detail,

                },

                headers: {

                    Authorization: `Bearer ${Cookies.get('access_token')}`,

                },

                success: function (res) {

                   data = console.log(res);

                },

            };



            let data;

            await $.ajax(option);

            return data;

        } catch (error) {

            // console.log(error);

        }

    };



  const renderView = async (data) => {



    const checkout_items_content = $("#checkout-items-content");



    const checkout_itemsCount_content = $("#checkout-itemsCount-content");



    const checkout_subtotal = $("#checkout-subtotal");



    const checkout_tax = $('#checkout-total-tax');



    const checkout_code_discount = $("#checkout-code-discount");



    const checkout_total = $("#checkout-total");



    const checkout_email = $("#checkout-email");



    const checkout_btn = $("#checkout-btn");



    const voucher_btn = $(".btn-modal-referral")



    let { items, user, sub_total, total, code, tax } = data;



    let { email } = user;



    let itemsCount = items.length || 0;



    



    



    items.forEach((item) => {



      let { type, detail, sub_total, tax} = item



      let { title, thumbnail, category} = detail



      let itemContent = `



      <div class="card mb-4">



        <div class="order-item card-body">



          <div class="order-list-detail d-flex">



              <img src="${thumbnail}" alt="" width="150px">



              <div class="order-desc">



                  <p class="mb-3 ${type.toLowerCase()}-badge">



                      ${type}



                  </p>



                  <h5 class="title">${title}</h5>



                  <h5 class="desc">${category}</h5>

                  <div class="order-list-subtotal d-flex">



                  <div class="d-flex align-items-end">
  
  
  
                    <h5 style="white-space: nowrap;">${getRupiah(sub_total)}</h5>
  
  
  
                  </div>
  
  
  
                </div>

              </div>



             



            </div>



          </div>



        </div>

      </div>



      `



      checkout_items_content.append(itemContent)



    })



    if (code) {

      sub_total = `${sub_total}`

      tax = `${tax}`

      total = `${total - (total * (code.discount_price / 100))}`

      checkout_code_discount.text(`${code.discount_price}%`)

    } else {

      sub_total = `${sub_total}`

      tax = `${tax}`

      total = `${total}`

    }



    checkout_itemsCount_content.text(`Total (${itemsCount} item)`)

    checkout_subtotal.text(getRupiah(sub_total))

    checkout_tax.text(getRupiah(tax))

    checkout_total.text(getRupiah(total))

    checkout_email.text(email)



    // voucher button on click, call populate voucehr func



    voucher_btn.on("click", () => {



      populateVoucher()



    })



    // checkout button on click, call checkout func

      

    checkout_btn.on("click", () => {

      checkout(data)

    })

  }



  const renderCart = async () => {



    const queryString = window.location.search;



    const urlParams = new URLSearchParams(queryString);



    const id = urlParams.get('id') || null;



    const type = urlParams.get('type') || null;



    const code = urlParams.get('code') || null;



    let data = await getCart()



    







    if (id && type) {



      data.items = []



      if (type == 'course') {



        data.items.push(await getCourse(id))



      } else if (type == 'bundling'){



        data.items.push(await getBundle(id))



      } else if (type == 'training'){



        data.items.push(await getTraining(id))



      } else if (type == 'webinar'){



        data.items.push(await getWebinar(id))



      } 



      data.tax = data.items[0].tax



      data.sub_total = data.items[0].sub_total



      data.total =  parseInt(data.items[0].sub_total) + parseInt(data.items[0].tax)



      // console.log(data)



    } else {



      data = await getCart()



    }



    data['code'] = await getVoucher(code)

    // console.log(data['code'])







    // console.log(data)



    renderView(data)







    return data



  }



  renderCart()



})