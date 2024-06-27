<?php



namespace Config;



use Doctrine\Common\Annotations\PhpParser;

use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Php;



// Create a new instance of our RouteCollection class.

$routes = Services::routes();



// Load the system's routing file first, so that the app and ENVIRONMENT

// can override as needed.

if (is_file(SYSTEMPATH . 'Config/Routes.php')) {

    require SYSTEMPATH . 'Config/Routes.php';
}



/*

 * --------------------------------------------------------------------

 * Router Setup

 * --------------------------------------------------------------------

 */

$routes->setDefaultNamespace('App\Controllers');

$routes->setDefaultController('Home');

$routes->setDefaultMethod('index');

$routes->setTranslateURIDashes(false);

$routes->set404Override();

// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps

// where controller filters or CSRF protection are bypassed.

// If you don't want to define all routes, please use the Auto Routing (Improved).

// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.

//$routes->setAutoRoute(false);



/*

 * --------------------------------------------------------------------

 * Route Definitions

 * --------------------------------------------------------------------

 */



// We get a performance increase by specifying the default

// route since we don't have to scan directories.



$routes->get('/', 'Home::index');


// $routes->get('/cv', 'AuthController::cv');

$routes->get('/cv/download', 'AuthController::cvDownload');

$routes->get('/referral-code', 'AuthController::referralCode');

$routes->get('/password', 'AuthController::privacy');

$routes->get('/order', 'AuthController::order');



$routes->group('', ['filter' => 'hasLogin'], function ($routes) {
    # account
    $routes->get('/profile', [\App\Controllers\Account\AccountController::class, 'index']);

    $routes->group('admin', function ($routes) {

        $routes->get('categories', [\App\Controllers\Admin\CategoryController::class, 'index']);
        $routes->get('tags', [\App\Controllers\Admin\TagController::class, 'index']);
        $routes->get('types', [\App\Controllers\Admin\TypeController::class, 'index']);


        $routes->get('courses', [\App\Controllers\Admin\CourseController::class, 'index']);
        $routes->get('courses/create', [\App\Controllers\Admin\CourseController::class, 'create']);
        $routes->get('courses/(:num)', [\App\Controllers\Admin\CourseController::class, 'show']);
        $routes->get('courses/video/(:num)', [\App\Controllers\Admin\CourseController::class, 'video']);
        $routes->get('courses/quiz/(:num)', [\App\Controllers\Admin\CourseController::class, 'quiz']);
        $routes->get('courses/member/(:num)', [\App\Controllers\Admin\CourseController::class, 'member']);
    });
});

$routes->group('member', ['filter' => 'accessMember'], function ($routes) {
    # penawaran
    $routes->get('hire-history', [\App\Controllers\Member\HireOfferController::class, 'history']);

    # cv
    $routes->get('cv', [\App\Controllers\Member\CvController::class, 'index']);
    $routes->get('cv/profile', [\App\Controllers\Member\CvController::class, 'profile']);
    $routes->get('cv/experience', [\App\Controllers\Member\CvController::class, 'experience']);
    $routes->get('cv/education', [\App\Controllers\Member\CvController::class, 'education']);
    $routes->get('cv/achievement', [\App\Controllers\Member\CvController::class, 'achievement']);
    $routes->get('cv/portofolio', [\App\Controllers\Member\CvController::class, 'portofolio']);
    $routes->get('cv/download', [\App\Controllers\Member\CvController::class, 'download']);

    # course
    $routes->get('courses', [\App\Controllers\Member\CourseController::class, 'index']);
    $routes->get('courses/(:num)', [\App\Controllers\Member\CourseController::class, 'show']);
    $routes->get('certificate/(:num)', [\App\Controllers\Member\CourseController::class, 'certificate']);
});

$routes->group('company', ['filter' => 'accessCompany'], function ($routes) {
    $routes->get('hire', [\App\Controllers\Company\HireController::class, 'index']);
    $routes->get('cart', [\App\Controllers\Company\HireController::class, 'cart']);
});





$routes->get('/forgot-password', 'AuthController::indexforgotPassword');

$routes->get('/forgot-password/submit', 'AuthController::forgotPassword');



$routes->get('/otp-email', 'AuthController::sendOtpEmail');

$routes->get('/send-otp', 'AuthController::indexSendOtp');

$routes->post('/send-otp', 'AuthController::sendOtp');



$routes->get('/new-password', 'AuthController::indexNewPassword');

$routes->post('/new-password', 'AuthController::newPassword');



// $routes->get('/register', 'AuthController::indexRegister');
$routes->get('register', [\App\Controllers\Auth\AuthController::class, 'register']);

$routes->get('/register-company', 'AuthController::indexRegisterCompany');

$routes->post('/register', 'AuthController::register');



// $routes->get('/login', 'AuthController::indexLogin');
$routes->get('login', [\App\Controllers\Auth\AuthController::class, 'login']);

$routes->post('/login', 'AuthController::login');

$routes->get('/login/loginWithGoogle', 'Api\AuthController::loginWithGoogle');

