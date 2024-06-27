<?php



namespace App\Controllers\Api;



use CodeIgniter\RESTful\ResourceController;

use CodeIgniter\API\ResponseTrait;

use App\Models\Users;

use Firebase\JWT\JWT;

use CodeIgniter\Cookie\Cookie;

use App\Models\Referral;

use App\Controllers\NotificationControllers;

use DateTime;

use DateInterval;

use CodeIgniter\Email\Email;



class AuthController extends ResourceController

{

    private $loginModel = NULL;

    private $googleClient = NULL;

    private $referral;
    private $sendnotification;


    function __construct()

    {
        helper("cookie");

        require_once APPPATH . "../vendor/autoload.php";

        $this->loginModel = new Users();

        $this->referral = new Referral();

        $this->sendnotification = new NotificationController();

        $this->googleClient = new \Google_Client();

        // $this->googleClient->setClientId("229684572752-p2d3d602o4jegkurrba5k2humu61k8cv.apps.googleusercontent.com");

        // $this->googleClient->setClientSecret("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");

        $this->googleClient->setClientId(getenv('go_client_id'));

        $this->googleClient->setClientSecret(getenv('go_client_secret'));

        // $this->googleClient->setRedirectUri(base_url() . "/login/loginGoogle");

        $this->googleClient->addScope("email");

        $this->googleClient->addScope("profile");
    }


    //  buat mobile 

    public function generateLink()
    {
        $this->googleClient->setRedirectUri(base_url() . "/login/loginGoogle");

        return $this->googleClient->createAuthUrl();
    }

    public function loginGoogle()

    {
        try {
            include 'SendNotification.php';

            $this->googleClient->setRedirectUri(base_url() . "/login/loginGoogle");

            $token = $this->googleClient->fetchAccessTokenWithAuthCode($this->request->getVar('code'));

            return $this->respond(['code' => $this->request->getVar('code'), 'token' => $token]);

            if (!isset($token['error'])) {

                $this->googleClient->setAccessToken($token['access_token']);

                $googleService = new \Google\Service\Oauth2($this->googleClient);

                $data = $googleService->userinfo->get();

                $currentDateTime = date("Y-m-d H:i:s");

                $userdata = array();

                $email = $data['email'];

                if ($this->loginModel->isAlreadyRegister($data['id']) || $this->loginModel->isAlreadyRegisterByEmail($email)) {

                    $userdata = [

                        'oauth_id' => $data['id'],

                        'email' => $email,

                        'updated_at' => $currentDateTime,

                        'activation_status' => '1',

                    ];

                    $this->loginModel->updateUserByEmail($userdata, $email);
                } else {

                    $userdata = [

                        'oauth_id' => $data['id'],

                        'email' => $email,

                        'fullname' => $data['name'],

                        'created_at' => $currentDateTime,

                        'activation_status' => '1',

                        'profile_picture' => 'default.png',

                        'role' => 'member',

                    ];

                    $this->loginModel->save($userdata);



                    // referral

                    $datauser = $this->loginModel->getUser($email);

                    do {

                        $code = strtoupper(bin2hex(random_bytes(4)));

                        $code_check = $this->referral->where('referral_code', $code)->first();
                    } while ($code_check);



                    $data = [

                        'user_id' => $datauser['id'],

                        'referral_code' => $code,

                        'discount_price' => 15

                    ];

                    $this->referral->save($data);



                    // notifikasi

                    $message = "Selamat anda berhasil melakukan registrasi. Silahkan lengkapi data diri Anda.";

                    SendNotification(0, $datauser['id'], $message, 3);
                }

                $datauser = $this->loginModel->getUser($email);

                $key = getenv('TOKEN_SECRET');

                $payload = [

                    "exp" => time() + (60 * 60 * 24 * 300),

                    'uid'   => $datauser['id'],

                    'email' => $email,

                    'fullname'   => $datauser['fullname'],

                    'company'   => $datauser['company'],

                    'role' => $datauser['role'],

                ];

                $token = JWT::encode($payload, $key, 'HS256');
            } else {

                $response = [

                    'status' => 500,

                    'error' => true,

                    'message' => 'Terdapat Masalah Saat Login',

                    'data' => []

                ];

                $this->respondCreated($response);

                return;
            }

            $response = [

                'status' => 200,

                'error' => false,

                'data' => [$token],

                'user' => [
                    'id' => $datauser['id'],
                    'job_id' => $datauser['job_id'],
                    'job_name' => isset($datauser['job_name']) ? $datauser['job_name'] : "Professional Independent",
                    'nama' => $datauser['fullname'],
                    'oauth_id' => $datauser['oauth_id'],
                    'email' => $datauser['email'],
                    'date_birth' => $datauser['date_birth'],
                    'address' => $datauser['address'],
                    'phone_number' => $datauser['phone_number'],
                    'linkedin' => $datauser['linkedin'],
                    'profile_picture' => $datauser['profile_picture'],
                    'role' => $datauser['role'],
                    'company' => $datauser['company'],
                    'activation_code' => $datauser['activation_code'],
                    'activation_status' => $datauser['activation_status']
                ]

            ];

            setcookie("access_token", $token, time() + (60 * 60 * 24 * 300), '/');

            return $this->respond($response);
        } catch (\Exception $e) {

            return $this->respond($e->getMessage());
        }
    }

