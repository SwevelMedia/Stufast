<div class="side-bar">

</div>

<script>
    $('ready', function() {
        const role = JSON.parse(atob(Cookies.get("access_token").split('.')[1], 'base64')).role;

        const pages = [

            {

                page: "<span class='d-none d-sm-block d-md-none d-lg-block'>profil</span>",

                url: '/profile',

                imageUrl: 'image/profile/profile-icon.svg',

            },

            // {

            //     page: "<span class='d-none d-sm-none d-md-block'>pelatihan</span>",

            //     url: '/my-training',

            //     imageUrl: 'image/profile/training.svg',

            // },

            {

                page: "<span class='d-none d-sm-block d-md-none d-lg-block'>kata sandi</span>",

                url: '/password',

                imageUrl: 'image/profile/password-icon.svg',

            },



            // {

            //     page: "referral code",

            //     url: "/referral-code",

            //     imageUrl: "image/profile/referral-icon.svg",

            // },

        ];



        if (role == "admin") {

            pages.unshift({

                page: "<span class='d-none d-sm-block d-md-none d-lg-block'>dashboard</span>",

                url: "/admin",

                imageUrl: "image/profile/dashboard.svg",

            })

        } else if (role == 'company') {

            pages.splice(1, 0, ...[

                {

                    page: "<span class='d-none d-sm-block d-md-none d-lg-block'>Tawaran</span>",

                    url: "/hire-history",

                    imageUrl: "image/profile/hire.svg",

                },

            ])

        } else {

            pages.splice(1, 0, ...[

                {

                    page: "<span class='d-none d-sm-block d-md-none d-lg-block'>CV</span>",

                    url: '/cv',

                    imageUrl: 'image/profile/cv.svg',

                },

                {

                    page: "<span class='d-none d-sm-block d-md-none d-lg-block'>kursus</span>",

                    url: '/course',

                    imageUrl: 'image/profile/course-icon.svg',

                },

                {

                    page: "<span class='d-none d-sm-block d-md-none d-lg-block'>Tawaran</span>",

                    url: "/hire-history",

                    imageUrl: "image/profile/hire.svg",

                },

                {

                    page: "<span class='d-none d-sm-block d-md-none d-lg-block'>pesanan</span>",

                    url: "/order",

                    imageUrl: "image/profile/referral-icon.svg",

                },

            ])

        }



        var resources = pages.map((page) => {

            return (`

                <a class=" profile-sidebar btn btn-grey-200 text-capitalize d-flex px-3 ${window.location.href.includes(page.url) ? "active" : ""}" style="" href="${page.url}">

                    <img src="${page.imageUrl}" alt="icon" class="pe-2 profile-icon"/>

                    ${page.page}

                </a>
                
            `)

        })



        $("div.side-bar").html(resources);
    })
</script>