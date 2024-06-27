<?php



namespace App\Controllers\Api;



use CodeIgniter\RESTful\ResourceController;

use CodeIgniter\API\ResponseTrait;

use App\Models\Users;

use App\Models\UserCourse;

use App\Models\Course;

use App\Models\Jobs;

use App\Models\CourseCategory;

use App\Models\CourseType;

use App\Models\CourseTag;

use App\Models\TypeTag;

use App\Models\Tag;

use App\Models\Bundling;

use App\Models\Resume;

use App\Models\Video;

use App\Models\VideoCategory;

use App\Models\UserVideo;

use App\Models\TimelineVideo;

use App\Models\Review;

use App\Models\Cart;

use App\Models\CourseBundling;

use App\Models\OrderCourse;

use App\Models\Order;

use App\Models\UserView;

use Firebase\JWT\JWT;

use getID3;



class UserCourseController extends ResourceController

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

            $userCourse = new UserCourse;



            $userId = $decoded->uid;

            $data = $userCourse->getData($userId)->getResultArray();

            $dataUsers = [];

            foreach ($data as $value) {

                $dataUsers[] = [

                    'course_id' => $value['course_id'],

                    'is_access' => $value['is_access']

                ];
            }



            return $this->respond($dataUsers);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }

        return $this->failNotFound('Data user tidak ditemukan');
    }

    public function get()
    {
        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];

        $model = new Course();

        $bundlingClass = new Bundling();

        $modelCourseCategory = new CourseCategory();

        $courseBundling = new CourseBundling();

        $modelCourseType = new CourseType();

        $modelCourseTag = new CourseTag();

        $modelTypeTag = new TypeTag();

        $modelTag = new Tag();

        $modelUser = new Users();

        $modelVidCat = new VideoCategory();

        $modelVideo = new Video();

        $path = site_url() . 'upload/course/thumbnail/';

        $pathVideo = site_url() . 'upload/course-video/';

        $pathVideoThumbnail = site_url() . 'upload/course-video/thumbnail/';

        include_once('getid3/getid3.php');

        $getID3 = new getID3;

        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $userCourse = new UserCourse;



            $userId = $decoded->uid;

            // get course 

            $user_course = $userCourse->getData($userId)->getResultArray();

            for ($i = 0; $i < count($user_course); $i++) {
                $data[$i] = $model->where('course_id', $user_course[$i]['course_id'])->first();
            }

            for ($i = 0; $i < count($data); $i++) {

                $author = $modelUser->where('id', $data[$i]['author_id'])->first();

                $data[$i]['author_fullname'] = $author['fullname'];

                $data[$i]['author_company'] = $author['company'];

                unset($data[$i]['author_id']);



                $data[$i]['thumbnail'] = site_url() . 'upload/course/thumbnail/' . $data[$i]['thumbnail'];

                $category = $modelCourseCategory

                    ->where('course_id', $data[$i]['course_id'])

                    ->join('category', 'category.category_id = course_category.category_id')

                    ->orderBy('course_category.course_category_id', 'DESC')

                    ->first();

                $type = $modelCourseType

                    ->where('course_id', $data[$i]['course_id'])

                    ->join('type', 'type.type_id = course_type.type_id')

                    ->orderBy('course_type.course_type_id', 'DESC')

                    ->findAll();

                $tag = $modelCourseTag

                    ->select('course_tag_id, course_tag.tag_id, name')

                    ->where('course_id', $data[$i]['course_id'])

                    ->join('tag', 'tag.tag_id = course_tag.tag_id')

                    ->orderBy('course_tag.course_tag_id', 'DESC')

                    ->findAll();

                $videoCat = $modelVidCat

                    ->where('course_id', $data[$i]['course_id'])

                    ->orderBy('video_category.video_category_id', 'DESC')

                    ->findAll();

                if ($type) {



                    $data[$i]['type'] = $type[0]["name"];



                    for ($k = 0; $k < count($type); $k++) {

                        $typeTag = $modelTypeTag

                            ->where('course_type.course_id', $data[$i]['course_id'])

                            // ->where('course_tag.course_id', $data[$i]['course_id'])

                            ->where('type.type_id', $type[$k]['type_id'])

                            ->join('type', 'type.type_id = type_tag.type_id')

                            ->join('tag', 'tag.tag_id = type_tag.tag_id')

                            ->join('course_type', 'course_type.type_id = type.type_id')

                            ->orderBy('course_type.course_id', 'DESC')

                            ->select('tag.*')

                            ->findAll();



                        for ($o = 0; $o < count($typeTag); $o++) {

                            $data[$i]['tag'][$o] = $typeTag[$o];
                        }
                    }



                    for ($x = 0; $x < count($videoCat); $x++) {

                        $video = $modelVideo

                            ->select('video_id, video, thumbnail')

                            ->where('video_category_id', $videoCat[$x]['video_category_id'])

                            ->orderBy('order', 'ASC')

                            ->findAll();



                        for ($z = 0; $z < count($video); $z++) {

                            $path = 'upload/course-video/';



                            $filename = $video[$z]['video'];

                            $video[$z]['thumbnail'] = $pathVideoThumbnail . $video[$z]['thumbnail'];



                            $checkIfVideoIsLink = stristr($filename, 'http://') ?: stristr($filename, 'https://');



                            if (!$checkIfVideoIsLink) {

                                $file = $getID3->analyze($path . $filename);



                                if (isset($file['error'][0])) {

                                    $checkFileIsExist = false;
                                } else {

                                    $checkFileIsExist = true;
                                }



                                if ($checkFileIsExist) {

                                    if (isset($file['playtime_string'])) {

                                        $duration = [
                                            "duration" => $file['playtime_string'],
                                            "video" => $pathVideo . $video[$z]['video'],
                                            "thumbnail" => $video[$z]['thumbnail']
                                        ];
                                    } else {

                                        $duration = ["duration" => '00:00:00'];
                                    }



                                    $data[$i]['video'][$z] = $duration;



                                    $video[$z] += $duration;

                                    $video[$z]['video'] = $pathVideo . $video[$z]['video'];
                                } else {

                                    $duration = ["duration" => '00:00:00'];

                                    $video[$z] += $duration;

                                    $data[$i]['video'][$z] = $duration;
                                }
                            } else {

                                $duration = ["duration" => '00:00:00'];

                                $video[$z] += $duration;
                            }
                        }



                        $sum = strtotime('00:00:00');

                        $totalTime = 0;

                        $dataTime = $data[$i]['video'];



                        foreach ($dataTime as $element) {

                            $time = implode($element);

                            if (substr_count($time, ':') == 1) {

                                $waktu = '00:' . $time;
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



                        $data[$i]['total_video_duration'] = ["total" => $result];
                    }
                } else {

                    $data[$i]['type'] = null;
                }

                if ($tag) {

                    $data[$i]['tag'] = $tag;
                } else {

                    $data[$i]['tag'] = null;
                }



                $data[$i]['category'] = $category;
            }


            // get bundling

            $userbundling = $userCourse

                ->where('user_id', $userId)

                ->where('course_id', null)

                ->select('user_course.user_course_id, user_course.user_id, user_course.bundling_id')

                ->findAll();



            $bund = $userbundling;

            $pathbundling = site_url() . 'upload/bundling/';

            for ($a = 0; $a < count($userbundling); $a++) {

                $bundling = $bundlingClass

                    ->where('bundling.bundling_id', $userbundling[$a]["bundling_id"])

                    ->join('category_bundling', 'bundling.category_bundling_id = category_bundling.category_bundling_id')

                    ->select('bundling.*, category_bundling.name as category_name')

                    ->findAll();



                for ($i = 0; $i < count($bundling); $i++) {

                    $bundling[$i]['thumbnail'] =  $pathbundling . $bundling[$i]['thumbnail'];
                }

                $bund[$a]['bundling'] = $bundling;

                $crs = $courseBundling

                    ->where('course_bundling.bundling_id', $userbundling[$a]["bundling_id"])

                    ->join('course', 'course_bundling.course_id = course.course_id')

                    ->select('course_bundling.*, course.*')

                    ->orderBy('course_bundling.order', 'ASC')

                    ->findAll();

                for ($i = 0; $i < count($crs); $i++) {

                    $author = $modelUser->where('id', $crs[$i]['author_id'])->first();

                    $crs[$i]['author_fullname'] = $author['fullname'];

                    $crs[$i]['author_company'] = $author['company'];

                    unset($crs[$i]['author_id']);



                    $crs[$i]['thumbnail'] = site_url() . 'upload/course/thumbnail/' . $crs[$i]['thumbnail'];

                    $category = $modelCourseCategory

                        ->where('course_id', $crs[$i]['course_id'])

                        ->join('category', 'category.category_id = course_category.category_id')

                        ->orderBy('course_category.course_category_id', 'DESC')

                        ->first();

                    $type = $modelCourseType

                        ->where('course_id', $crs[$i]['course_id'])

                        ->join('type', 'type.type_id = course_type.type_id')

                        ->orderBy('course_type.course_type_id', 'DESC')

                        ->findAll();

                    $tag = $modelCourseTag

                        ->select('course_tag_id, course_tag.tag_id, name')

                        ->where('course_id', $crs[$i]['course_id'])

                        ->join('tag', 'tag.tag_id = course_tag.tag_id')

                        ->orderBy('course_tag.course_tag_id', 'DESC')

                        ->findAll();

                    $videoCat = $modelVidCat

                        ->where('course_id', $crs[$i]['course_id'])

                        ->orderBy('video_category.video_category_id', 'DESC')

                        ->findAll();

                    if ($type) {



                        $crs[$i]['type'] = $type[0]["name"];



                        for ($k = 0; $k < count($type); $k++) {

                            $typeTag = $modelTypeTag

                                ->where('course_type.course_id', $crs[$i]['course_id'])

                                // ->where('course_tag.course_id', $crs[$i]['course_id'])

                                ->where('type.type_id', $type[$k]['type_id'])

                                ->join('type', 'type.type_id = type_tag.type_id')

                                ->join('tag', 'tag.tag_id = type_tag.tag_id')

                                ->join('course_type', 'course_type.type_id = type.type_id')

                                ->orderBy('course_type.course_id', 'DESC')

                                ->select('tag.*')

                                ->findAll();



                            for ($o = 0; $o < count($typeTag); $o++) {

                                $crs[$i]['tag'][$o] = $typeTag[$o];
                            }
                        }



                        for ($x = 0; $x < count($videoCat); $x++) {

                            $video = $modelVideo

                                ->select('video_id, video, thumbnail')

                                ->where('video_category_id', $videoCat[$x]['video_category_id'])

                                ->orderBy('order', 'ASC')

                                ->findAll();



                            for ($z = 0; $z < count($video); $z++) {

                                $path = 'upload/course-video/';



                                $filename = $video[$z]['video'];

                                $video[$z]['thumbnail'] = $pathVideoThumbnail . $video[$z]['thumbnail'];



                                $checkIfVideoIsLink = stristr($filename, 'http://') ?: stristr($filename, 'https://');



                                if (!$checkIfVideoIsLink) {

                                    $file = $getID3->analyze($path . $filename);



                                    if (isset($file['error'][0])) {

                                        $checkFileIsExist = false;
                                    } else {

                                        $checkFileIsExist = true;
                                    }



                                    if ($checkFileIsExist) {

                                        if (isset($file['playtime_string'])) {

                                            $duration = [
                                                "duration" => $file['playtime_string'],
                                                "video" => $pathVideo . $video[$z]['video'],
                                                "thumbnail" => $video[$z]['thumbnail']
                                            ];
                                        } else {

                                            $duration = ["duration" => '00:00:00'];
                                        }



                                        $crs[$i]['video'][$z] = $duration;



                                        $video[$z] += $duration;

                                        $video[$z]['video'] = $pathVideo . $video[$z]['video'];
                                    } else {

                                        $duration = ["duration" => '00:00:00'];

                                        $video[$z] += $duration;

                                        $crs[$i]['video'][$z] = $duration;
                                    }
                                } else {

                                    $duration = ["duration" => '00:00:00'];

                                    $video[$z] += $duration;
                                }
                            }



                            $sum = strtotime('00:00:00');

                            $totalTime = 0;

                            $dataTime = $crs[$i]['video'];



                            foreach ($dataTime as $element) {

                                $time = implode($element);

                                if (substr_count($time, ':') == 1) {

                                    $waktu = '00:' . $time;
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



                            $crs[$i]['total_video_duration'] = ["total" => $result];
                        }
                    } else {

                        $crs[$i]['type'] = null;
                    }

                    if ($tag) {

                        $crs[$i]['tag'] = $tag;
                    } else {

                        $crs[$i]['tag'] = null;
                    }



                    $crs[$i]['category'] = $category;
                }

                $bund[$a]['course'] = $crs;
            }

            $response = [
                'course' => $data,
                'bundling' => $bund
            ];
            return $this->respond($response);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }

        return $this->failNotFound('Data user tidak ditemukan');
    }



    public function course()
    {
        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];

        $model = new Course();

        $bundlingClass = new Bundling();

        $modelCourseCategory = new CourseCategory();

        $modelCourseType = new CourseType();

        $modelCourseTag = new CourseTag();

        $modelTypeTag = new TypeTag();

        $modelTag = new Tag();

        $modelUser = new Users();

        $modelVidCat = new VideoCategory();

        $modelVideo = new Video();

        $path = site_url() . 'upload/course/thumbnail/';

        $pathVideo = site_url() . 'upload/course-video/';

        $pathVideoThumbnail = site_url() . 'upload/course-video/thumbnail/';

        include_once('getid3/getid3.php');

        $getID3 = new getID3;

        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $userCourse = new UserCourse;



            $userId = $decoded->uid;

            // get course 

            $user_course = $userCourse->getData($userId)->getResultArray();

            for ($i = 0; $i < count($user_course); $i++) {
                $data[$i] = $model->where('course_id', $user_course[$i]['course_id'])->first();
            }

            for ($i = 0; $i < count($data); $i++) {

                $author = $modelUser->where('id', $data[$i]['author_id'])->first();

                $data[$i]['author_fullname'] = $author['fullname'];

                $data[$i]['author_company'] = $author['company'];

                unset($data[$i]['author_id']);



                $data[$i]['thumbnail'] = site_url() . 'upload/course/thumbnail/' . $data[$i]['thumbnail'];

                $category = $modelCourseCategory

                    ->where('course_id', $data[$i]['course_id'])

                    ->join('category', 'category.category_id = course_category.category_id')

                    ->orderBy('course_category.course_category_id', 'DESC')

                    ->first();

                $type = $modelCourseType

                    ->where('course_id', $data[$i]['course_id'])

                    ->join('type', 'type.type_id = course_type.type_id')

                    ->orderBy('course_type.course_type_id', 'DESC')

                    ->findAll();

                $tag = $modelCourseTag

                    ->select('course_tag_id, course_tag.tag_id, name')

                    ->where('course_id', $data[$i]['course_id'])

                    ->join('tag', 'tag.tag_id = course_tag.tag_id')

                    ->orderBy('course_tag.course_tag_id', 'DESC')

                    ->findAll();

                $videoCat = $modelVidCat

                    ->where('course_id', $data[$i]['course_id'])

                    ->orderBy('video_category.video_category_id', 'DESC')

                    ->findAll();

                if ($type) {



                    $data[$i]['type'] = $type[0]["name"];



                    for ($k = 0; $k < count($type); $k++) {

                        $typeTag = $modelTypeTag

                            ->where('course_type.course_id', $data[$i]['course_id'])

                            // ->where('course_tag.course_id', $data[$i]['course_id'])

                            ->where('type.type_id', $type[$k]['type_id'])

                            ->join('type', 'type.type_id = type_tag.type_id')

                            ->join('tag', 'tag.tag_id = type_tag.tag_id')

                            ->join('course_type', 'course_type.type_id = type.type_id')

                            ->orderBy('course_type.course_id', 'DESC')

                            ->select('tag.*')

                            ->findAll();



                        for ($o = 0; $o < count($typeTag); $o++) {

                            $data[$i]['tag'][$o] = $typeTag[$o];
                        }
                    }



                    for ($x = 0; $x < count($videoCat); $x++) {

                        $video = $modelVideo

                            ->select('video_id, video, thumbnail')

                            ->where('video_category_id', $videoCat[$x]['video_category_id'])

                            ->orderBy('order', 'ASC')

                            ->findAll();



                        for ($z = 0; $z < count($video); $z++) {

                            $path = 'upload/course-video/';



                            $filename = $video[$z]['video'];

                            $video[$z]['thumbnail'] = $pathVideoThumbnail . $video[$z]['thumbnail'];



                            $checkIfVideoIsLink = stristr($filename, 'http://') ?: stristr($filename, 'https://');



                            if (!$checkIfVideoIsLink) {

                                $file = $getID3->analyze($path . $filename);



                                if (isset($file['error'][0])) {

                                    $checkFileIsExist = false;
                                } else {

                                    $checkFileIsExist = true;
                                }



                                if ($checkFileIsExist) {

                                    if (isset($file['playtime_string'])) {

                                        $duration = [
                                            "duration" => $file['playtime_string'],
                                            "video" => $pathVideo . $video[$z]['video'],
                                            "thumbnail" => $video[$z]['thumbnail']
                                        ];
                                    } else {

                                        $duration = ["duration" => '00:00:00'];
                                    }



                                    $data[$i]['video'][$z] = $duration;



                                    $video[$z] += $duration;

                                    $video[$z]['video'] = $pathVideo . $video[$z]['video'];
                                } else {

                                    $duration = ["duration" => '00:00:00'];

                                    $video[$z] += $duration;

                                    $data[$i]['video'][$z] = $duration;
                                }
                            } else {

                                $duration = ["duration" => '00:00:00'];

                                $video[$z] += $duration;
                            }
                        }



                        $sum = strtotime('00:00:00');

                        $totalTime = 0;

                        $dataTime = $data[$i]['video'];



                        foreach ($dataTime as $element) {

                            $time = implode($element);

                            if (substr_count($time, ':') == 1) {

                                $waktu = '00:' . $time;
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



                        $data[$i]['total_video_duration'] = ["total" => $result];
                    }
                } else {

                    $data[$i]['type'] = null;
                }

                if ($tag) {

                    $data[$i]['tag'] = $tag;
                } else {

                    $data[$i]['tag'] = null;
                }



                $data[$i]['category'] = $category;
            }

            $response = [
                'course' => $data
            ];
            return $this->respond($response);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }

        return $this->failNotFound('Data user tidak ditemukan');
    }

    public function bundling()
    {
        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];

        $model = new Course();

        $bundlingClass = new Bundling();

        $modelCourseCategory = new CourseCategory();

        $courseBundling = new CourseBundling();

        $modelCourseType = new CourseType();

        $modelCourseTag = new CourseTag();

        $modelTypeTag = new TypeTag();

        $modelTag = new Tag();

        $modelUser = new Users();

        $modelVidCat = new VideoCategory();

        $modelVideo = new Video();

        $path = site_url() . 'upload/course/thumbnail/';

        $pathVideo = site_url() . 'upload/course-video/';

        $pathVideoThumbnail = site_url() . 'upload/course-video/thumbnail/';

        include_once('getid3/getid3.php');

        $getID3 = new getID3;

        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $userCourse = new UserCourse;



            $userId = $decoded->uid;


            // get bundling

            $userbundling = $userCourse

                ->where('user_id', $userId)

                ->where('course_id', null)

                ->select('user_course.user_course_id, user_course.user_id, user_course.bundling_id')

                ->findAll();



            $bund = $userbundling;

            $pathbundling = site_url() . 'upload/bundling/';

            for ($a = 0; $a < count($userbundling); $a++) {

                $bundling = $bundlingClass

                    ->where('bundling.bundling_id', $userbundling[$a]["bundling_id"])

                    ->join('category_bundling', 'bundling.category_bundling_id = category_bundling.category_bundling_id')

                    ->select('bundling.*, category_bundling.name as category_name')

                    ->findAll();



                for ($i = 0; $i < count($bundling); $i++) {

                    $bundling[$i]['thumbnail'] =  $pathbundling . $bundling[$i]['thumbnail'];
                }

                $bund[$a]['bundling'] = $bundling;

                $crs = $courseBundling

                    ->where('course_bundling.bundling_id', $userbundling[$a]["bundling_id"])

                    ->join('course', 'course_bundling.course_id = course.course_id')

                    ->select('course_bundling.*, course.*')

                    ->orderBy('course_bundling.order', 'ASC')

                    ->findAll();

                for ($i = 0; $i < count($crs); $i++) {

                    $author = $modelUser->where('id', $crs[$i]['author_id'])->first();

                    $crs[$i]['author_fullname'] = $author['fullname'];

                    $crs[$i]['author_company'] = $author['company'];

                    unset($crs[$i]['author_id']);



                    $crs[$i]['thumbnail'] = site_url() . 'upload/course/thumbnail/' . $crs[$i]['thumbnail'];

                    $category = $modelCourseCategory

                        ->where('course_id', $crs[$i]['course_id'])

                        ->join('category', 'category.category_id = course_category.category_id')

                        ->orderBy('course_category.course_category_id', 'DESC')

                        ->first();

                    $type = $modelCourseType

                        ->where('course_id', $crs[$i]['course_id'])

                        ->join('type', 'type.type_id = course_type.type_id')

                        ->orderBy('course_type.course_type_id', 'DESC')

                        ->findAll();

                    $tag = $modelCourseTag

                        ->select('course_tag_id, course_tag.tag_id, name')

                        ->where('course_id', $crs[$i]['course_id'])

                        ->join('tag', 'tag.tag_id = course_tag.tag_id')

                        ->orderBy('course_tag.course_tag_id', 'DESC')

                        ->findAll();

                    $videoCat = $modelVidCat

                        ->where('course_id', $crs[$i]['course_id'])

                        ->orderBy('video_category.video_category_id', 'DESC')

                        ->findAll();

                    if ($type) {



                        $crs[$i]['type'] = $type[0]["name"];



                        for ($k = 0; $k < count($type); $k++) {

                            $typeTag = $modelTypeTag

                                ->where('course_type.course_id', $crs[$i]['course_id'])

                                // ->where('course_tag.course_id', $crs[$i]['course_id'])

                                ->where('type.type_id', $type[$k]['type_id'])

                                ->join('type', 'type.type_id = type_tag.type_id')

                                ->join('tag', 'tag.tag_id = type_tag.tag_id')

                                ->join('course_type', 'course_type.type_id = type.type_id')

                                ->orderBy('course_type.course_id', 'DESC')

                                ->select('tag.*')

                                ->findAll();



                            for ($o = 0; $o < count($typeTag); $o++) {

                                $crs[$i]['tag'][$o] = $typeTag[$o];
                            }
                        }



                        for ($x = 0; $x < count($videoCat); $x++) {

                            $video = $modelVideo

                                ->select('video_id, video, thumbnail')

                                ->where('video_category_id', $videoCat[$x]['video_category_id'])

                                ->orderBy('order', 'ASC')

                                ->findAll();



                            for ($z = 0; $z < count($video); $z++) {

                                $path = 'upload/course-video/';



                                $filename = $video[$z]['video'];

                                $video[$z]['thumbnail'] = $pathVideoThumbnail . $video[$z]['thumbnail'];



                                $checkIfVideoIsLink = stristr($filename, 'http://') ?: stristr($filename, 'https://');



                                if (!$checkIfVideoIsLink) {
                                    // tes
                                    // if (1) {

                                    $file = $getID3->analyze($path . $filename);



                                    if (isset($file['error'][0])) {

                                        $checkFileIsExist = false;
                                    } else {

                                        $checkFileIsExist = true;
                                    }



                                    if ($checkFileIsExist) {
                                        // tes
                                        // if (1) {

                                        if (isset($file['playtime_string'])) {

                                            $duration = [
                                                "duration" => $file['playtime_string'],
                                                "video" => $pathVideo . $video[$z]['video'],
                                                "thumbnail" => $video[$z]['thumbnail']
                                            ];
                                        } else {

                                            $duration = ["duration" => '00:00:00'];
                                        }



                                        $crs[$i]['video'][$z] = $duration;



                                        $video[$z] += $duration;

                                        $video[$z]['video'] = $pathVideo . $video[$z]['video'];
                                        // tes
                                        // $video[$z]['video'] = 'https://stufast.id/upload/course-video/' . $video[$z]['video'];
                                    } else {

                                        $duration = ["duration" => '00:00:00'];

                                        $video[$z] += $duration;

                                        $crs[$i]['video'][$z] = $duration;
                                    }
                                } else {

                                    $duration = ["duration" => '00:00:00'];

                                    $video[$z] += $duration;
                                }

                                // tes
                                // $crs[$i]['video'][$z]['video'] =  $video[$z]['video'];
                                // $crs[$i]['video'][$z]['thumbnail'] = $video[$z]['thumbnail'];
                            }



                            $sum = strtotime('00:00:00');

                            $totalTime = 0;

                            $dataTime = $crs[$i]['video'];



                            foreach ($dataTime as $element) {

                                $time = implode($element);

                                if (substr_count($time, ':') == 1) {

                                    $waktu = '00:' . $time;
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



                            $crs[$i]['total_video_duration'] = ["total" => $result];
                        }
                    } else {

                        $crs[$i]['type'] = null;
                    }

                    if ($tag) {

                        $crs[$i]['tag'] = $tag;
                    } else {

                        $crs[$i]['tag'] = null;
                    }



                    $crs[$i]['category'] = $category;
                }

                $bund[$a]['course'] = $crs;
            }

            $response = [
                'bundling' => $bund
            ];
            return $this->respond($response);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }

        return $this->failNotFound('Data user tidak ditemukan');
    }
}
