<?php



namespace App\Controllers;



use CodeIgniter\Controller;

use App\Models\UsersModel;

use App\Models\ForgotPasswordModel;

use App\Models\Hire;

use Firebase\JWT\JWT;

use App\Controllers\Api\UserController;

use Dompdf\Dompdf;

use DateTime;

use DateInterval;

helper('all_helpers');



class AuthController extends BaseController

{

	private $googleClient = NULL;



	function __construct()

	{

		helper("cookie");



		require_once APPPATH . "../vendor/autoload.php";

		$this->googleClient = new \Google_Client();

		// $this->googleClient->setClientId("229684572752-p2d3d602o4jegkurrba5k2humu61k8cv.apps.googleusercontent.com");

		// $this->googleClient->setClientSecret("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");

		$this->googleClient->setClientId(getenv('go_client_id'));

		$this->googleClient->setClientSecret(getenv('go_client_secret'));

		$this->googleClient->setRedirectUri(base_url() . "/login/loginWithGoogle");

		$this->googleClient->addScope("email");

		$this->googleClient->addScope("profile");
	}



	public function indexLogin()

	{

		$request = service('request');

		$userAgent = $request->getUserAgent();

		if (get_cookie("access_token")) {



			// if ($userAgent->isMobile()) {

			// 	return redirect()->to(base_url() . '/mobile/profile');

			// } else {

			// 	return redirect()->to(base_url() . "/profile");

			// }

			return redirect()->to(base_url() . "/profile");
		}

		$data = [

			"title" => "Masuk",

			"googleButton" => $this->googleClient->createAuthUrl(),

		];



		// if ($userAgent->isMobile()) {

		// 	return redirect()->to(base_url() . '/mobile/login');

		// } else {

		// 	return view('pages/authentication/login', $data);

		// }

		return view('pages/authentication/login', $data);
	}



	public function login()

	{

		// echo $this->request->getBody();

		// var_dump($this->request);

		if ($this->request->isAJAX()) {

			$requestBody = json_decode($this->request->getBody());

			$token = $requestBody->access_token;

			echo $token;

			$key = getenv('TOKEN_SECRET');

			try {

				$decoded = JWT::decode($token, $key, array('HS256'));
			} catch (\Exception $e) {

				echo 'Caught exception: ',  $e->getMessage(), "\n";

				return;
			}



			if ($decoded) {

				if (!$decoded->email) {

					return redirect()->back();
				}

				return redirect()->to(base_url() . "/profile");
			} else {

				return redirect()->back();
			}
		}
	}





	public function loginWithGoogle()

	{

		return redirect()->to(base_url() . "/profile");
	}



	public function profile()

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

			if (!$decoded->email) {

				return redirect()->back();
			}

			$email = $decoded->email;
		}

		$data = [

			"title" => "Profil Saya",

			"profile" => "",

			"cardButton" => 'Join The Webinar',

		];

		return view('pages/navigation/profile', $data);
	}

	public function cv()

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

			if (!$decoded->email) {

				return redirect()->back();
			}

			$email = $decoded->email;
		}

		$data = [

			"title" => "Curriculum Vitae",

			"profile" => "",

			"cardButton" => 'Join The Webinar',

		];

		return view('pages/navigation/cv', $data);
	}

	public function cvDownload()

	{

		$hire = new Hire;

		$hide = false;

		if (isset($_GET['user_id'])) {

			$user_id = $_GET['user_id'];

			if (isset($_GET['token'])) {

				$header = $_GET['token'];
			} else {

				$header = $_COOKIE['access_token'];
			}

			$key = getenv('TOKEN_SECRET');

			$decoded = JWT::decode($header, $key, ['HS256']);

			$cek = $hire

				->where('company_id', $decoded->uid)

				->where('user_id', $user_id)

				->where('result', 'accept')

				->findAll();

			if (count($cek) == 0) $hide = true;
		} else {

			if (isset($_GET['token'])) {

				$header = $_GET['token'];
			} else {

				$header = $_COOKIE['access_token'];
			}

			$key = getenv('TOKEN_SECRET');

			$decoded = JWT::decode($header, $key, ['HS256']);

			$user_id = $decoded->uid;
		}

		$user = new UserController();

		$data = $user->getCvData($user_id, $hide);

		$html = view('components/profile/cv_view', $data['data']);

		$dompdf = new Dompdf();

		$dompdf->loadHtml($html);

		$dompdf->setPaper('A4', 'potrait');

		$dompdf->render();

		$dompdf->stream('CV - ' . $data['data']['fullname'] . '.pdf', array('Attachment' => 0));

		exit();
	}

	public function course()

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

			if (!$decoded->email) {

				return redirect()->back();
			}

			$email = $decoded->email;
		}

		$data = [

			"title" => 'Kursus Saya',

			"cardButton" => 'Join The Webinar',

		];

		return view('pages/navigation/course', $data);
	}



	public function training()

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

			if (!$decoded->email) {

				return redirect()->back();
			}

			$email = $decoded->email;
		}

		$data = [

			"title" => 'Pelatihan Saya',

			"cardButton" => 'Join The Webinar',

		];

		return view('pages/navigation/my_training', $data);
	}



	function logout()
	{
		helper('cookie');
		delete_cookie('access_token');
		return redirect()->to(base_url());
	}



	public function indexRegister()

	{

		$data = [

			"title" => "Daftar",

			"googleButton" => $this->googleClient->createAuthUrl(),

		];

		return view('pages/authentication/register', $data);
	}



	public function indexRegisterCompany()

	{

		$data = [

			"title" => "Daftar",

			"googleButton" => $this->googleClient->createAuthUrl(),

		];

		return view('pages/authentication/register_comp', $data);
	}



	public function register()

	{

		if ($this->request->isAJAX()) {

			return redirect()->to(base_url() . '/login');
		}

		return redirect()->to(base_url() . '/login');
	}



	public function indexforgotPassword()

	{

		if (get_cookie("email")) {

			return redirect()->to('/send-otp');
		}

		$data = [

			"title" => "Atur Ulang Sandi",

		];

		return view('pages/authentication/forgot_password', $data);
	}



	public function forgotPassword()

	{

		return redirect()->to('/send-otp');
	}



	public function indexSendOtp()

	{

		$data = [

			"title" => "Kode OTP",

		];

		return view('pages/authentication/otp_code', $data);
	}



	public function sendOtp()

	{

		return redirect()->to(base_url() . "/new-password");
	}



	public function indexNewPassword()

	{

		$data = [

			"title" => "Atur Ulang Sandi",

		];

		return view('pages/authentication/new_password', $data);
	}



	public function newPassword()

	{

		return redirect()->to('/login');
	}



	public function referralCode()

	{

		if (!get_cookie("access_token")) {

			return redirect()->to(base_url());
		}

		$data = [

			"title" => "Kode Referral",

			"profile" => "",

			"cardButton" => 'Join The Webinar',

		];

		return view('pages/navigation/referral_code', $data);
	}

	public function privacy()

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

			if (!$decoded->email) {

				return redirect()->back();
			}

			$email = $decoded->email;
		}

		$data = [

			"email" => $email,

			"title" => "Ubah Kata Sandi",

		];

		return view('pages/navigation/password', $data);
	}

	public function order()

	{

		if (!get_cookie("access_token")) {

			return redirect()->to(base_url());
		}

		$data = [

			"title" => "Riwayat Pesanan",

		];

		return view('pages/navigation/order', $data);
	}
}
