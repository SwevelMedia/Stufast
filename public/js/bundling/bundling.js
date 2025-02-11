$(document).ready(function() {

    handleBundling()

})



async function handleBundling() {

    let id = $('#bundling-id').val()



    try {

        let res = null;

        if (Cookies.get('access_token')) {

            res = await $.ajax({

                url: `/api/bundling/detail/${id}`,
    
                method: "GET",
    
                dataType: "json",
    
                headers: {
    
                    Authorization: `Bearer ${Cookies.get('access_token')}`
    
                }
    
            })

        } else {

            res = await $.ajax({

                url: `/api/bundling/detail/${id}`,
    
                method: "GET",
    
                dataType: "json"
    
            })

        }

        

        let bundling = res

        let courses = res.course_bundling



        if (Cookies.get('access_token')) {

            let userBundlingRes = await $.ajax({

                url: `/api/bundling/user-bundling`,

                method: "GET",

                dataType: "json",

                headers: {

                    Authorization: `Bearer ${Cookies.get('access_token')}`

                }

            })

            

            let userBundling = userBundlingRes.coursebundling.find(function(userBundling) {

                return userBundling.bundling_id == id

            })

            

            bundling.isBought = userBundling ? true : false

        }



        $('.detail-bundling-title').text(bundling.title)

        $('.label-category').text(bundling.author_company)

        $('.detail-bundling-description').html(bundling.description.replace(/\n/g, "<br />"))

        $('.course-list').html(courses.map(function(course, i) {

            return `

                <li class="list-group-item py-3">

                    <div class="d-flex align-items-center gap-5">

                        <div class="item-number text-center">

                            <p class="m-0">Course</p>

                            <p class="m-0" style="font-size: 20px; font-weight: bold">${++i}</p>

                        </div>

                        <div class="flex-fill">

                            <a href="/course/${course.course_id}">

                                <h6>${course.title}</h6>

                            </a>

                            <p class="m-0">${course.total_video} Video</p>

                        </div>

                    </div>

                </li>

            `

        }))

        $('.ringkasan-list').html(courses.map(function(course, i) {

            return `

                <li>

                    <div class="d-flex gap-2">

                        <div class="flex-fill">${course.title}</div>

                        <!-- <div class="text-end text-nowrap">${getRupiah(course.new_price)}</div> -->

                    </div>

                </li>

            `

        }))

        

        $('.order-total').text(getRupiah(bundling.new_price.toString()))



        if (bundling.isBought) {

            $('.bought').hide()

            $('.judul-ringkasan').text('Ringkasan Bundling')

        } else {

            $('#checkout-btn').attr('href', `/checkout?type=bundling&id=${id}`)

        }



    } catch (error) {

        // console.log(error)

    }

}