$routes->get('/login/loginGoogle', 'Api\AuthController::loginGoogle');

$routes->post('/login/loginOneTapGoogle', 'Api\AuthController::loginOneTapGoogle');

$routes->get('/logout', 'AuthController::logout');

$routes->get('/activateuser', 'AuthController::activateUser');



$routes->get('/send-otp', 'AuthController::indexSendOtp');

$routes->post('/send-otp', 'AuthController::sendOtp');



$routes->get('/faq', 'Client\FaqController::index');

$routes->get('/about-us', 'Home::aboutUs');

$routes->get('/terms-and-conditions', 'Home::termsAndConditions');

$routes->get('/pricing-plan', 'Home::pricingPlan');



$routes->get('/cart', 'Home::cart');

$routes->get('/checkout', 'Home::checkout');

$routes->get('/invoice', 'Home::invoice');

$routes->get('/webinar', 'Home::webinar');

$routes->get('/sign-up', 'Home::signUp');

$routes->get('/forgot-password', 'Home::forgotPassword');

$routes->get('/send-otp', 'Home::sendOTP');

$routes->get('/new-password', 'Home::newPassword');

$routes->get('/email', 'Home::email');

$routes->get('/certificates', 'Home::certificate');


# public course
$routes->get('/courses', 'Home::courses');
$routes->get('/course-detail', 'Home::courseDetail');
$routes->get('/course/:num', 'Home::courseDetailNew');
$routes->get('/courses/bundling/(:num)', 'Home::bundlingCart/$1');

$routes->get('courses-dev', 'Home::courses_dev');
$routes->get('courses-dev/(:num)', 'Home::courses_detail_dev');

$routes->get('/training', 'Home::training');

$routes->get('/training/:num', 'Home::trainingDetail/$1');



$routes->get('/article', 'Home::article');

$routes->get('/article/:num', 'Home::articleDetail/$1');



$routes->get('/course', 'AuthController::course');

$routes->get('/my-training', 'AuthController::training');



// $routes->get('talent', 'Home::talent_hub');

$routes->get('talent', [\App\Controllers\Home::class, 'talent']);
$routes->get('talent/detail/(:num)', [\App\Controllers\Home::class, 'detail_talent']);


// $routes->get('hire-history', 'Home::hireHistory');



// $routes->get('/pricing/bundling/(:num)', 'Home::bundlingPricing/$1');



//end of route single 



// route admin 

$routes->group('admin/', static function ($routes) {

    $routes->get('', 'AdminController::index');

    $routes->get('user', 'AdminController::user');

    $routes->get('user/(:segment)', 'AdminController::userDetail/$1');



    $routes->get('transaction', 'AdminController::transaction');

    $routes->get('transaction/(:segment)', 'AdminController::transactionDetail/$1');



    // $routes->get('course', 'AdminController::course');

    // $routes->get('course/(:segment)', 'AdminController::courseDetail/$1');



    $routes->get('video/(:segment)', 'AdminController::videoDetail/$1');

    $routes->get('quiz/(:segment)', 'AdminController::quizDetail/$1');

    $routes->get('contact', 'AdminController::contactList');



    $routes->get('bundling', 'AdminController::bundling/$1');

    $routes->get('bundling/(:segment)', 'AdminController::bundlingDetail/$1');

    $routes->get('faq', 'AdminController::faq');
});



// route api 

