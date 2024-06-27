<?php



namespace App\Controllers\Api;



use App\Controllers\Api\UserController as ApiUserController;

use CodeIgniter\RESTful\ResourceController;

use CodeIgniter\API\ResponseTrait;

use App\Models\Resume;

use App\Models\UserCourse;

use App\Models\Course;

use App\Models\Bundling;

use App\Models\CourseBundling;

use App\Models\Video;

use App\Models\UserVideo;

use App\Models\VideoCategory;

use App\Models\Users;

use App\Controllers\UserController;

use Firebase\JWT\JWT;

use DateTime;



class ResumeController extends ResourceController

{

    use ResponseTrait;



    public function __construct()

    {

        $this->resume = new Resume();

        $this->usercontroller = new ApiUserController();
    }



    public function index()

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

            if ($data['role'] != 'admin') {

                return $this->fail('Tidak dapat di akses selain admin', 400);
            }



            $path = site_url() . 'upload/course/resume/';



            $dataresume = $this->resume->findAll();



            for ($i = 0; $i < count($dataresume); $i++) {

                if ($dataresume[$i]['task'] != null) {

                    $dataresume[$i]['task'] = $path . $dataresume[$i]['task'];
                };
            }
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }

        return $this->respond($dataresume);
    }



    public function show($id = null)

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];



        try {

            $decoded = JWT::decode($token, $key, ['HS256']);



            $path = site_url() . 'upload/course/resume/';



            $data = $this->resume->where('resume_id', $id)->first();



            if ($data['task'] != null) {

                $data['task'] = $path . $data['task'];
            };



            if ($data) {

                return $this->respond($data);
            } else {

                return $this->failNotFound('Data Resume tidak ditemukan');
            }
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }

        return $this->failNotFound('Resume tidak ditemukan');
    }



    public function showadmin($id = null)

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

            if ($data['role'] != 'admin') {

                return $this->fail('Tidak dapat di akses selain admin', 400);
            }



            $data = $this->resume->where('resume_id', $id)->first();



            if ($data) {

                return $this->respond($data);
            } else {

                return $this->failNotFound('Data Resume tidak ditemukan');
            }
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }

        return $this->failNotFound('Resume tidak ditemukan');
    }



    public function create()

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];



        try {

            $decoded = JWT::decode($token, $key, ['HS256']);



            $rules_a = [

                'video_id' => 'required',

                'resume' => 'required|min_length[100]|max_length[1000]'

            ];



            $rules_b = [

                "task" => "uploaded[task]|max_size[task,262144]",

            ];



            $messages_a = [

                "video_id" => [

                    "required" => "{field} tidak boleh kosong",

                ],

                "resume" => [

                    "required" => "{field} tidak boleh kosong",

                    "min_length" => "{field} minimal 100 karakter",

                    "max_length" => "{field} maksimal 1000 karakter",

                ],

            ];



            $messages_b = [

                "task" => [

                    'uploaded' => '{field} tidak boleh kosong',

                    'max_size' => 'Ukuran {field} Maksimal 256 MB'

                ],

            ];


            $resume_exist = $this->resume->where('video_id', $this->request->getVar('video_id'))->where('user_id', $decoded->uid)->first();

            if ($resume_exist) {

                return $this->fail("Data resume sudah ada");
            }


            if (null !== $this->request->getVar('empty') && $this->request->getVar('empty') == 1) {

                $dataresume = [

                    'video_id' => $this->request->getVar('video_id'),

                    'user_id' => $decoded->uid,

                    'resume' => '',

                ];

                $this->resume->insert($dataresume);

                $response = [

                    'status'   => 201,

                    'success'    => 201,

                    'messages' => [

                        'success' => 'Resume berhasil dibuat'

                    ]

                ];
            } else {

                if ($this->validate($rules_a, $messages_a) == TRUE && $this->validate($rules_b, $messages_b) == FALSE) {

                    $dataresume = [

                        'video_id' => $this->request->getVar('video_id'),

                        'user_id' => $decoded->uid,

                        'resume' => $this->request->getVar('resume'),

                    ];

                    $this->resume->insert($dataresume);



                    $response = [

                        'status'   => 201,

                        'success'    => 201,

                        'messages' => [

                            'success' => 'Resume berhasil dibuat'

                        ]

                    ];
                } elseif ($this->validate($rules_a, $messages_a) == TRUE && $this->validate($rules_b, $messages_b) == TRUE) {

                    $datatask = $this->request->getFile('task');

                    $fileName = $datatask->getRandomName();

                    $dataresume = [

                        'video_id' => $this->request->getVar('video_id'),

                        'user_id' => $decoded->uid,

                        'resume' => $this->request->getVar('resume'),

                        'task' => $fileName,

                    ];

                    $datatask->move('upload/course/resume/', $fileName);

                    $this->resume->insert($dataresume);



                    $response = [

                        'status'   => 201,

                        'success'    => 201,

                        'messages' => [

                            'success' => 'Resume berhasil dibuat'

                        ]

                    ];
                } else {

                    $response = [

                        'status'   => 400,

                        'error'    => 400,

                        'messages' => $this->validator->getErrors(),

                    ];
                }
            }
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }

        return $this->respondCreated($response);
    }



    // public function create_2()

    // {

    //     $key = getenv('TOKEN_SECRET');

    //     $header = $this->request->getServer('HTTP_AUTHORIZATION');

    //     if (!$header) return $this->failUnauthorized('Akses token diperlukan');

    //     $token = explode(' ', $header)[1];



    //     try {

    //         $decoded = JWT::decode($token, $key, ['HS256']);



    //         $rules_a = [

    //             'video_id' => 'required',

    //             'resume' => 'required|min_length[100]|max_length[3000]'

    //         ];



    //         $rules_b = [

    //             "task" => "uploaded[task]|max_size[task,262144]",

    //         ];



    //         $messages_a = [

    //             "video_id" => [

    //                 "required" => "{field} tidak boleh kosong",

    //             ],

    //             "resume" => [

    //                 "required" => "{field} tidak boleh kosong",

    //                 "min_length" => "{field} minimal 100 karakter",

    //                 "max_length" => "{field} maksimal 3000 karakter",

    //             ],

    //         ];



    //         $messages_b = [

    //             "task" => [

    //                 'uploaded' => '{field} tidak boleh kosong',

    //                 'max_size' => 'Ukuran {field} Maksimal 256 MB'

    //             ],

    //         ];


    //         $resume_exist = $this->resume->where('video_id', $this->request->getVar('video_id'))->where('user_id', $decoded->uid)->first();

    //         if ($resume_exist) {

    //             return $this->fail("Data resume sudah ada");
    //         }



    //         if (null !== $this->request->getVar('empty') && $this->request->getVar('empty') == 1) {

    //             $dataresume = [

    //                 'video_id' => $this->request->getVar('video_id'),

    //                 'user_id' => $decoded->uid,

    //                 'resume' => '',

    //             ];

    //             $this->resume->insert($dataresume);

    //             $response = [

    //                 'status'   => 201,

    //                 'success'    => 201,

    //                 'messages' => [

    //                     'success' => 'Resume berhasil dibuat'

    //                 ]

    //             ];
    //         } else {

    //             if ($this->validate($rules_a, $messages_a) == TRUE && $this->validate($rules_b, $messages_b) == FALSE) {

    //                 $dataresume = [

    //                     'video_id' => $this->request->getVar('video_id'),

    //                     'user_id' => $decoded->uid,

    //                     'resume' => $this->request->getVar('resume'),

    //                 ];

    //                 $this->resume->insert($dataresume);



    //                 $response = [

    //                     'status'   => 201,

    //                     'success'    => 201,

    //                     'messages' => [

    //                         'success' => 'Resume berhasil dibuat'

    //                     ]

    //                 ];
    //             } elseif ($this->validate($rules_a, $messages_a) == TRUE && $this->validate($rules_b, $messages_b) == TRUE) {

    //                 $datatask = $this->request->getFile('task');

    //                 $fileName = $datatask->getRandomName();

    //                 $dataresume = [

    //                     'video_id' => $this->request->getVar('video_id'),

    //                     'user_id' => $decoded->uid,

    //                     'resume' => $this->request->getVar('resume'),

    //                     'task' => $fileName,

    //                 ];

    //                 $datatask->move('upload/course/resume/', $fileName);

    //                 $this->resume->insert($dataresume);



    //                 $response = [

    //                     'status'   => 201,

    //                     'success'    => 201,

    //                     'messages' => [

    //                         'success' => 'Resume berhasil dibuat'

    //                     ]

    //                 ];
    //             } else {

    //                 $response = [

    //                     'status'   => 400,

    //                     'error'    => 400,

    //                     'messages' => $this->validator->getErrors(),

    //                 ];
    //             }
    //         }
    //     } catch (\Throwable $th) {

    //         return $this->fail($th->getMessage());
    //     }

    //     return $this->respondCreated($response);
    // }



    public function update($id = null)

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];



        try {

            $decoded = JWT::decode($token, $key, ['HS256']);



            // $input = $this->request->getRawInput();



            $rules = [

                'video_id' => 'required',

                'resume' => 'required|min_length[100]|max_length[3000]'

            ];



            $messages = [

                "video_id" => [

                    "required" => "{field} tidak boleh kosong",

                ],

                "resume" => [

                    "required" => "{field} tidak boleh kosong",

                    "min_length" => "{field} minimal 100 karakter",

                    "max_length" => "{field} maksimal 3000 karakter",

                ],

            ];



            $data = [

                "video_id" => $this->request->getVar('video_id'),

                "user_id" => $decoded->uid,

                "resume" => $this->request->getVar('resume'),

            ];



            $response = [

                'status'   => 200,

                'error'    => null,

                'messages' => [

                    'success' => 'Resume berhasil diperbarui'

                ]

            ];



            $cek = $this->resume->where('resume_id', $id)->findAll();



            if (!$cek) {

                return $this->failNotFound('Data Resume tidak ditemukan');
            }



            if (!$this->validate($rules, $messages)) {

                return $this->failValidationErrors($this->validator->getErrors());
            }



            if ($this->resume->update($id, $data)) {

                return $this->respond($response);
            }
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }

        return $this->failNotFound('Data Resume tidak ditemukan');
    }



    public function getSertifikat($user_id, $type, $id)

    {

        $modelUserCourse = new UserCourse();

        $modelCourseBundling = new CourseBundling;

        $modelVideoCategory = new VideoCategory;

        $modelCourse = new Course;

        $modelBundling = new Bundling;

        $modelUser = new Users;

        try {

            if ($type == 'course') {

                $course = $modelCourse

                    ->where('course_id', $id)

                    ->select('title')

                    ->first();

                $user = $modelUser

                    ->where('id', $user_id)

                    ->select('fullname')

                    ->first();

                $course['fullname'] = $user['fullname'];


                $item = $modelVideoCategory

                    ->where('video_category.course_id', $id)

                    ->where('user_video.user_id', $user_id)

                    ->join('video', 'video.video_category_id=video_category.video_category_id')

                    ->join('user_video', 'user_video.video_id=video.video_id')

                    ->orderBy('video.order', 'ASC')

                    ->select('video.title, user_video.score, user_video.updated_at')

                    ->findAll();


                $total_item = $modelVideoCategory

                    ->where('video_category.course_id', $id)

                    ->join('video', 'video.video_category_id=video_category.video_category_id')

                    ->orderBy('video.order', 'ASC')

                    ->select('video.title')

                    ->findAll();


                if (count($item) != count($total_item)) {

                    return $data = [

                        'status' => 'error',

                        'message' => 'Anda belum menyelesaikan course ini'

                    ];
                }


                $final_score = 0;

                $finished_at = null;

                $i = 1;

                foreach ($item as $value) {

                    if ($i == 1) $course['buy_at'] = $value['updated_at'];

                    $final_score += $value['score'];

                    $finished_at = $value['updated_at'];

                    $i++;
                }

                $buy = new DateTime($course['buy_at']);

                $finish = new DateTime($finished_at);

                $course['buy_at'] = $buy->format('d F Y');

                $course['finished_at'] = $finish->format('d F Y');

                $final_score /= count($item);

                $course['score'] = ceil($final_score);

                $course['type'] = 'Kursus';

                $course['item'] = $item;

                $data = [

                    'status' => 'success',

                    'message' => 'Data ditemukan',

                    'data' => $course

                ];
            } else if ($type == 'bundling') {

                $bundling = $modelBundling

                    ->where('bundling_id', $id)

                    ->select('title')

                    ->first();

                $user = $modelUser

                    ->where('id', $user_id)

                    ->select('fullname')

                    ->first();

                $bundling['fullname'] = $user['fullname'];


                $item = $modelCourseBundling

                    ->where('course_bundling.bundling_id', $id)

                    ->join('course', 'course.course_id=course_bundling.course_id')

                    ->select('course.course_id, course.title')

                    ->orderBy('course_bundling.order', 'ASC')

                    ->findAll();


                $finished_at = null;


                for ($i = 0; $i < count($item); $i++) {

                    $course_bundling = $item[$i];

                    $item_course = $modelVideoCategory

                        ->where('video_category.course_id', $course_bundling['course_id'])

                        ->where('user_video.user_id', $user_id)

                        ->join('video', 'video.video_category_id=video_category.video_category_id')

                        ->join('user_video', 'user_video.video_id=video.video_id')

                        ->orderBy('video.order', 'ASC')

                        ->select('video.title, user_video.score, user_video.updated_at')

                        ->findAll();


                    $total_item = $modelVideoCategory

                        ->where('video_category.course_id', $course_bundling['course_id'])

                        ->join('video', 'video.video_category_id=video_category.video_category_id')

                        ->orderBy('video.order', 'ASC')

                        ->select('video.title')

                        ->findAll();


                    if (count($item_course) != count($total_item)) {

                        return $data = [

                            'status' => 'error',

                            'message' => 'Anda belum menyelesaikan bundling ini'

                        ];
                    }


                    $item[$i]['score'] = 0;

                    $j = 0;

                    foreach ($item_course as $value) {

                        if ($i == 0 && $j == 0) $bundling['buy_at'] = $value['updated_at'];

                        $item[$i]['score'] += $value['score'];

                        $finished_at = $value['updated_at'];

                        $j++;
                    }

                    $item[$i]['score'] = ceil($item[$i]['score'] / count($item_course));
                }

                $score = 0;

                foreach ($item as $value) {

                    $score += $value['score'];
                }

                $buy = new DateTime($bundling['buy_at']);

                $finish = new DateTime($finished_at);

                $bundling['buy_at'] = $buy->format('d F Y');

                $bundling['finished_at'] = $finish->format('d F Y');

                $score /= count($item);

                $bundling['score'] = ceil($score);

                $bundling['type'] = 'Bundling';

                $bundling['item'] = $item;

                $data = [

                    'status' => 'success',

                    'message' => 'Data ditemukan',

                    'data' => $bundling

                ];
            } else {

                return $data = [

                    'status' => 'error',

                    'message' => 'Data tidak ditemukan'

                ];
            }

            return $data;
        } catch (\Throwable $th) {

            return $th;
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



            $data = $this->resume->where('resume_id', $id)->findAll();

            if ($data) {

                $this->resume->delete($id);

                $response = [

                    'status'   => 200,

                    'error'    => null,

                    'messages' => [

                        'success' => 'Resume berhasil dihapus'

                    ]

                ];
            }

            return $this->respondDeleted($response);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }

        return $this->failNotFound('Data resume tidak ditemukan');
    }
}
