<?php



namespace App\Controllers\Api;



use App\Models\Cart;

use CodeIgniter\RESTful\ResourceController;

use CodeIgniter\API\ResponseTrait;

use App\Models\Users;

use App\Models\Jobs;

use App\Models\Order;

use App\Models\Notification;

use App\Models\UserCourse;

use App\Models\UserAchievement;

use App\Models\UserEducation;

use App\Models\UserCv;

use App\Models\UserExperience;

use App\Models\Course;

use App\Models\Bundling;

use App\Models\VideoCategory;

use App\Models\Video;

use App\Models\UserVideo;

use App\Models\CourseBundling;

use App\Models\Review;

use Firebase\JWT\JWT;

use CodeIgniter\Files\File;

use CodeIgniter\HTTP\IncomingRequest;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use getID3;



class UserController extends ResourceController

{

    use ResponseTrait;


    public function userDetail($id = null)
    {
        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];



        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $user = new Users;



            $data = $user->select('role')->where('id', $decoded->uid)->first();

            if ($data['role'] == 'member') {

                return $this->fail('Tidak dapat di akses oleh member', 400);
            }



            $job = new Jobs;

            $modelOrder = new Order;

            $modelUserCourse = new UserCourse;

            $modelCourse = new Course;

            $modelVideoCategory = new VideoCategory;

            $modelVideo = new Video;

            $modelUserVideo = new UserVideo;



            $data = $user->where('id', $id)->first();

            $job_data = $job->where('job_id', $data['job_id'])->first();



            $path = site_url() . 'upload/users/';



            $userCourse = $modelUserCourse->where('user_id', $id)->findAll();



            $course = [];

            $videoCategory = [];



            for ($i = 0; $i < count($userCourse); $i++) {

                $course_ = $modelCourse->where('course_id', $userCourse[$i]['course_id'])->first();

                $course[$i] = $course_;



                array_push($videoCategory, $modelVideoCategory

                    ->where('course_id', $userCourse[$i]['course_id'])

                    ->orderBy('video_category.video_category_id', 'DESC')

                    ->first());



                if (isset($videoCategory[0]) && $videoCategory[0]['title'] != '') {

                    $course[$i]['video_category'] = $videoCategory;
                }





                for ($l = 0; $l < count($videoCategory); $l++) {

                    $video = $modelVideo

                        ->where('video_category_id', $videoCategory[$l]['video_category_id'])

                        ->orderBy('order', 'ASC')

                        ->findAll();



                    if ($videoCategory[0]['title'] != '') {

                        $course[$i]['video_category'][$l]['video'] = $video;



                        for ($p = 0; $p < count($video); $p++) {

                            $user_video = $modelUserVideo

                                ->select('score')

                                ->where('user_id', $decoded->uid)

                                ->where('video_id', $video[$p]['video_id'])

                                ->findAll();

                            if ($user_video) {

                                $course[$i]['video_category'][$l]['video'][$p]['score'] = $user_video[0]['score'];
                            } else {

                                $course[$i]['video_category'][$l]['video'][$p]['score'] = 0;
                            }
                        }
                    } else {

                        $course[$i]['video'] = $video;



                        for ($p = 0; $p < count($video); $p++) {

                            $user_video = $modelUserVideo

                                ->select('score')

                                ->where('user_id', $decoded->uid)

                                ->where('video_id', $video[$p]['video_id'])

                                ->findAll();

                            if ($user_video) {

                                $course[$i]['video'][$p]['score'] = $user_video[0]['score'];
                            } else {

                                $course[$i]['video'][$p]['score'] = 0;
                            }
                        }
                    }
                }
            }



            $order = $modelOrder->where('user_id', $id)->findAll();

            $orderData = [];

            if ($order != NULL) {

                foreach ($order as $value) {

                    $orderData[] = [

                        'order_id' => $value['order_id'],

                        'gross_amount' => $value['gross_amount'],

                        'transaction_status' => $value['transaction_status'],

                        'order_time' => $value['created_at']

                    ];
                }
            }



            $response = [

                'id' => $id,

                'profile_picture' => $path . $data['profile_picture'],

                'fullname' =>  $data['fullname'],

                'email' => $data['email'],

                'role' => $data['role'],

                'date_birth' => $data['date_birth'],

                'job_name' => (is_null($data['job_id'])) ? "Professional Independent" : $job_data['job_name'],

                'address' => $data['address'],

                'phone_number' => $data['phone_number'],

                'linkedin' => $data['linkedin'],

                'transaction' => $orderData,

                'course' => $course,

                'cv' => $data['cv'],

            ];

            return $this->respond($response);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
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



            $data = $user->select('role')->where('id', $decoded->uid)->first();

            if ($data['role'] != 'admin') {

                return $this->fail('Tidak dapat di akses selain admin', 400);
            }



            $job = new Jobs;



            $data = $user->findAll();



            $path = site_url() . 'upload/users/';



            $response = [];



            for ($i = 0; $i < count($data); $i++) {

                $job_data = $job->where('job_id', $data[$i]['job_id'])->first();

                if ($data[$i]['profile_picture'] == null) {

                    $profilenull = $path . "dafault.png";
                } else {

                    $profilenull = $path . $data[$i]['profile_picture'];
                }

                array_push($response, [

                    'id' => $data[$i]['id'],

                    'profile_picture' => $profilenull,

                    'fullname' =>  $data[$i]['fullname'],

                    'email' => $data[$i]['email'],

                    'role' => $data[$i]['role'],

                    'date_birth' => $data[$i]['date_birth'],

                    'job_name' => (is_null($data[$i]['job_id'])) ? "Professional Independent" : $job_data['job_name'],

                    'address' => $data[$i]['address'],

                    'phone_number' => $data[$i]['phone_number'],

                    "created_at" => $data[$i]['created_at'],

                    "updated_at" => $data[$i]['updated_at'],

                    'cv' => $data[$i]['cv'],

                ]);
            }