    public function loginWithGoogle()

    {

        include 'SendNotification.php';

        $this->googleClient->setRedirectUri(base_url() . "/login/loginWithGoogle");



        $token = $this->googleClient->fetchAccessTokenWithAuthCode($this->request->getVar('code'));

        if (!isset($token['error'])) {

            $this->googleClient->setAccessToken($token['access_token']);



            $googleService = new \Google\Service\Oauth2($this->googleClient);

            $data = $googleService->userinfo->get();

            $currentDateTime = date("Y-m-d H:i:s");

            $userdata = array();

            $email = $data['email'];

            if ($this->loginModel->isAlreadyRegister($data['id']) || $this->loginModel->isAlreadyRegisterByEmail($email)) {

                $userdata = [

                    'oauth_id' => $data['id'],

                    'email' => $email,

                    'updated_at' => $currentDateTime,

                    'activation_status' => '1',

                ];

                $this->loginModel->updateUserByEmail($userdata, $email);
            } else {

                $userdata = [

                    'oauth_id' => $data['id'],

                    'email' => $email,

                    'fullname' => $data['name'],

                    'created_at' => $currentDateTime,

                    'activation_status' => '1',

                    'profile_picture' => 'default.png',

                    'role' => 'member',

                ];

                $this->loginModel->save($userdata);



                // referral

                $datauser = $this->loginModel->getUser($email);

                do {

                    $code = strtoupper(bin2hex(random_bytes(4)));

                    $code_check = $this->referral->where('referral_code', $code)->first();
                } while ($code_check);



                $data = [

                    'user_id' => $datauser['id'],

                    'referral_code' => $code,

                    'discount_price' => 15

                ];

                $this->referral->save($data);



                // notifikasi

                $message = "Selamat anda berhasil melakukan registrasi. Silahkan lengkapi data diri Anda.";

                SendNotification(0, $datauser['id'], $message, 3);
            }

            $datauser = $this->loginModel->getUser($email);

            $key = getenv('TOKEN_SECRET');

            $payload = [

                "exp" => time() + (60 * 60 * 24 * 300),

                'uid'   => $datauser['id'],

                'email' => $email,

                'fullname'   => $datauser['fullname'],

                'company'   => $datauser['company'],

                'role' => $datauser['role'],

            ];

            $token = JWT::encode($payload, $key, 'HS256');
        } else {

            $response = [

                'status' => 500,

                'error' => true,

                'message' => 'Terdapat Masalah Saat Login',

                'data' => []

            ];

            $this->respondCreated($response);

            return;
        }

        $response = [

            'status' => 200,

            'error' => false,

            'data' => [$token],

            'user' => [
                'id' => $datauser['id'],
                'job_id' => $datauser['job_id'],
                'job_name' => isset($datauser['job_name']) ? $datauser['job_name'] : "Professional Independent",
                'nama' => $datauser['fullname'],
                'oauth_id' => $datauser['oauth_id'],
                'email' => $datauser['email'],
                'date_birth' => $datauser['date_birth'],
                'address' => $datauser['address'],
                'phone_number' => $datauser['phone_number'],
                'linkedin' => $datauser['linkedin'],
                'profile_picture' => $datauser['profile_picture'],
                'role' => $datauser['role'],
                'company' => $datauser['company'],
                'activation_code' => $datauser['activation_code'],
                'activation_status' => $datauser['activation_status']
            ]

        ];

        $this->respondCreated($response);

        setcookie("access_token", $token, time() + (60 * 60 * 24 * 300), '/');

        return redirect()->to(base_url() . "/login");
    }



