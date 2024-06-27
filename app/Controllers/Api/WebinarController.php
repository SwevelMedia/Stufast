<?php



namespace App\Controllers\Api;



use CodeIgniter\RESTful\ResourceController;

use CodeIgniter\API\ResponseTrait;

use App\Models\Webinar;

use App\Models\UserWebinar;

use App\Models\Cart;

use App\Models\Users;

use Firebase\JWT\JWT;
use Tests\Support\Entity\User;

class WebinarController extends ResourceController

{

    use ResponseTrait;



    public function __construct()

    {

        $this->webinar = new Webinar();

        $this->user_webinar = new UserWebinar();
    }



    public function index()

    {

        $path = site_url() . 'upload/webinar/';

        $data = $this->webinar->findAll();

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if ($header) {

            $token = explode(' ', $header)[1];

            $decoded = JWT::decode($token, $key, ['HS256']);
        }

        for ($i = 0; $i < count($data); $i++) {

            $data[$i]['thumbnail'] = $path . $data[$i]['thumbnail'];

            $data[$i]['owned'] = false;

            if ($header) {

                $owned = $this->user_webinar

                    ->where('user_id', $decoded->uid)

                    ->where('webinar_id', $data[$i]['webinar_id'])

                    ->first();

                if ($owned) {

                    $data[$i]['owned'] = true;
                }
            }
        }



        return $this->respond($data);
    }



    public function userWebinar()

    {

        $path = site_url() . 'upload/webinar/';

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];

        $decoded = JWT::decode($token, $key, ['HS256']);

        $data = $this->webinar

            ->select('webinar.*, user_webinar.user_id')

            ->join('user_webinar', 'user_webinar.webinar_id = webinar.webinar_id')

            ->where('user_id', $decoded->uid)

            ->findAll();

        for ($i = 0; $i < count($data); $i++) {

            $data[$i]['thumbnail'] = $path . $data[$i]['thumbnail'];
        }



