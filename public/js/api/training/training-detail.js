

$(document).ready(function () {

    let url = window.location.href

    let training_id = url.substring(url.lastIndexOf('/') + 1)

    sessionStorage.setItem('training_id', training_id)

    // handle training

    const trainingDetailData = getTrainingDetailData(training_id)

    trainingDetailData.then((training) => {

        training = training[0]

        // console.log(training)

        let category = training.category ? training.category[0]['name'] : "Basic";

        console.log(training)

        $(".title").html(training.title)

        $(".category").html(category)

        $(".banner").attr("src", training.thumbnail)

        $(".description").html(training.description)

        $(".price").html(getRupiah(training.new_price))

        $(".checkout").click(() => {

            window.location.href = `/checkout?type=training&id=${training_id}`

        })

        if(training.isBought) $(".order-card").addClass("d-none")

        $("#loader").addClass('d-none')

        $("#content").removeClass("d-none")

        $("#content").addClass("d-flex")

    })

})



const getTrainingDetailData = async (id) => {

    const option = {

        type: "GET",

        url: document.location.origin + `/api/course/filter/training/detail/${id}`,

        dataType: "json",

    }

    let data

    await $.ajax(option).done((training) => {

        data = training

    })

    if (Cookies.get("access_token")) {

        const userTrainings = await $.ajax({

            url: "/api/user-course",

            method: "GET",

            dataType: "json",

            headers: {
                Authorization: `Bearer ${Cookies.get("access_token")}`,
            },

        });

        data = data.map(function (training) {

            return {

                ...training,

                isBought: userTrainings
                    .map(function (userTraining) {
                        return userTraining.course_id;
                    })
                    .includes(training.course_id),
            };

        });

    } else {

        data = data.map(function (training) {

            return {

                ...training,

                isBought: false,

            };

        });

    }

    return data

}



