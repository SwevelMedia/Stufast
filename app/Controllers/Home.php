<?php



namespace App\Controllers;

use Dompdf\Dompdf;

use Dompdf\Options;

use CodeIgniter\Database\ConnectionInterface;

use App\Controllers\Api\ResumeController;

use App\Controllers\Api\OrderController;
use App\Handlers\AuthHandler;
use App\Handlers\RoleHandler;
use Firebase\JWT\JWT;

use CodeIgniter\API\ResponseTrait;


helper('all_helpers');


class Home extends BaseController

{

    use ResponseTrait;

    public function __construct()
    {
        helper('cookie');
    }

    public function index()
    {
        $data = [
            "title" => "The First Group - Mentoring Focused Course in Indonesia",
        ];

        return view('pages/home/home', $data);
    }

    public function signUp()
    {
        return view('pages/authentication/sign_up');
    }

    public function forgotPassword()
    {
        return view('pages/authentication/forgot_password');
    }

    public function sendOTP()
    {
        return view('pages/authentication/otp_code');
    }

    public function newPassword()
    {
        return view('pages/authentication/new_password');
    }


    public function talent_hub()
    {
        $data = [
            'title' => 'Talent'
        ];

        return view('pages/navigation/talent_hub', $data);
    }


    public function talent()
    {
        return view('home/talent', [
            'title' => 'Talent',
            'user' => AuthHandler::getUser(),
            'role' => RoleHandler::getRole()
        ]);
    }

    public function detail_talent()
    {
        return view('home/detail_talent', [
            'title' => 'Profil Talent',
            'user' => AuthHandler::getUser(),
            'role' => RoleHandler::getRole()
        ]);
    }


    public function faq()
    {
        return view('pages/navigation/faq');
    }

    public function aboutUs()
    {
        $data = [
            "title" => "Tentang Kami",
        ];

        return view('pages/navigation/about_us', $data);
    }

    public function termsAndConditions()
    {
        $data = [
            "title" => "Syarat dan Ketentuan",
        ];

        return view('pages/navigation/terms_and_condition', $data);
    }

    public function pricingPlan()
    {
        $data = [
            "title" => "Pricing Plan",
        ];

        return view('pages/navigation/pricing_plan', $data);
    }

    public function bundlingCart($id)
    {
        $data = [
            "title" => "Bundling",
            "id" => $id,
        ];

        return view('pages/course/bundling', $data);
    }

    public function courseDetail()
    {
        $data = [
            "title" => "Detail Kursus",
        ];

        return view('pages/course/course-detail', $data);
    }



    public function courseDetailNew()
    {
        $data = [
            "title" => "Detail Kursus",
        ];

        return view('pages/course/course-detail', $data);
    }



    public function cart()
    {
        $data = [
            "title" => "Keranjang Anda",
        ];

        return view('pages/course/cart', $data);
    }



    public function webinar()

    {

        $data = [

            "title" => "Webinar",

        ];

        return view('pages/navigation/webinar', $data);
    }

    public function training()

    {

        $data = [

            "title" => "Pelatihan",

        ];

        return view('pages/navigation/training', $data);
    }

    public function trainingDetail()

    {

        $data = [

            "title" => "Detail Pelatihan",

        ];

        return view('pages/course/training-detail', $data);
    }

    public function courses_dev()
    {
        return view('home/courses', [
            'title' => 'Kursus',
            'user' => AuthHandler::getUser(),
            'role' => RoleHandler::getRole()
        ]);
    }

    public function courses_detail_dev()
    {
        return view('home/course_detail', [
            'title' => 'Detail Kursus',
            'user' => AuthHandler::getUser(),
            'role' => RoleHandler::getRole()
        ]);
    }

    public function courses()
    {
        $data = [
            "title" => "Kursus",
        ];

        return view('pages/navigation/courses', $data);
    }

    public function article()

    {

        $data = [

            "title" => "Artikel",

        ];

        return view('pages/navigation/article', $data);
    }



    public function articleDetail($id)

    {

        $data = [

            "title" => "Artikel",

            "id" => $id

        ];

        return view('pages/navigation/article', $data);
    }



    public function checkout()

    {

        $data = [

            "title" => "Checkout",

        ];

        return view('pages/course/checkout', $data);
    }



    public function invoice()

    {

        if (isset($_GET['token'])) {

            $header = $_GET['token'];
        } else {

            $header = $_COOKIE['access_token'];
        }

        $key = getenv('TOKEN_SECRET');

        $decoded = JWT::decode($header, $key, ['HS256']);

        $order_id = $_GET['order_id'];


        $invoice = new OrderController();

        $data = $invoice->invoiceData($order_id, $decoded->uid);


        if ($data['status'] == 'error') {

            return "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><meta name='viewport' content='width=device-width, initial-scale=1.0'><title>Terjadi Kesalahan</title><style>body{display:flex;flex-direction: column;align-items:center;justify-content:center;height:100vh;margin:0;}</style></head><body><img src='/image/notif/failed.png' height=300px><h3 style='font-weight:normal'>" . $data['message'] . "</h3 style='text-weight:none'></body></html>";
        }

        $html = view('pages/course/invoice', $data['data']);

        $dompdf = new Dompdf();

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        $dompdf->stream($data['data']['order_id'] . '.pdf', array('Attachment' => 0));

        exit();
    }



    public function email()

    {

        $data = [

            "order_id" => 1,

            "total_price" => 200000,

        ];

        return view('html_email/payment_success.html', $data);
    }

    public function certificate()

    {

        if (isset($_GET['token'])) {

            $header = $_GET['token'];
        } else {

            $header = $_COOKIE['access_token'];
        }

        $key = getenv('TOKEN_SECRET');

        $decoded = JWT::decode($header, $key, ['HS256']);

        $type = $_GET['type'];

        $id = $_GET['id'];


        $sertifikat = new ResumeController();

        $data = $sertifikat->getSertifikat($decoded->uid, $type, $id);


        if ($data['status'] == 'error') {

            return "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><meta name='viewport' content='width=device-width, initial-scale=1.0'><title>Akses ditolak</title><style>body{display:flex;flex-direction: column;align-items:center;justify-content:center;height:100vh;margin:0;}</style></head><body><img src='/image/notif/failed.png' height=300px><h3 style='font-weight:normal'>" . $data['message'] . "</h3 style='text-weight:none'></body></html>";
        }


        $html = view('certificates/certificates_view', $data['data']);

        $dompdf = new Dompdf();

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'landscape');

        $dompdf->render();

        $dompdf->stream($data['data']['title'] . '.pdf', array('Attachment' => 0));

        exit();
    }


    public function hire()
    {

        $data = [

            "title" => "Hire",

        ];

        return view('pages/navigation/hire', $data);
    }


    public function hireHistory()
    {

        $data = [

            "title" => "Riwayat Hire",

        ];

        return view('pages/navigation/hire_history', $data);
    }
}