        return $this->respond($data);
    }



    public function show($id = null)

    {

        $path = site_url() . 'upload/webinar/';

        $data = $this->webinar

            ->select('webinar.*, tag.name as tag_name')

            ->join('tag', 'tag.tag_id=webinar.tag_id')

            ->where('webinar_id', $id)

            ->first();

        if ($data) {

            $data['thumbnail'] = $path . $data['thumbnail'];

            $data['tax'] = (empty($data['new_price'])) ? $data['old_price'] * 0.11 : $data['new_price'] * 0.11;

            return $this->respond($data);
        } else {

            return $this->failNotFound('Data Webinar tidak ditemukan');
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



            // cek role user

            $data = $user->select('role')->where('id', $decoded->uid)->first();

            if ($data['role'] == 'member') {

                return $this->fail('Tidak dapat di akses oleh member', 400);
            }



            $rules = [

                'title' => 'required|min_length[8]',

                'webinar_type' => 'required',

                'description' => 'required|min_length[8]',

                'tag_id' => 'required',

                'old_price' => 'required|numeric',

                'new_price' => 'permit_empty|numeric',

                'thumbnail' => 'uploaded[thumbnail]'

                    . '|is_image[thumbnail]'

                    . '|mime_in[thumbnail,image/jpg,image/jpeg,image/png,image/webp]'

                    . '|max_size[thumbnail,4000]'

            ];



            $messages = [

                "title" => [

                    "required" => "{field} tidak boleh kosong",

                    'min_length' => '{field} minimal 8 karakter'

                ],

                "webinar_type" => [

                    "required" => "{field} tidak boleh kosong",

                ],

                "description" => [

                    "required" => "{field} tidak boleh kosong",

                    'min_length' => '{field} minimal 8 karakter'

                ],

                "tag_id" => [

                    "required" => "{field} tidak boleh kosong",

                ],

                "old_price" => [

                    "required" => "{field} tidak boleh kosong",

                    "numeric" => "{field} harus berisi nomor",

                ],

                "new_price" => [

                    "numeric" => "{field} harus berisi nomor",

                ],

                "thumbnail" => [

                    'uploaded' => '{field} tidak boleh kosong',

                    'mime_in' => 'File Extention Harus Berupa png, jpg, atau jpeg',

                    'max_size' => 'Ukuran File Maksimal 4 MB'

                ],

            ];



            if ($this->validate($rules, $messages)) {

                $dataThumbnail = $this->request->getFile('thumbnail');

                $fileName = $dataThumbnail->getRandomName();

                $dataWebinar = [

                    'author_id' => $decoded->uid,

                    'title' => $this->request->getVar('title'),

                    'webinar_type' => $this->request->getVar('webinar_type'),

                    'description' => $this->request->getVar('description'),

                    'tag_id' => $this->request->getVar('tag_id'),

                    'old_price' => $this->request->getVar('old_price'),

                    'new_price' => $this->request->getVar('new_price'),

                    'thumbnail' => $fileName,

                ];

                $dataThumbnail->move('upload/webinar/', $fileName);

                $this->webinar->save($dataWebinar);



                $response = [

                    'status'   => 201,

                    'success'    => 201,

                    'messages' => [

                        'success' => 'Webinar berhasil dibuat'

                    ]

                ];
            } else {

                $response = [

                    'status'   => 400,

                    'error'    => 400,

                    'messages' => $this->validator->getErrors(),

                ];
            }



            return $this->respondCreated($response);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }



    public function update($id = null)

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];

        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $user = new Users;



            // cek role user

            $data = $user->select('role')->where('id', $decoded->uid)->first();

            if ($data['role'] == 'member') {

                return $this->fail('Tidak dapat di akses oleh member', 400);
            }



            $rules_a = [

                'title' => 'required|min_length[8]',

                'webinar_type' => 'required',

                'description' => 'required|min_length[8]',

                'tag_id' => 'required',

                'old_price' => 'required|numeric',

                'new_price' => 'permit_empty|numeric',

            ];



            $rules_b = [

                'thumbnail' => 'uploaded[thumbnail]'

                    . '|is_image[thumbnail]'

                    . '|mime_in[thumbnail,image/jpg,image/jpeg,image/png,image/webp]'

                    . '|max_size[thumbnail,4000]'

            ];



            $messages_a = [

                "title" => [

                    "required" => "{field} tidak boleh kosong",

                    'min_length' => '{field} minimal 8 karakter'

                ],

                "webinar_type" => [

                    "required" => "{field} tidak boleh kosong",

                ],

                "description" => [

                    "required" => "{field} tidak boleh kosong",

                    'min_length' => '{field} minimal 8 karakter'

                ],

                "tag_id" => [

                    "required" => "{field} tidak boleh kosong",

                ],

                "old_price" => [

                    "required" => "{field} tidak boleh kosong",

                    "numeric" => "{field} harus berisi nomor",

                ],

                "new_price" => [

                    "numeric" => "{field} harus berisi nomor",

                ],

            ];



            $messages_b = [

                "thumbnail" => [

                    'uploaded' => '{field} tidak boleh kosong',

                    'mime_in' => 'File Extention Harus Berupa png, jpg, atau jpeg',

                    'max_size' => 'Ukuran File Maksimal 4 MB'

                ],

            ];



            $findWebinar = $this->webinar->where('webinar_id', $id)->first();

            if ($findWebinar) {

                if ($this->validate($rules_a, $messages_a)) {

                    if ($this->validate($rules_b, $messages_b)) {



                        $oldThumbnail = $findWebinar['thumbnail'];

                        $dataThumbnail = $this->request->getFile('thumbnail');



                        if ($dataThumbnail->isValid() && !$dataThumbnail->hasMoved()) {

                            if (file_exists("upload/webinar/" . $oldThumbnail)) {

                                unlink("upload/webinar/" . $oldThumbnail);
                            }

                            $fileName = $dataThumbnail->getRandomName();

                            $dataThumbnail->move('upload/webinar/', $fileName);
                        } else {

                            $fileName = $oldThumbnail['thumbnail'];
                        }



                        $dataWebinar = [

                            'author_id' => $decoded->uid,

                            'title' => $this->request->getVar('title'),

                            'webinar_type' => $this->request->getVar('webinar_type'),

                            'description' => $this->request->getVar('description'),

                            'tag_id' => $this->request->getVar('tag_id'),

                            'old_price' => $this->request->getVar('old_price'),

                            'new_price' => $this->request->getVar('new_price'),

                            'thumbnail' => $fileName,

                        ];



                        $this->webinar->update($id, $dataWebinar);



                        $response = [

                            'status'   => 201,

                            'success'    => 201,

                            'messages' => [

                                'success' => 'Webinar berhasil diubah'

                            ]

                        ];
                    }



                    $dataWebinar = [

                        'author_id' => $decoded->uid,

                        'title' => $this->request->getVar('title'),

                        'webinar_type' => $this->request->getVar('webinar_type'),

                        'description' => $this->request->getVar('description'),

                        'tag_id' => $this->request->getVar('tag_id'),

                        'old_price' => $this->request->getVar('old_price'),

                        'new_price' => $this->request->getVar('new_price'),

                    ];



                    $this->webinar->update($id, $dataWebinar);



                    $response = [

                        'status'   => 201,

                        'success'    => 201,

                        'messages' => [

                            'success' => 'Webinar berhasil diubah'

                        ]

                    ];
                } else {

                    $response = [

                        'status'   => 400,

                        'error'    => 400,

                        'messages' => $this->validator->getErrors(),

                    ];
                }
            } else {

                $response = [

                    'status'   => 400,

                    'error'    => 400,

                    'messages' => 'Data tidak ditemukan',

                ];
            }



            return $this->respondCreated($response);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }



    public function delete($id = null)

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];

        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $user = new Users;

            $cart = new Cart;

            $userWebinar = new UserWebinar;



            // cek role user

            $data = $user->select('role')->where('id', $decoded->uid)->first();

            if ($data['role'] == 'member') {

                return $this->fail('Tidak dapat di akses oleh member', 400);
            }



            if ($this->webinar->find($id)) {

                $findCart = $cart->where('webinar_id', $id)->findAll();

                if ($findCart) {

                    foreach ($findCart as $data) {

                        $cart->delete($data['cart_id']);
                    }
                }

                $findUserWebinar = $userWebinar->where('webinar_id', $id)->findAll();

                if ($findUserWebinar) {

                    foreach ($findUserWebinar as $data) {

                        $userWebinar->delete($data['user_webinar_id']);
                    }
                }

                $this->webinar->delete($id);

                $response = [

                    'status'   => 200,

                    'success'    => 200,

                    'messages' => [

                        'success' => 'Webinar berhasil di hapus'

                    ]

                ];

                return $this->respondDeleted($response);
            } else {

                return $this->failNotFound('Data tidak di temukan');
            }
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }
}
