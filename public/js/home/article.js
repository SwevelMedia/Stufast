$(document).ready(function() {



// var swiper = new Swiper(".newSwiper", {

//     allowTouchMove: false,

//     navigation: {

//         nextEl: ".page-next",

//         prevEl: ".page-back",

//     },

//     pagination: {

//         el: ".pagination-number",

//         clickable: true,

//         renderBullet: function (index, className) {

//             return '<span class="' + className + '">' + (index + 1) + "</span>";

//         },

//     }

// });



    var url = window.location.href;

    url = url.split("/");

    let id = url[4];



    handleArtikelById(id);

    async function handleArtikelById(id) {

        try {



            const artikelDetail = await $.ajax({

                url: '/api/artikel/detail/'+ id,

                method: 'GET',

                dataType: 'json'

            })

            

            $tag = artikelDetail.name_tag;

            $tagId = artikelDetail.tag_article_id;

            $title = artikelDetail.title;

            $sub_title = artikelDetail.sub_title;

            $content = artikelDetail.content;

            $content_image = artikelDetail.content_image;

            $created_at = artikelDetail.created_at;



            $('.article-title p').html($tag);

            $('.article-title H3').html($title);

            $('.swiper-slide H5').html($sub_title);

            $('.swiper-slide .image').html(

                `<img class="ms-3" src="`+ $content_image +`" alt="">`

            );

            $('.swiper-slide p').html($content);



            $('.writer-text-group p').html($created_at);

            

            $('.artikel').html(artikelDetail.map(artikel => {

                return `

                    <div class="col col-md-4">

                        <a href="/article/${artikel.article_id}" class="artikel-item" data-atikel-id=${artikel.article_id}>

                            <div class="image">

                                <img src="${artikel.content_image}" alt="">

                                <div class="gradient"></div>

                                <div class="content">

                                    <h2>${artikel.title}</h2>

                                    <p>

                                        ${artikel.content.slice(0, 100)}...

                                    </p>

                                </div>

                            </div>

                        </a>

                    </div>

                `

            }))

    

        } catch (error) {

            // console.log(error)

        }

    };



    handleSuggestionArtikel();

    async function handleSuggestionArtikel() {



        try{

            const artikelDetail = await $.ajax({

                url: '/api/artikel/detail/'+ id,

                method: 'GET',

                dataType: 'json'

            })



            let tagId = artikelDetail.tag_article_id;



            const responseSuggestion = await $.ajax({

                url: '/api/artikel/suggestion/'+ tagId,

                method: 'GET',

                dataType: 'json'

            })

            

            $('.suggestion-card-list').html(responseSuggestion.map(suggestion => {

                return `

                    <a href="/article/${suggestion.article_id}">

                    <div class="suggesstion-card pb-4">

                        <img class="thumbnail mb-3" src="${suggestion.content_image}" alt="">

                        <div class="padding-wrapper px-3">

                            <p class="category">${suggestion.name_tag}</p>

                            <h4 class="title mt-1">${suggestion.title}</h4>

                            <p class="desc mt-3">

                                ${suggestion.content.slice(0, 100)}...

                            </p>

                            <div class="writer-info-card d-flex mt-3 align-items-center">

                                <img src="/image/article/profile-pic.png" alt="">

                                <div class="writer-text-group">

                                    <h6>Admin</h6>

                                    <p>30 September 1966</p>

                                </div>

                            </div>

                        </div>

                    </div>

                    </a>

                `

            }))





        }catch{



        }

    }

})