    public function loginOneTapGoogle()

    {

        include "SendNotification.php";

        // set google client ID

        $google_oauth_client_id = "229684572752-p2d3d602o4jegkurrba5k2humu61k8cv.apps.googleusercontent.com";



        // create google client object with client ID

        $client = new \Google_Client([

            'client_id' => $google_oauth_client_id

        ]);



        // verify the token sent from AJAX

        $id_token = $_POST['credential'];



        // print_r($id_token);



        $payload = $client->verifyIdToken($id_token);

        if ($payload && $payload['aud'] == $google_oauth_client_id) {

            // get user information from Google

            $currentDateTime = date("Y-m-d H:i:s");

            $user_google_id = $payload['sub'];

            $email = $payload["email"];

            $userdata = array();



            if ($this->loginModel->isAlreadyRegister($user_google_id) || $this->loginModel->isAlreadyRegisterByEmail($email)) {

                $userdata = [

                    'oauth_id' => $user_google_id,

                    'email' => $email,

                    'updated_at' => $currentDateTime,

                    'activation_status' => '1',

                ];

                $this->loginModel->updateUserByEmail($userdata, $email);
            } else {

                $userdata = [

                    'oauth_id' => $user_google_id,

                    'email' => $email,

                    'updated_at' => $currentDateTime,

                    'activation_status' => '1',

                    'profile_picture' => 'default.png',

                    'role' => 'member',

                ];

                $this->loginModel->save($userdata);



                // referral

                $datauser = $this->loginModel->getUser($email);

                do {

                    $code = strtoupper(bin2hex(random_bytes(4)));

                    $code_check = $this->referral->where('referral_code', $code)->first();
                } while ($code_check);



                $data = [

                    'user_id' => $datauser['id'],

                    'referral_code' => $code,

                    'discount_price' => 15

                ];

                $this->referral->save($data);



                // notifikasi

                $message = "Selamat anda berhasil melakukan registrasi. Silahkan lengkapi data diri Anda.";

                SendNotification(0, $datauser['id'], $message, 3);
            }

            $datauser = $this->loginModel->getUser($email);

            $key = getenv('TOKEN_SECRET');

            $payload = [

                "exp" => time() + (60 * 60 * 24 * 300),

                'uid'   => $datauser['id'],

                'email' => $email,

                'fullname'   => $datauser['fullname'],

                'company'   => $datauser['company'],

                'role' => $datauser['role'],

            ];

            $token = JWT::encode($payload, $key, 'HS256');
        } else {

            $response = [

                'status' => 500,

                'error' => true,

                'message' => 'Terdapat Masalah Saat Login',

                'data' => []

            ];

            $this->respondCreated($response);

            return;
        }

        $response = [

            'status' => 200,

            'error' => false,

            'data' => [$token]

        ];

        $this->respondCreated($response);

        setcookie("access_token", $token, time() + (60 * 60 * 24 * 300), '/');

        return redirect()->to(base_url() . "/login");
    }



    public function loginGoogleMobile()

