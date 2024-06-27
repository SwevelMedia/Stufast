<?php



namespace App\Controllers\Api;



use CodeIgniter\RESTful\ResourceController;

use CodeIgniter\API\ResponseTrait;

use App\Models\Bundling;

use App\Models\Course;

use App\Models\UserCourse;

use App\Models\CourseCategory;

use App\Models\CourseBundling;

use App\Models\CourseTag;

use App\Models\Video;

use App\Models\VideoCategory;

use App\Models\Users;

use App\Models\Cart;

use App\Models\Review;

use App\Models\UserVideo;

use Firebase\JWT\JWT;

use getID3;



class BundlingController extends ResourceController

{

    use ResponseTrait;

    protected $bundling;
    protected $pathbundling;
    protected $pathcourse;
    protected $key;


    public function __construct()
    {
        $this->bundling = new Bundling();

        $this->pathbundling = site_url() . 'upload/bundling/';

        $this->pathcourse = site_url() . 'upload/course/thumbnail/';

        $this->key = getenv('TOKEN_SECRET');
    }



    public function index()
    {
        $modelBundling = new Bundling();

        $bundling = $this->bundling
            ->join('category_bundling', 'bundling.category_bundling_id = category_bundling.category_bundling_id')
            ->join('users', 'bundling.author_id = users.id')
            ->select('bundling.*, category_bundling.name as category_name, users.fullname as author_name, users.company as author_company')
            ->where('author_id', 3)
            ->limit(1)
            ->get()
            ->getResultArray();



        for ($i = 0; $i < count($bundling); $i++) {

            $bundling[$i]['thumbnail'] = $this->pathbundling . $bundling[$i]['thumbnail'];
        }



        $data['bundling'] = $bundling;



        for ($x = 0; $x < count($bundling); $x++) {

            $course = $modelBundling

                ->where('bundling.bundling_id', $bundling[$x]['bundling_id'])

                ->where('course_bundling.deleted_at', null)

                ->join('course_bundling', 'bundling.bundling_id=course_bundling.bundling_id')

                ->join('course', 'course_bundling.course_id=course.course_id')

                ->select('course.*')

                ->findAll();



            for ($i = 0; $i < count($course); $i++) {

                $course[$i]['thumbnail'] = $this->pathcourse . $course[$i]['thumbnail'];
            }



            $data['bundling'][$x]['course'] = $course;
        }



        if (count($data) > 0) {

            return $this->respond($data);
        } else {

            return $this->failNotFound('Tidak ada data');
        }
    }



    public function all()

