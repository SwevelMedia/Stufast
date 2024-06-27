<?php



namespace App\Controllers\Api;



use CodeIgniter\API\ResponseTrait;

use App\Controllers\BaseController;

use App\Models\HireBatch;

use App\Models\Users;

use Firebase\JWT\JWT;



class HireBatchController extends BaseController

{

    use ResponseTrait;


    public function index()

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];


        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $user = new Users;

            $hire_batch = new HireBatch;

            $data = $user->select('fullname, role')->where('id', $decoded->uid)->first();

            if ($data['role'] != 'company') {

                return $this->fail('Hanya dapat diakses oleh perusahaan', 400);
            }

            $batch = $hire_batch

                ->join('users', 'hire_batch.user_id=users.id')

                ->join('user_cv', 'hire_batch.user_id=user_cv.user_id', 'left')

                ->where('company_id', $decoded->uid)

                ->select('hire_batch.hire_batch_id, hire_batch.user_id, users.fullname, users.profile_picture, user_cv.status, user_cv.method, user_cv.min_salary, user_cv.max_salary')

                ->orderBy('hire_batch.created_at', 'DESC')

                ->findAll();

            foreach ($batch as &$item) {

                if ($item['fullname'] != null) {

                    $item['fullname'] = ucwords(strtolower($item['fullname']));
                } else {

                    $item['fullname'] = '-';
                }

                $item['profile_picture'] = site_url() . 'upload/users/' . $item['profile_picture'];

                if ($item['status'] == null) $item['status'] = 'Pegawai Tetap atau Freelance';

                if ($item['method'] == null) $item['method'] = 'WFO atau Remote';

                if (($item['min_salary'] != null && $item['min_salary'] != 0) && ($item['max_salary'] != null && $item['max_salary'] != 0)) {

                    $item['range'] = 'Rp. ' . number_format($item['min_salary'], 0, ',', '.') . ' - Rp. ' . number_format($item['max_salary'], 0, ',', '.');
                } else if (($item['min_salary'] == null || $item['min_salary'] == 0) && ($item['max_salary'] != null || $item['max_salary'] != 0)) {

                    $item['range'] = '< Rp. ' . number_format($item['max_salary'], 0, ',', '.');
                } else if (($item['min_salary'] != null || $item['min_salary'] != 0) && ($item['max_salary'] == null || $item['max_salary'] == 0)) {

                    $item['range'] = '> Rp. ' . number_format($item['min_salary'], 0, ',', '.');
                } else {

                    $item['range'] = 'Menyesuaikan';
                }
            }

            return $this->respond($batch);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }

    public function create()

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];


        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $user = new Users;

            $hire_batch = new HireBatch;

            $user_id = $this->request->getVar('user_id');


            $data = $user->select('fullname, role')->where('id', $decoded->uid)->first();

            if ($data['role'] != 'company') {

                return $this->fail('Hanya dapat diakses oleh perusahaan', 400);
            }


            $cek = $hire_batch->where('user_id', $user_id)->where('company_id', $decoded->uid)->first();

            if ($cek) {

                return $this->fail('Talent sudah ditambahkan', 400);
            }


            $input = [

                'user_id' => $user_id,

                'company_id' => $decoded->uid

            ];

            $hire_batch->save($input);


            $response = [

                'status'   => 201,

                'success'    => 201,

                'messages' => [

                    'success' => 'Talent berhasil di tambahkan'

                ]

            ];

            return $this->respondCreated($response);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }


    public function delete()

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];


        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $user = new Users;

            $hire_batch = new HireBatch;

            $id = $this->request->getVar('id');


            $data = $user->select('fullname, role')->where('id', $decoded->uid)->first();

            if ($data['role'] != 'company') {

                return $this->fail('Hanya dapat diakses oleh perusahaan', 400);
            }


            $cek = $hire_batch->where('hire_batch_id', $id)->first();

            if ($cek) {

                if ($cek['company_id'] != $decoded->uid) {

                    return $this->fail('Anda tidak memiliki hak akses terhadap item ini.');
                }

                $hire_batch->delete($id);

                $response = [

                    'status'   => 200,

                    'success'    => 200,

                    'messages' => [

                        'success' => 'Talent berhasil di hapus'

                    ]

                ];

                return $this->respondDeleted($response);
            } else {

                return $this->fail('Data tidak ditemukan');
            }
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }
}