    {
        try {

            include 'SendNotification.php';

            if (!$this->request->getVar('id') || !$this->request->getVar('email') || !$this->request->getVar('fullname')) {

                return $this->fail('Data tidak boleh kosong');
            }

            $data = [

                'id' => $this->request->getVar('id'),

                'email' => $this->request->getVar('email'),

                'name' => $this->request->getVar('fullname'),

            ];

            $currentDateTime = date("Y-m-d H:i:s");

            $userdata = array();

            $email = $data['email'];

            if ($this->loginModel->isAlreadyRegister($data['id']) || $this->loginModel->isAlreadyRegisterByEmail($email)) {

                $userdata = [

                    'oauth_id' => $data['id'],

                    'email' => $email,

                    'updated_at' => $currentDateTime,

                    'activation_status' => '1',

                ];

                $registered = true;

                $this->loginModel->updateUserByEmail($userdata, $email);
            } else {

                $userdata = [

                    'oauth_id' => $data['id'],

                    'email' => $email,

                    'fullname' => $data['name'],

                    'created_at' => $currentDateTime,

                    'activation_status' => '1',

                    'profile_picture' => 'default.png',

                    'role' => 'member',

                ];

                $registered = false;

                $this->loginModel->save($userdata);



                // referral

                $datauser = $this->loginModel->getUser($email);

                do {

                    $code = strtoupper(bin2hex(random_bytes(4)));

                    $code_check = $this->referral->where('referral_code', $code)->first();
                } while ($code_check);



                $data = [

                    'user_id' => $datauser['id'],

                    'referral_code' => $code,

                    'discount_price' => 15

                ];

                $this->referral->save($data);



                // notifikasi

                $message = "Selamat anda berhasil melakukan registrasi. Silahkan lengkapi data diri Anda.";

                SendNotification(0, $datauser['id'], $message, 3);
            }

            $datauser = $this->loginModel->getUser($email);

            $key = getenv('TOKEN_SECRET');

            $payload = [

                "exp" => time() + (60 * 60 * 24 * 300),

                'uid'   => $datauser['id'],

                'email' => $email,

                'fullname'   => $datauser['fullname'],

                'company'   => $datauser['company'],

                'role' => $datauser['role'],

            ];

            $token = JWT::encode($payload, $key, 'HS256');

            $response = [

                'status' => 200,

                'error' => false,

                'data' => [$token],

                'user' => [
                    'id' => $datauser['id'],
                    'isRegistered' => $registered,
                    'job_id' => $datauser['job_id'],
                    'job_name' => isset($datauser['job_name']) ? $datauser['job_name'] : "Professional Independent",
                    'nama' => $datauser['fullname'],
                    'oauth_id' => $datauser['oauth_id'],
                    'email' => $datauser['email'],
                    'date_birth' => $datauser['date_birth'],
                    'address' => $datauser['address'],
                    'phone_number' => $datauser['phone_number'],
                    'linkedin' => $datauser['linkedin'],
                    'profile_picture' => $datauser['profile_picture'],
                    'role' => $datauser['role'],
                    'company' => $datauser['company'],
                    'activation_code' => $datauser['activation_code'],
                    'activation_status' => $datauser['activation_status']
                ]

            ];

            setcookie("access_token", $token, time() + (60 * 60 * 24 * 300), '/');

            return $this->respond($response);
        } catch (\Exception $e) {

            return $this->respond($e->getMessage());
        }
    }



    public function register()

