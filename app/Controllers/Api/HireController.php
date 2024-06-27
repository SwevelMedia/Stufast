<?php



namespace App\Controllers\Api;



use CodeIgniter\API\ResponseTrait;

use App\Controllers\BaseController;

use App\Models\Hire;

use App\Models\HireBatch;

use App\Models\Users;

use Firebase\JWT\JWT;

use DateTime;



class HireController extends BaseController

{

    use ResponseTrait;

    public function hire()

    {

        include 'SendNotification.php';

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];


        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $user = new Users;

            $hire = new Hire;

            $hire_batch = new HireBatch;

            $data = $user->select('fullname, role')->where('id', $decoded->uid)->first();

            if ($data['role'] != 'company') {

                return $this->fail('Hanya dapat diakses oleh perusahaan', 400);
            }


            $input_value = $this->request->getJSON();

            $fail = false;

            $message = '';

            if (!isset($input_value->position)) {

                $message = "Posisi tidak boleh kosong";

                $fail = true;
            }

            if (!isset($input_value->status)) {

                $message = "Status pekerjaan tidak boleh kosong";

                $fail = true;
            }

            if (!isset($input_value->method)) {

                $message = "Metode pekerjaan tidak boleh kosong";

                $fail = true;
            }

            if ($fail) {

                return $this->fail($message);
            }



            $input = [

                'company_id' => $decoded->uid,

                'position' => $input_value->position,

                'status' => $input_value->status,

                'method' => $input_value->method,

                'min_date' => $input_value->min_date ?? null,

                'max_date' => $input_value->max_date ?? null,

                'min_salary' => $input_value->min_salary ?? null,

                'max_salary' => $input_value->max_salary ?? null,

                'information' => $input_value->information ?? null,

                'result' => 'pending',

            ];

            $hire_ids = [];

            foreach ($input_value->user_id as $id) {

                $input['user_id'] = $id;

                $hire->save($input);

                if ($input_value->delete_batch) {

                    $hire_batch->where('user_id', $id)->where('company_id', $decoded->uid)->delete();
                }

                SendNotification(0, $id, $data['fullname'] . " mengundang Anda untuk bergabung bersama mereka sebagai " . $input['position'], 4);

                $hire_id = $hire->getInsertID();

                $hire_ids[] = $hire_id;
            }


            // sendWhatsappNotification();


            $response = [

                'status'   => 201,

                'success'    => 201,

                'messages' => [

                    'success' => 'Talent berhasil di hire'

                ],

                'data' => [

                    'hire_id' => $hire_ids,

                    'company_name' => $data['fullname'],

                    'position' => $input['position']

                ]

            ];