$routes->group('api/', static function ($routes) {

    $routes->post('register', 'Api\AuthController::register');

    $routes->post('register-company', 'Api\AuthController::registerCompany');

    $routes->post('login', 'Api\AuthController::login');

    $routes->post('login/google', 'Api\AuthController::loginGoogleMobile');

    $routes->get('activateuser', 'Api\AuthController::activateUser');

    $routes->post('changePassword', 'Api\AuthController::changePassword');

    $routes->post('device-token', 'Api\AuthController::updateDeviceToken');


    $routes->get('generate-link', 'Api\AuthController::generateLink');

    $routes->post('login/google', 'Api\AuthController::loginGoogle');


    $routes->post('forgot-password', 'Api\ForgotPasswordController::forgotPassword');

    $routes->post('send-otp', 'Api\ForgotPasswordController::sendOtp');

    $routes->post('new-password', 'Api\ForgotPasswordController::newPassword');


    $routes->group('talent-hub/', static function ($routes) {

        $routes->get('', 'Api\TalentHubController::index');

        $routes->get('(:num)', 'Api\TalentHubController::topTalent/$1');

        $routes->get('page/(:num)', 'Api\TalentHubController::page/$1');

        $routes->post('pagination', 'Api\TalentHubController::pagination');

        $routes->get('detail/(:num)', 'Api\TalentHubController::show/$1');

        $routes->get('populate', 'Api\TalentHubController::populate');
    });


    $routes->group('hire/', static function ($routes) {

        $routes->post('', 'Api\HireController::hire');

        $routes->post('confirm', 'Api\HireController::confirm');

        $routes->post('notif', 'Api\HireController::notif');

        $routes->post('confirm-notif', 'Api\HireController::confirmNotif');

        $routes->get('user', 'Api\HireController::userHistory');

        $routes->get('company', 'Api\HireController::companyHistory');
    });


    $routes->group('hire-batch/', static function ($routes) {

        $routes->get('', 'Api\HireBatchController::index');

        $routes->post('', 'Api\HireBatchController::create');

        $routes->delete('', 'Api\HireBatchController::delete');
    });


    $routes->group('banner/', static function ($routes) {

        $routes->get('', 'Api\BannerController::index');
    });


    $routes->get('user-course', 'Api\UserCourseController::index');

    // $routes->get('course-user', 'Api\UserCourseController::get');

    $routes->get('profile', 'Api\UserController::profile');

    $routes->get('profile/course', 'Api\UserController::userCourse');

    $routes->get('order', 'Api\UserController::order');


    $routes->group('user-course/', static function ($routes) {

        $routes->get('all', 'Api\UserCourseController::get');

        $routes->get('course', 'Api\UserCourseController::course');

        $routes->get('bundling', 'Api\UserCourseController::bundling');
    });


    $routes->group('faq/', static function ($routes) {

        $routes->get('', 'Api\FaqController::index');

        $routes->post('create', 'Api\FaqController::create');

        $routes->get('detail/(:segment)', 'Api\FaqController::show/$1');

        $routes->put('update/(:segment)', 'Api\FaqController::update/$1');

        $routes->delete('delete/(:segment)', 'Api\FaqController::delete/$1');
    });



    $routes->group('voucher/', static function ($routes) {

        $routes->get('', 'Api\VoucherController::index');

        $routes->get('detail/(:segment)', 'Api\VoucherController::show/$1');

        $routes->get('code-detail', 'Api\VoucherController::show_code');

        $routes->post('create', 'Api\VoucherController::create');

        $routes->put('update/(:segment)', 'Api\VoucherController::update/$1');

        $routes->delete('delete/(:segment)', 'Api\VoucherController::delete/$1');
    });



    $routes->group('review/', static function ($routes) {

        $routes->get('', 'Api\ReviewController::index');

        $routes->get('detail', 'Api\ReviewController::index_review');

        $routes->post('create', 'Api\ReviewController::create');

        $routes->post('create_2', 'Api\ReviewController::create_2');

        $routes->get('rating/(:num)', 'Api\ReviewController::ratingcourse/$1');
    });



    $routes->group('jobs/', static function ($routes) {

        $routes->get('', 'Api\JobsController::index');

        $routes->get('detail/(:segment)', 'Api\JobsController::show/$1');

        $routes->post('create', 'Api\JobsController::create');

        $routes->put('update/(:segment)', 'Api\JobsController::update/$1');

        $routes->delete('delete/(:segment)', 'Api\JobsController::delete/$1');
    });



    $routes->group('testimoni/', static function ($routes) {

        $routes->get('', 'Api\TestimoniController::index');

        $routes->get('detail/(:segment)', 'Api\TestimoniController::show/$1');

        $routes->post('create', 'Api\TestimoniController::create');

        $routes->put('update/(:segment)', 'Api\TestimoniController::update/$1');

        $routes->delete('delete/(:segment)', 'Api\TestimoniController::delete/$1');
    });



    $routes->group('pap/', static function ($routes) {

        $routes->get('', 'Api\PolicyAndPrivacyController::index');

        $routes->get('detail/(:num)', 'Api\PolicyAndPrivacyController::show/$1');

        $routes->post('create', 'Api\PolicyAndPrivacyController::create');

        $routes->put('update/(:num)', 'Api\PolicyAndPrivacyController::update/$1');

        $routes->delete('delete/(:num)', 'Api\PolicyAndPrivacyController::delete/$1');
    });



    $routes->group('training/', static function ($routes) {

        $routes->get('', 'Api\CourseController::training');

        $routes->get('latest', 'Api\CourseController::latestTraining');

        $routes->get('detail/(:num)', 'Api\CourseController::trainingDetail/$1');
    });



    $routes->group('users/', static function ($routes) {

        $routes->get('admin', 'Api\UserController::index');

        $routes->get('', 'Api\UserController::profile');

        $routes->get('progress', 'Api\UserController::learningProgress');

        // $routes->get('jobs', 'Api\UserController::jobs');

        $routes->get('mentor', 'Api\UserController::getMentor');

        $routes->post('update/(:num)', 'Api\UserController::update/$1');

        $routes->post('update/profile-picture/(:num)', 'Api\UserController::updateProfilePicture/$1');

        $routes->post('admin/update/(:num)', 'Api\UserController::updateUserByAdmin/$1');

        $routes->delete('delete/(:num)', 'Api\UserController::delete/$1');

        $routes->get('(:num)', 'Api\UserController::userDetail/$1');

        $routes->get('role', 'Api\UserController::getRole');

        $routes->get('author', 'Api\UserController::getAuthor');

        $routes->group('cv/', static function ($routes) {

            $routes->get('', 'Api\UserController::getCv');

            $routes->post('update-cv', 'Api\UserController::updateCv');

            $routes->post('update-edu', 'Api\UserController::updateEdu');

            $routes->post('update-exp', 'Api\UserController::updateExp');

            $routes->post('update-ach', 'Api\UserController::updateAch');

            $routes->post('portofolio/(:num)', 'Api\UserController::updatePortofolio/$1');

            $routes->delete('portofolio', 'Api\UserController::deletePortofolio');
        });
    });



    $routes->group('course/', static function ($routes) {

        $routes->get('all', 'Api\CourseController::all');

        $routes->post('pagination', 'Api\CourseController::pagination');

        $routes->get('topic/(:num)', 'Api\CourseController::getTopic/$1');

        $routes->get('(:num)', 'Api\CourseController::show/$1');

        $routes->get('by/(:num)', 'Api\CourseController::author/$1');

        $routes->get('search/(:segment)', 'Api\CourseController::find/$1');

        $routes->get('find/(:segment)', 'Api\CourseController::search/$1');

        $routes->post('create', 'Api\CourseController::create');

        $routes->post('update/(:num)', 'Api\CourseController::update/$1');

        $routes->delete('delete/(:num)', 'Api\CourseController::delete/$1');

        $routes->get('latest', 'Api\CourseController::latest');

        $routes->get('latest-2', 'Api\CourseController::latest_2');

        $routes->get('filter/(:segment)/(:num)', 'Api\CourseController::trainingByAuthor/$1/$2');

        $routes->get('filter/(:segment)/detail/(:num)', 'Api\CourseController::detailTraining/$1/$2');

        $routes->get('filter/(:segment)', 'Api\CourseController::filter/$1');

        $routes->get('(:num)/member', 'Api\CourseController::userProgress/$1');

        $routes->get('category/(:num)', 'Api\CourseController::filterByCat/$1');

        $routes->get('tag/(:num)', 'Api\CourseController::filterByTag/$1');

        $routes->get('type/(:num)', 'Api\CourseController::filterByType/$1');

        $routes->post('submit-task', 'Api\VideoController::submitTask');

        $routes->post('submit-task-mobile/(:num)', 'Api\VideoController::submitTaskMobile/$1');

        $routes->get('task-history/(:num)', 'Api\VideoController::taskHistory/$1');





        //OTHER PLATFORM ROUTE

        $routes->get('', 'Api\CourseController::index');

        $routes->get('detail/(:num)', 'Api\CourseController::show/$1');

        $routes->get('author/(:segment)', 'Api\CourseController::author/$1');

        $routes->get('author/filter/(:segment)/title/(:num)', 'Api\CourseController::filterByTitle/$1/$2');

        $routes->get('author/filter/(:segment)/category/(:num)', 'Api\CourseController::filterByCategory/$1/$2');

        $routes->get('latest/(:num)', 'Api\CourseController::latest/$1');

        $routes->get('find/(:segment)', 'Api\CourseController::find/$1');

        $routes->get('latest/author/(:num)', 'Api\CourseController::getLatestCourseByAuthor/$1');

        $routes->get('topic/(:num)', 'Api\CourseController::getTopic/$1');





        $routes->group('video/', static function ($routes) {

            $routes->get('(:num)', 'Api\VideoController::index/$1');

            $routes->post('(:num)', 'Api\VideoController::answer/$1');

            $routes->post('create', 'Api\VideoController::create');

            $routes->post('update/(:segment)', 'Api\VideoController::update/$1');

            $routes->post('order', 'Api\VideoController::order');

            $routes->delete('delete/(:segment)', 'Api\VideoController::delete/$1');
        });



        $routes->group('video_2/', static function ($routes) {

            $routes->get('(:num)', 'Api\VideoController::getQuiz/$1');

            $routes->post('(:num)', 'Api\VideoController::answer_2/$1');
        });
    });



    $routes->group('user-video/', static function ($routes) {

        $routes->get('', 'Api\UserVideoController::index');

        $routes->get('detail/(:segment)', 'Api\UserVideoController::show/$1');

        $routes->get('(:segment)/(:segment)', 'Api\UserVideoController::getSkorById/$1/$2');

        $routes->get('detail/(:segment)/(:segment)', 'Api\UserVideoController::showuser/$1/$2');

        $routes->post('create', 'Api\UserVideoController::create');

        $routes->put('update/(:segment)', 'Api\UserVideoController::update/$1');

        $routes->delete('delete/(:segment)', 'Api\UserVideoController::delete/$1');
    });



    $routes->group('bundling/', static function ($routes) {

        $routes->post('create', 'Api\BundlingController::create');

        $routes->post('update/(:segment)', 'Api\BundlingController::update/$1');

        $routes->delete('delete/(:segment)', 'Api\BundlingController::delete/$1');



        //OTHER PLATFORM ROUTE

        $routes->get('', 'Api\BundlingController::index');

        $routes->get('all', 'Api\BundlingController::all');

        $routes->get('detail/(:segment)', 'Api\BundlingController::show/$1');

        $routes->get('user-bundling', 'Api\BundlingController::getUserBundling');
    });



    $routes->group('course-bundling/', static function ($routes) {

        $routes->get('', 'Api\CourseBundlingController::index');

        $routes->get('detail/(:segment)', 'Api\CourseBundlingController::show/$1');

        $routes->post('create', 'Api\CourseBundlingController::create');

        $routes->put('update/(:segment)', 'Api\CourseBundlingController::update/$1');

        $routes->delete('delete/(:segment)', 'Api\CourseBundlingController::delete/$1');

        $routes->delete('deletebybundling/(:segment)', 'Api\CourseBundlingController::deletebybundling/$1');

        $routes->post('create-order', 'api\CourseBundlingController::createorder');

        $routes->post('update-order', 'api\CourseBundlingController::updateorder');
    });



    $routes->group('category/', static function ($routes) {

        $routes->get('', 'Api\CategoryController::index');

        $routes->get('detail/(:num)', 'Api\CategoryController::show/$1');

        $routes->post('create', 'Api\CategoryController::create');

        $routes->put('update/(:num)', 'Api\CategoryController::update/$1');

        $routes->delete('delete/(:num)', 'Api\CategoryController::delete/$1');
    });



    $routes->group('category-bundling/', static function ($routes) {

        $routes->get('', 'Api\CategoryBundlingController::index');

        $routes->get('detail/(:num)', 'Api\CategoryBundlingController::show/$1');

        $routes->post('create', 'Api\CategoryBundlingController::create');

        $routes->put('update/(:num)', 'Api\CategoryBundlingController::update/$1');

        $routes->delete('delete/(:num)', 'Api\CategoryBundlingController::delete/$1');
    });



    $routes->group('video-category/', static function ($routes) {

        $routes->get('', 'Api\VideoCategoryController::index');

        $routes->get('detail/(:num)', 'Api\VideoCategoryController::show/$1');

        $routes->post('create', 'Api\VideoCategoryController::create');

        $routes->put('update/(:num)', 'Api\VideoCategoryController::update/$1');

        $routes->delete('delete/(:num)', 'Api\VideoCategoryController::delete/$1');
    });



    $routes->group('tag/', static function ($routes) {

        $routes->get('', 'Api\TagController::index');

        $routes->get('filter/(:num)', 'Api\TagController::filter/$1');

        $routes->get('detail/(:num)', 'Api\TagController::show/$1');

        $routes->post('create', 'Api\TagController::create');

        $routes->put('update/(:num)', 'Api\TagController::update/$1');

        $routes->delete('delete/(:num)', 'Api\TagController::delete/$1');
    });



    $routes->group('course_tag/', static function ($routes) {

        $routes->get('', 'Api\CourseTagController::index');

        $routes->get('filter/(:segment)/(:num)', 'Api\CourseTagController::filter/$1/$2');
    });



    $routes->group('course_category/', static function ($routes) {

        $routes->get('', 'Api\CourseCategoryController::index');

        $routes->get('filter/(:segment)/(:num)', 'Api\CourseCategoryController::filter/$1/$2');
    });



    $routes->group('notification/', static function ($routes) {

        // domain/api/notification/{user_id yang sedang login}

        // akan memberikan output semua notifikasi user tersebut dan juga public notifikasi

        $routes->get('', 'Api\NotificationController::index');

        $routes->put('read/(:segment)', 'Api\NotificationController::read/$1');

        $routes->post('create', 'Api\NotificationController::create');

        $routes->put('update/(:num)', 'Api\NotificationController::update/$1');

        $routes->delete('delete/(:num)', 'Api\NotificationController::delete/$1');

        $routes->post('midtrans', 'Api\NotificationController::midtrans');

        $routes->post('push', 'Api\NotificationController::sendPush');
    });



    $routes->group('user-notification/', static function ($routes) {

        $routes->get('', 'Api\UserNotificationController::index');

        $routes->get('detail/(:num)', 'Api\UserNotificationController::detail/$1');
    });



    $routes->group('contactus/', static function ($routes) {

        $routes->get('', 'Api\ContactUsController::index');

        $routes->get('detail/(:num)', 'Api\ContactUsController::show/$1');

        $routes->post('answer', 'Api\ContactUsController::answer');

        $routes->post('question', 'Api\ContactUsController::question');

        $routes->delete('delete/(:num)', 'Api\ContactUsController::delete/$1');
    });



    $routes->group('type/', static function ($routes) {

        $routes->get('', 'Api\TypeController::index');

        $routes->get('detail/(:num)', 'Api\TypeController::show/$1');

        $routes->post('create', 'Api\TypeController::create');

        $routes->put('update/(:num)', 'Api\TypeController::update/$1');

        $routes->delete('delete/(:num)', 'Api\TypeController::delete/$1');
    });



    $routes->group('course_type/', static function ($routes) {

        $routes->get('', 'Api\CourseTypeController::index');

        $routes->get('filter/(:segment)/(:num)', 'Api\CourseTypeController::filter/$1/$2');
    });



    $routes->group('type_tag/', static function ($routes) {

        $routes->get('', 'Api\TypeTagController::index');

        $routes->get('filter/(:segment)/(:num)', 'Api\TypeTagController::filter/$1/$2');
    });



    $routes->group('cart/', static function ($routes) {

        $routes->get('', 'Api\CartController::index');

        $routes->get('all', 'Api\CartController::all');

        $routes->post('create/(:segment)/(:num)', 'Api\CartController::create/$1/$2');

        $routes->delete('delete/(:num)', 'Api\CartController::delete/$1');

        $routes->post('process', 'Api\CartController::process');
    });



    $routes->group('mentor/', static function ($routes) {

        $routes->get('', 'Api\MentorController::index');
    });



    // /api/order/ 

    $routes->group('order/', static function ($routes) {

        $routes->get('', 'Api\OrderController::index');

        $routes->get('generatesnap', 'Api\OrderController::generateSnap');

        $routes->post('generatesnap-2', 'Api\OrderController::generateSnap2');

        $routes->post('notif-handler', 'Api\OrderController::notifHandler');

        $routes->get('get-order-by-id/(:num)', 'Api\OrderController::getOrderById/$1');

        $routes->get('get-order-by-author', 'Api\OrderController::getOrderByAuthor');

        $routes->get('get-order-by-member/(:segment)', 'Api\OrderController::getOrderByMember/$1');

        $routes->post('create', 'Api\OrderController::create');

        $routes->post('cancel', 'Api\OrderController::cancel');

        $routes->get('web-view', 'Api\OrderController::webView');
    });



    $routes->group('quiz/', static function ($routes) {

        $routes->get('(:num)', 'Api\QuizController::index/$1');

        $routes->post('create', 'Api\QuizController::create');

        $routes->put('update/(:segment)', 'Api\QuizController::update/$1');

        $routes->delete('delete/(:segment)', 'Api\QuizController::delete/$1');
    });



    $routes->group('referral/', static function ($routes) {

        $routes->get('', 'Api\ReferralController::index');

        $routes->post('create', 'Api\ReferralController::create');
    });



    $routes->group('artikel/', static function ($routes) {

        $routes->get('', 'Api\ArticleController::index');

        $routes->get('suggestion/(:num)', 'Api\ArticleController::suggestion/$1');

        $routes->get('detail/(:num)', 'Api\ArticleController::show/$1');

        $routes->post('create', 'Api\ArticleController::create');

        $routes->post('update/(:num)', 'Api\ArticleController::update/$1');

        $routes->delete('delete/(:num)', 'Api\ArticleController::delete/$1');
    });



    $routes->group('tag-artikel/', static function ($routes) {

        $routes->get('', 'Api\TagArticleController::index');

        $routes->get('detail/(:num)', 'Api\TagArticleController::show/$1');

        $routes->post('create', 'Api\TagArticleController::create');

        $routes->put('update/(:num)', 'Api\TagArticleController::update/$1');

        $routes->delete('delete/(:num)', 'Api\TagArticleController::delete/$1');
    });



    $routes->group('webinar/', static function ($routes) {

        $routes->get('', 'Api\WebinarController::index');

        $routes->get('detail/(:num)', 'Api\WebinarController::show/$1');

        $routes->post('create', 'Api\WebinarController::create');

        $routes->post('update/(:num)', 'Api\WebinarController::update/$1');

        $routes->delete('delete/(:num)', 'Api\WebinarController::delete/$1');
    });

    $routes->group('user-webinar/', static function ($routes) {

        $routes->get('', 'Api\WebinarController::userWebinar');
    });



    $routes->group('resume/', static function ($routes) {

        $routes->get('', 'Api\ResumeController::index');

        $routes->get('detail/(:num)', 'Api\ResumeController::show/$1');

        $routes->post('create', 'Api\ResumeController::create');

        // $routes->post('create_2', 'Api\ResumeController::create_2');

        $routes->post('update/(:num)', 'Api\ResumeController::update/$1');

        $routes->delete('delete/(:num)', 'Api\ResumeController::delete/$1');

        $routes->get('get-sertifikat', 'Api\ResumeController::getSertifikat');
    });



    $routes->group('invoice/', static function ($routes) {

        $routes->get('get-invoice-by-id/(:num)', 'Api\OrderController::getOrderById/$1');
    });



    // api untuk platform lain

    $routes->get('user-list', 'Api\UserController::getUser');
});