    {

        $rules = [

            "fullname" => "required",

            "email" => "required|valid_email",

            "phone_number" => "required",

            "address" => "required",

            "date_birth" => "required",

            "password" => "required|min_length[8]|max_length[50]",

            "password_confirm" => "matches[password]",

        ];



        $messages = [

            "fullname" => [

                'required' => '{field} tidak boleh kosong'

            ],

            "email" => [

                'required' => '{field} tidak boleh kosong',

                'valid_email' => 'Format email tidak sesuai'

            ],

            "phone_number" => [

                'required' => '{field} tidak boleh kosong'

            ],

            "address" => [

                'required' => '{field} tidak boleh kosong'

            ],

            "date_birth" => [

                'required' => '{field} tidak boleh kosong'

            ],

            "password" => [

                'required' => '{field} tidak boleh kosong',

                'min_length' => '{field} minimal 8 karakter',

                'max_length' => '{field} maksimal 50 karakter'

            ],

            "password_confirm" => [

                'matches' => 'Konfirmasi kata sandi tidak cocok dengan kata sandi'

            ],

        ];



        $key = getenv('TOKEN_SECRET');

        $payload = array(

            "exp" => time() + (60 * 60 * 24 * 300),

            "email" => $this->request->getVar('email')

        );

        $token = JWT::encode($payload, $key);



        if (!$this->validate($rules, $messages)) {
            $response = [

                'message' => $this->validator->getErrors(),

                'data' => []

            ];

            return $this->fail($response);
        } else {

            $email =  $this->request->getVar('email');

            $verifyEmail = $this->loginModel

                ->where("email", $email)

                ->where("activation_status", 0)

                ->first();

            $verifyActivation = $this->loginModel

                ->where("email", $email)

                ->where("activation_status", 1)

                ->first();

            $userdata['fullname'] = $this->request->getVar('fullname');

            $userdata['email'] = $email;

            $userdata['phone_number'] = $this->request->getVar('phone_number');

            $userdata['address'] = $this->request->getVar('address');

            $userdata['date_birth'] = $this->request->getVar('date_birth');

            $userdata['role'] = 'member';

            $userdata['profile_picture'] = 'default.png';

            $userdata['password'] = password_hash($this->request->getVar('password'), PASSWORD_BCRYPT);

            $userdata['activation_code'] = $token;

            // $userdata['activation_status'] = '1';



            if ($verifyActivation) return $this->failNotFound('Email telah digunakan');



            if ($verifyEmail == NULL) {

                $this->loginModel->save($userdata);
            } else {

                $this->loginModel->updateUserByEmail($userdata, $email);
            }

            $this->sendActivationEmail($this->request->getVar('email'), $token);

            $user = $this->loginModel->where("email", $this->request->getVar('email'))->first();

            $response = [

                'status' => 201,

                'success' => 201,

                'message' => 'Akun berhasil dibuat, silakan periksa email Anda untuk aktivasi',

                'data' => [
                    'user' => [
                        'id' => $user['id'],
                        'job_id' => $user['job_id'],
                        'nama' => $user['fullname'],
                        'oauth_id' => $user['oauth_id'],
                        'email' => $user['email'],
                        'date_birth' => $user['date_birth'],
                        'address' => $user['address'],
                        'phone_number' => $user['phone_number'],
                        'profile_picture' => $user['profile_picture'],
                        'role' => $user['role'],
                        'company' => $user['company'],
                        'activation_code' => $user['activation_code'],
                        'activation_status' => $user['activation_status']
                    ]
                ]

            ];

            return $this->respondCreated($response);
        }
    }


    public function registerCompany()

    {

        $rules = [

            "fullname" => "required",

            "email" => "required|valid_email",

            "phone_number" => "required",

            "address" => "required",

            "password" => "required|min_length[8]|max_length[50]",

            "password_confirm" => "matches[password]",

        ];



        $messages = [

            "fullname" => [

                'required' => '{field} tidak boleh kosong'

            ],

            "email" => [

                'required' => '{field} tidak boleh kosong',

                'valid_email' => 'Format email tidak sesuai'

            ],

            "phone_number" => [

                'required' => '{field} tidak boleh kosong'

            ],

            "address" => [

                'required' => '{field} tidak boleh kosong'

            ],

            "password" => [

                'required' => '{field} tidak boleh kosong',

                'min_length' => '{field} minimal 8 karakter',

                'max_length' => '{field} maksimal 50 karakter'

            ],

            "password_confirm" => [

                'matches' => 'Konfirmasi kata sandi tidak cocok dengan kata sandi'

            ],

        ];



        $key = getenv('TOKEN_SECRET');

        $payload = array(

            "exp" => time() + (60 * 60 * 24 * 300),

            "email" => $this->request->getVar('email')

        );

        $token = JWT::encode($payload, $key);



        if (!$this->validate($rules, $messages)) {



            $response = [

                'message' => $this->validator->getErrors(),

                'data' => []

            ];

            return $this->fail($response);
        } else {

            $email =  $this->request->getVar('email');

            $verifyEmail = $this->loginModel

                ->where("email", $email)

                ->where("activation_status", 0)

                ->first();

            $verifyActivation = $this->loginModel

                ->where("email", $email)

                ->where("activation_status", 1)

                ->first();

            $userdata['fullname'] = $this->request->getVar('fullname');

            $userdata['email'] = $email;

            $userdata['phone_number'] = $this->request->getVar('phone_number');

            $userdata['address'] = $this->request->getVar('address');

            $userdata['role'] = 'company';

            $userdata['profile_picture'] = 'company-default.jpg';

            $userdata['password'] = password_hash($this->request->getVar('password'), PASSWORD_BCRYPT);

            $userdata['activation_code'] = $token;

            // $userdata['activation_status'] = '1';



            if ($verifyActivation) return $this->failNotFound('Email telah digunakan');



            if ($verifyEmail == NULL) {

                $this->loginModel->save($userdata);
            } else {

                $this->loginModel->updateUserByEmail($userdata, $email);
            }

            $this->sendActivationEmail($this->request->getVar('email'), $token);

            $user = $this->loginModel->where("email", $this->request->getVar('email'))->first();

            $response = [

                'status' => 201,

                'success' => 201,

                'message' => 'Akun berhasil dibuat, silakan periksa email Anda untuk aktivasi',

                'data' => [
                    'user' => [
                        'id' => $user['id'],
                        'job_id' => $user['job_id'],
                        'nama' => $user['fullname'],
                        'oauth_id' => $user['oauth_id'],
                        'email' => $user['email'],
                        'date_birth' => $user['date_birth'],
                        'address' => $user['address'],
                        'phone_number' => $user['phone_number'],
                        'profile_picture' => $user['profile_picture'],
                        'role' => $user['role'],
                        'company' => $user['company'],
                        'activation_code' => $user['activation_code'],
                        'activation_status' => $user['activation_status']
                    ]
                ]

            ];

            return $this->respondCreated($response);
        }
    }


