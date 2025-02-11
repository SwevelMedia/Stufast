<?php



namespace App\Controllers;



use CodeIgniter\Controller;

use App\Models\UsersModel;

use App\Models\ForgotPasswordModel;

use Firebase\JWT\JWT;



class AdminController extends BaseController

{

    private $googleClient = NULL;



    function __construct()

    {

        helper("cookie");



        require_once APPPATH . "../vendor/autoload.php";

        $this->googleClient = new \Google_Client();

        $this->googleClient->setClientId(getenv('go_client_id'));

        $this->googleClient->setClientSecret(getenv('go_client_secret'));

        $this->googleClient->setRedirectUri(base_url() . "/login/loginWithGoogle");

        $this->googleClient->addScope("email");

        $this->googleClient->addScope("profile");
    }



    public function index()

    {

        if (!get_cookie("access_token")) {

            return redirect()->to(base_url());
        }

        $token = get_cookie("access_token");

        $key = getenv('TOKEN_SECRET');

        try {

            $decoded = JWT::decode($token, $key, array('HS256'));
        } catch (\Exception $e) {

            echo 'Caught exception: ',  $e->getMessage(), "\n";

            return;
        }

        if ($decoded) {

            if ($decoded->role != 'admin' && $decoded->role != 'author') {

                // return redirect()->back();
                echo $decoded->role;
            }

            $email = $decoded->email;

            $data = [

                "title" => $email,

                "profile" => "",

                "cardButton" => 'Join The Webinar',

            ];

            return view('pages/admin/index', $data);
        }
    }



    // 

    public function user()

    {

        if (!get_cookie("access_token")) {

            return redirect()->to(base_url());
        }

        $token = get_cookie("access_token");

        $key = getenv('TOKEN_SECRET');

        try {

            $decoded = JWT::decode($token, $key, array('HS256'));
        } catch (\Exception $e) {

            echo 'Caught exception: ',  $e->getMessage(), "\n";

            return;
        }

        if ($decoded) {

            if ($decoded->role == 'author') {

                return redirect()->to('/admin/user/' . $decoded->uid);
            }

            if ($decoded->role != 'admin') {

                return redirect()->back();
            }

            $email = $decoded->email;

            $data = [

                "title" => "User",

            ];

            return view('pages/admin/user', $data);
        }
    }



    public function userDetail()

    {

        $data = [

            "title" => "User Detail",

        ];

        return view('pages/admin/user_detail', $data);
    }



    public function transaction()

    {

        $data = [

            "title" => "Transaction",

        ];

        return view('pages/admin/transaction', $data);
    }



    public function transactionDetail()

    {

        $data = [

            "title" => "Transaction Detail",

        ];

        return view('pages/admin/transaction_detail', $data);
    }



    public function course()

    {

        $data = [

            "title" => "Course",

        ];

        return view('pages/admin/course', $data);
    }



    public function courseDetail()

    {

        $data = [

            "title" => "Course Detail",

        ];

        return view('pages/admin/course_detail', $data);
    }



    public function videoDetail()

    {

        $data = [

            "title" => "Video Detail",

        ];

        return view('pages/admin/video_detail', $data);
    }

    public function quizDetail()

    {

        $data = [

            "title" => "Quiz Chapter Detail",

        ];

        return view('pages/admin/quiz_detail', $data);
    }



    public function bundling()

    {

        $data = [

            "title" => "Bundling List",

        ];

        return view('pages/admin/bundling', $data);
    }



    public function bundlingDetail()

    {

        $data = [

            "title" => "Bundling Detail",

        ];

        return view('pages/admin/bundling_detail', $data);
    }



    public function faq()

    {

        $data = [

            "title" => "FAQ Menu",

        ];

        return view('pages/admin/faq', $data);
    }



    public function contactList()

    {

        $data = [

            "title" => "List Message",

        ];

        return view('pages/admin/contactlist', $data);
    }
}