# API v1

$routes->group('api/v1', function ($routes) {

    $routes->group('', ['filter' => 'auth'], function ($routes) {
        # account
        $routes->get('me', [\App\Controllers\Api\Account\AccountController::class, 'index']);
        $routes->post('me', [\App\Controllers\Api\Account\AccountController::class, 'store']);

        # data diri (cv)
        $routes->get('profile', [\App\Controllers\Api\User\UserCvController::class, 'index']);
        $routes->post('profile', [\App\Controllers\Api\User\UserCvController::class, 'updateProfile']);
        $routes->post('profile/portofolio', [\App\Controllers\Api\User\UserCvController::class, 'uploadPortofolio']);

        # data pengalaman
        $routes->get('experience', [\App\Controllers\Api\User\UserExperienceController::class, 'index']);
        $routes->post('experience', [\App\Controllers\Api\User\UserExperienceController::class, 'store']);
        $routes->post('experience/(:num)', [\App\Controllers\Api\User\UserExperienceController::class, 'update']);
        $routes->post('experience/delete/(:num)', [\App\Controllers\Api\User\UserExperienceController::class, 'destroy']);
        $routes->get('experience/(:num)', [\App\Controllers\Api\User\UserExperienceController::class, 'show']);

        # data pendidikan
        $routes->get('education', [\App\Controllers\Api\User\UserEducationController::class, 'index']);
        $routes->post('education', [\App\Controllers\Api\User\UserEducationController::class, 'store']);
        $routes->post('education/(:num)', [\App\Controllers\Api\User\UserEducationController::class, 'update']);
        $routes->post('education/delete/(:num)', [\App\Controllers\Api\User\UserEducationController::class, 'destroy']);
        $routes->get('education/(:num)', [\App\Controllers\Api\User\UserEducationController::class, 'show']);

        # data prestasi
        $routes->get('achievement', [\App\Controllers\Api\User\UserAchievementController::class, 'index']);
        $routes->post('achievement', [\App\Controllers\Api\User\UserAchievementController::class, 'store']);
        $routes->put('achievement/(:num)', [\App\Controllers\Api\User\UserAchievementController::class, 'update']);
        $routes->delete('achievement/(:num)', [\App\Controllers\Api\User\UserAchievementController::class, 'destroy']);
        $routes->get('achievement/(:num)', [\App\Controllers\Api\User\UserAchievementController::class, 'show']);

        # data portofolio
        $routes->get('portofolio', [\App\Controllers\Api\User\UserPortofolioController::class, 'index']);
        $routes->post('portofolio', [\App\Controllers\Api\User\UserPortofolioController::class, 'store']);
        $routes->put('portofolio/(:num)', [\App\Controllers\Api\User\UserPortofolioController::class, 'update']);
        $routes->delete('portofolio/(:num)', [\App\Controllers\Api\User\UserPortofolioController::class, 'destroy']);

        # user-course
        $routes->get('member/courses', [\App\Controllers\Api\User\UserCourseController::class, 'index']);
        $routes->post('member/courses/history', [\App\Controllers\Api\Video\VideoController::class, 'historyVideo']);


        $routes->post('member/certificates', [\App\Controllers\Api\User\UserVideoController::class, 'certificateMember']);
        $routes->post('member/score-certificate', [\App\Controllers\Api\User\UserVideoController::class, 'certificateScore']);

        # company
        $routes->group('', ['filter' => 'companyAuth'], function ($routes) {
            # hire
            $routes->get('hires', [\App\Controllers\Api\Hire\HireController::class, 'index']);
            $routes->post('hires', [\App\Controllers\Api\Hire\HireController::class, 'store']);
            $routes->post('hires/(:num)', [\App\Controllers\Api\Hire\HireController::class, 'update']);
            $routes->post('hires/delete/(:num)', [\App\Controllers\Api\Hire\HireController::class, 'delete']);
            $routes->get('hires/(:num)', [\App\Controllers\Api\Hire\HireController::class, 'show']);

            $routes->post('talent/recommendation', [\App\Controllers\Api\Hire\HireController::class, 'recommendation']);

            # hire-offer
            $routes->get('hire-offer', [\App\Controllers\Api\Hire\HireOfferController::class, 'index']);
            $routes->post('hire-offer', [\App\Controllers\Api\Hire\HireOfferController::class, 'store']);
            $routes->post('hire-accept', [\App\Controllers\Api\Hire\HireOfferController::class, 'accept']);
            $routes->post('hire-process', [\App\Controllers\Api\Hire\HireOfferController::class, 'process']);
            $routes->post('company-confirm', [\App\Controllers\Api\Hire\HireOfferController::class, 'confirm']);
        });

        # penawaran
        $routes->get('hire-history', [\App\Controllers\Api\Hire\HireOfferController::class, 'history']);
        $routes->post('hire-confirm/(:num)', [\App\Controllers\Api\Hire\HireOfferController::class, 'update']);

        # kategori
        $routes->get('categories', [\App\Controllers\Api\Category\CategoryController::class, 'index']);
        $routes->post('categories', [\App\Controllers\Api\Category\CategoryController::class, 'store']);
        $routes->put('categories/(:num)', [\App\Controllers\Api\Category\CategoryController::class, 'update']);
        $routes->delete('categories/(:num)', [\App\Controllers\Api\Category\CategoryController::class, 'destroy']);
        $routes->get('categories/(:num)', [\App\Controllers\Api\Category\CategoryController::class, 'show']);

        # tag
        $routes->get('tags', [\App\Controllers\Api\Tag\TagController::class, 'index']);
        $routes->post('tags', [\App\Controllers\Api\Tag\TagController::class, 'store']);
        $routes->put('tags/(:num)', [\App\Controllers\Api\Tag\TagController::class, 'update']);
        $routes->delete('tags/(:num)', [\App\Controllers\Api\Tag\TagController::class, 'destroy']);
        $routes->get('tags/(:num)', [\App\Controllers\Api\Tag\TagController::class, 'show']);

        # tipe
        $routes->get('types', [\App\Controllers\Api\Type\TypeController::class, 'index']);
        $routes->post('types', [\App\Controllers\Api\Type\TypeController::class, 'store']);
        $routes->put('types/(:num)', [\App\Controllers\Api\Type\TypeController::class, 'update']);
        $routes->delete('types/(:num)', [\App\Controllers\Api\Type\TypeController::class, 'destroy']);
        $routes->get('types/(:num)', [\App\Controllers\Api\Type\TypeController::class, 'show']);

        # course
        $routes->get('courses', [\App\Controllers\Api\Course\CourseController::class, 'index']);
        $routes->post('courses', [\App\Controllers\Api\Course\CourseController::class, 'store']);
        $routes->put('courses/(:num)', [\App\Controllers\Api\Course\CourseController::class, 'update']);
        $routes->delete('courses/(:num)', [\App\Controllers\Api\Course\CourseController::class, 'destroy']);

        # video
        $routes->post('video-playlist', [\App\Controllers\Api\Video\VideoController::class, 'index']);
        $routes->post('video-upload', [\App\Controllers\Api\Video\VideoController::class, 'store']);
        $routes->delete('video/(:num)', [\App\Controllers\Api\Video\VideoController::class, 'destroy']);

        # quiz
        $routes->get('quiz/(:num)', [\App\Controllers\Api\Quiz\QuizController::class, 'index']);
        $routes->post('quiz', [\App\Controllers\Api\Quiz\QuizController::class, 'store']);
        $routes->put('quiz/(:num)', [\App\Controllers\Api\Quiz\QuizController::class, 'update']);
        $routes->delete('quiz/(:num)', [\App\Controllers\Api\Quiz\QuizController::class, 'destroy']);
        $routes->get('quiz/detail/(:num)', [\App\Controllers\Api\Quiz\QuizController::class, 'show']);

        # course member
        $routes->get('courses/member/(:num)', [\App\Controllers\Api\User\UserCourseController::class, 'showMemberCourse']);
        $routes->post('courses/member/(:num)', [\App\Controllers\Api\User\UserCourseController::class, 'storeMemberToCourse']);
        $routes->delete('courses/member/(:num)', [\App\Controllers\Api\User\UserCourseController::class, 'destroyMemberFromCourse']);

        # member-task
        $routes->get('member/tasks', [\App\Controllers\Api\Task\MemberTaskController::class, 'index']);
        $routes->post('member/tasks', [\App\Controllers\Api\Task\MemberTaskController::class, 'store']);
        $routes->put('member/tasks/(:num)', [\App\Controllers\Api\Task\MemberTaskController::class, 'update']);
        $routes->delete('member/tasks/(:num)', [\App\Controllers\Api\Task\MemberTaskController::class, 'destroy']);
        $routes->get('member/tasks/(:num)', [\App\Controllers\Api\Task\MemberTaskController::class, 'show']);

        # member-assignment
        $routes->get('member/assignments', [\App\Controllers\Api\Assignment\MemberAssignmentController::class, 'index']);
        $routes->post('member/assignments', [\App\Controllers\Api\Assignment\MemberAssignmentController::class, 'store']);
        $routes->put('member/assignments/(:num)', [\App\Controllers\Api\Assignment\MemberAssignmentController::class, 'update']);
        $routes->delete('member/assignments/(:num)', [\App\Controllers\Api\Assignment\MemberAssignmentController::class, 'destroy']);

        # user view
        $routes->get('member/user-view', [\App\Controllers\Api\User\UserViewController::class, 'index']);
        $routes->post('member/user-view', [\App\Controllers\Api\User\UserViewController::class, 'store']);

        # users
        $routes->get('users', [\App\Controllers\Api\User\UserController::class, 'index']);
    });

    # auth
    $routes->post('login', [\App\Controllers\Api\Auth\AuthController::class, 'login']);
    $routes->post('register', [\App\Controllers\Api\Auth\AuthController::class, 'register']);

    # talent
    $routes->get('talents', [\App\Controllers\Api\Talent\TalentController::class, 'index']);
    $routes->get('talents/(:num)', [\App\Controllers\Api\Talent\TalentController::class, 'show']);

    # course
    $routes->get('courses-active', [\App\Controllers\Api\Course\CourseController::class, 'active']);
    $routes->get('courses/(:num)', [\App\Controllers\Api\Course\CourseController::class, 'show']);
    $routes->post('video-public', [\App\Controllers\Api\Video\VideoController::class, 'publicVideo']);
});

$routes->get('/snap/sukses', 'Home::index');

$routes->get('/snap/batal', 'Home::index');





/*

 * --------------------------------------------------------------------

 * Additional Routing

 * --------------------------------------------------------------------

 *

 * There will often be times that you need additional routing and you

 * need it to be able to override any defaults in this file. Environment

 * based routes is one such time. require() additional route files here

 * to make that happen.

 *

 * You will have access to the $routes object within that file without

 * needing to reload it.

 */

if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {

    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