    {

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if ($header) {

            $token = explode(' ', $header)[1];

            $decoded = JWT::decode($token, $this->key, ['HS256']);
        }

        $modelBundling = new Bundling();

        $modelVideo = new Video();

        $modelUserReview = new Review();

        $modelUserVideo = new UserVideo();

        $modelCourseTag = new CourseTag();

        $modelUserBundling = new UserCourse();

        $modelVideoCategory = new VideoCategory();

        $modelBundling = new Bundling();



        $bundling = $this->bundling

            ->join('category_bundling', 'bundling.category_bundling_id = category_bundling.category_bundling_id')

            ->join('users', 'bundling.author_id = users.id')

            ->select('bundling.*, category_bundling.name as category_name, users.fullname as author_name, users.company as author_company')

            ->findAll();



        for ($i = 0; $i < count($bundling); $i++) {

            $bundling[$i]['thumbnail'] = $this->pathbundling . $bundling[$i]['thumbnail'];
        }



        $data['bundling'] = $bundling;



        for ($x = 0; $x < count($bundling); $x++) {

            $course = $modelBundling

                ->where('bundling.bundling_id', $bundling[$x]['bundling_id'])

                ->where('course_bundling.deleted_at', null)

                ->join('course_bundling', 'bundling.bundling_id=course_bundling.bundling_id')

                ->join('course', 'course_bundling.course_id=course.course_id')

                ->select('course.*')

                ->findAll();


            $progress = 0;

            $check_lolos_raw = [];

            for ($i = 0; $i < count($course); $i++) {

                $course[$i]['thumbnail'] = $this->pathcourse . $course[$i]['thumbnail'];

                if ($header) {

                    $videoCategory = $modelVideoCategory->where('course_id', $course[$i]['course_id'])->first();

                    $video = $modelVideo->where('video_category_id', $videoCategory['video_category_id'])->findAll();



                    $total_video_yang_dikerjakan = 0;

                    $dari = count($video);

                    foreach ($video as $key => $video_) {

                        $userVideo = $modelUserVideo->where('user_id', $decoded->uid)->where('video_id', $video_['video_id'])->first();



                        if ($userVideo) {

                            $total_video_yang_dikerjakan++;
                        }
                    }


                    if ($total_video_yang_dikerjakan == count($video)) {

                        $progress++;
                    }
                }
            }

            $data['bundling'][$x]['progress'] = $progress . ' / ' . count($course);

            $data['bundling'][$x]['course_bundling'] = $course;
        }



        if (count($data) > 0) {

            return $this->respond($data);
        } else {

            return $this->failNotFound('Tidak ada data');
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

                "category_bundling_id" => "required",

                "title" => "required",

                "description" => "required|max_length[255]",

                "old_price" => "required|numeric",

                "new_price" => "required|numeric",

                'thumbnail' => 'uploaded[thumbnail]'

                    . '|is_image[thumbnail]'

                    . '|mime_in[thumbnail,image/jpg,image/jpeg,image/png,image/webp]'

                    . '|max_size[thumbnail,4000]'

            ];



            $messages = [

                "category_bundling_id" => [

                    "required" => "{field} tidak boleh kosong"

                ],

                "title" => [

                    "required" => "{field} tidak boleh kosong"

                ],

                "description" => [

                    "required" => "{field} tidak boleh kosong",

                    "max_length" => "{field} maksimal 255 karakter",

                ],

                "new_price" => [

                    "required" => "{field} tidak boleh kosong",

                    "numeric" => "{field} harus berisi angka"

                ],

                "old_price" => [

                    "required" => "{field} tidak boleh kosong",

                    "numeric" => "{field} harus berisi angka"

                ],

                "thumbnail" => [

                    'uploaded' => '{field} tidak boleh kosong',

                    'mime_in' => 'File Extention Harus Berupa png, jpg, atau jpeg',

                    'max_size' => 'Ukuran File Maksimal 4 MB'

                ],

            ];



            if (!$this->validate($rules, $messages)) {

                $response = [

                    'status' => 500,

                    'error' => true,

                    'message' => $this->validator->getErrors(),

                    'data' => []

                ];
            } else {

                $dataThumbnail = $this->request->getFile('thumbnail');

                $fileName = $dataThumbnail->getRandomName();



                $data = [

                    'category_bundling_id' => $this->request->getVar("category_bundling_id"),

                    'title' => $this->request->getVar("title"),

                    'description' => $this->request->getVar("description"),

                    'new_price' => $this->request->getVar("new_price"),

                    'old_price' => $this->request->getVar("old_price"),

                    'author_id' => $decoded->uid,

                    'thumbnail' => $fileName,

                ];



                $dataThumbnail->move('upload/bundling/', $fileName);

                $this->bundling->save($data);



                $bundling_id = $this->bundling->getInsertID();



                $response = [

                    'status' => 200,

                    'error' => false,

                    'message' => 'Bundling berhasil dibuat',

                    'data' => ['bundling_id' => $bundling_id]

                ];
            }
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }

        return $this->respondCreated($response);
    }



    public function show($id = null)

    {

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if ($header) {

            $token = explode(' ', $header)[1];

            $decoded = JWT::decode($token, $this->key, ['HS256']);
        }

        $modelBundling = new Bundling();

        $modelVideo = new Video();

        $modelUserReview = new Review();

        $modelUserVideo = new UserVideo();

        $modelCourseTag = new CourseTag();

        $modelUserBundling = new UserCourse();

        $modelVideoCategory = new VideoCategory();

        include_once('getid3/getid3.php');

        $getID3 = new getID3;



        $path_bundling = site_url() . 'upload/bundling/';

        $path_course = site_url() . 'upload/course/thumbnail/';



        $path_thumbnail_video = site_url() . 'upload/course-video/thumbnail/';

        $path_video = site_url() . 'upload/course-video/';



        if ($modelBundling->find($id)) {

            // $data['bundling'] = $modelBundling->where('bundling_id', $id)->first();



            $data = $modelBundling->where('bundling_id', $id)

                ->join('users', 'bundling.author_id=users.id')

                ->join('category_bundling', 'bundling.category_bundling_id=category_bundling.category_bundling_id')

                ->select('bundling.*, category_bundling.name as category_name, users.fullname as author_name, users.company as author_company')

                ->first();



            if ($data) {

                $data['owned'] = false;

                $data['is_review'] = false;

                if ($header) {

                    $own = $modelUserBundling

                        ->where('user_id', $decoded->uid)

                        ->where('bundling_id', $id)

                        ->findAll();

                    if ($own) {

                        $data['owned'] = true;
                    }

                    $userReview = $modelUserReview

                        ->where('user_id', $decoded->uid)

                        ->where('bundling_id',  $id)

                        ->first();

                    if ($userReview) {

                        $data['is_review'] = true;
                    }
                }

                $data['thumbnail'] = $path_bundling . $data['thumbnail'];
            }



            $price = (empty($data['new_price'])) ? $data['old_price'] : $data['new_price'];



            $data['tax'] = $price * 11 / 100;



            $course_bundling = $modelBundling

                ->where('bundling.bundling_id', $id)

                ->join('course_bundling', 'bundling.bundling_id=course_bundling.bundling_id')

                ->select('course_bundling.*')

                ->orderBy('bundling.bundling_id', 'DESC')

                ->first();



            $video2 = $modelBundling

                ->where('bundling.bundling_id', $id)

                ->join('course_bundling', 'bundling.bundling_id=course_bundling.bundling_id')

                ->join('course', 'course_bundling.course_id=course.course_id')

                ->join('course_category', 'course.course_id=course_category.course_id')

                ->join('video_category', 'course_category.course_id=video_category.course_id')

                ->join('video', 'video_category.video_category_id=video.video_category_id')

                ->select('video_category.*, video.*')

                ->orderBy('bundling.bundling_id', 'DESC')

                ->first();



            $course = $modelBundling

                ->where('bundling.bundling_id', $id)

                ->where('course_bundling.deleted_at', null)

                ->join('course_bundling', 'bundling.bundling_id=course_bundling.bundling_id')

                ->join('course', 'course_bundling.course_id=course.course_id')

                ->join('course_type', 'course.course_id=course_type.course_id')

                ->join('type', 'course_type.type_id=type.type_id')

                ->join('course_category', 'course.course_id=course_category.course_id')

                ->join('category', 'course_category.category_id=category.category_id')

                ->join('video_category', 'course.course_id=video_category.course_id')

                ->select('course.*, category.name AS `category_name`, video_category.video_category_id, type.name AS course_type, course_bundling.order')

                ->orderBy('bundling.bundling_id', 'DESC')

                ->findAll();



            $course_tag = $modelCourseTag

                ->where('course_tag.course_id', $course_bundling['course_id'])

                ->join('tag', 'course_tag.tag_id=tag.tag_id')

                ->select('tag.name')

                ->findAll();



            for ($i = 0; $i < count($course); $i++) {

                $course[$i]['thumbnail'] = $path_course . $course[$i]['thumbnail'];

                $course[$i]['course_tag'] = $course_tag;
            }



            $data['course_bundling'] = $course;



            for ($l = 0; $l < count($course); $l++) {

                $video = $modelVideo

                    ->where('video_category_id', $course[$l]['video_category_id'])

                    ->orderBy('order', 'DESC')

                    ->findAll();



                $countvideo = $modelVideo

                    ->where('video_category_id', $course[$l]['video_category_id'])

                    ->orderBy('order', 'DESC')

                    ->countAllResults();



                $data['course_bundling'][$l]['total_video'] = "$countvideo";
                $data['course_bundling'][$l]['is_review'] = false;
                $data['course_bundling'][$l]['score'] = null;
                $data['course_bundling'][$l]['mengerjakan_video'] = '0 / ' . $countvideo;
                $data['course_bundling'][$l]['lolos'] = false;



                for ($c = 0; $c < count($video); $c++) {

                    $video[$c]['thumbnail'] = $path_thumbnail_video . $video[$c]['thumbnail'];

                    $video[$c]['video'] = $path_video . $video[$c]['video'];
                }



                $data['course_bundling'][$l]['video'] = $video;


                if ($data['owned']) {

                    $userReview = $modelUserReview

                        ->where('user_id', $decoded->uid)

                        ->where('course_id',  $course[$l]['course_id'])

                        ->first();



                    if ($userReview) {

                        $data['course_bundling'][$l]['is_review'] = true;
                    }

                    $scoreCourseRaw = 0;



                    $videoCategory = $modelVideoCategory->where('course_id', $course[$l]['course_id'])->first();

                    $video = $modelVideo->where('video_category_id', $videoCategory['video_category_id'])->findAll();



                    $total_video_yang_dikerjakan = 0;

                    $dari = count($video);

                    foreach ($video as $key => $video_) {

                        $userVideo = $modelUserVideo->where('user_id', $decoded->uid)->where('video_id', $video_['video_id'])->first();



                        if ($userVideo) {

                            $total_video_yang_dikerjakan++;

                            $scoreCourseRaw += $userVideo['score'];
                        }
                    }

                    $data['course_bundling'][$l]['mengerjakan_video'] = $total_video_yang_dikerjakan . ' / ' . $dari;



                    if ($total_video_yang_dikerjakan == count($video)) {

                        $data['course_bundling'][$l]['lolos'] = true;
                    }



                    $scoreCourse = $scoreCourseRaw / count($video);

                    $data['course_bundling'][$l]['score'] = $scoreCourse;
                }

                for ($z = 0; $z < count($video); $z++) {

                    $path_video = 'upload/course-video/';

                    $pathVideo = site_url() . 'upload/course-video/';



                    $filename = $video[$z]['video'];



                    $checkIfVideoIsLink = stristr($filename, 'http://') ?: stristr($filename, 'https://');



                    if (!$checkIfVideoIsLink) {

                        $file = $getID3->analyze($path_video . $filename);



                        if (isset($file['error'][0])) {

                            $checkFileIsExist = false;
                        } else {

                            $checkFileIsExist = true;
                        }



                        if ($checkFileIsExist) {

                            if (isset($file['playtime_string'])) {

                                $duration = ["duration" => $file['playtime_string']];
                            } else {

                                $duration = ["duration" => '00:00:00'];
                            }



                            $data['course_bundling'][$l]['video'][$z] = $duration;



                            $video[$z] += $duration;

                            $video[$z]['video'] = $pathVideo . $video[$z]['video'];
                        } else {

                            $duration = ["duration" => '00:00:00'];

                            $video[$z] += $duration;

                            $data['course_bundling'][$l]['video'][$z] = $duration;
                        }
                    } else {

                        $duration = ["duration" => '00:00:00'];

                        $video[$z] += $duration;
                    }
                }

                $sum = strtotime('00:00:00');

                $totalTime = 0;

                $dataTime = $data['course_bundling'][$l]['video'];



                foreach ($dataTime as $element) {

                    $time = implode($element);

                    if (substr_count($time, ':') == 1) {

                        $time = '00:' . $time;
                    }

                    $strTime = date("H:i:s", strtotime($time));



                    $timeInSec = strtotime($strTime) - $sum;



                    $totalTime = $totalTime + $timeInSec;
                }



                $hours = intval($totalTime / 3600);



                $totalTime = $totalTime - ($hours * 3600);



                $minutes = intval($totalTime / 60);



                $second = $totalTime - ($minutes * 60);



                $result = ($hours . " Jam : " . $minutes . " Menit : " . $second . " Detik");



                $data['course_bundling'][$l]['total_video_duration'] = $result;
            }

            return $this->respond($data);
        } else {

            return $this->failNotFound('Tidak ada data');
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

                "category_bundling_id" => "required",

                "title" => "required",

                "description" => "required|max_length[255]",

                "old_price" => "required|numeric",

                "new_price" => "required|numeric"

            ];



            $rules_b = [

                'thumbnail' => 'uploaded[thumbnail]'

                    . '|is_image[thumbnail]'

                    . '|mime_in[thumbnail,image/jpg,image/jpeg,image/png,image/webp]'

                    . '|max_size[thumbnail,4000]'

            ];



            $messages_a = [

                "category_bundling_id" => [

                    "required" => "{field} tidak boleh kosong"

                ],

                "title" => [

                    "required" => "{field} tidak boleh kosong"

                ],

                "description" => [

                    "required" => "{field} tidak boleh kosong",

                    "max_length" => "{field} maksimal 255 karakter",

                ],

                "new_price" => [

                    "required" => "{field} tidak boleh kosong",

                    "numeric" => "{field} harus berisi angka"

                ],

                "old_price" => [

                    "required" => "{field} tidak boleh kosong",

                    "numeric" => "{field} harus berisi angka"

                ]

            ];



            $messages_b = [

                "thumbnail" => [

                    'uploaded' => '{field} tidak boleh kosong',

                    'mime_in' => 'File Extention Harus Berupa png, jpg, atau jpeg',

                    'max_size' => 'Ukuran File Maksimal 4 MB'

                ],

            ];



            $findBundling = $this->bundling->where('bundling_id', $id)->first();

            if ($findBundling) {

                if ($this->validate($rules_a, $messages_a)) {

                    if ($this->validate($rules_b, $messages_b)) {



                        $oldThumbnail = $findBundling['thumbnail'];

                        $dataThumbnail = $this->request->getFile('thumbnail');



                        if ($dataThumbnail->isValid() && !$dataThumbnail->hasMoved()) {

                            if (file_exists("upload/bundling/" . $oldThumbnail)) {

                                unlink("upload/bundling/" . $oldThumbnail);
                            }

                            $fileName = $dataThumbnail->getRandomName();

                            $dataThumbnail->move('upload/bundling/', $fileName);
                        } else {

                            $fileName = $oldThumbnail['thumbnail'];
                        }



                        $data = [

                            'category_bundling_id' => $this->request->getVar("category_bundling_id"),

                            'title' => $this->request->getVar("title"),

                            'description' => $this->request->getVar("description"),

                            'new_price' => $this->request->getVar("new_price"),

                            'old_price' => $this->request->getVar("old_price"),

                            'author_id' => $decoded->uid,

                            'thumbnail' => $fileName,

                        ];



                        $this->bundling->update($id, $data);



                        $response = [

                            'status'   => 201,

                            'success'    => 201,

                            'messages' => [

                                'success' => 'Bundling berhasil diubah'

                            ]

                        ];
                    }



                    $data = [

                        'category_bundling_id' => $this->request->getVar("category_bundling_id"),

                        'title' => $this->request->getVar("title"),

                        'description' => $this->request->getVar("description"),

                        'new_price' => $this->request->getVar("new_price"),

                        'old_price' => $this->request->getVar("old_price"),

                        'author_id' => $this->request->getVar("author_id")

                    ];



                    $this->bundling->update($id, $data);



                    $response = [

                        'status'   => 201,

                        'success'    => 201,

                        'messages' => [

                            'success' => 'Bundling berhasil diubah'

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

        return $this->failNotFound('Data Bundling tidak ditemukan');
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

            $modelCourseBundling = new CourseBundling;

            $modelCart = new Cart;



            // cek role user

            $data = $user->select('role')->where('id', $decoded->uid)->first();

            if ($data['role'] == 'member') {

                return $this->fail('Tidak dapat di akses oleh member', 400);
            }



            $data = $this->bundling->where('bundling_id', $id)->findAll();

            if ($data) {

                $modelCourseBundling->where('bundling_id', $id)->delete();

                $modelCart->where('bundling_id', $id)->delete();

                $this->bundling->delete($id);

                $response = [

                    'status'   => 200,

                    'error'    => null,

                    'messages' => [

                        'success' => 'Bundling berhasil dihapus'

                    ]

                ];

                return $this->respondDeleted($response);
            } else {

                return $this->failNotFound('Data bundling tidak ditemukan');
            }
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }

        return $this->failNotFound('Data bundling tidak ditemukan');
    }



    public function getUserBundling()

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];



        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $user = new Users;

            $modelUserCourse = new UserCourse();

            $modelBundling = new Bundling();



            $userbundling = $modelUserCourse

                ->where('user_id', $decoded->uid)

                ->where('course_id', null)

                ->select('user_course.user_course_id, user_course.user_id, user_course.bundling_id')

                ->findAll();



            $data['coursebundling'] = $userbundling;



            for ($a = 0; $a < count($userbundling); $a++) {

                $bundling = $this->bundling

                    ->where('bundling.bundling_id', $userbundling[$a]["bundling_id"])

                    ->join('category_bundling', 'bundling.category_bundling_id = category_bundling.category_bundling_id')

                    ->select('bundling.*, category_bundling.name as category_name')

                    ->findAll();



                for ($i = 0; $i < count($bundling); $i++) {

                    $bundling[$i]['thumbnail'] = $this->pathbundling . $bundling[$i]['thumbnail'];
                }



                $data['coursebundling'][$a]['bundling'] = $bundling;
            }



            if ($data) {

                return $this->respond($data);
            } else {

                return $this->failNotFound('Tidak ada data');
            }
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }

        return $this->failNotFound('Data bundling tidak ditemukan');
    }
}