    function sendActivationEmail($emailTo, $token)

    {

        //$to = $this->request->getVar('mailTo'); 

        $subject = 'Link Aktivasi Akun';

        $link = base_url() . "/api/activateuser?token=" . $token;
        // $link = "https://stufast.id/api/activateuser?token=" . $token;

        $path = base_url();
        // $path = "https://stufast.id/";

        $data = [

            "link" => $link,

            "email" => $emailTo,

            "path" => $path,

        ];

        $message = view('html_email/email_verify.html', $data);



        $funcEmail = new Email();

        $funcEmail->setTo($emailTo);

        $funcEmail->setFrom('noreply@stufast.id', 'Konfirmasi Pendaftaran');



        $funcEmail->setSubject($subject);

        $funcEmail->setMessage($message);

        $funcEmail->send();
    }

    public function activateUser()

    {

        include 'SendNotification.php';

        $session = \Config\Services::session();

        $token = $this->request->getVar('token');

        //echo $token;

        $key = getenv('TOKEN_SECRET');

        $referral = new Referral();

        try {

            $decoded = JWT::decode($token, $key, array('HS256'));



            $uid = $this->loginModel->select('id')->where('email', $decoded->email)->first();

            $check = $referral->where('user_id',  $uid)->first();



            do {

                $code = strtoupper(bin2hex(random_bytes(4)));

                $code_check = $referral->where('referral_code', $code)->first();
            } while ($code_check);



            if (!$check) {

                $data = [

                    'user_id' => $uid,

                    'referral_code' => $code,

                    'discount_price' => 15

                ];

                $referral->save($data);
            }
        } catch (\Firebase\JWT\ExpiredException $e) {

            //echo 'Caught exception: ',  $e->getMessage(), "\n";

            $message = [

                "message" => $e->getMessage()

            ];

            return view('errors/html/error_404', $message);

            //return;

        }



        $this->loginModel->updateUserByEmail([

            'activation_status' => 1,

            'activation_code' => '',

            // 'profile_picture' => 'default.png',

        ], $decoded->email);



        // notifikasi

        $message = "Selamat anda berhasil melakukan registrasi. Silahkan lengkapi data diri Anda.";

        SendNotification(0, $uid, $message, 3);



        $session->setFlashdata('activation_success', 'Email berhasil diverifikasi');

        return redirect()->to(base_url() . "/login");
    }



    public function indexLogin()

    {

        $data = [

            "title" => "Sign In",

            "googleButton" => $this->googleClient->createAuthUrl(),

        ];

        return $this->respond($data);
    }



    public function indexRegister()

    {

        $data = [

            "title" => "Sign Up",

            "googleButton" => $this->googleClient->createAuthUrl(),

        ];

        return $this->respond($data);
    }