            return $this->respond($response);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }



    public function getRole()

    {

        $data['role'] = ['admin', 'partner', 'author', 'member', 'mentor'];



        return $this->respond($data);
    }



    public function userCourse()

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];



        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $user = new Users;

            $job = new Jobs;

            $modelUserCourse = new UserCourse;

            $modelCourse = new Course;

            $modelBundling = new Course;

            $modelVideo = new Video;

            $modelVideoCategory = new VideoCategory;

            $modelUserVideo = new UserVideo;

            $modelCourseBundling = new CourseBundling;

            $modelUserReview = new Review;

            include_once('getid3/getid3.php');

            $getID3 = new getID3;



            $data = $user->where('id', $decoded->uid)->first();

            $job_data = $job->where('job_id', $data['job_id'])->first();

            $learning_progres = 0;

            $total_item = 0;

            $finished_item = 0;



            $path_profile = site_url() . 'upload/users/';

            $path_cv = site_url() . 'upload/cv/';

            $path_course = site_url() . 'upload/course/thumbnail/';

            $path_bundling = site_url() . 'upload/bundling/';



            $userCourse = $modelUserCourse->select('user_course.*, course.*')

                ->join('course', 'user_course.course_id=course.course_id')

                ->where('course.service', 'course')

                ->where('user_course.user_id', $decoded->uid)

                ->where('user_course.bundling_id', NULL)

                ->findAll();



            $course = $userCourse;

            for ($i = 0; $i < count($userCourse); $i++) {

                $total_item++;

                $score_raw = 0;

                $score_final = 0;

                $userReview = $modelUserReview->where('user_id', $decoded->uid)

                    ->where('course_id', $userCourse[$i]['course_id'])

                    ->first();



                $course_ = $modelCourse->where('course_id', $userCourse[$i]['course_id'])->first();

                $course[$i] = $course_;

                $course[$i]['thumbnail'] = site_url() . 'upload/course-video/thumbnail/' . $course[$i]['thumbnail'];



                if ($userReview) {

                    $course[$i]['review'] = $userReview['score'];

                    $course[$i]['is_review'] = true;
                } else {

                    $course[$i]['is_review'] = false;
                }



                $course[$i]['thumbnail'] = $path_course . $course_['thumbnail'];



                $videoCat_ = $modelVideoCategory->where('course_id', $userCourse[$i]['course_id'])->first();

                if ($videoCat_) {

                    $video_ = $modelVideo->where('video_category_id', $videoCat_['video_category_id'])->findAll();



                    $userVideo = 0;



                    $total_video_yang_dikerjakan = 0;

                    $lolos = false;

                    for ($l = 0; $l < count($video_); $l++) {

                        $userVideo_ = $modelUserVideo->where('user_id', $decoded->uid)->where('video_id', $video_[$l]['video_id'])->first();



                        if ($userVideo_) {

                            $userVideo++;

                            $total_video_yang_dikerjakan++;

                            $score_raw += $userVideo_['score'];
                        }
                    }

                    $score_final = $total_video_yang_dikerjakan > 0 ? $score_raw / $total_video_yang_dikerjakan : 0;

                    $course[$i]['score'] = $score_final;

                    $course[$i]['mengerjakan_video'] = $total_video_yang_dikerjakan . ' / ' . count($video_);

                    if ($total_video_yang_dikerjakan == count($video_)) {

                        $course[$i]['lolos'] = true;

                        $finished_item++;
                    } else {

                        $course[$i]['lolos'] = false;
                    }

                    for ($x = 0; $x < count($videoCat_); $x++) {

                        $video = $modelVideo

                            ->select('video_id, video, thumbnail')

                            ->where('video_category_id', $videoCat_['video_category_id'])

                            ->orderBy('order', 'ASC')

                            ->findAll();



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



                                    $course[$i]['video'][$z] = $duration;



                                    $video[$z] += $duration;

                                    $video[$z]['video'] = $pathVideo . $video[$z]['video'];
                                } else {

                                    $duration = ["duration" => '00:00:00'];

                                    $video[$z] += $duration;

                                    $course[$i]['video'][$z] = $duration;
                                }
                            } else {

                                $duration = ["duration" => '00:00:00'];

                                $video[$z] += $duration;
                            }
                        }



                        $sum = strtotime('00:00:00');

                        $totalTime = 0;

                        $dataTime = $course[$i]['video'];



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



                        $course[$i]['total_video_duration'] = $result;
                    }
                } else {

                    $course[$i]['score'] = 0;

                    $course[$i]['mengerjakan_video'] = "0 / 0";

                    $course[$i]['lolos'] = false;

                    $course[$i]['total_video_duration'] = "0 Jam : 0 Menit : 0 Detik";
                }
            }



            $userBundling = $modelUserCourse->select('bundling.bundling_id, title, description, old_price, new_price, thumbnail')

                ->where('user_id', $decoded->uid)

                ->join('bundling', 'user_course.bundling_id=bundling.bundling_id')

                ->where('course_id', NULL)

                ->findAll();


            $response = [

                'id' => $decoded->uid,

                'profile_picture' => $path_profile . $data['profile_picture'],

                'fullname' => $data['fullname'],

                'email' => $decoded->email,

                'date_birth' => $data['date_birth'],

                'job_name' => (is_null($data['job_id'])) ? "Professional Independent" : $job_data['job_name'],

                'address' => $data['address'],

                'phone_number' => $data['phone_number'],

                'linkedin' => $data['linkedin'],

                'created_at' => $data['created_at'],

                'cv' => $data['cv'],

                'learning_progress' => 0,

                'course' => $course,

                'bundling' => [],

                //'bundling' => (array) array_merge((array) $userBundling[0], ['course_bundling' => $courseBundling]),

            ];



            if ($userBundling) {

                for ($i = 0; $i < count($userBundling); $i++) {

                    $userBundling[$i]['thumbnail'] = $path_bundling . $userBundling[$i]['thumbnail'];
                }

                $courseBundling = [];

                // foreach ($userBundling as $key => $value) {

                for ($k = 0; $k < count($userBundling); $k++) {

                    $courseBundling_ = $modelCourseBundling->select('course.course_id, title, service, description, key_takeaways, suitable_for, old_price, new_price, thumbnail, author_id, course.created_at, course.updated_at')

                        ->join('course', 'course_bundling.course_id=course.course_id', 'right')

                        ->where('course_bundling.bundling_id', $userBundling[$k]['bundling_id'])

                        ->findAll();


                    $userReview = $modelUserReview->where('user_id', $decoded->uid)

                        ->where('bundling_id', $userBundling[$k]['bundling_id'])

                        ->first();


                    if ($userReview) {

                        $userBundling[$k]['review'] = $userReview['score'];

                        $userBundling[$k]['is_review'] = true;
                    } else {

                        $userBundling[$k]['is_review'] = false;
                    }



                    $scoreBundling = 0;

                    $scoreBundlingRaw = [];

                    $total_video_yang_dikerjakan_raw = [];

                    $check_lolos_raw = [];

                    $total_course_yang_dikerjakan = 0;



                    foreach ($courseBundling_ as $key => $courseBundling) {

                        $total_item++;



                        $userReview = $modelUserReview

                            ->where('user_id', $decoded->uid)

                            ->where('course_id', $courseBundling['course_id'])

                            ->first();



                        if ($userReview) {

                            $courseBundling_[$key]['review'] = $userReview['score'];

                            $courseBundling_[$key]['is_review'] = true;
                        } else {

                            $courseBundling_[$key]['is_review'] = false;
                        }



                        $scoreCourseRaw = 0;

                        $scoreCourseRaw2 = [];



                        $videoCategory = $modelVideoCategory->where('course_id', $courseBundling['course_id'])->first();

                        $video = $modelVideo->where('video_category_id', $videoCategory['video_category_id'])->findAll();



                        $total_video_yang_dikerjakan = 0;

                        $dari = count($video);

                        foreach ($video as $key => $video_) {

                            $userVideo = $modelUserVideo->where('user_id', $decoded->uid)->where('video_id', $video_['video_id'])->first();



                            if ($userVideo) {

                                $total_video_yang_dikerjakan++;

                                array_push($scoreCourseRaw2, $userVideo['score']);

                                $scoreCourseRaw += $userVideo['score'];
                            }
                        }

                        array_push($total_video_yang_dikerjakan_raw, $total_video_yang_dikerjakan . ' / ' . $dari);



                        if ($total_video_yang_dikerjakan == count($video)) {

                            array_push($check_lolos_raw, true);

                            $finished_item++;
                        } else {

                            array_push($check_lolos_raw, false);
                        }



                        if ($total_video_yang_dikerjakan == 0) {

                            $scoreCourse = 0;
                        } else {

                            $scoreCourse = $scoreCourseRaw / $total_video_yang_dikerjakan;

                            $total_course_yang_dikerjakan++;
                        }


                        array_push($scoreBundlingRaw, $scoreCourse);
                    }



                    // return $this->respond($check_lolos_raw);

                    // return $this->respond($scoreBundlingRaw);

                    // foreach ($scoreBundlingRaw as $key => $value) {

                    for ($o = 0; $o < count($scoreBundlingRaw); $o++) {

                        $scoreBundling += $scoreBundlingRaw[$o];
                    }


                    if ($total_course_yang_dikerjakan == 0) {

                        $scoreBundling = 0;
                    } else {

                        $scoreBundling /= $total_course_yang_dikerjakan;
                    }



                    $userBundling[$k]['score'] = $scoreBundling;

                    $userBundling[$k]['progress'] = '';

                    $userBundling[$k]['lolos'] = false;

                    $course_lolos = 0;

                    // $userBundling[$k]['mengerjakan_video'] = $total_video_yang_dikerjakan_raw;

                    // $userBundling[$k]['lolos'] = $check_lolos_raw;





                    $userBundling[$k]['course_bundling'] = $courseBundling_;



                    for ($o = 0; $o < count($userBundling[$k]['course_bundling']); $o++) {

                        $userBundling[$k]['course_bundling'][$o]['score'] = $scoreBundlingRaw[$o];

                        $userBundling[$k]['course_bundling'][$o]['thumbnail'] = $path_course . $userBundling[$k]['course_bundling'][$o]['thumbnail'];



                        $userBundling[$k]['course_bundling'][$o]['mengerjakan_video'] = $total_video_yang_dikerjakan_raw[$o];



                        $userBundling[$k]['course_bundling'][$o]['lolos'] = $check_lolos_raw[$o];

                        if ($userBundling[$k]['course_bundling'][$o]['lolos'] = $check_lolos_raw[$o] == true) {
                            $course_lolos++;
                        }

                        $videoCat_ = $modelVideoCategory->where('course_id', $userBundling[$k]['course_bundling'][$o]['course_id'])->first();

                        for ($x = 0; $x < count($videoCat_); $x++) {

                            $video = $modelVideo

                                ->select('video_id, video, thumbnail')

                                ->where('video_category_id', $videoCat_['video_category_id'])

                                ->orderBy('order', 'ASC')

                                ->findAll();



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



                                        $userBundling[$k]['course_bundling'][$o]['video'][$z] = $duration;



                                        $video[$z] += $duration;

                                        $video[$z]['video'] = $pathVideo . $video[$z]['video'];
                                    } else {

                                        $duration = ["duration" => '00:00:00'];

                                        $video[$z] += $duration;

                                        $userBundling[$k]['course_bundling'][$o]['video'][$z] = $duration;
                                    }
                                } else {

                                    $duration = ["duration" => '00:00:00'];

                                    $video[$z] += $duration;
                                }
                            }



                            $sum = strtotime('00:00:00');

                            $totalTime = 0;

                            $dataTime = $userBundling[$k]['course_bundling'][$o]['video'];



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



                            $userBundling[$k]['course_bundling'][$o]['total_video_duration'] = $result;
                        }
                    }

                    $userBundling[$k]['progress'] = $course_lolos . ' / ' . count($userBundling[$k]['course_bundling']);

                    if ($course_lolos == count($userBundling[$k]['course_bundling'])) {

                        $userBundling[$k]['lolos'] = true;
                    }
                }



                // $courseBundling['thumbnail'] = $path_bundling . $courseBundling['thumbnail'];



                // foreach ($courseBundling['course_bundling'] as $key => $value) {

                //     $courseBundling['course_bundling'][$key]['thumbnail'] = $path_course . $courseBundling['course_bundling'][$key]['thumbnail'];

                // }



                $response['bundling'] = $userBundling;
            }

            if ($finished_item != 0 && $total_item != 0) {

                $response['learning_progress'] = (int)(($finished_item / $total_item) * 100);
            }



            return $this->respond($response);
        } catch (\Throwable $th) {

            //throw $th;

            return $this->fail($th->getMessage());
        }

        return $this->failNotFound('Data user tidak ditemukan');
    }



    public function profile()

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];



        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $user = new Users;

            $job = new Jobs;



            $data = $user->where('id', $decoded->uid)->first();

            $job_data = $job->where('job_id', $data['job_id'])->first();



            $path_profile = site_url() . 'upload/users/';



            $response = [

                'id' => $decoded->uid,

                'profile_picture' => $path_profile . $data['profile_picture'],

                'fullname' => $data['fullname'],

                'email' => $decoded->email,

                'date_birth' => $data['date_birth'],

                'job_name' => (is_null($data['job_id'])) ? "Professional Independent" : $job_data['job_name'],

                'job_id' => $data['job_id'],

                'address' => $data['address'],

                'phone_number' => $data['phone_number'],

            ];

            return $this->respond($response);
        } catch (\Throwable $th) {

            //throw $th;

            return $this->fail($th->getMessage());
        }
    }



    // TODO: Opsi untuk user ketika ingin menghapus foto profile dan otomatis terganti ke default

    public function update($id = null)

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];



        $decoded = JWT::decode($token, $key, ['HS256']);



        try {

            $user = new Users;



            if ($decoded->uid != $id) {

                return $this->failNotFound('Parameters & Token user tidak sesuai');
            };



            $cek = $user->where('id', $id)->first();



            if (!$cek) {

                return $this->failNotFound('Data user tidak ditemukan');
            }



            $rules_a = [

                'fullname' => 'required',

                'address' => 'required',

                'phone_number' => 'required|numeric',

                'date_birth' => 'required'

            ];

            $rules_b = [

                'profile_picture' => 'uploaded[profile_picture]'

                    . '|is_image[profile_picture]'

                    . '|mime_in[profile_picture,image/jpg,image/jpeg,image/png]'

                    . '|max_size[profile_picture,4000]'

            ];


            $messages_a = [

                'fullname' => ['required' => 'Nama tidak boleh kosong'],

                'address' => ['required' => 'Alamat tidak boleh kosong'],

                'phone_number' => [

                    'required' => 'Nomor telepon tidak boleh kosong',

                    'numeric' => 'Nomor telepon harus berisi numerik'

                ],

                'date_birth' => ['required' => 'Tanggal lahir tidak boleh kosong']
            ];



            $messages_b = [

                'profile_picture' => [

                    'uploaded' => 'Foto profil tidak boleh kosong',

                    'mime_in' => 'Ekstensi file harus berupa png, jpg, atau jpeg',

                    'max_size' => 'Ukuran gambar maksimal 4 MB'

                ]

            ];



            if ($this->validate($rules_a, $messages_a)) {

                // foto profil
                if (isset($_FILES["profile_picture"]) && is_uploaded_file($_FILES["profile_picture"]["tmp_name"])) {

                    if ($this->validate($rules_b, $messages_b)) {

                        $oldThumbnail = $cek['profile_picture'];

                        $profilePicture = $this->request->getFile('profile_picture');


                        if ($profilePicture->isValid() && !$profilePicture->hasMoved()) {

                            if (file_exists("upload/users/" . $oldThumbnail)) {

                                if ($oldThumbnail != 'default.png' && $oldThumbnail != 'company-default.jpg') {

                                    unlink("upload/users/" . $oldThumbnail);
                                }
                            }

                            $fileName = $profilePicture->getRandomName();

                            $profilePicture->move('upload/users/', $fileName);
                        } else {

                            $fileName = $oldThumbnail;
                        }

                        $data['profile_picture'] = $fileName;
                    } else {

                        $response = [

                            'status'   => 400,

                            'error'    => 400,

                            'messages' => $this->validator->getErrors(),

                        ];

                        return $this->fail($response);
                    }
                }


                $data['fullname'] = $this->request->getVar('fullname');

                $data['job_id'] = $this->request->getVar('job_id');

                $data['address'] = $this->request->getVar('address');

                $data['phone_number'] = $this->request->getVar('phone_number');

                $data['date_birth'] = $this->request->getVar('date_birth');

                $user->update($id, $data);

                $response = [

                    'status'   => 201,

                    'success'    => 201,

                    'messages' => [

                        'success' => 'Profil berhasil diupdate'

                    ]

                ];
            } else {

                $response = [

                    'status'   => 400,

                    'error'    => 400,

                    'messages' => $this->validator->getErrors(),

                ];

                return $this->fail($response);
            }

            return $this->respondCreated($response);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }

        return $this->failNotFound('Data user tidak ditemukan');
    }



    public function updateProfilePicture($id = null)

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];

        $decoded = JWT::decode($token, $key, ['HS256']);

        try {

            $user = new Users;

            if ($decoded->uid != $id) {

                return $this->failNotFound('Parameters & Token user tidak sesuai');
            };

            $cek = $user->where('id', $id)->first();

            if (!$cek) {

                return $this->failNotFound('Data user tidak ditemukan');
            }


            $binaryData = file_get_contents('php://input');

            $allowedExtensions = ['jpg', 'jpeg', 'png'];

            $maxFileSize = 4000 * 1024;

            $pathFotoProfil = 'upload/users/';

            $imageSize = @getimagesizefromstring($binaryData);

            $extension = strtolower(image_type_to_extension($imageSize[2], false));


            if (empty($binaryData)) {

                return $this->fail(['error' => 'Foto profil tidak boleh kosong'], 400);
            }


            if (!$imageSize) {

                return $this->fail(['error' => 'Ekstensi file harus berupa png, jpg, atau jpeg'], 400);
            }


            if (!in_array($extension, $allowedExtensions)) {

                return $this->fail(['error' => 'Ekstensi file harus berupa png, jpg, atau jpeg'], 400);
            }


            if (strlen($binaryData) > $maxFileSize) {

                return $this->fail(['error' => 'Ukuran gambar maksimal 4 MB'], 400);
            }


            $oldThumbnail = $cek['profile_picture'];

            if (file_exists("upload/users/" . $oldThumbnail)) {

                if ($oldThumbnail != 'default.png' && $oldThumbnail != 'company-default.jpg') {

                    unlink("upload/users/" . $oldThumbnail);
                }
            }

            $filename = uniqid() . '.' . $extension;

            file_put_contents($pathFotoProfil . $filename, $binaryData);

            $user->update($id, ['profile_picture' => $filename]);

            $response = [

                'status'   => 201,

                'success'    => 201,

                'messages' => [

                    'success' => 'Foto profil berhasil diupdate'

                ]

            ];

            return $this->respond($response);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }



    public function getCv()
    {
        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];

        $decoded = JWT::decode($token, $key, ['HS256']);

        try {

            $user = new Users;

            $cv = new UserCv;

            $edu = new UserEducation;

            $exp = new UserExperience;

            $ach = new UserAchievement;

            $cek = $user->where('id', $decoded->uid)->first();

            if (!$cek) {

                return $this->failNotFound('Data user tidak ditemukan');
            }

            $data = $user

                ->select('id, fullname, email, address, phone_number, date_birth, profile_picture')

                ->where('id', $decoded->uid)

                ->first();

            $data['profile_picture'] = site_url() . 'upload/users/' . $data['profile_picture'];

            $user_cv = $cv->where('user_id', $decoded->uid)->first();

            $user_edu = $edu->where('user_id', $decoded->uid)->orderBy('year')->findAll();

            $organization = $exp->where('user_id', $decoded->uid)->where('type', 'organization')->orderBy('year')->findAll();

            $job = $exp->where('user_id', $decoded->uid)->where('type', 'job')->orderBy('year')->findAll();

            $user_ach = $ach->where('user_id', $decoded->uid)->orderBy('year')->findAll();

            $data['about'] = $user_cv ? $user_cv['about'] : '';

            $data['portofolio'] = $user_cv && $user_cv['portofolio'] ? site_url() . 'upload/portofolio/' . $user_cv['portofolio'] : '';

            $data['status'] = $user_cv ? $user_cv['status'] : '';

            $data['method'] = $user_cv ? $user_cv['method'] : '';

            $data['min_salary'] = $user_cv ? (int)$user_cv['min_salary'] : 0;

            $data['max_salary'] = $user_cv ? (int)$user_cv['max_salary'] : 0;

            if (($data['min_salary'] != 0) && ($data['max_salary'] != 0)) {

                $data['range'] = 'Rp. ' . number_format($data['min_salary'], 0, ',', '.') . ' - Rp. ' . number_format($data['max_salary'], 0, ',', '.');
            } else if (($data['min_salary'] == 0) && ($data['max_salary'] != 0)) {

                $data['range'] = '< Rp. ' . number_format($data['max_salary'], 0, ',', '.');
            } else if (($data['min_salary'] != 0) && ($data['max_salary'] == 0)) {

                $data['range'] = '> Rp. ' . number_format($data['min_salary'], 0, ',', '.');
            } else {

                $data['range'] = 'Menyesuaikan';
            }

            $data['facebook'] = $user_cv ? $user_cv['facebook'] : '';

            $data['instagram'] = $user_cv ? $user_cv['instagram'] : '';

            $data['linkedin'] = $user_cv ? $user_cv['linkedin'] : '';

            $data['education'] = $user_edu ? $user_edu : [];

            $data['organization'] = $organization ? $organization : [];

            $data['job'] = $job ? $job : [];

            $data['achievement'] = $user_ach ? $user_ach : [];

            return $this->respond($data);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }



    public function getCvData($id, $hide)
    {

        try {

            $user = new Users;

            $cv = new UserCv;

            $edu = new UserEducation;

            $exp = new UserExperience;

            $ach = new UserAchievement;

            $cek = $user->where('id', $id)->first();

            if (!$cek) {

                return $output = [

                    'status' => 'error',

                    'message' => 'Data tidak ditemukan'

                ];
            }

            $data = $user

                ->select('id, fullname, email, address, phone_number, date_birth, profile_picture')

                ->where('id', $id)

                ->first();

            if ($hide) {

                $data['email'] = '-';

                $data['phone_number'] = '-';
            }

            if ($data['date_birth'] == "0000-00-00") {

                $data['date_birth'] = '-';
            } else {

                $data['date_birth'] = date("d F Y", strtotime($data['date_birth']));
            }

            $user_cv = $cv->where('user_id', $id)->first();

            $user_edu_formal = $edu->where('user_id', $id)->where('status', 'formal')->orderBy('year')->findAll();

            $user_edu_informal = $edu->where('user_id', $id)->where('status', 'informal')->orderBy('year')->findAll();

            $organization = $exp->where('user_id', $id)->where('type', 'organization')->orderBy('year')->findAll();

            $job = $exp->where('user_id', $id)->where('type', 'job')->orderBy('year')->findAll();

            $user_ach = $ach->where('user_id', $id)->orderBy('year')->findAll();

            $data['about'] = $user_cv ? $user_cv['about'] : '-';

            $data['portofolio'] = $user_cv && $user_cv['portofolio'] ? site_url() . 'upload/portofolio/' . $user_cv['portofolio'] : '';

            $data['status'] = $user_cv ? $user_cv['status'] : '';

            $data['method'] = $user_cv ? $user_cv['method'] : '';

            $data['min_salary'] = $user_cv ? (int)$user_cv['min_salary'] : 0;

            $data['max_salary'] = $user_cv ? (int)$user_cv['max_salary'] : 0;

            $data['facebook'] = $user_cv ? $user_cv['facebook'] : '';

            $data['instagram'] = $user_cv ? $user_cv['instagram'] : '';

            $data['linkedin'] = $user_cv ? $user_cv['linkedin'] : '';

            $data['education_formal'] = $user_edu_formal ? $user_edu_formal : [];

            $data['education_informal'] = $user_edu_informal ? $user_edu_informal : [];

            $data['organization'] = $organization ? $organization : [];

            $data['job'] = $job ? $job : [];

            $data['achievement'] = $user_ach ? $user_ach : [];

            return $output = [

                'status' => 'success',

                'message' => 'Data ditemukan',

                'data' => $data

            ];
        } catch (\Throwable $th) {

            return $th->getMessage();
        }
    }



    public function updateCv()
    {
        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];

        $decoded = JWT::decode($token, $key, ['HS256']);

        try {

            $user = new Users;

            $cv = new UserCv;

            $cek = $user->where('id', $decoded->uid)->first();

            if (!$cek) {

                return $this->failNotFound('Data user tidak ditemukan');
            }

            $rules = [

                'about' => 'required'

            ];

            $messages = [

                'about' => ['required' => 'Tentang Saya tidak boleh kosong']

            ];

            $rules_portofolio = [

                'portofolio' => 'mime_in[portofolio,application/pdf]'

                    . '|max_size[portofolio,5000]'

            ];

            $messages_portofolio = [

                'portofolio' => [

                    'mime_in' => 'Ekstensi file harus berupa pdf',

                    'max_size' => 'Ukuran file maksimal 5 MB'

                ],

            ];

            if ($this->validate($rules, $messages)) {

                $data = [

                    'about' => $this->request->getVar('about'),

                    'min_salary' => $this->request->getVar('min_salary'),

                    'max_salary' => $this->request->getVar('max_salary'),

                    'instagram' => $this->request->getVar('instagram'),

                    'facebook' => $this->request->getVar('facebook'),

                    'linkedin' => $this->request->getVar('linkedin'),

                    'status' => $this->request->getVar('status'),

                    'method' => $this->request->getVar('method')

                ];

                $user_cv = $cv->where('user_id', $decoded->uid)->first();

                if ($this->request->getFile('portofolio')) {

                    if ($this->validate($rules_portofolio, $messages_portofolio)) {

                        $file = $this->request->getFile('portofolio');

                        if ($file->isValid() && !$file->hasMoved()) {

                            $fileName = $file->getRandomName();

                            $file->move('upload/portofolio/', $fileName);

                            $data['portofolio'] = $fileName;

                            if ($user_cv['portofolio']) {

                                if (file_exists("upload/portofolio/" . $user_cv['portofolio'])) {

                                    unlink("upload/portofolio/" . $user_cv['portofolio']);
                                }
                            }
                        }
                    } else {

                        return $this->fail($this->validator->getErrors());
                    }
                }

                if ($user_cv) {

                    $cv->update($user_cv['user_cv_id'], $data);
                } else {

                    $data['user_id'] = $decoded->uid;

                    $cv->save($data);
                }

                $response = [

                    'status'   => 201,

                    'success'    => 201,

                    'messages' => [

                        'success' => 'Data berhasil disimpan'

                    ]

                ];

                return $this->respondCreated($response);
            } else {

                return $this->fail($this->validator->getErrors());
            }
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }



    public function updatePortofolio($id = null)

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];

        $decoded = JWT::decode($token, $key, ['HS256']);

        try {

            $user_cv = new UserCV;

            if ($decoded->uid != $id) {

                return $this->failNotFound('Parameters & Token user tidak sesuai');
            }

            $cek = $user_cv->where('user_id', $id)->first();

            if (!$cek) {

                return $this->failNotFound('Isi data CV terlebih dahulu');
            }

            $binaryData = file_get_contents('php://input');

            $allowedExtensions = ['pdf'];

            $maxFileSize = 5000 * 1024;

            $pathPortofolio = 'upload/portofolio/';

            $fileSize = strlen($binaryData);

            $imageSize = @getimagesizefromstring($binaryData);

            // $extension = strtolower(image_type_to_extension($imageSize[2], false));

            // return $this->respond();

            if (empty($binaryData)) {

                return $this->fail(['error' => 'File PDF tidak boleh kosong'], 400);
            }

            if (strpos($binaryData, '%PDF') !== 0) {

                return $this->fail(['error' => 'Ekstensi file harus berupa PDF'], 400);
            }

            if ($fileSize > $maxFileSize) {

                return $this->fail(['error' => 'Ukuran file maksimal 5 MB'], 400);
            }

            $oldPortofolio = $cek['portofolio'];

            if ($oldPortofolio != null && $oldPortofolio != '' && file_exists("upload/portofolio/" . $oldPortofolio)) {

                unlink("upload/portofolio/" . $oldPortofolio);
            }

            $filename = uniqid() . '.pdf';

            file_put_contents($pathPortofolio . $filename, $binaryData);

            $user_cv->update($cek['user_cv_id'], ['portofolio' => $filename]);

            $response = [

                'status' => 201,

                'success' => 201,

                'messages' => [

                    'success' => 'Portofolio berhasil diperbarui'

                ]

            ];

            return $this->respond($response);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }



    public function deletePortofolio()
    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];

        $decoded = JWT::decode($token, $key, ['HS256']);

        try {

            $user_cv = new UserCV;

            $cek = $user_cv->where('user_id', $decoded->uid)->first();

            if (!$cek) {

                return $this->failNotFound('Isi data CV terlebih dahulu');
            }

            $oldPortofolio = $cek['portofolio'];

            if ($oldPortofolio != null && $oldPortofolio != '' && file_exists("upload/portofolio/" . $oldPortofolio)) {

                unlink("upload/portofolio/" . $oldPortofolio);
            }

            $user_cv->update($cek['user_cv_id'], ['portofolio' => '']);

            $response = [

                'status' => 201,

                'success' => 201,

                'messages' => [

                    'success' => 'Portofolio berhasil dihapus'

                ]

            ];

            return $this->respond($response);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }



    public function updateEdu()
    {
        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];

        $decoded = JWT::decode($token, $key, ['HS256']);

        try {

            $user = new Users;

            $user_education = new UserEducation;

            $cek = $user->where('id', $decoded->uid)->first();

            if (!$cek) {

                return $this->failNotFound('Data user tidak ditemukan');
            }

            $education = $this->request->getJSON()->education;

            $data_user = $user_education->where('user_id', $decoded->uid)->findAll();


            foreach ($education as $item) {

                if (!$item->status) {

                    $message = "Jenis pendidikan tidak boleh kosong";

                    return $this->fail($message);
                } elseif (!$item->education_name) {

                    $message = "Nama instansi tidak boleh kosong";

                    return $this->fail($message);
                } elseif (!$item->year) {

                    $message = "Tahun lulus tidak boleh kosong";

                    return $this->fail($message);
                }
            }


            foreach ($data_user as $edu) {

                $found = false;

                foreach ($education as $eduData) {

                    if ($edu['user_education_id'] == $eduData->id) {

                        $found = true;

                        break;
                    }
                }
                if (!$found) {

                    $user_education->delete($edu['user_education_id']);
                }
            }


            foreach ($education as $item) {

                $input = [

                    'user_id' => $decoded->uid,

                    'status' => $item->status,

                    'education_name' => $item->education_name,

                    'major' => $item->major,

                    'year' => $item->year

                ];

                if ($item->id) {

                    $user_education->update($item->id, $input);
                } else {

                    $user_education->save($input);
                }
            }


            $response = [

                'status'   => 201,

                'success'    => 201,

                'messages' => [

                    'success' => 'Data berhasil disimpan'

                ]

            ];

            return $this->respondCreated($response);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }



    public function updateExp()
    {
        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];

        $decoded = JWT::decode($token, $key, ['HS256']);

        try {

            $user = new Users;

            $user_exp = new UserExperience;

            $cek = $user->where('id', $decoded->uid)->first();

            if (!$cek) {

                return $this->failNotFound('Data user tidak ditemukan');
            }

            $exp = $this->request->getJSON()->exp;

            $data_user = $user_exp->where('user_id', $decoded->uid)->findAll();

            foreach ($exp as $item) {

                if (!$item->type) {

                    $message = "Jenis pengalaman tidak boleh kosong";

                    return $this->fail($message);
                } elseif (!$item->instance_name) {

                    $message = "Nama instansi tidak boleh kosong";

                    return $this->fail($message);
                } elseif (!$item->position) {

                    $message = "Posisi tidak boleh kosong";

                    return $this->fail($message);
                } elseif (!$item->year) {

                    $message = "Tahun tidak boleh kosong";

                    return $this->fail($message);
                }
            }

            foreach ($data_user as $exp_user) {

                $found = false;

                foreach ($exp as $expData) {

                    if ($exp_user['user_experience_id'] == $expData->id) {

                        $found = true;

                        break;
                    }
                }
                if (!$found) {

                    $user_exp->delete($exp_user['user_experience_id']);
                }
            }

            foreach ($exp as $item) {

                $input = [

                    'user_id' => $decoded->uid,

                    'type' => $item->type,

                    'instance_name' => $item->instance_name,

                    'position' => $item->position,

                    'year' => $item->year

                ];

                if ($item->id) {

                    $user_exp->update($item->id, $input);
                } else {

                    $user_exp->save($input);
                }
            }

            $response = [

                'status'   => 201,

                'success'    => 201,

                'messages' => [

                    'success' => 'Data berhasil disimpan'

                ]

            ];

            return $this->respondCreated($response);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }



    public function updateAch()
    {
        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];

        $decoded = JWT::decode($token, $key, ['HS256']);

        try {

            $user = new Users;

            $user_ach = new UserAchievement;

            $cek = $user->where('id', $decoded->uid)->first();

            if (!$cek) {

                return $this->failNotFound('Data user tidak ditemukan');
            }

            $ach = $this->request->getJSON()->ach;


            $data_user = $user_ach->where('user_id', $decoded->uid)->findAll();

            foreach ($ach as $item) {

                if (!$item->event_name) {

                    $message = "Nama kegiatan tidak boleh kosong";

                    return $this->fail($message);
                } elseif (!$item->position) {

                    $message = "Posisi tidak boleh kosong";

                    return $this->fail($message);
                } elseif (!$item->year) {

                    $message = "Tahun tidak boleh kosong";

                    return $this->fail($message);
                }
            }

            foreach ($data_user as $ach_user) {

                $found = false;

                foreach ($ach as $achData) {

                    if ($ach_user['user_achievement_id'] == $achData->id) {

                        $found = true;

                        break;
                    }
                }

                if (!$found) {

                    $user_ach->delete($ach_user['user_achievement_id']);
                }
            }

            foreach ($ach as $item) {

                $input = [

                    'user_id' => $decoded->uid,

                    'event_name' => $item->event_name,

                    'position' => $item->position,

                    'year' => $item->year

                ];

                if ($item->id) {

                    $user_ach->update($item->id, $input);
                } else {

                    $user_ach->save($input);
                }
            }

            $response = [

                'status'   => 201,

                'success'    => 201,

                'messages' => [

                    'success' => 'Data berhasil disimpan'

                ]

            ];

            return $this->respondCreated($response);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }



    public function updateUserByAdmin($id = null)

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];



        $decoded = JWT::decode($token, $key, ['HS256']);



        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $user = new Users;



            // cek role user

            $data = $user->select('role')->where('id', $decoded->uid)->first();

            if ($data['role'] != 'admin') {

                return $this->fail('Tidak dapat di akses selain admin', 400);
            }



            $cek = $user->where('id', $id)->findAll();



            if (!$cek) {

                return $this->failNotFound('Data user tidak ditemukan');
            }



            $rules_a = [

                'fullname' => 'required',

                'job_id' => 'required',

                'role' => 'required',

                'date_birth' => 'required|valid_date',

                'phone_number' => 'required|numeric',

                'address' => 'required',

            ];



            $rules_b = [

                'profile_picture' => 'uploaded[profile_picture]'

                    . '|is_image[profile_picture]'

                    . '|mime_in[profile_picture,image/jpg,image/jpeg,image/png,image/webp]'

                    . '|max_size[profile_picture,4000]'

            ];



            $messages_a = [

                'fullname' => ['required' => '{field} tidak boleh kosong'],

                'job_id' => ['required' => '{field} tidak boleh kosong'],

                'role' => ['required' => '{field} tidak boleh kosong'],

                'date_birth' => [

                    'required' => '{field} tidak boleh kosong',

                    'valid_date' => '{field} format tanggal tidak sesuai'

                ],

                'phone_number' => [

                    'required' => '{field} tidak boleh kosong',

                    'numeric' => '{field} harus berisi numerik'

                ],

                'address' => ['required' => '{field} tidak boleh kosong'],

            ];



            $messages_b = [

                'profile_picture' => [

                    'uploaded' => '{field} tidak boleh kosong',

                    'mime_in' => 'File Extention Harus Berupa png, jpg, atau jpeg',

                    'max_size' => 'Ukuran File Maksimal 4 MB'

                ],

            ];



            if ($this->validate($rules_a, $messages_a)) {

                if ($this->validate($rules_b, $messages_b)) {

                    $profilePicture = $this->request->getFile('profile_picture');

                    $fileName = $profilePicture->getRandomName();

                    $data = [

                        'fullname' => $this->request->getVar('fullname'),

                        'job_id' => $this->request->getVar('job_id'),

                        'role' => $this->request->getVar('role'),

                        'address' => $this->request->getVar('address'),

                        'date_birth' => $this->request->getVar('date_birth'),

                        'phone_number' => $this->request->getVar('phone_number'),

                        'profile_picture' => $fileName,

                    ];

                    $profilePicture->move('upload/users/', $fileName);

                    $user->update($id, $data);



                    $response = [

                        'status'   => 201,

                        'success'    => 201,

                        'messages' => [

                            'success' => 'Profil berhasil diupdate'

                        ]

                    ];
                } else {

                    $response = [

                        'status'   => 400,

                        'error'    => 400,

                        'messages' => $this->validator->getErrors(),

                    ];
                }

                $data = [

                    'fullname' => $this->request->getVar('fullname'),

                    'job_id' => $this->request->getVar('job_id'),

                    'role' => $this->request->getVar('role'),

                    'address' => $this->request->getVar('address'),

                    'date_birth' => $this->request->getVar('date_birth'),

                    'phone_number' => $this->request->getVar('phone_number'),

                ];



                $user->update($id, $data);



                $response = [

                    'status'   => 201,

                    'success'    => 201,

                    'messages' => [

                        'success' => 'Profil berhasil diupdate'

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

        return $this->failNotFound('Data user tidak ditemukan');
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

            $notification = new Notification;



            // cek role user

            $data = $user->select('role')->where('id', $decoded->uid)->first();

            if ($data['role'] != 'admin') {

                return $this->fail('Tidak dapat di akses selain admin', 400);
            }



            $data = $user->where('id', $id)->findAll();

            if ($data) {

                $cart->delete($data);

                $notification->delete($data);

                $user->delete($id);

                $response = [

                    'status'   => 200,

                    'error'    => null,

                    'messages' => [

                        'success' => 'User berhasil dihapus'

                    ]

                ];

                return $this->respondDeleted($response);
            } else {

                return $this->failNotFound('Data User tidak ditemukan');
            }
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }

        return $this->failNotFound('Data User tidak ditemukan');
    }



    public function learningProgress()

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];



        try {

            $decoded = JWT::decode($token, $key, ['HS256']);



            $modelUserCourse = new UserCourse;

            $modelUserVideo = new UserVideo;

            $modelVideoCategory = new VideoCategory;

            $modelVideo = new Video;



            $userCourse = $modelUserCourse->where('user_id', $decoded->uid)->where('bundling_id', null)->findAll();

            for ($i = 0; $i < count($userCourse); $i++) {

                $videoCategory = $modelVideoCategory->where('course_id', $userCourse[$i]['course_id'])->first();

                $video = $modelVideo->where('video_category_id', $videoCategory['video_category_id'])->findAll();





                $completed = [];



                for ($j = 0; $j < count($video); $j++) {

                    $userVideo = $modelUserVideo->where('user_id', $decoded->uid)->where('video_id', $video[$j]['video_id'])->first();

                    if (isset($userVideo)) {

                        $completed[$j] = $userVideo;
                    } else {

                        continue;
                    }
                }



                $progress[$i] = [

                    'course_id' => $userCourse[$i]['course_id'],

                    'completed' => count($completed),

                    'total' => count($video)

                ];
            }



            $response = [

                'id' => $decoded->uid,

                'progress' => isset($progress) ? $progress : [],

            ];

            return $this->respond($response);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }



    public function getAuthor()

    {

        $user = new Users;

        $modelCourse = new Course;

        $modelBundling = new Bundling;

        $modelReview = new Review;



        $path = site_url() . 'upload/users/';



        $getdataauthor = $user

            ->where('role', 'author')

            ->select('id, fullname, email, profile_picture, role, company')

            ->findAll();



        for ($c = 0; $c < count($getdataauthor); $c++) {

            $getdataauthor[$c]['profile_picture'] = $path . $getdataauthor[$c]['profile_picture'];
        }



        $data['author'] = $getdataauthor;



        $rating_author_raw = 0;

        $rating_author_final = 0;



        for ($i = 0; $i < count($getdataauthor); $i++) {

            $course = $modelCourse

                ->where('author_id', $getdataauthor[$i]['id'])

                ->select('course_id')

                ->findAll();



            $bundling = $modelBundling

                ->where('author_id', $getdataauthor[$i]['id'])

                ->select('bundling_id')

                ->findAll();



            $rating_course_raw = 0;

            $rating_course_final = 0;



            if ($course != null) {

                for ($x = 0; $x < count($course); $x++) {

                    $cek_course = $modelReview->where('course_id', $course[$x]['course_id'])->findAll();



                    if ($cek_course != null) {

                        $reviewcourse = $modelReview->where('course_id', $course[$x]['course_id'])->findAll();



                        $rating_raw = 0;

                        $rating_final = 0;



                        for ($n = 0; $n < count($reviewcourse); $n++) {

                            $rating_raw += $reviewcourse[$n]['score'];

                            $rating_final = $rating_raw / count($reviewcourse);



                            // $data['author'][$i]['course'][$x]['rating_course'] = $rating_final;

                        }



                        $rating_course_raw += $rating_final;

                        $rating_course_final = $rating_course_raw / count($course);

                        // $data['author'][$i]['course_final_rating'] = $rating_course_final;

                    } else {

                        // $data['author'][$i]['course_final_rating'] = 0;

                    }
                }
            } else {

                // $data['author'][$i]['course_final_rating'] = 0;

            }

            $rating_bundling_final = 0;



            if ($bundling != null) {

                for ($z = 0; $z < count($bundling); $z++) {

                    $cek_bundling = $modelReview->where('bundling_id', $bundling[$z]['bundling_id'])->findAll();



                    $rating_bundling_raw = 0;

                    $rating_bundling_final = 0;



                    if ($cek_bundling != null) {

                        $reviewbundling = $modelReview->where('bundling_id', $bundling[$z]['bundling_id'])->findAll();



                        $rating_raw = 0;

                        $rating_final = 0;



                        for ($m = 0; $m < count($reviewbundling); $m++) {

                            $rating_raw += $reviewbundling[$m]['score'];

                            $rating_final = $rating_raw / count($reviewbundling);



                            // $data['author'][$i]['bundling'][$z]['rating_bundling'] = $rating_final;

                        }

                        $rating_bundling_raw += $rating_final;

                        $rating_bundling_final = $rating_bundling_raw / count($bundling);

                        // $data['author'][$i]['bundling_final_rating'] = $rating_bundling_final;

                    } else {

                        // $data['author'][$i]['bundling_final_rating'] = 0;

                    }
                }
            } else {

                // $data['author'][$i]['bundling_final_rating'] = 0;

            }



            if ($course != null || $bundling != null) {

                $rating_author_raw = $rating_bundling_final + $rating_course_final;

                $rating_author_final = $rating_author_raw / 2;

                $data['author'][$i]['author_final_rating'] = $rating_author_final;
            } else {

                $data['author'][$i]['author_final_rating'] = 0;
            }
        }

        return $this->respond($data);
    }


    public function getUser()
    {
        try {

            // token : eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1lIjoiU3R1ZmFzdC5pZCJ9.mt2ZCJStqKUwKekZ1FCzTvVuHSRJpaM7gaprKq5JKfA

            $key = getenv('TOKEN_SECRET');

            $header = $this->request->getServer('HTTP_AUTHORIZATION');

            if (!$header) return $this->failUnauthorized('Akses token diperlukan');

            $token = explode(' ', $header)[1];

            $decoded = JWT::decode($token, $key, ['HS256']);

            if ($decoded->name != "Stufast.id") return $this->failUnauthorized('Akses tidak diberikan');

            $user = new Users;

            $email = 0;

            $phone = 0;

            $company = ['-'];

            $list = $user->orderBy('fullname', 'ASC')->findAll();

            $company_raw = $user->groupBy('company')

                ->orderBy('company', 'ASC')

                ->findAll();

            foreach ($company_raw as $row) {

                if ($row['company'] != null) {

                    $company[] = $row['company'];
                }
            }

            for ($i = 0; $i < count($list); $i++) {

                if ($list[$i]['email']) {

                    $email++;
                }

                if ($list[$i]['phone_number']) {

                    $phone++;
                }

                $data[$i]['name'] = $list[$i]['fullname'];

                $data[$i]['email'] = $list[$i]['email'];

                $data[$i]['phone'] = $list[$i]['phone_number'];

                $data[$i]['category'] = ucwords($list[$i]['role']);

                if ($list[$i]['company']) {

                    $data[$i]['institute'] = $list[$i]['company'];
                } else {

                    $data[$i]['institute'] = '-';
                }
            }

            $response = [

                'status'   => 200,

                'error'    => null,

                'data' => [

                    'total' => [

                        'email' => $email,

                        'phone' => $phone

                    ],

                    'category' => [

                        'Member',

                        'Author',

                        'Admin'

                    ],

                    'institute' => $company,

                    'leads' => $data

                ]

            ];

            return $this->respondDeleted($response);
        } catch (\Throwable $th) {

            return $this->failUnauthorized('Akses tidak diberikan');
        }
    }
}