            return $this->respondCreated($response);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }



    public function notif()

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];


        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $user = new Users;

            $hire = new Hire;

            $notif = new OrderController();

            $input_value = $this->request->getJSON();

            $data = $user->select('fullname, role')->where('id', $decoded->uid)->first();

            if ($data['role'] != 'company') {

                return $this->fail('Hanya dapat diakses oleh perusahaan', 400);
            }


            $phone_numbers = $hire

                ->whereIn('hire_id', $input_value->hire_id)

                ->where('company_id', $decoded->uid)

                ->join('users', 'users.id=hire.user_id')

                ->select('users.phone_number, users.device_token')

                ->findAll();


            foreach ($phone_numbers as $item) {

                if (substr($item['phone_number'], 0, 2) === "08") {

                    $item['phone_number'] = "628" . substr($item['phone_number'], -strlen($item['phone_number']) + 2);
                } else if (substr($item['phone_number'], 0, 1) === "8") {

                    $item['phone_number'] = "628" . substr($item['phone_number'], -strlen($item['phone_number']) + 1);
                }

                if (substr($item['phone_number'], 0, 3) === "628") {

                    $url = 'https://wag.swevel.com/send-message';

                    $payload = json_encode([

                        'api_key' => getenv('wag_api_key'),

                        'sender' => getenv('wag_number'),

                        'number' => $item['phone_number'],

                        'message' => 'Kabar baik! *' . $input_value->company_name . '* mengundang Anda untuk bergabung bersama mereka sebagai *' . $input_value->position . '*. Segera kunjungi laman www.stufast.id untuk informasi lebih lanjut. Waktunya untuk mengambil kendali atas masa depan karirmu!'

                    ]);

                    $contextOptions = [

                        'http' => [

                            'header' => "Content-type: application/json\r\n",

                            'method' => 'POST',

                            'content' => $payload,

                        ],

                    ];

                    $context = stream_context_create($contextOptions);

                    file_get_contents($url, false, $context);

                    if ($item['device_token']) {

                        $notif->sendPush($item['device_token'], 'Kabar gembira!', $input_value->company_name . ' mengundang Anda untuk bergabung bersama mereka sebagai ' . $input_value->position, site_url() . 'image/logo.svg', site_url());
                    }

                    $delay = mt_rand(5, 10);

                    sleep($delay);
                }
            }

            $response = [

                'status'   => 201,

                'success'    => 201,

                'messages' => [

                    'success' => 'Notifikasi berhasil dikirim'

                ],

            ];

            return $this->respond($response);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }



    public function confirm()

    {

        include 'SendNotification.php';

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];


        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $user = new Users;

            $hire = new Hire;


            $data = $user->select('fullname, role, phone_number, email')->where('id', $decoded->uid)->first();

            if ($data['role'] != 'member') {

                return $this->fail('Hanya dapat diakses oleh member', 400);
            }


            $hire_id = $this->request->getVar('hire_id');

            $cek = $hire

                ->where('hire_id', $hire_id)

                ->where('user_id', $decoded->uid)

                ->join('users', 'users.id=hire.company_id')

                ->select('hire.*, users.phone_number')

                ->first();

            if (!$cek) {

                return $this->failNotFound('Data tidak ditemukan');
            }


            $input = [

                'result' => $this->request->getVar('result')

            ];

            $hire->update($hire_id, $input);


            if ($input['result'] == 'accept') {

                $waMessage = "Selamat! " . $data['fullname'] . " menerima undangan Anda untuk bergabung dalam posisi " . $cek['position'] . ". Berikut adalah kontak yang bisa anda hubungi :\nNo. Telepon : " . $data['phone_number'] . "\nEmail : " . $data['email'] . "\n\nKunjungi www.stufast.id untuk informasi lebih lanjut dan temui mereka di jalan menuju kesuksesan!";

                SendNotification(0, $cek['company_id'], $data['fullname'] . " menerima undangan Anda untuk bergabung dalam posisi " . $cek['position'], 4);
            } else {

                $waMessage = "Pemberitahuan Penting! " . $data['fullname'] . " menolak undangan Anda untuk bergabung dalam posisi " . $cek['position'] . ". Tetap positif dan temukan lebih banyak talent di www.stufast.id!";

                SendNotification(0, $cek['company_id'], $data['fullname'] . " menolak undangan Anda untuk bergabung dalam posisi " . $cek['position'], 5);
            }


            $response = [

                'status'   => 200,

                'success'    => 200,

                'messages' => [

                    'success' => 'Jawaban anda berhasil terkirim'

                ],

                'data' => [

                    'message' => $waMessage

                ]

            ];

            return $this->respond($response);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }



    public function confirmNotif()

    {

        include 'SendNotification.php';

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];


        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $user = new Users;

            $message = $this->request->getVar('message');


            $data = $user->select('fullname, role, phone_number')->where('id', $decoded->uid)->first();

            if ($data['role'] != 'member') {

                return $this->fail('Hanya dapat diakses oleh member', 400);
            }


            if (substr($data['phone_number'], 0, 2) === "08") {

                $data['phone_number'] = "628" . substr($data['phone_number'], -strlen($data['phone_number']) + 2);
            } else if (substr($data['phone_number'], 0, 1) === "8") {

                $data['phone_number'] = "628" . substr($data['phone_number'], -strlen($data['phone_number']) + 1);
            }

            if (substr($data['phone_number'], 0, 3) === "628") {

                $url = 'https://wag.swevel.com/send-message';

                $payload = json_encode([

                    'api_key' => getenv('wag_api_key'),

                    'sender' => getenv('wag_number'),

                    'number' => $data['phone_number'],

                    'message' => $message

                ]);

                $contextOptions = [

                    'http' => [

                        'header' => "Content-type: application/json\r\n",

                        'method' => 'POST',

                        'content' => $payload,

                    ],

                ];

                $context = stream_context_create($contextOptions);

                file_get_contents($url, false, $context);
            }


            $response = [

                'status'   => 200,

                'success'    => 200,

                'messages' => [

                    'success' => 'Notifikasi berhasil terkirim'

                ]

            ];

            return $this->respond($response);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }



    public function userHistory()

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];


        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $user = new Users;

            $hire = new Hire;

            $data = $user->select('fullname, role')->where('id', $decoded->uid)->first();

            if ($data['role'] != 'member') {

                return $this->fail('Hanya dapat diakses oleh member', 400);
            }

            if (!isset($_GET['filter'])) {

                $history = $hire

                    ->join('users', 'hire.company_id=users.id')

                    ->where('user_id', $decoded->uid)

                    ->select('hire.*, users.fullname, users.profile_picture, users.address')

                    ->orderBy('hire.updated_at', 'DESC')

                    ->findAll();
            } else {

                $history = $hire

                    ->join('users', 'hire.company_id=users.id')

                    ->where('user_id', $decoded->uid)

                    ->where('result', $_GET['filter'])

                    ->select('hire.*, users.fullname, users.profile_picture, users.address')

                    ->orderBy('hire.updated_at', 'DESC')

                    ->findAll();
            }

            foreach ($history as &$item) {

                $item['profile_picture'] = site_url() . 'upload/users/' . $item['profile_picture'];

                $item['information'] = $item['information'] ? $item['information'] : "-";


                if (($item['min_salary'] != 0) && ($item['max_salary'] != 0)) {

                    $item['range'] = 'Rp. ' . number_format($item['min_salary'], 0, ',', '.') . ' - Rp. ' . number_format($item['max_salary'], 0, ',', '.');
                } else if (($item['min_salary'] == 0) && ($item['max_salary'] != 0)) {

                    $item['range'] = '< Rp. ' . number_format($item['max_salary'], 0, ',', '.');
                } else if (($item['min_salary'] != 0) && ($item['max_salary'] == 0)) {

                    $item['range'] = '> Rp. ' . number_format($item['min_salary'], 0, ',', '.');
                } else {

                    $item['range'] = 'Menyesuaikan';
                }


                if (($item['min_date'] != "0000-00-00") && ($item['max_date'] != "0000-00-00")) {

                    $min_date = date("j F Y", strtotime($item['min_date']));

                    $max_date = date("j F Y", strtotime($item['max_date']));

                    $item['period'] = $min_date . ' - ' . $max_date;
                } else if (($item['min_date'] == "0000-00-00") && ($item['max_date'] != "0000-00-00")) {

                    $max_date = date("j F Y", strtotime($item['max_date']));

                    $item['period'] = 'Sampai ' . $max_date;
                } else if (($item['min_date'] != "0000-00-00") && ($item['max_date'] == "0000-00-00")) {

                    $min_date = date("j F Y", strtotime($item['min_date']));

                    $item['period'] = 'Mulai ' . $min_date;
                } else {

                    $item['period'] = 'Menyesuaikan';
                }
            }

            return $this->respond($history);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }



    public function companyHistory($filter = null)

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];


        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $user = new Users;

            $hire = new Hire;

            $data = $user->select('fullname, role')->where('id', $decoded->uid)->first();

            if ($data['role'] != 'company') {

                return $this->fail('Hanya dapat diakses oleh perusahaan', 400);
            }

            if (!isset($_GET['filter'])) {

                $history = $hire

                    ->join('users', 'hire.user_id=users.id')

                    ->where('company_id', $decoded->uid)

                    ->select('hire.*, users.fullname, users.profile_picture, users.address')

                    ->orderBy('hire.updated_at', 'DESC')

                    ->findAll();
            } else {

                $history = $hire

                    ->join('users', 'hire.user_id=users.id')

                    ->where('company_id', $decoded->uid)

                    ->where('result', $_GET['filter'])

                    ->select('hire.*, users.fullname, users.profile_picture, users.address')

                    ->orderBy('hire.updated_at', 'DESC')

                    ->findAll();
            }

            foreach ($history as &$item) {

                $item['profile_picture'] = site_url() . 'upload/users/' . $item['profile_picture'];

                $item['information'] = $item['information'] ? $item['information'] : "-";

                if (($item['min_salary'] != 0) && ($item['max_salary'] != 0)) {

                    $item['range'] = 'Rp. ' . number_format($item['min_salary'], 0, ',', '.') . ' - Rp. ' . number_format($item['max_salary'], 0, ',', '.');
                } else if (($item['min_salary'] == 0) && ($item['max_salary'] != 0)) {

                    $item['range'] = '< Rp. ' . number_format($item['max_salary'], 0, ',', '.');
                } else if (($item['min_salary'] != 0) && ($item['max_salary'] == 0)) {

                    $item['range'] = '> Rp. ' . number_format($item['min_salary'], 0, ',', '.');
                } else {

                    $item['range'] = 'Menyesuaikan';
                }
            }

            return $this->respond($history);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }
}