    public function indexforgotPassword()

    {

        $data = [

            "title" => "Reset Password",

        ];

        return $this->respond($data);
    }



    public function indexSendOtp()

    {

        $data = [

            "title" => "OTP Code",

        ];

        return $this->respond($data);
    }



    public function indexNewPassword()

    {

        $data = [

            "title" => "Reset Password",

        ];

        return $this->respond($data);
    }



    public function login()

    {

        $rules = [

            "email" => "required|valid_email",

            "password" => "required",

        ];



        $messages = [

            "email" => [

                "required" => "{field} tidak boleh kosong",

                'valid_email' => 'Format email tidak sesuai'

            ],

            "password" => [

                "required" => "{field} tidak boleh kosong"

            ],

        ];

        if (!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());



        $verifyEmail = $this->loginModel->getUser($this->request->getVar('email'));

        if (!$verifyEmail) return $this->failNotFound('Email atau kata sandi salah');



        $verifyPass = password_verify($this->request->getVar('password'), $verifyEmail['password']);

        if (!$verifyPass) {

            return $this->fail('Email atau kata sandi salah');
        } else if ($verifyEmail['activation_status'] != 1) {

            return $this->fail('Pengguna belum aktif');
        } else {
        }

        $datauser = $verifyEmail;

        $key = getenv('TOKEN_SECRET');

        $payload = [

            "exp" => time() + (60 * 60 * 24 * 300),

            'uid'   => $verifyEmail['id'],

            'email' => $verifyEmail['email'],

            'fullname'   => $datauser['fullname'],

            'company'   => $datauser['company'],

            'role' => $datauser['role'],

        ];

        $token = JWT::encode($payload, $key, 'HS256');



        $response = [

            'status' => 200,

            'success' => 200,

            // 'data' => [$token],

            'data' => [
                $token
            ],

            'user' => [
                'id' => $datauser['id'],
                'isRegistered' => true,
                'job_id' => $datauser['job_id'],
                'job_name' => isset($datauser['job_name']) ? $datauser['job_name'] : "Professional Independent",
                'nama' => $datauser['fullname'],
                'oauth_id' => $datauser['oauth_id'],
                'email' => $datauser['email'],
                'date_birth' => $datauser['date_birth'],
                'address' => $datauser['address'],
                'phone_number' => $datauser['phone_number'],
                'profile_picture' => $datauser['profile_picture'],
                'role' => $datauser['role'],
                'company' => $datauser['company'],
                'activation_code' => $datauser['activation_code'],
                'activation_status' => $datauser['activation_status']
            ]



        ];

        return $this->respond($response);
    }



    public function changePassword()

    {

        $key = getenv('TOKEN_SECRET');

        $user = new Users;

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];

        $decoded = JWT::decode($token, $key, ['HS256']);

        $rules = [

            "xpassword" => "required",

            "new-password" => "required",

        ];

        $message = [

            'required' => "Password tidak boleh kosong!",

        ];

        if (!$this->validate($rules, $message)) {

            return $this->fail($this->validator->getErrors());
        }

        $verify_email = $this->loginModel->where("email", $decoded->email)->first();

        $verify_pass = password_verify($this->request->getVar('xpassword'), $verify_email['password']);

        if (!$verify_pass) {

            $response = [

                'status' => 401,

                'message' => 'Password salah!',

            ];

            return $this->response->setStatusCode(400)->setJSON($response);
        } else {

            $userdata = [

                'password' => password_hash($this->request->getVar('new-password'), PASSWORD_BCRYPT),

            ];

            $user->update($verify_email['id'], $userdata);

            $response = [

                'status' => 200,

                'message' => 'Berhasil mengubah password!',

            ];

            return $this->respond($response);
        }
    }

    public function updateDeviceToken()
    {

        $key = getenv('TOKEN_SECRET');

        $user = new Users;

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];

        $decoded = JWT::decode($token, $key, ['HS256']);

        $input = [

            'device_token' => $this->request->getVar('device_token')

        ];

        $user->update($decoded->uid, $input);


        $response = [

            'status' => 200,

            'message' => 'Device token berhasil diperbarui'

        ];

        return $this->respond($response);
    }
}
